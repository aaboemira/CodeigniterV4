<?php
namespace App\Controllers\Admin\Settings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Pages_model;

class Pages extends ADMIN_Controller
{

    protected $Pages_model;

    public function __construct()
    {
        $this->Pages_model = new Pages_model();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Pages Manage';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['pages'] = $this->Pages_model->getPages(null, true);
        if (isset($_POST['pname'])) {
            $this->Pages_model->setPage($_POST['pname']);
            $this->saveHistory('Add new page with name - ' . $_POST['pname']);
            return redirect()->to('admin/pages');
        }
        if (isset($_GET['delete'])) {
            $this->Pages_model->deletePage($_GET['delete']);
            $this->saveHistory('Delete page');
            return redirect()->to('admin/pages');
        }
        $this->saveHistory('Go to Pages manage');
        $page = 'settings/pages';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
        
    }

}
