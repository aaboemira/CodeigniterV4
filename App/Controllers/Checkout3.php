<?php

namespace App\Controllers;

use App\Models\Public_model;
use App\Models\admin\Orders_model;
use App\Models\admin\Home_admin_model;
use App\Models\admin\Products_model;
use App\Libraries\SendMail;
use CodeIgniter\I18n\Time;

class Checkout3 extends BaseController
{
    private $orderId;
    protected $Public_model;
    protected $Orders_model;
    protected $Home_admin_model;
    protected $Products_model;
    protected $sendmail;

    public function __construct()
    {
        $this->Public_model = new Public_model();
        $this->Orders_model = new Orders_model();
        $this->Home_admin_model = new Home_admin_model();
        $this->Products_model = new Products_model();
        $this->sendmail = new Sendmail(); // Initialize the $sendmail property
    }

    public function index()
    {
        $controller=new Checkout1();

        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('checkout3');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['countries'] = $controller->getCountries();
        if ($this->request->getPost('action') == 'change_address') {
            $userData = $this->Public_model->getUserWithAddressesByEmail(session()->get('email'));
            $data['user_data'] = $userData;
            $data['change_address'] = true;
            return $this->render('checkout1', $head, $data);
        }
        if (isset($_POST['user_status'])) {
            // Process the form data
            $formDataValid = $controller->processFormData($this->request->getPost());
            if ($formDataValid) {
                return redirect()->to(LANG_URL . '/checkout3');
            } else {
                // If form data is not valid, re-render the form with errors
                return $this->render('checkout1', $head, $data);
            }
        }
        if (isset($_POST['goOrder'])) {

            $_POST['payment_type'] =  session('payment_type');
            $_POST['shipping_type'] =  session('shipping_type');
            $_POST['email'] =  session('email');
            $_POST['phone'] =  session('phone');
            $_POST['first_name'] =  session('first_name');
            $_POST['last_name'] =  session('last_name');
            $_POST['company'] =  session('company');
            $_POST['firmenzusatz'] =  session('firmenzusatz');
            $_POST['adresszusatz'] =  session('adresszusatz');
            $_POST['notes'] =  session('notes');
            $_POST['post_dataprotection'] =  session('post_dataprotection');
            $_POST['shipping_price']=session('shipping_price');
            $_POST['shipping_type']=session('shipping_type');
            $_POST['shipping_address']=session('shipping_address');
            $_POST['billing_address']=session('billing_address');
            $_POST['status']='received';
            //hier alle variablen von session nach post kopieren
            $_POST['referrer'] = session('referrer');
            $_POST['clean_referrer'] = cleanReferral($_POST['referrer']);
            $_POST['user_id'] = isset($_SESSION['logged_user']) ? $_SESSION['logged_user'] : 0;
            $orderId = $this->Public_model->setOrder($_POST);
            $products=$this->getOrderProducts($_POST['id'],$_POST['quantity']);
            $products=$this->addProductTitle($products);
            $address = array(
                'shipping_address'=>$_POST['shipping_address'],
                'billing_address'=>$_POST['billing_address'],
                'phone'     => $_POST['phone'],
                'email'     => $_POST['email']
            );
   
            $currentDateTime = Time::now('Europe/Berlin', 'de_DE');
            if (!isset(session('discountCodeResult')['discount_amount'])) {
                $session = session();
                $discountCodeResult = session('discountCodeResult') ?? [];
                $discountCodeResult['discount_amount'] = 0;
                $session->set('discountCodeResult', $discountCodeResult);
            }
            
            
            $discount_amount = session('discountCodeResult')['discount_amount'] ?? 0;
            $_POST['discountAmount']=$discount_amount;
            $orderData = array(
                'order_id'=>$orderId,
                'shipping_full_name'=>$_POST['shipping_address']['shipping_first_name'].' '.$_POST['shipping_address']['shipping_last_name'],
                'billing_full_name'=>$_POST['billing_address']['billing_first_name'].' '.$_POST['billing_address']['billing_last_name'],
                'shipping_type'=>$_POST['shipping_type'],
                'shipping_price'=>$_POST['shipping_price'],
                'payment_type'=>$_POST['payment_type'],
                'email'=>$_POST['email'],
                'country'=>$_POST['billing_address']['billing_country'],
                'products'=>$products,
                'currency'=>config('config')->currency,
                'discount'=>$discount_amount,
                'order_date' => $currentDateTime->format('d.m.Y:H:i:s'),
                'address'=>$address,
                'image_url'=> base_url('/attachments/shop_images/')
            );
            if ($orderId != false) {
                $this->orderId = $orderId;
                $this->sendBestellbestaetigung($orderData);
                //$this->setActivationLink();
                $this->sendNotifications();
                session()->set('new_order_flag', true); // Set the new order flag

                return $this->goToDestination();
            } else {
                ///log_message('error', 'Cant save order!! ' . json_encode( $_POST));
                session()->setFlashdata('order_error', true);
                return redirect()->to(LANG_URL . '/checkout/order-error');
            }

        }
        if (session('discountCodeResult') !== false) {
            $discountCodeResult = session('discountCodeResult');
        }

        $data['bank_account'] = $this->Orders_model->getBankAccountSettings();
        $data['cashondelivery_visibility'] = $this->Home_admin_model->getValueStore('cashondelivery_visibility');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['bestSellers'] = $this->Public_model->getbestSellers();
        $data['shipping_price'] = number_format(floatval(session('shipping_price')), 2, '.', '');
        $data['shipping_type']=session('shipping_type');
        return $this->render('checkout3', $head, $data);
    }


