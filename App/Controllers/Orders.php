<?php

namespace App\Controllers;

use App\Models\admin\Products_model;
use App\Models\Public_model;

class Orders extends BaseController
{
    protected $Public_model;
    private $num_rows = 10;
    public function __construct()
    {
        $this->Public_model = new Public_model();
        $this->Products_model = new Products_model();

    }

    // ... [rest of your Users controller code] ...
    public function orders($page = 0)
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
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

        return $this->render('orders/index', $head, $data);
    }
    public function addTotalAmountToOrders($orders) {
        foreach ($orders as &$order) {
            $productsTotal = 0;
            $products = unserialize($order['products']);
            foreach ($products as $product) {
                $productInfo = $product['product_info'];
                $productsTotal += $productInfo['price'] * $product['product_quantity'];
            }

            $discountPercentage = $order['discount']?? 0;
            $productsTotal -= ($productsTotal * ($discountPercentage / 100));


            // Add shipping price if available
            $shippingPrice = $order['shipping_price'] ?? 0;
            $productsTotal += $shippingPrice;

            // Format total amount to have 2 decimal places
            $order['total_amount'] = number_format($productsTotal, 2, '.', '');
        }
        return $orders;
    }


    public function showOrder($orderId)
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        if (!$orderId) {
            // Redirect or show an error if no order ID is provided
            return redirect()->to('/orders');
        }

        $orderDetails = $this->Public_model->getOrderById($orderId);

        if (!$orderDetails) {
            // Redirect or show an error if the order is not found
            return redirect()->to('/orders');
        }
        // Unserialize products data
        $orderDetails['products'] = unserialize($orderDetails['products']);

        $data = [
            'order' => $orderDetails,
        ];

        return $this->render('orders/show_orders', $head, $data);
    }

    public function generateInvoice($orderId) {
        $orderDetails = $this->Public_model->getOrderById($orderId);
        $country=$orderDetails['billing_country'];
        if (!$orderDetails) {
            // Handle the case where the order doesn't exist
            return redirect()->to('/orders');
        }
        $german = ($country == 'Deutschland') ? true : false;

        // Retrieve products and other necessary information
        $products = unserialize($orderDetails['products']);
        $orderDetails['products']=$this->addProductTitle($products);
        $orderDetails['currency']=CURRENCY;
        $orderDetails['date']=date('d.m.Y ', $orderDetails['date']);
        $pdfLibrary = new \App\Libraries\GenerateInvoice();
        if ($german) {
            $pdfContent = $pdfLibrary->generateInvoiceHtml($orderDetails, $orderDetails['products']);
        } else {
            $pdfContent = $pdfLibrary->generateInvoiceHtmlEnglish($orderDetails, $orderDetails['products']);
        }
        // Output PDF to browser (you may also implement file saving if needed)
        $this->response->setHeader('Content-Type', 'application/pdf');
        echo $pdfContent;
    }

    public function addProductTitle($productsData)
    {
        $result = array();

        foreach ($productsData as $product) {
            if (isset($product['product_info']['id'])) {
                $productId = $product['product_info']['id'];
                $translationTitle = $this->Products_model->getProductTranslationTitle($productId);

                if ($translationTitle !== false) {
                    $product['product_info']['title'] = $translationTitle;
                } else {
                    $product['product_info']['title'] = 'Translation Not Found'; // You can customize this message.
                }

                $result[] = $product;
            }
        }

        return $result;
    }

}
