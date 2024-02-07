<?php

namespace App\Controllers;

use App\Models\Public_model;

use App\Libraries\SendMail;

class Address extends BaseController
{

    private $registerErrors = array();

    protected $Public_model;


    public function __construct()
    {
        $this->Public_model = new Public_model();
        $this->sendmail = new SendMail();
        $this->validation = \Config\Services::validation();

    }



    public function index()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $userId = session()->get('logged_user');

        $head = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);

        $data = array();
        $data['countries'] = $this->getCountries();
        $data['userAddresses'] = $this->Public_model->getUserAddressesByID($userId);

        return $this->render('address/show_address', $head, $data); // Updated view name
    }

    public function edit()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $userId = session()->get('logged_user');
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);


        $data = array();
        $data['countries'] = $this->getCountries();
        $data['userAddresses'] = $this->Public_model->getUserAddressesByID($userId);

        return $this->render('address/editAddress', $head, $data); // Keep existing view name for editing
    }
    public function updateAddresses()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $userId = session()->get('logged_user');
 
        // Validate and sanitize input data
        $input = $this->request->getPost();

        $errors = $this->validateAddressInfo($input);
        if (!empty($errors)) {
            session()->setFlashdata('submit_error', $errors);
            return redirect()->back()->withInput();
        }
        // Add your validation logic here. This is very important for security and data integrity.

        $billingAddress = [
            'first_name' => $input['billing_first_name'],
            'last_name'  => $input['billing_last_name'],
            'company'    => $input['billing_company'],
            'street'     => $input['billing_street'],
            'housenr'    => $input['billing_housenr'],
            'country'    => $input['billing_country'],
            'post_code'  => $input['billing_post_code'],
            'city'       => $input['billing_city']
        ];

        $shippingAddress = [
            'first_name' => $input['shipping_first_name'],
            'last_name'  => $input['shipping_last_name'],
            'company'    => $input['shipping_company'],
            'street'     => $input['shipping_street'],
            'housenr'    => $input['shipping_housenr'],
            'country'    => $input['shipping_country'],
            'post_code'  => $input['shipping_post_code'],
            'city'       => $input['shipping_city']
        ];

        // Call the model's update method
        $result = $this->Public_model->updateUserAddressesById($userId, $billingAddress, $shippingAddress);

        if ($result) {
            // Set a success message in session and redirect
            session()->setFlashdata('success', lang_safe('address_update_success'));
        } else {
            // Set an error message in session and redirect
            session()->setFlashdata('error', lang_safe('address_update_error'));
        }

        return redirect()->to(LANG_URL . '/address');
    }
    private function getCountries()
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
    private function validateAddressInfo($post)
    {
        $errors = [];
    
        // Validate first names
        if (mb_strlen(trim($post['shipping_first_name'])) == 0 || mb_strlen(trim($post['billing_first_name'])) == 0) {
            $errors[] = lang_safe('first_name_empty');
        }
    
        // Validate last names
        if (mb_strlen(trim($post['shipping_last_name'])) == 0 || mb_strlen(trim($post['billing_last_name'])) == 0) {
            $errors[] = lang_safe('last_name_empty');
        }
    
        // Validate streets
        if (mb_strlen(trim($post['shipping_street'])) == 0 || mb_strlen(trim($post['billing_street'])) == 0) {
            $errors[] = lang_safe('invalid_street');
        }
    
        // Sanitize and validate house numbers
        $post['shipping_housenr'] = preg_replace("/[^0-9]/", '', $post['shipping_housenr']);
        $post['billing_housenr'] = preg_replace("/[^0-9]/", '', $post['billing_housenr']);
        if (mb_strlen(trim($post['shipping_housenr'])) == 0 || mb_strlen(trim($post['billing_housenr'])) == 0) {
            $errors[] = lang_safe('invalid_housenr');
        }
    
        // Validate countries
        if (mb_strlen(trim($post['shipping_country'])) == 0 || mb_strlen(trim($post['billing_country'])) == 0) {
            $errors[] = lang_safe('invalid_country');
        }
    
        // Validate cities
        if (mb_strlen(trim($post['shipping_city'])) == 0 || mb_strlen(trim($post['billing_city'])) == 0) {
            $errors[] = lang_safe('invalid_city');
        }
    
        // Sanitize and validate post codes
        $post['shipping_post_code'] = preg_replace("/[^0-9]/", '', $post['shipping_post_code']);
        $post['billing_post_code'] = preg_replace("/[^0-9]/", '', $post['billing_post_code']);
        if (mb_strlen(trim($post['shipping_post_code'])) == 0 || mb_strlen(trim($post['billing_post_code'])) == 0) {
            $errors[] = lang_safe('invalid_post_code');
        }

        return $errors;
    }
    
}