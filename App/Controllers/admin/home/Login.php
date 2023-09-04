<?php
namespace App\Controllers\admin\home;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */

use App\Controllers\admin\ADMIN_Controller;

class Login extends ADMIN_Controller
{

    public function index()
    {
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Login';
        $head['description'] = '';
        $head['keywords'] = '';
        //$this->load->view('_parts/header', $head);
        if (session('logged_in')) {
            return redirect()->to('admin/home');
        } else {
            $validation = service('validation');
            $validation->setRules([
                'username' => 'trim|required',
                'password'    => 'trim|required'
            ]);
            if ($validation->run($this->request->getPost()) === true) {
                $result = $this->Home_admin_model->loginCheck($_POST);
                if (!empty($result)) {
                    $_SESSION['last_login'] = $result['last_login'];
                    session()->set('logged_in', $result['username']);
                    //$this->session->set_userdata('logged_in', $result['username']);
                    $this->saveHistory('User ' . $result['username'] . ' logged in');
                    return redirect()->to('admin/home');
                } else {
                    $this->saveHistory('Cant login with - User: ' . $_POST['username'] . ' and Pass: ' . $_POST['username']);
                    session()->setFlashdata('err_login', 'Wrong username or password!');
                    return redirect()->to('admin');
                }
            }
            return view('templates/admin/_parts/template', ['page'=> 'home/login', 'data' => $head ,'head' => $head, 'footer' => $head]);
        }
        $this->load->view('_parts/footer');
    }

}
