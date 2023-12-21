<?php

namespace App\Controllers;

use App\Models\Public_model;

class Account extends BaseController
{
    protected $Public_model;
    private $num_rows = 5;
    public function __construct()
    {
        $this->Public_model = new Public_model();
    }

    public function account($page = 0)
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }

        if (isset($_POST['update'])) {
            $userId = session()->get('logged_user');
            $userInfo = $this->Public_model->getUserWithAddressesByID($userId);
            // Check and handle password update
            if (!empty($_POST['new_password']) && isset($_POST['change_password_flag']) && $_POST['change_password_flag'] == '1') {
                // Hash the input current password
                $hashedCurrentPassword = hash('sha256', $_POST['current_password']);
                // Compare with the stored password
                if ($hashedCurrentPassword != $userInfo['password']) {
                    session()->setFlashdata('error', 'Current password does not match');
                    return redirect()->to(current_url());
                }
                if (!$this->password_check($_POST['new_password'])) {
                    session()->setFlashdata('error', lang_safe('invalid_password'));
                    return redirect()->to(current_url());
                }
                // Proceed with password update
                $passwordData = [
                    'id' => $_SESSION['logged_user'],
                    'pass' => $_POST['new_password']
                ];
                $this->Public_model->updatePassword($passwordData);
            }
                $userData = [
                    'email' => $this->request->getPost('email'),
                    'first_name' => $this->request->getPost('first_name'),
                    'last_name' => $this->request->getPost('last_name'),
                    'phone' => $this->request->getPost('phone'),
                    'mobile' => $this->request->getPost('mobile'),
                    'language' => $this->request->getPost('language'),
                    'street' => $this->request->getPost('street'),
                    'housenr' => $this->request->getPost('housenr'),
                    'country' => $this->request->getPost('country'),
                    'post_code' => $this->request->getPost('post_code'),
                    'city' => $this->request->getPost('city'),
                    'billing_id' => $userInfo['billing_address_id']
                ];

                $this->Public_model->updateProfile($userId, $userData);

                session()->setFlashdata('success', 'Success');
                return redirect()->to(current_url());
        }

        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserWithAddressesByID($_SESSION['logged_user']);

        return $this->render('account', $head, $data);
    }

    public function delete()
    {
        $userId = session()->get('logged_user');
        if ($userId) {
            // Attempt to delete the user account and store the result
            $deleteResult = $this->Public_model->deleteUserAccount($userId);

            if ($deleteResult) {
                // If deletion is successful, log the user out
                session()->destroy();

                // Redirect to the homepage with a success message
                session()->setFlashdata('account_deleted', 'Your account has been successfully deleted.');
                return redirect()->to(LANG_URL);
            } else {
                // If deletion fails, do not destroy the session and return back with an error message
                session()->setFlashdata('error', 'There was a problem deleting your account.');
                return redirect()->back(); // This will redirect the user to the previous page
            }
        }

        // If the user is not logged in or there's another error, handle accordingly
        session()->setFlashdata('error', 'You must be logged in to delete your account.');
        return redirect()->to(LANG_URL . '/login');
    }
    private function password_check($password)
    {
        if (strlen($password) < 8 ||
            preg_match('/\s/', $password) ||
            !preg_match('/[#?!@$%^&*-]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[a-zA-Z]/', $password)) {
            return false;
        }
        return true;
    }

}
