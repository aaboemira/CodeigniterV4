<?php

namespace App\Controllers;

use App\Models\Public_model;

class Orders extends BaseController
{
    protected $Public_model;
    private $num_rows = 10;
    public function __construct()
    {
        $this->Public_model = new Public_model();
    }

    // ... [rest of your Users controller code] ...
    public function orders($page = 0)
    {
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
        $rowscount = $this->Public_model->getUserOrdersHistoryCount($_SESSION['logged_user']);

        $data['shipping_link']='https://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=';
        //echo '<pre>'; print_r($data['orders_history']); die;
        $totalPages = ceil($rowscount / $this->num_rows); // Calculate total pages
        $page = max(1, min($page, $totalPages));
        $data['orders'] = $this->Public_model->getUserOrdersHistory($_SESSION['logged_user'], $this->num_rows, $page);
        $data['orders'] = $this->addTotalAmountToOrders($data['orders']);

        // Manually create pagination links
        $data['paginationLinks'] = '';
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = $page == $i ? 'active' : '';
            $data['paginationLinks'] .= "<li class='page-item $active'><a class='page-link' href='/orders/$i'>$i</a></li>";
        }

        return $this->render('orders', $head, $data);
    }
    public function addTotalAmountToOrders($orders) {
        foreach ($orders as &$order) {
            $productsTotal = 0;
            $products = unserialize($order['products']);
            foreach ($products as $product) {
                $productInfo = $product['product_info'];
                $productsTotal += $productInfo['price'] * $product['product_quantity'];
            }

            // Apply discount if available
            $discountAmount = $order['discount_amount'] ?? 0;
            if ($discountAmount > 0) {
                $discountType = $order['discount_type'];
                if ($discountType === 'percentage') {
                    $productsTotal -= ($productsTotal * ($discountAmount / 100));
                } elseif ($discountType === 'fixed') {
                    $productsTotal -= $discountAmount;
                }
            }

            // Add shipping price if available
            $shippingPrice = $order['shipping_price'] ?? 0;
            $productsTotal += $shippingPrice;

            // Add total amount to the order
            $order['total_amount'] = round($productsTotal, 2);
        }
        return $orders;
    }

// ... [rest of your Users controller code] ...



}
