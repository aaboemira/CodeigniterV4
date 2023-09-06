<?php

namespace App\Controllers;

use App\Models\admin\Orders_model;

class Checkout extends BaseController
{

    private $orderId;

    protected $Orders_model;

    public function __construct()
    {
        $this->Orders_model = new Orders_model();
    }

    public function index()
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('checkout_1');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);

        if (isset($_POST['payment_type'])) {
            $errors = $this->userInfoValidate($_POST);
            if (!empty($errors)) {
                session()->setFlashdata('submit_error', $errors);
            } else {
                $_POST['referrer'] = session('referrer');
                $_POST['clean_referrer'] = cleanReferral($_POST['referrer']);
                $_POST['user_id'] = isset($_SESSION['logged_user']) ? $_SESSION['logged_user'] : 0;
                $orderId = $this->Public_model->setOrder($_POST);
                if ($orderId != false) {
                    /*
                     * Save product orders in vendors profiles
                     */
                    $this->setVendorOrders();
                    $this->orderId = $orderId;
                    $this->setActivationLink();
                    $this->sendNotifications();
                    $this->goToDestination();
                } else {
                    ///log_message('error', 'Cant save order!! ' . implode('::', $_POST));
                    session()->setFlashdata('order_error', true);
                    return redirect()->to(LANG_URL . '/checkout/order-error');
                }
            }
        }
        $data['bank_account'] = $this->Orders_model->getBankAccountSettings();
        $data['cashondelivery_visibility'] = $this->Home_admin_model->getValueStore('cashondelivery_visibility');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['bestSellers'] = $this->Public_model->getbestSellers();
        return $this->render('checkout_1', $head, $data);
    }

    private function setVendorOrders()
    {
        $this->Public_model->setVendorOrder($_POST);
    }

    /*
     * Send notifications to users that have nofify=1 in /admin/adminusers
     */

    private function sendNotifications()
    {
        $users = $this->Public_model->getNotifyUsers();
        $myDomain = config('config')->base_url;
        if (!empty($users)) {
            //$this->sendmail->clearAddresses();
            foreach ($users as $user) {
                $this->sendmail->sendTo($user, 'Admin', 'New order Recieved in ' . $myDomain, 'Check it https://www.nodedevices.de/admin/orders');
            }
        }
    }

    private function setActivationLink()
    {
        if (config('config')->base_url === true) {
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
            return redirect()->to(LANG_URL . '/checkout/successbank');
        }
        if ($_POST['payment_type'] == 'cashOnDelivery') {
            return redirect()->to(LANG_URL . '/checkout/successcash');
        }
        if ($_POST['payment_type'] == 'PayPal') {
            @set_cookie('paypal', $this->orderId, 2678400);
            $_SESSION['discountAmount'] = $_POST['discountAmount'];
            return redirect()->to(LANG_URL . '/checkout/paypalpayment');
        }
    }

    private function userInfoValidate($post)
    {
        $errors = array();
        if (mb_strlen(trim($post['first_name'])) == 0) {
            $errors[] = lang_safe('first_name_empty');
        }
        if (mb_strlen(trim($post['last_name'])) == 0) {
            $errors[] = lang_safe('last_name_empty');
        }
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = lang_safe('invalid_email');
        }
        // $post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
        // if (mb_strlen(trim($post['phone'])) == 0) {
        //     $errors[] = lang_safe('invalid_phone');
        // }
        if (mb_strlen(trim($post['street'])) == 0) {
            $errors[] = lang_safe('invalid_street');
        }
        $post['housenr'] = preg_replace("/[^0-9]/", '', $post['housenr']);
        if (mb_strlen(trim($post['housenr'])) == 0) {
            $errors[] = lang_safe('invalid_housenr');
        }

        if (mb_strlen(trim($post['country'])) == 0) {
            $errors[] = lang_safe('invalid_country');
        }
        if (mb_strlen(trim($post['city'])) == 0) {
            $errors[] = lang_safe('invalid_city');
        }
        $post['post_code'] = preg_replace("/[^0-9]/", '', $post['post_code']);
        if (mb_strlen(trim($post['post_code'])) == 0) {
            $errors[] = lang_safe('invalid_post_code');
        }
        if(!isset($_POST['post_dataprotection'])){
            $errors[] = lang_safe('invalid_dataprotection');
        }

        return $errors;
    }

    public function orderError()
    {
        if (session('order_error')) {
            $data = array();
            $head = array();
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            return $this->render('checkout_parts/order_error', $head, $data);
        } else {
            return redirect()->to(LANG_URL . '/checkout');
        }
    }

    public function paypalPayment()
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('checkout');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['paypal_sandbox'] = $this->Home_admin_model->getValueStore('paypal_sandbox');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['shipping_price']=session('shipping_price');
        $data['shipping_type']=session('shipping_type');
        return $this->render('checkout_parts/paypal_payment', $head, $data);
    }

    public function successPaymentCashOnD()
    {
        if (session('success_order')) {
            unset($_SESSION['discountCodeResult']);
            unset($_SESSION['shopping_cart']);
            $this->shoppingcart->clearShoppingCart();
            $data = array();
            $head = array();
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            return $this->render('checkout_parts/payment_success_cash', $head, $data);
        } else {
            return redirect()->to(LANG_URL . '/checkout');
        }
    }

    public function successPaymentBank()
    {
        if (session('success_order')) {
            unset($_SESSION['discountCodeResult']);
            unset($_SESSION['shopping_cart']);
            $data = array();
            $head = array();
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            $data['bank_account'] = $this->Orders_model->getBankAccountSettings();
            $this->shoppingcart->clearShoppingCart();
            return $this->render('checkout_parts/payment_success_bank', $head, $data);
        } else {
            return redirect()->to(LANG_URL . '/checkout');
        }
    }

    public function paypal_cancel()
    {

        if (get_cookie('paypal') == null) {
            return redirect()->to(base_url());
        }
        @delete_cookie('paypal');
        $orderId = get_cookie('paypal');
        $this->Public_model->changePaypalOrderStatus($orderId, 'canceled');
        $data = array();
        $head = array();
        $head['title'] = '';
        $head['description'] = '';
        $head['keywords'] = '';
        return $this->render('checkout_parts/paypal_cancel', $head, $data);
    }

    public function paypal_success()
    {
        if (get_cookie('paypal') == null) {
            return redirect()->to(base_url());
        @delete_cookie('paypal');
        $this->shoppingcart->clearShoppingCart();
        unset($_SESSION['discountCodeResult']);
        unset($_SESSION['shopping_cart']);
        $this->shoppingcart->clearShoppingCart();
        $orderId = get_cookie('paypal');
        $this->Public_model->changePaypalOrderStatus($orderId, 'payed');
        $data = array();
        $head = array();
        $head['title'] = '';
        $head['description'] = '';
        $head['keywords'] = '';
        return $this->render('checkout_parts/paypal_success', $head, $data);
    }

}
}