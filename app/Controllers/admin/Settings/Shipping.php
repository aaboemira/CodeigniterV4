<?php
namespace App\Controllers\Admin\Settings;

use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Settings_model;

class Shipping extends ADMIN_Controller
{
    protected $Settings_model;

    public function __construct()
    {
        parent::__construct();
        $this->Settings_model = new Settings_model();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Shipping Settings';
        $head['description'] = 'Manage free shipping values';
        $head['keywords'] = '';
    
        if (isset($_POST['submit'])) {
            $germanyFreeShipping = isset($_POST['free_shipping_germany']) && $_POST['free_shipping_germany'] !== '' ? $_POST['free_shipping_germany'] : null;
            $europeFreeShipping = isset($_POST['free_shipping_europe']) && $_POST['free_shipping_europe'] !== '' ? $_POST['free_shipping_europe'] : null;
            
            $germanSelectedOptions = $_POST['german_shipping_options'] ?? [];
            $this->Settings_model->updateShippingOptionsByDestination($germanSelectedOptions, 1);
        
            // Handle European shipping options
            $europeanSelectedOptions = $_POST['european_shipping_options'] ?? [];
            $this->Settings_model->updateShippingOptionsByDestination($europeanSelectedOptions, 2);
        
            $this->Settings_model->updateShippingValues($germanyFreeShipping, $europeFreeShipping);
            session()->setFlashdata('result_publish', 'Free shipping values updated successfully.');
            return redirect()->to('admin/shipping');
        }
        $data['shipping_settings'] = $this->Settings_model->getShippingSettings();
        $data['german_shipping_options'] = $this->Settings_model->getGermanShippingOptions();
        $data['european_shipping_options'] = $this->Settings_model->getEuropeanShippingOptions();
        $page = 'settings/shipping';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }
    
}
