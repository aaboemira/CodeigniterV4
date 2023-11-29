<?php

namespace App\Controllers;

use App\Models\admin\Products_model;
use App\Models\Public_model;

class SmartDevices extends BaseController
{
    protected $Public_model;
    private $num_rows = 10;
    public function __construct()
    {
        $this->Public_model = new Public_model();

    }

    public function index($page = 0)
    {
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);

        $userUID = $_SESSION['logged_user']; // Example: Fetch UID from session
        $rowscount = $this->Public_model->countSmartHomeDevicesByUID($userUID);
        $totalPages = ceil($rowscount / $this->num_rows); // Calculate total pages
        $page = max(1, min($page, $totalPages));



        $devices = $this->Public_model->getSmartHomeDevicesByUID($userUID,$this->num_rows,$page);

        $data['devices']=$devices;


        $data['paginationLinks'] = '';
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = $page == $i ? 'active' : '';
            $data['paginationLinks'] .= "<li class='page-item $active'><a class='page-link' href='/orders/$i'>$i</a></li>";
        }

        return $this->render('smart_devices/index', $head, $data);
    }

}
