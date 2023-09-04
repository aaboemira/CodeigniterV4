<?php
namespace App\Controllers\Admin\home;

use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Orders_model;
use App\Models\admin\History_model;

class Home extends ADMIN_Controller
{

    protected $Orders_model;
    protected $History_model;

    public function __construct()
    {
        $this->Orders_model = new Orders_model();
        $this->History_model = new History_model();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Home';
        $head['description'] = '';
        $head['keywords'] = '';
        $data['newOrdersCount'] = $this->Orders_model->ordersCount(true);
        $data['lowQuantity'] = $this->Home_admin_model->countLowQuantityProducts();
        $data['lastSubscribed'] = $this->Home_admin_model->lastSubscribedEmailsCount();
        $data['activity'] = $this->History_model->getHistory(10, 0);
        $data['mostSold'] = $this->Home_admin_model->getMostSoldProducts();
        $data['byReferral'] = $this->Home_admin_model->getReferralOrders();
        $data['ordersByPaymentType'] = $this->Home_admin_model->getOrdersByPaymentType();
        $data['ordersByMonth'] = $this->Home_admin_model->getOrdersByMonth();
        $this->saveHistory('Go to home page');
        $head['showBrands'] = 1;
        $head['numNotPreviewOrders'] = 1;
        $head['activePages'] = [];
        $head['warnings'] = '';
        return view('templates/admin/_parts/template', ['page'=> 'home/home', 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    /*
     * Called from ajax
     */

    public function changePass()
    {
        $this->login_check();
        $result = $this->Home_admin_model->changePass($_POST['new_pass'], $this->username);
        if ($result == true) {
            echo 1;
        } else {
            echo 0;
        }
        $this->saveHistory('Password change for user: ' . $this->username);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('admin');
    }

}
