<?php
namespace App\Controllers\Admin\Ecommerce;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Orders_model;

class Brands extends ADMIN_Controller
{

    protected $Brands_model;

    public function __construct()
    {
        $this->Brands_model = new Brands_model();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Brands';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_POST['name'])) {
            $this->Brands_model->setBrand($_POST['name']);
            return redirect()->to('admin/brands');
        }

        if (isset($_GET['delete'])) {
            $this->Brands_model->deleteBrand($_GET['delete']);
            return redirect()->to('admin/brands');
        }

        $data['brands'] = $this->Brands_model->getBrands();

        $this->saveHistory('Go to brands page');
        $page = 'ecommerce/brands';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
