<?php

namespace App\Controllers;

use App\Models\Public_model;
use App\Libraries\SendMail;
use App\Models\admin\Products_model;

class PaypalController extends BaseController
{
    protected $Public_model;
    private $paypalApiUrl ;
    private $clientId ;
    private $secret;
    protected $logger;
    protected $Products_model;

    protected $sendmail;
    public function __construct()
    {
        $this->Public_model = new Public_model();
        helper('api_helper');
        $this->logger = service('logger');        ;
        $this->sendmail = new Sendmail(); // Initialize the $sendmail property
        $this->Products_model = new Products_model();
        $this->clientId = getenv('PAYPAL_CLIENT_ID');
        $this->secret = getenv('PAYPAL_CLIENT_SECRET');
        $this->paypalApiUrl = getenv('PAYPAL_API');

    }

    public function getPaypalAccessToken()
    {
        $curlData = http_build_query([
            'grant_type' => 'client_credentials',
            'return_unconsented_scopes'=>true
        ]);
        $response = callAPI('POST', $this->paypalApiUrl . '/v1/oauth2/token', $curlData, [
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->secret),
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        $result = json_decode($response);


        return $result->access_token ?? null;
    }

    public function createPaypalOrder()
    {
        $accessToken = $this->getPaypalAccessToken();
        if (!$accessToken) {
            return $this->response->setJSON(['error' => 'Unable to obtain access token']);
        }


        $postData = $this->request->getJSON();
        $products = $postData->products ?? [];
        $orderItems = [];
        $totalAmount = 0;

        foreach ($products as $product) {
            $productTotal = $product->price * $product->quantity;
            $totalAmount += $productTotal;
            $orderItems[] = [
                'name' => $product->name,
                'sku' => $product->id,
                'unit_amount' => [
                    'currency_code' => 'EUR',
                    'value' => number_format((float)$product->price, 2, '.', '')
                ],
                'quantity' => $product->quantity
            ];
        }
        $totalAmount = number_format($totalAmount, 2, '.', '');

        $orderData = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'items' => $orderItems,
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => $totalAmount,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'EUR',
                                'value' => $totalAmount
                            ],

                        ]
                    ]

                ]
            ],
            'application_context' => [
                'return_url' => base_url('/'),
                'cancel_url' => base_url('/cancel')
            ]
        ];
        


        $response = callAPI('POST', $this->paypalApiUrl . '/v2/checkout/orders', json_encode($orderData), [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
            'Prefer:return=representation'

        ]);


        if (!$response) {
            return $this->response->setJSON(['error' => 'Error creating PayPal order']);
        }
        return $this->response->setJSON(json_decode($response));
    }

    public function capturePaypalOrder() {
        $accessToken = $this->getPaypalAccessToken();
        if (!$accessToken) {
            return $this->response->setJSON(['error' => 'Unable to obtain access token']);
        }
    
        $postData = $this->request->getJSON();
        $orderId = $postData->orderID ?? null;
    
        if (!$orderId) {
            return $this->response->setJSON(['error' => 'No order ID provided']);
        }
    
        // Attempt to capture the payment

        $url=$this->paypalApiUrl ."/v2/checkout/orders/$orderId/capture";
        $response = callAPI('POST', $url, null, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
            'Prefer:return=representation'
        ]);
        $captureData = json_decode($response, true);

        if (!$response || $captureData['status'] !== 'COMPLETED') {
            return $this->response->setJSON(['error' => 'Failed to pay']);

        }

        return $this->response->setJSON($captureData);
    }
    
    public function saveOrder() {
        $jsonData = $this->request->getJSON(true);

        $orderData = $this->extractOrderData($jsonData);
        $result = $this->Public_model->setOrder($orderData);
        session()->set('payment_success', true);
        
        // Use orderData to process products
        $processedProducts = $this->getAndProcessOrderProducts($orderData);
        $orderData['products'] = $processedProducts;
        if ($result) {
            // Transform the order data for the email function
            $emailData = $this->transformForEmail($orderData);

            // Send email confirmation
            $this->sendBestellbestaetigung($emailData);
    
            return $this->response->setJSON(['success' => 'Order saved successfully.', 'order_id' => $result]);
        } else {
            return $this->response->setJSON(['error' => 'Failed to save order.']);
        }
    }
    
    public function paymentSuccess() {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('checkout');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        if (!session()->get('payment_success')) {
            return redirect()->to(base_url());
        }

        // Reset the session variable to prevent refresh access
        session()->remove('payment_success');

        // Assuming $head and $data are prepared for rendering
        return $this->render('checkout_parts/paypal_success', $head, $data);
    }


    public function prepareOrderData() {
        $session = session();
        $cartItems = $session->get('shopping_cart') ?? [];
        $productCounts = array_count_values($cartItems);
        $products = [];
    
        foreach ($productCounts as $productId => $quantity) {
            $productDetails = $this->Public_model->getMinimalProductDetailsById($productId);
            if ($productDetails) {
                $products[] = [
                    'id' => $productId,
                    'price' => number_format((float)$productDetails['price'], 2, '.', ''),
                    'name' => $productDetails['title'],
                    'quantity' => $quantity
                ];
            }
        }
    
        return $this->response->setJSON(['products' => $products]);
    }
    public function postPayment(){
        $jsonData = $this->request->getJSON(true);
        $type = $jsonData['type'] ?? null;

        if ($type === 'shopping_cart') {
            $this->clearShoppingCart();
        }

        // Return a success message
        return $this->response->setJSON(['message' => 'Payment successfully processed.']);
    }
    private function extractOrderData($responseData) {
        $payerInfo = $responseData['payer'];
        $shippingInfo = $responseData['purchase_units'][0]['shipping'];
        $items = $responseData['purchase_units'][0]['items']; // Get all items
        $captureInfo = $responseData['purchase_units'][0]['payments']['captures'][0];
        $shippingPrice=$responseData['purchase_units'][0]['amount']['breakdown']['shipping']['value'];

        $parsedShippingAddress = $this->parseAddress($shippingInfo['address']['address_line_1']);
        $shipping_title=$this->calculateCostBasedOnAddress($shippingInfo['address']['country_code']);

        $shipping_title=$shipping_title['title'];
        // Initialize arrays for product names, prices, quantities, and IDs (SKUs)
        $productNames = [];
        $productPrices = [];
        $quantities = [];
        $productIds = [];
    
        foreach ($items as $item) {
            $productNames[] = $item['name'];
            $productPrices[] = $item['unit_amount']['value'];
            $quantities[] = $item['quantity'];
            $productIds[] = $item['sku'];
        }
    
        $orderData = [
            'email' => $payerInfo['email_address'],
            'first_name' => $payerInfo['name']['given_name'],
            'last_name' => $payerInfo['name']['surname'],
            'phone' => '',
            'notes' => '',
            'referrer' => '',
            'clean_referrer' => '',
            'shipping_type' => $shipping_title, // Assuming this exists in $orderData
            'shipping_price' => $shippingPrice,
            'payment_type' => 'Paypal',
            'status' => 'received',
            'order_id' => $captureInfo['id'],
            'id' => $productIds, // Array of product ids (SKUs)
            'shipping_address' => [
                'shipping_first_name' => $payerInfo['name']['given_name'],
                'shipping_last_name' => $payerInfo['name']['surname'],
                'shipping_company' => '',
                'shipping_street' => $parsedShippingAddress['street'],
                'shipping_housenr' => $parsedShippingAddress['housenr'],
                'shipping_city' => $shippingInfo['address']['admin_area_2'],
                'shipping_country' => $shippingInfo['address']['country_code'],
                'shipping_post_code' => $shippingInfo['address']['postal_code'],
            ],
            'product_names' => $productNames,
            'product_prices' => $productPrices,
            'quantity' => $quantities,
            'total_amount' => $captureInfo['amount']['value'],
            'currency' => $captureInfo['amount']['currency_code'],
        ];
    
        $orderData['billing_address'] = $orderData['shipping_address'];
        foreach ($orderData['billing_address'] as $key => $value) {
            $newKey = str_replace('shipping_', 'billing_', $key);
            $orderData['billing_address'][$newKey] = $value;
            unset($orderData['billing_address'][$key]);
        }
    
        return $orderData;
    }
    
    
    private function parseAddress($addressLine) {
        $address = [
            'street' => $addressLine,
            'housenr' => ''
        ];
    
        // Check if there is a dot followed by numbers (housenr)
        if (preg_match('/\.\s*\d+/', $addressLine, $matches, PREG_OFFSET_CAPTURE)) {
            $dotPosition = $matches[0][1];
            $address['street'] = substr($addressLine, 0, $dotPosition);
            $address['housenr'] = substr($addressLine, $dotPosition + 1);
        }
    
        return $address;
    }
    private function transformForEmail($orderData) {
        // Transforming orderData to the format needed by sendBestellbestaetigung
        $emailData = [
            'order_id' => $orderData['order_id'],
            'shipping_full_name' => $orderData['shipping_address']['shipping_first_name'] . ' ' . $orderData['shipping_address']['shipping_last_name'],
            'billing_full_name' => $orderData['billing_address']['billing_first_name'] . ' ' . $orderData['billing_address']['billing_last_name'],
            'shipping_type' => $orderData['shipping_type'],
            'shipping_price' => $orderData['shipping_price'],
            'payment_type' => $orderData['payment_type'],
            'email' => $orderData['email'],
            'country' => $orderData['shipping_address']['shipping_country'],
            'products' => $orderData['products'], // Assuming orderData contains product details
            'currency' => config('config')->currency,
            'discount' => 0, // Assuming no discount, adjust as necessary
            'order_date' => date('d.m.Y:H:i:s'), // Current date and time
            'address' => [
                'shipping_address' => $orderData['shipping_address'],
                'billing_address' => $orderData['billing_address'],
                'phone' => $orderData['phone'],
                'email' => $orderData['email']
            ]
        ];
    
        return $emailData;
    }
    private function sendBestellbestaetigung($orderData)
    {

        $users = $this->Public_model->getNotifyUsers();
        $german = ($orderData['country'] == 'Deutschland') ? true : false;
        $titleadmin=$german?"Neue Bestellung bei nodedevices.de":"New order on nodedevices.de";
        $titlecustomer=$german?"Ihre Bestellung bei nodedevices.de":"Your order on nodedevices.de";

        //to customer
        $this->sendmail->orderConfirmation($orderData['email'], $orderData['address']['billing_address']['billing_first_name'] . ' ' . $orderData['address']['billing_address']['billing_last_name'], $titlecustomer,$orderData,$german);
        //$this->sendmail->clearAddresses();
        //Send to admin users
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->sendmail->orderConfirmation($user, $orderData['email'], $titleadmin, $orderData, $german);
            }
        }
    }
    public function getAndProcessOrderProducts($orderData) {
        $productIds = $orderData['id']; // Array of product ids (SKUs) from order data
        $quantities = $orderData['quantity']; // Quantities from order data
    
        $productsToOrder = [];
    
        foreach ($productIds as $index => $productId) {
            // Fetch product info and assign quantity for each product
            $productInfo = $this->Public_model->getOneProductForSerialize($productId);
            $quantity = $quantities[$index];
    
            $productsToOrder[] = [
                'product_info' => $productInfo,
                'product_quantity' => $quantity
            ];
        }
    
        // Now add the product titles (translation)
        $productsToOrder = $this->addProductTitle($productsToOrder);
    
        return $productsToOrder;
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
    
    private function clearShoppingCart()
    {
        $this->shoppingcart->clearShoppingCart();
    }
    public function calculateShipping()
{
    $postData = $this->request->getJSON();
    $shippingAddress=$postData->shippingAddress ?? null;
    if($shippingAddress){
        $shippingCost = $this->calculateCostBasedOnAddress($shippingAddress->country_code);
        return $this->response->setJSON([
            'shipping_cost' => $shippingCost['price'],
            'shipping_title'=> $shippingCost['title']
    ]);
    }
}

private function calculateCostBasedOnAddress($country)
{
    $dist = ($country === 'DE') ? 1 : 2;

    $shippingOptions = $this->Public_model->getShippingsByDist($dist);

    $lowestShippingCost = null;
    $lowestShippingTitle = '';

    foreach ($shippingOptions as $option) {
        if ($lowestShippingCost === null || $option['price'] < $lowestShippingCost) {
            $lowestShippingCost = $option['price'];
            $lowestShippingTitle = $option['title']; // Assuming 'title' is the field name for the shipping option title
        }
    }

    if ($lowestShippingCost !== null) {
        return [
            'price' => $lowestShippingCost,
            'title' => $lowestShippingTitle
        ];
    } else {
        return [
            'price' => '0.00', // Default shipping cost
            'title' => 'Standard Shipping' // Default shipping title
        ];
    }
}

public function updatePaypalOrderWithShipping()
{
    $accessToken = $this->getPaypalAccessToken();
    if (!$accessToken) {
        return $this->response->setJSON(['error' => 'Unable to obtain access token']);
    }


    $postData = $this->request->getJSON();
    $orderId = $postData->orderID ?? null;
    $shippingCost = $postData->shippingCost ?? null;
    $totalAmount = $postData->total_amount ?? null; // New total amount including shipping



    if (!$orderId || !$shippingCost || !$totalAmount) {

        return $this->response->setJSON(['error' => 'Order ID, shipping cost, or total amount is missing']);
    }

    $path = "/purchase_units/@reference_id=='default'/amount";
    $patchData = [
        [
            'op' => 'add',
            'path' => $path,
            'value' => [
                'currency_code' => 'EUR',
                'value' => number_format((float)$totalAmount, 2, '.', ''),
                'breakdown' => [
                    'item_total' => [
                        'currency_code' => 'EUR',
                        'value' => number_format((float)($totalAmount - $shippingCost), 2, '.', '')
                    ],
                    'shipping' => [
                        'currency_code' => 'EUR',
                        'value' => number_format((float)$shippingCost, 2, '.', '')
                    ]
                ]
            ]
        ]
    ];     
    $response = callAPIForPatch($this->paypalApiUrl . "/v2/checkout/orders/$orderId", $patchData, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json',
    ]);


    if (!$response) {
        return $this->response->setJSON(['error' => 'Error updating PayPal order']);
    }
    return $this->response->setJSON(json_decode($response));
}

}
