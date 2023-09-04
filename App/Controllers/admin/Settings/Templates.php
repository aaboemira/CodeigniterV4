<?php
namespace App\Controllers\admin\settings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Home_admin_model;

class Templates extends ADMIN_Controller
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
        $head['title'] = 'Administration - Templates';
        $head['description'] = '!';
        $head['keywords'] = '';
        if (isset($_POST['template'])) {
            $this->Home_admin_model->setValueStore('template', $_POST['template']);
            return redirect()->to('admin/templates');
        }
        $templates = scandir(TEMPLATES_DIR);
        foreach ($templates as $template) {
            if ($template != "." && $template != "..") {
                $data['templates'][] = $template;
            }
        }
        $data['seleced_template'] = $this->Home_admin_model->getValueStore('template');
        $this->saveHistory('Go to Templates Page');
        $page = 'settings/templates';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
