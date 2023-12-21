<?php

namespace App\Controllers;

use App\Models\Public_model;
use App\Models\admin\Orders_model;

class Checkout1 extends BaseController
{
    protected $Public_model;
    protected $Orders_model;

   public function __construct()
    {
        $this->Public_model = new Public_model();
		$this->Orders_model = new Orders_model();
    }

    public function index()
    {
        helper('form'); // This loads your custom form helper

        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('checkout1');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['countries'] = $this->getCountries();
        // Assuming 'submit' is the name of your form's submit button
        if (session()->has('logged_user')) {
            $userData = $this->Public_model->getUserWithAddressesByEmail(session()->get('email'));
            $data['user_data'] = $userData;
            if ($this->request->getPost('action') == 'change_address') {
                $data['change_address'] = true;
                return $this->render('checkout1', $head, $data);
            }
        }
        if (isset($_POST['user_status'])) {

            $formDataValid = $this->processFormData($this->request->getPost());
            if ($formDataValid) {
                return redirect()->to(LANG_URL . '/checkout2');
            } else {
                // If form data is not valid, re-render the form with errors
                return $this->render('checkout1', $head, $data);
            }
        }
        if (isset($_POST['guest_checkout'])) {
            return redirect()->to(LANG_URL . '/checkout1');
        }

        if (session()->has('logged_user')) {
            helper('form'); // This loads your custom form helper
            return $this->render('checkout1/logged_checkout1', $head, $data);
        }
        return $this->render('checkout1', $head, $data);

    }
    public function processFormData($postData)
    {
        // Set email and phone number in session
        if(isset($postData['email'])) {
            session()->set('email', $postData['email']);
        }
        if(isset($postData['phone'])) {
            session()->set('phone', $postData['phone']);
        }

        // Prepare shipping and billing address arrays
        $shippingAddress = [
            'shipping_first_name' => $postData['shipping_first_name'],
            'shipping_last_name' => $postData['shipping_last_name'],
            'shipping_company' => $postData['shipping_company'],
            'shipping_street' => $postData['shipping_street'],
            'shipping_housenr' => $postData['shipping_housenr'],
            'shipping_country' => $postData['shipping_country'],
            'shipping_post_code' => $postData['shipping_post_code'],
            'shipping_city' => $postData['shipping_city'],
        ];
        $billingAddress = [
            'billing_first_name' => $postData['billing_first_name'],
            'billing_last_name' => $postData['billing_last_name'],
            'billing_company' => $postData['billing_company'],
            'billing_street' => $postData['billing_street'],
            'billing_housenr' => $postData['billing_housenr'],
            'billing_country' => $postData['billing_country'],
            'billing_post_code' => $postData['billing_post_code'],
            'billing_city' => $postData['billing_city'],
        ];
        if (session()->has('logged_user')) {

            $shippingData = [
                'first_name' => $postData['shipping_first_name'],
                'last_name' => $postData['shipping_last_name'],
                'company' => $postData['shipping_company'],
                'street' => $postData['shipping_street'],
                'housenr' => $postData['shipping_housenr'],
                'country' => $postData['shipping_country'],
                'post_code' => $postData['shipping_post_code'],
                'city' => $postData['shipping_city'],

            ];

            $billingData = [
                'first_name' => $postData['billing_first_name'],
                'last_name' => $postData['billing_last_name'],
                'company' => $postData['billing_company'],
                'street' => $postData['billing_street'],
                'housenr' => $postData['billing_housenr'],
                'country' => $postData['billing_country'],
                'post_code' => $postData['billing_post_code'],
                'city' => $postData['billing_city'],
            ];

            $userId = session()->get('logged_user');
            $saveBillingAddress = isset($_POST['save_billing_address']);
            $saveShippingAddress = isset($_POST['save_shipping_address']);
            if ($saveBillingAddress) {
                $updateBillingResult = $this->Public_model->updateBillingAddress($userId, $billingData);
                if ($updateBillingResult === false) {
                    // Handle error
                    session()->setFlashdata('submit_error', 'Failed to update billing address.');
                    return false;
                }
            }

            if ($saveShippingAddress) {
                $updateShippingResult = $this->Public_model->updateShippingAddress($userId, $shippingData);
                if ($updateShippingResult === false) {
                    // Handle error
                    session()->setFlashdata('submit_error', 'Failed to update shipping address.');
                    return false;
                }
            }

        }
        // Check if updates were successful

        // Set shipping and billing addresses in session
        session()->set('shipping_address', $shippingAddress);
        session()->set('billing_address', $billingAddress);

        // Set if shipping address is the same as billing address
        session()->set('same_address', isset($postData['sameShipping']) ? $postData['sameShipping'] : false);

        // Set additional notes if provided
        if(isset($postData['notes'])) {
            session()->set('notes', $postData['notes']);
        }

        // Set data protection agreement if provided
        if(isset($postData['post_dataprotection'])) {
            session()->set('post_dataprotection', $postData['post_dataprotection']);
        }

        // Validate user input
        $errors = $this->userInfoValidate($postData);
        if (!empty($errors)) {
            // If errors exist, set flash data for errors
            session()->setFlashdata('submit_error', $errors);
            return false; // Return false to indicate validation failed
        } else {

            // If validation passes, redirect to the next step
            return true;
        }
    }
    private function processUserFormData($userData)
    {
        session()->set('email', $userData->email);
        session()->set('phone', $userData->phone);

        $shippingAddress = [
            'shipping_first_name' => $userData->shipping_first_name,
            'shipping_last_name' => $userData->shipping_last_name,
            'shipping_company' => $userData->shipping_company,
            'shipping_street' => $userData->shipping_street,
            'shipping_housenr' => $userData->shipping_street,
            'shipping_country' =>  $userData->shipping_housenr,
            'shipping_post_code' => $userData->shipping_country,
            'shipping_city' => $userData->shipping_post_code,
        ];
        $billingAddress = [
            'billing_first_name' => $userData->billing_first_name,
            'billing_last_name' => $userData->billing_last_name,
            'billing_company' => $userData->billing_company,
            'billing_street' => $userData->billing_street,
            'billing_housenr' => $userData->billing_housenr,
            'billing_country' => $userData->billing_country,
            'billing_post_code' => $userData->billing_post_code,
            'billing_city' => $userData->billing_city,
        ];
        // Set shipping and billing addresses in session
        session()->set('shipping_address', $shippingAddress);
        session()->set('billing_address', $billingAddress);
            $shippingData = [
                'first_name' => $userData->shipping_first_name,
                'last_name' => $userData->shipping_last_name,
                'company' => $userData->shipping_company,
                'street' => $userData->shipping_street,
                'housenr' => $userData->shipping_housenr,
                'country' => $userData->shipping_country,
                'post_code' => $userData->shipping_post_code,
                'city' => $userData->shipping_city,

            ];

            $billingData = [
                'first_name' => $userData->billing_first_name,
                'last_name' => $userData->billing_last_name,
                'company' => $userData->billing_company,
                'street' => $userData->billing_street,
                'housenr' => $userData->billing_housenr,
                'country' => $userData->billing_country,
                'post_code' => $userData->billing_post_code,
                'city' => $userData->billing_city,
            ];

            $userId = session()->get('logged_user');
            $updateShippingResult = $this->Public_model->updateShippingAddress($userId, $shippingData);
            $updateBillingResult = $this->Public_model->updateBillingAddress($userId, $billingData);

            if ($updateShippingResult === false || $updateBillingResult === false) {
                session()->setFlashdata('submit_error', 'Failed to update addresses.');
                return false;
            }


        // ... Additional processing and validation ...

        return true; // or false based on validation
    }

