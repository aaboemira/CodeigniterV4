<?php

namespace App\Controllers;

use App\Libraries\ShoppingCart;
use App\Models\admin\Settings_model;
use App\Models\Public_model;
use App\Models\admin\Orders_model;

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
        foreach ($shipments as &$shipment) {
            // Initialize info key in case it's not set
            $shipment['info'] = '';
    
            // Check if this shipping option has free_shipping_enabled
            if ($shipment['free_shipping_enabled']==1) {
                // Determine if the order is eligible for free shipping
                if ($orderTotal >= $freeShippingValue) {
                    $shipment['price'] = 0; // Set price to 0 if eligible for free shipping
                    $shipment['eligible_for_free_shipping'] = true;
                } else {
                    // Calculate the remaining amount for free shipping eligibility
                    $remainingAmount = $freeShippingValue - $orderTotal;
                    $shipment['additional_amount_for_free'] =  number_format($remainingAmount, 2) ;
                    $shipment['eligible_for_free_shipping'] = false;
                }
            }
            // If free_shipping_enabled is not set or false, no additional info is added
            // and the shipment['eligible_for_free_shipping'] remains unset or false
        }
        unset($shipment); // Break the reference with the last element
        return $shipments;
    }
    
    
    

   
}