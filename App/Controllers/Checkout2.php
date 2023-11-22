<?php

namespace App\Controllers;

use App\Models\Public_model;
use App\Models\admin\Orders_model;

class Checkout2 extends BaseController
{
    protected $Public_model;
    protected $Orders_model;
    
   public function __construct()
    {
        $this->Public_model = new Public_model();
		$this->Orders_model = new Orders_model();
    }

    public function index()
    {
        $data = array();
        $head = array();
		$arrSeo = $this->Public_model->getSeo('checkout2');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $dist = isset($_SESSION['shipping_address']['country']) && $_SESSION['shipping_address']['country'] === 'Deutschland' ? 1 : 2;
        $data['shipments'] = $this->Public_model->getShippingsByDist($dist);
        
		if (isset($_POST['payment_type'])) {
            
            if(isset($_POST['payment_type']))
                session()->set('payment_type', $_POST['payment_type']);
            if(isset($_POST['shipping_type']))
                session()->set('shipping_type', $_POST['shipping_type']);
            if(isset($_POST['selected_shipping_price'])) {
                session()->set('shipping_price', $_POST['selected_shipping_price']);
            }
            return redirect()->to(LANG_URL . '/checkout3');
		}
		
		$data['bank_account'] = $this->Orders_model->getBankAccountSettings();
        $data['cashondelivery_visibility'] = $this->Home_admin_model->getValueStore('cashondelivery_visibility');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['bestSellers'] = $this->Public_model->getbestSellers();
		return $this->render('checkout2', $head, $data);
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
            return redirect()->to(LANG_URL . '/checkout2');
        }
    }
	
		
   
}