    private function mapAddressData($data, $prefix, $stripPrefix = false)
    {
        $mappedData = [];
        foreach ($data as $key => $value) {
            $isObj = is_object($data);
            $actualKey = $isObj ? $key : $prefix . $key;
            $newKey = $stripPrefix ? str_replace($prefix, '', $actualKey) : $actualKey;

            if ($isObj) {
                $mappedData[$newKey] = $data->$key;
            } else {
                $mappedData[$newKey] = $value;
            }
        }
        return $mappedData;
    }

    private function goToDestination()
    {
        if ($_POST['payment_type'] == 'cashOnDelivery' || $_POST['payment_type'] == 'Bank') {
            $this->shoppingcart->clearShoppingCart();
            session()->setFlashdata('success_order', true);
        }
        if ($_POST['payment_type'] == 'Bank') {
            $_SESSION['order_id'] = $this->orderId;
            $_SESSION['final_amount'] = $_POST['final_amount'] . $_POST['amount_currency'];
            return redirect()->to(LANG_URL . '/checkout/successbank');
        }
        if ($_POST['payment_type'] == 'cashOnDelivery') {
            return redirect()->to(LANG_URL . '/checkout/successcash');
        }
        if ($_POST['payment_type'] == 'PayPal') {
            @set_cookie('paypal', $this->orderId, 2678400);
            $_SESSION['discountAmount'] = $_POST['discountAmount'];
            return redirect()->to(LANG_URL . '/checkout/paypalpayment');
        }
    }

