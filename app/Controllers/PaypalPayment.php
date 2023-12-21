<?php

namespace App\Controllers;

use App\Models\Public_model;

class PaypalPayment extends BaseController
{
    public function __construct()
    {
        $this->Public_model = new Public_model();
        helper('api_helper');

    }
    function getPayPalAccessToken() {
        $clientId = getenv('PAYPAL_CLIENT_ID');
        $clientSecret = getenv('PAYPAL_CLIENT_SECRET');
        $headers = [
            "Authorization: Basic " . base64_encode($clientId . ":" . $clientSecret),
            "Content-Type: application/x-www-form-urlencoded"
        ];
        $data = ["grant_type" => "client_credentials"];
        $response = callAPI("POST", "https://api-m.sandbox.paypal.com/v1/oauth2/token", $data, $headers);
        $responseArray = json_decode($response, true);
        return $responseArray['access_token'] ?? null;
    }
    public function createOrder() {
        $requestData = $this->request->getJSON(true);
        $accessToken = $this->getPayPalAccessToken();
        die(var_dump($accessToken));
        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];
        $orderData = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD", // or your currency code
                        "value" => $requestData['items'][0]['price'] // assuming single item; adapt as needed
                    ]
                ]
            ]
        ];
        $response = callAPI("POST", "https://api.sandbox.paypal.com/v2/checkout/orders", json_encode($orderData), $headers);
        $responseArray = json_decode($response, true);
        
        if (isset($responseArray['id'])) {
            return $this->response->setJSON(['id' => $responseArray['id']]);
        } else {
            // Handle error response
            return $this->response->setStatusCode(500)->setJSON($responseArray);
        }
    }
    
    

    public function captureOrder($orderId) {
        $accessToken = $this->getPayPalAccessToken();
        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];
        $response = callAPI("POST", "https://api.sandbox.paypal.com/v2/checkout/orders/$orderId/capture", null, $headers);
        // Process the response
    }
    
}