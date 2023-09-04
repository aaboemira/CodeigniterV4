<?php

namespace App\Controllers;

use App\Models\Public_model;
use App\Models\admin\Orders_model;
use App\Models\admin\Home_admin_model;

class Checkout3 extends BaseController
{
    private $orderId;
    protected $Public_model;
    protected $Orders_model;
    protected $Home_admin_model;

   public function __construct()
    {
        $this->Public_model = new Public_model();
		$this->Orders_model = new Orders_model();
        $this->Home_admin_model = new Home_admin_model();
    }

    public function index()
    {
        $data = array();
        $head = array();
		$arrSeo = $this->Public_model->getSeo('checkout3');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        
        if (isset($_POST['goOrder'])) {
            
            $_POST['payment_type'] =  session('payment_type');
            $_POST['shipping_type'] =  session('shipping_type');
            $_POST['email'] =  session('email');
            $_POST['phone'] =  session('phone');
            $_POST['first_name'] =  session('first_name');
            $_POST['last_name'] =  session('last_name');
            $_POST['company'] =  session('company');
            $_POST['firmenzusatz'] =  session('firmenzusatz');
            $_POST['street'] =  session('street');
            $_POST['housenr'] =  session('housenr');
            $_POST['adresszusatz'] =  session('adresszusatz');
            $_POST['country'] =  session('country');
            $_POST['post_code'] =  session('post_code');
            $_POST['city'] =  session('city');
            $_POST['notes'] =  session('notes');
            $_POST['post_dataprotection'] =  session('post_dataprotection');
            $_POST['shipping_price']=session('shipping_price');
            $_POST['shipping_type']=session('shipping_type');
            //hier alle variablen von session nach post kopieren
            $_POST['referrer'] = session('referrer');
            $_POST['clean_referrer'] = cleanReferral($_POST['referrer']);
            $_POST['user_id'] = isset($_SESSION['logged_user']) ? $_SESSION['logged_user'] : 0;

            $orderId = $this->Public_model->setOrder($_POST);
            if ($orderId != false) {
                $this->orderId = $orderId;
                $this->setActivationLink();
                $this->sendNotifications();
                return $this->goToDestination();
            } else {
                ///log_message('error', 'Cant save order!! ' . json_encode( $_POST));
                session()->setFlashdata('order_error', true);
                return redirect()->to(LANG_URL . '/checkout/order-error');
            }
            
        }

		$data['bank_account'] = $this->Orders_model->getBankAccountSettings();
        $data['cashondelivery_visibility'] = $this->Home_admin_model->getValueStore('cashondelivery_visibility');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['bestSellers'] = $this->Public_model->getbestSellers();
        $data['shipping_price']=session('shipping_price');
        $data['shipping_type']=session('shipping_type');

        return $this->render('checkout3', $head, $data);
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
                 //$this->sendmail->sendTo($user, 'Admin', 'New order in ' . $myDomain, 'Check it https://www.nodedevices.de/admin/orders');
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
            $data = array();
            $head = array();
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            $data['bank_account'] = $this->Orders_model->getBankAccountSettings();
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
        }
        @delete_cookie('paypal');
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