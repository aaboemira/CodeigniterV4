<?php

namespace App\Controllers;

use App\Models\admin\Home_admin_model;
use App\Libraries\SendMail;

class Support extends BaseController
{

    protected $Home_admin_model;
    protected $sendmail;
    
    public function __construct()
    {
        $this->Home_admin_model = new Home_admin_model();
        $this->sendmail = new SendMail();
    }

    public function index()
    {
        $head = array();
        $data = array();
        $arrSeo = $this->Public_model->getSeo('support');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('support', $head, $data);
    }

}