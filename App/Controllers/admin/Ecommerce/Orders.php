<?php
namespace App\Controllers\Admin\Ecommerce;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */

use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Orders_model;
use App\Models\admin\Home_admin_model;
use App\Models\admin\Products_model;

use App\Libraries\SendMail;
use App\Libraries\GeneratePDF;
use Config\Config;

class Orders extends ADMIN_Controller
{

    private $num_rows = 10;
    protected $Orders_model;
    protected $Home_admin_model;
    protected $Products_model;

    protected $sendmail;
    protected $generatePDF;

    public function __construct()
    {
        //$this->SendMail = new SendMail();
        $this->Orders_model = new Orders_model();
        $this->Home_admin_model = new Home_admin_model();
        $this->Products_model = new Products_model();

        $this->sendmail = new Sendmail(); // Initialize the $sendmail property
        $this->generatePDF = new GeneratePDF(); // Initialize the $sendmail property

    }

    public function index($page = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Orders';
        $head['description'] = '!';
        $head['keywords'] = '';

        $order_by = null;
        if (isset($_GET['order_by'])) {
            $order_by = $_GET['order_by'];
        }
        $rowscount = $this->Orders_model->ordersCount();
        $data['orders'] = $this->Orders_model->orders($this->num_rows, $page, $order_by);
        $data['links_pagination'] = pagination('admin/orders', $rowscount, $this->num_rows, 3);
        if (isset($_POST['paypal_sandbox'])) {
            $this->Home_admin_model->setValueStore('paypal_sandbox', $_POST['paypal_sandbox']);
            if ($_POST['paypal_sandbox'] == 1) {
                $msgKey = 'paypal_sandbox_enabled';
            } else {
                $msgKey = 'paypal_sandbox_disabled';
            }
            session()->setFlashdata('paypal_sandbox', lang_safe($msgKey));
            $this->saveHistory(lang_safe($msgKey));
            return redirect()->to('admin/orders?settings');
        }
        if (isset($_POST['paypal_email'])) {
            $this->Home_admin_model->setValueStore('paypal_email', $_POST['paypal_email']);
            session()->setFlashdata('paypal_email', lang_safe('paypal_email_change_success'));
            $this->saveHistory('Change paypal business email to: ' . $_POST['paypal_email']);
            return redirect()->to('admin/orders?settings');
        }
        if (isset($_POST['cashondelivery_visibility'])) {
            $this->Home_admin_model->setValueStore('cashondelivery_visibility', $_POST['cashondelivery_visibility']);
            session()->setFlashdata('cashondelivery_visibility', lang_safe('cashondelivery_visibility_change_success'));
            $this->saveHistory('Change Cash On Delivery Visibility - ' . $_POST['cashondelivery_visibility']);
            return redirect()->to('admin/orders?settings');
        }
        if (isset($_POST['iban'])) {
            $this->Orders_model->setBankAccountSettings($_POST);
            session()->setFlashdata('bank_account', lang_safe('bank_account_settings_save_success'));
            $this->saveHistory('Bank account settings saved for : ' . $_POST['name']);
            return redirect()->to('admin/orders?settings');
        }
        if (isset($_POST['action']) && $_POST['action'] === 'sendBestellbestaetigung') {
            $products = $this->addProductTitle((unserialize($_POST['products'])));
    
            $discount = empty($_POST['discount']) ? 0 : $_POST['discount'];
            $shippingNum = empty($_POST['shipping_number']) ? "-" : $_POST['shipping_number'];
    
            $userDetails = array(
                'addr_1' => $_POST['addr_1'],
                'addr_2' => $_POST['addr_2'],
                'company' => $_POST['company'],
                'country' => $_POST['country'],
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email']
            );
    
            $orderData = array(
                'id' => $_POST['id'],
                'full_name' => $_POST['full_name'],
                'shipping_number' => $shippingNum,
                'shipping_type' => $_POST['shipping_type'],
                'payment_type' => $_POST['payment_type'],
                'order_id' => $_POST['order_id'],
                'discount' => $discount,
                'products' => $products,
                'user_details' => $userDetails,
                'order_date' => date('d.m.Y ', $_POST['order_date']),
                'shipping_price' => $_POST['shipping_price'],
                'currency' => config('config')->currency
            );

            $this->sendBestellbestaetigung($orderData);
        }
        $data['paypal_sandbox'] = $this->Home_admin_model->getValueStore('paypal_sandbox');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['cashondelivery_visibility'] = $this->Home_admin_model->getValueStore('cashondelivery_visibility');
        $data['bank_account'] = $this->Orders_model->getBankAccountSettings();
        $data['orderStatuses'] = $this->Orders_model->getOrderStatuses();

        if ($page == 0) {
            $this->saveHistory('Go to orders page');
        }
        $page = 'ecommerce/orders';
                // Remove the new_order_flag session variable
                session()->remove('new_order_flag');
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    public function changeOrdersOrderStatus()
    {
        $this->login_check();

        $result = false;
        $sendedVirtualProducts = true;
        $virtualProducts = $this->Home_admin_model->getValueStore('virtualProducts');
        /*
         * If we want to use Virtual Products
         * Lets send email with download links to user email
         * In error logs will be saved if cant send email from PhpMailer
         */
        if ($virtualProducts == 1) {
            if ($_POST['to_status'] == 1) {
                $sendedVirtualProducts = $this->sendVirtualProducts();
            }
        }

        if ($sendedVirtualProducts == true) {
            $result = $this->Orders_model->changeOrderStatus($_POST['the_id'], $_POST['to_status']);
        }

        if ($result == true && $sendedVirtualProducts == true) {
            echo 1;
        } else {
            echo 0;
        }
        $this->saveHistory('Change status of Order Id ' . $_POST['the_id'] . ' to status ' . $_POST['to_status']);
    }

    private function sendVirtualProducts()
    {
        if(isset($_POST['products']) && $_POST['products'] != '') {
            $products = unserialize(html_entity_decode($_POST['products']));
            foreach ($products as $product_id => $product_quantity) {
                $productInfo = modules::run('admin/ecommerce/products/getProductInfo', $product_id);
                /*
                 * If is virtual product, lets send email to user
                 */
                if ($productInfo['virtual_products'] != null) {
                    if (!filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL)) {
                        log_message('error', 'Ivalid customer email address! Cant send him virtual products!');
                        return false;
                    }
                    $result = $this->sendmail->sendTo($_POST['userEmail'], 'Dear Customer', 'Virtual products', $productInfo['virtual_products']);
                    return $result;
                }
            }
            return true;
        }
    }
    public function sendBestellbestaetigung($orderData)
    {
        $users = $this->Public_model->getNotifyUsers();
        $myDomain = config('config')->base_url;
        $german = ($orderData['user_details']['country'] == 'Deutschland') ? true : false;
        if (!empty($users)) {   
            if ($german) {
                $pdf = $this->generatePDF->generateInvoiceHtml($orderData, $orderData['products']);
            } else {
                $pdf = $this->generatePDF->generateInvoiceHtmlEnglish($orderData, $orderData['products']);
            }
            $title=$german?"Ihre Bestellung bei nodedevices.de":"Your order on nodedevices.de";
            $attachmentName=$german?"Rechnung_SHND".$orderData['order_id'].'.pdf':"Invoice_SHND".$orderData['order_id'].'.pdf';
            $this->sendmail->sendToBestellbestaetigung($orderData['user_details']['email'], $orderData['user_details']['full_name'], $title , 'Check it https://www.nodedevices.de/admin/orders',$orderData,$pdf,$attachmentName,$german);

        }
       $this->Orders_model->changeOrderStatus($orderData['order_id'],'preparing_shipment');
        echo '<script>window.location.href = "'.site_url('admin/orders').'";</script>';
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