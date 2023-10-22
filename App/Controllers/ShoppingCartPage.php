<?php

namespace App\Controllers;

use App\Models\Public_model;

class ShoppingCartPage extends BaseController
{

    protected $Public_model;

    public function __construct()
    {
        $this->Public_model = new Public_model();
    }

    public function index()
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('shoppingcart');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        
        return $this->render('shopping_cart', $head, $data);
    }

}
