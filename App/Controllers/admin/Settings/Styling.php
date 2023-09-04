<?php
namespace App\Controllers\Admin\Settings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Home_admin_model;

class Styling extends ADMIN_Controller
{
    protected $Home_admin_model;

    public function __construct()
    {
        $this->Home_admin_model = new Home_admin_model();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Styling';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_POST['newStyle'])) {
            $this->Home_admin_model->setValueStore('newStyle', $_POST['newStyle']);
            $this->saveHistory('Change site styling');
            return redirect()->to('admin/styling');
        }

        $data['newStyle'] = $this->Home_admin_model->getValueStore('newStyle');
        $this->saveHistory('Go to Styling page');
        $page = 'settings/styling';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
