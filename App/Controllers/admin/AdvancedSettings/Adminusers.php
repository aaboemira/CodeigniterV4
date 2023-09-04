<?php
namespace App\Controllers\Admin\AdvancedSettings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Admin_users_model;

class Adminusers extends ADMIN_Controller
{

    protected $Admin_users_model;

    public function __construct()
    {
        $this->Admin_users_model = new Admin_users_model();
    }


    public function index()
    {
        $this->login_check();
        if (isset($_GET['delete'])) {
            $this->Admin_users_model->deleteAdminUser($_GET['delete']);
            session()->setFlashdata('result_delete', 'User is deleted!');
            return redirect()->to('admin/adminusers');
        }
        if (isset($_GET['edit']) && !isset($_POST['username'])) {
            $_POST = $this->Admin_users_model->getAdminUsers($_GET['edit']);
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Admin Users';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['users'] = $this->Admin_users_model->getAdminUsers();
        
        if ($this->request->getMethod() === 'post') {
            $validation = service('validation');

            $rules = [
                'username' => 'trim|required',
            ];
            if (isset($_POST['edit']) && $_POST['edit'] == 0) {
                $rules['password'] = 'trim|required';
            }
            $validation->setRules($rules);
            if ($validation->run($this->request->getPost()) === true) {
                $this->Admin_users_model->setAdminUser($_POST);
                $this->saveHistory('Create admin user - ' . $_POST['username']);
                return redirect()->to('admin/adminusers');
            }
        }
        
        $this->saveHistory('Go to Admin Users');
        $page = 'advanced_settings/adminUsers';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
