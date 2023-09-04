<?php
namespace App\Controllers\Admin\Settings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Titles_model;
use App\Models\admin\Languages_model;

class Titles extends ADMIN_Controller
{

    protected $Titles_model;
    protected $Languages_model;

    public function __construct()
    {
        $this->Titles_model = new Titles_model();
        $this->Languages_model = new Languages_model();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Titles / Descriptions';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_POST['save'])) {
            $this->Titles_model->setSeoPageTranslations($_POST);
            $this->saveHistory('Changed Titles / Descriptions');
            session()->setFlashdata('result_publish', 'Saved successful!');
            return redirect()->to('admin/titles');
        }

        $data['seo_trans'] = $this->Titles_model->getSeoTranslations();
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['seo_pages'] = $this->Titles_model->getSeoPages();
        $this->saveHistory('Go to Titles / Descriptions page');
        $page = 'settings/titles';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