    private function userInfoValidate($post)
    {
        $errors = array();
        if (mb_strlen(trim($post['shipping_first_name'])) == 0||mb_strlen(trim($post['billing_first_name'])) == 0) {
            $errors[] = lang_safe('first_name_empty');
        }
        if (mb_strlen(trim($post['shipping_last_name'])) == 0||mb_strlen(trim($post['billing_last_name'])) == 0) {
            $errors[] = lang_safe('last_name_empty');
        }
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = lang_safe('invalid_email');
        }
        // $post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
        // if (mb_strlen(trim($post['phone'])) == 0) {
        //     $errors[] = lang_safe('invalid_phone');
        // }
        if (mb_strlen(trim($post['shipping_street'])) == 0 ||mb_strlen(trim($post['billing_street'])) == 0) {
            $errors[] = lang_safe('invalid_street');
        }
        $post['shipping_housenr'] = preg_replace("/[^0-9]/", '', $post['shipping_housenr']);
        $post['billing_housenr'] = preg_replace("/[^0-9]/", '', $post['billing_housenr']);

        if (mb_strlen(trim($post['shipping_housenr'])) == 0||mb_strlen(trim($post['billing_housenr'])) == 0) {
            $errors[] = lang_safe('invalid_housenr');
        }

        if (mb_strlen(trim($post['shipping_country'])) == 0||mb_strlen(trim($post['billing_country'])) == 0) {
            $errors[] = lang_safe('invalid_country');
        }
        if (mb_strlen(trim($post['shipping_city'])) == 0||mb_strlen(trim($post['billing_city'])) == 0) {
            $errors[] = lang_safe('invalid_city');
        }
        $post['shipping_post_code'] = preg_replace("/[^0-9]/", '', $post['shipping_post_code']);
        $post['billing_post_code'] = preg_replace("/[^0-9]/", '', $post['billing_post_code']);

        if (mb_strlen(trim($post['shipping_post_code'])) == 0 ||mb_strlen(trim($post['billing_post_code'])) == 0) {
            $errors[] = lang_safe('invalid_post_code');
        }
        if(!isset($_POST['post_dataprotection'])){
            $errors[] = lang_safe('invalid_dataprotection');
        }

        return $errors;
    }

    public function orderError()
    {
        if (session('order_error')) {
            $data = array();
            $head = array();
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            return $this->render('checkout_parts/order_error', $head, $data);
        } else {
            return redirect()->to(LANG_URL . '/checkout1');
        }
    }

    public function login()
    {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('pass');
            $usersController = new Users();
            if ($usersController->performLogin($email, $password)) {
                return redirect()->to(LANG_URL . '/checkout1');
            } else {
                session()->setFlashdata('loginError', lang_safe('wrong_user'));
                return redirect()->to(LANG_URL . '/checkout1')->withInput();
            }
    }
    public function guestCheckout()
    {
        $head = array();
        $data = array();
        $arrSeo = $this->Public_model->getSeo('checkout0');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        if (session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/checkout1');
        }

        return $this->render('checkout1/unauth_checkout1', $head, $data); // Update with the correct view path
    }
    public function getCountries()
    {
        return $countries = [
            'Deutschland',
            'Belgien',
            'Bulgarien',
            'Dänemark',
            'Estland',
            'Finnland',
            'Griechenland',
            'Kroatien',
            'Lettland',
            'Litauen',
            'Luxemburg',
            'Malta',
            'Monaco',
            'Niederlande',
            'Österreich',
            'Polen',
            'Portugal',
            'Rumänien',
            'Schweden',
            'Slowakei',
            'Slowenien',
            'Spanien',
            'Tschechische Republik',
            'Ungarn',
            'Zypern',
        ];
    }

}