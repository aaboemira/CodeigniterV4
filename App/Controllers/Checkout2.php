<?php

namespace App\Controllers;

use App\Libraries\ShoppingCart;
use App\Models\admin\settings_model;
use App\Models\public_model;
use App\Models\admin\orders_model;

class Checkout2 extends BaseController
{
    protected $public_model;
    protected $orders_model;
    protected $settings_model;
    protected $shopping_cart;

   public function __construct()
    {
        $this->public_model = new public_model();
		$this->orders_model = new orders_model();
        $this->settings_model = new settings_model(); // Add this line
        $this->shopping_cart=new ShoppingCart();

    }

    public function index()
    {
        $data = array();
        $head = array();
		$arrSeo = $this->public_model->getSeo('checkout2');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $dist = isset($_SESSION['shipping_address']['shipping_country']) && $_SESSION['shipping_address']['shipping_country'] === 'Deutschland' ? 1 : 2;
        // Get free shipping values
        $shippingSettings = $this->settings_model->getShippingSettings();
        $freeShippingValue = $dist == 1 ? ($shippingSettings['free_shipping_germany'] ) : ($shippingSettings['free_shipping_europe'] );
        $shopping_cart=$this->shopping_cart->getCartItems();

        // Get order total (you need to determine how to get this value)
        $orderTotal = $shopping_cart['finalSum'];
        // Get and filter shipments
        $shipments = $this->Public_model->getShippingsByDist($dist);
        $data['shipments'] = $this->filterShipments($shipments, $freeShippingValue, $orderTotal);
        $data['freeShippingInfo'] = [
            'isEligible' => is_numeric($freeShippingValue) && $orderTotal >= $freeShippingValue,
            'difference' => is_numeric($freeShippingValue) ? max(0, $freeShippingValue - $orderTotal) : null
        ];
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
		
		$data['bank_account'] = $this->orders_model->getBankAccountSettings();
        $data['cashondelivery_visibility'] = $this->Home_admin_model->getValueStore('cashondelivery_visibility');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['bestSellers'] = $this->public_model->getbestSellers();
		return $this->render('checkout2', $head, $data);
    }

    public function orderError()
    {
        if (session('order_error')) {
            $data = array();
            $head = array();
            $arrSeo = $this->public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            return $this->render('checkout_parts/order_error', $head, $data);
        } else {
            return redirect()->to(LANG_URL . '/checkout2');
        }
    }
	
    private function filterShipments($shipments, $freeShippingValue, $orderTotal)
    {
        return array_filter($shipments, function ($shipment) use ($freeShippingValue, $orderTotal) {
            // If freeShippingValue is null, filter out free shipping options
            if (is_null($freeShippingValue) && $shipment['price'] == 0) {
                return false;
            }
            // If orderTotal is less than freeShippingValue and shipment price is 0, filter out the shipment
            if (!is_null($freeShippingValue) && $orderTotal < $freeShippingValue && $shipment['price'] == 0) {
                return false;
            }
            return true;
        });
    }
    

   
}