    /*
     * Send notifications to users that have nofify=1 in /admin/adminusers
     */
    private function sendNotifications()
    {
        $users = $this->Public_model->getNotifyUsers();

        if (!empty($users)) {
            //$this->sendmail->clearAddresses();
            foreach ($users as $user) {
                $this->sendmail->sendTo($user, 'Admin', 'Neue Bestellung eingegangen', 'Check it https://www.nodedevices.de/admin/orders');
            }
        }
    }

    private function setActivationLink()
    {
        if (config('config')->send_confirm_link === true) {
            $link = md5($this->orderId . time());
            $result = $this->Public_model->setActivationLink($link, $this->orderId);
            if ($result == true) {
                $url = parse_url(base_url());
                $msg = lang_safe('please_confirm') . base_url('confirm/' . $link);
                $this->sendmail->sendTo($_POST['email'], $_POST['first_name'] . ' ' . $_POST['last_name'], lang_safe('confirm_order_subj') . $url['host'], $msg);
            }
        }
        else{

        }
    }

    private function goToDestination()
    {
        if ($_POST['payment_type'] == 'cashOnDelivery' || $_POST['payment_type'] == 'Bank') {
            $this->shoppingcart->clearShoppingCart();
            session()->setFlashdata('success_order', true);
        }
        if ($_POST['payment_type'] == 'Bank') {
            $_SESSION['order_id'] = $this->orderId;
            $_SESSION['final_amount'] = $_POST['final_amount'] . $_POST['amount_currency'];
            unset($_SESSION['shopping_cart']);
            @delete_cookie('shopping_cart');
            unset($_SESSION['shipping_address'],$_SESSION['billing_address'],$_SESSION['guest_user']);

            return redirect()->to(LANG_URL . '/checkout/successbank')->withCookies();
        }
        if ($_POST['payment_type'] == 'cashOnDelivery') {
            unset($_SESSION['shopping_cart']);
            @delete_cookie('shopping_cart');
            unset($_SESSION['shipping_address'],$_SESSION['billing_address'],$_SESSION['guest_user']);

            return redirect()->to(LANG_URL . '/checkout/successcash')->withCookies();
        }
        if ($_POST['payment_type'] == 'PayPal') {
            @set_cookie('paypal', $this->orderId, 2678400);
            $_SESSION['discountAmount'] = $_POST['discountAmount'];

            return redirect()->to(LANG_URL . '/checkout/paypalpayment')->withCookies();
        }
    }
    //Bestell Eingangsbestätigung to customer
    public function sendBestellbestaetigung($orderData)
    {

        $users = $this->Public_model->getNotifyUsers();
        $german = ($orderData['country'] == 'Deutschland') ? true : false;
        $titleadmin=$german?"Neue Bestellung bei nodedevices.de":"New order on nodedevices.de";
        $titlecustomer=$german?"Ihre Bestellung bei nodedevices.de":"Your order on nodedevices.de";

        //to customer
        $this->sendmail->orderConfirmation($orderData['email'], $_POST['billing_address']['billing_first_name'] . ' ' . $_POST['billing_address']['billing_last_name'], $titlecustomer,$orderData,$german);
        //$this->sendmail->clearAddresses();
        //Send to admin users
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->sendmail->orderConfirmation($user, $orderData['email'], $titleadmin, $orderData, $german);
            }
        }
        $this->Orders_model->changeOrderStatus($orderData['order_id'],'receipt_confirmed');

    }
    public function getOrderProducts($id,$quantity){
        $post['products'] = array();
        $i=0;
        foreach ($id as $product) {
            $post['products'][$product] = $quantity[$i];
            $i++;
        }
        if(!empty($post['products'])) {
            foreach($post['products'] as $pr_id => $pr_qua) {
                $products_to_order[] = [
                    'product_info' => $this->Public_model->getOneProductForSerialize($pr_id),
                    'product_quantity' => $pr_qua
                ];
            }
        }
        return $products_to_order;
    }
    public function addProductTitle($productsData)
    {
        $result = array();

        foreach ($productsData as $product) {
            if (isset($product['product_info']['id'])) {
                $productId = $product['product_info']['id'];
                $translationTitle = $this->Products_model->getProductTranslationTitle($productId);

                if ($translationTitle !== false) {
                    $product['product_info']['title'] = $translationTitle;
                } else {
                    $product['product_info']['title'] = 'Translation Not Found'; // You can customize this message.
                }

                $result[] = $product;
            }
        }

        return $result;
    }

}