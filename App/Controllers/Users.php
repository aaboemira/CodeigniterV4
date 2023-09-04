<?php

namespace App\Controllers;

class Users extends BaseController
{

    private $registerErrors = array();
    private $user_id;
    private $num_rows = 5;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
    }

    public function index()
    {
        show_404();
    }

    public function login()
    {
        if (isset($_POST['login'])) {
            $result = $this->Public_model->checkPublicUserIsValid($_POST);
            if ($result !== false) {
                $_SESSION['logged_user'] = $result; //id of user
                return redirect()->to(LANG_URL . '/checkout');
            } else {
                session()->setFlashdata('userError', lang_safe('wrong_user'));
            }
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('user_login');
        $head['description'] = lang_safe('user_login');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('login', $head, $data);
    }

    public function register()
    {
        if (isset($_POST['signup'])) {
            $result = $this->registerValidate();
            if ($result == false) {
                session()->setFlashdata('userError', $this->registerErrors);
                return redirect()->to(LANG_URL . '/register');
            } else {
                $_SESSION['logged_user'] = $this->user_id; //id of user
                return redirect()->to(LANG_URL . '/checkout');
            }
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('user_register');
        $head['description'] = lang_safe('user_register');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('signup', $head, $data);
    }

    public function myaccount($page = 0)
    {
        if (isset($_POST['update'])) {
            $_POST['id'] = $_SESSION['logged_user'];
            $count_emails = $this->Public_model->countPublicUsersWithEmail($_POST['email'], $_POST['id']);
            if ($count_emails == 0) {
                $this->Public_model->updateProfile($_POST);
            }
            return redirect()->to(LANG_URL . '/myaccount');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
        $rowscount = $this->Public_model->getUserOrdersHistoryCount($_SESSION['logged_user']);
        $data['orders_history'] = $this->Public_model->getUserOrdersHistory($_SESSION['logged_user'], $this->num_rows, $page);
        $data['links_pagination'] = pagination('myaccount', $rowscount, $this->num_rows, 2);
        return $this->render('user', $head, $data);
    }

    public function logout()
    {
        unset($_SESSION['logged_user']);
        return redirect()->to(LANG_URL);
    }

    private function registerValidate()
    {
        $errors = array();
        if (mb_strlen(trim($_POST['name'])) == 0) {
            $errors[] = lang_safe('please_enter_name');
        }
        if (mb_strlen(trim($_POST['phone'])) == 0) {
            $errors[] = lang_safe('please_enter_phone');
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = lang_safe('invalid_email');
        }
        if (mb_strlen(trim($_POST['pass'])) == 0) {
            $errors[] = lang_safe('enter_password');
        }
        if (mb_strlen(trim($_POST['pass_repeat'])) == 0) {
            $errors[] = lang_safe('repeat_password');
        }
        if ($_POST['pass'] != $_POST['pass_repeat']) {
            $errors[] = lang_safe('passwords_dont_match');
        }

        $count_emails = $this->Public_model->countPublicUsersWithEmail($_POST['email']);
        if ($count_emails > 0) {
            $errors[] = lang_safe('user_email_is_taken');
        }
        if (!empty($errors)) {
            $this->registerErrors = $errors;
            return false;
        }
        $this->user_id = $this->Public_model->registerUser($_POST);
        return true;
    }

}