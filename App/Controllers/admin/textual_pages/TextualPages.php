<?php
namespace App\Controllers\Admin\textual_pages;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Textual_pages_model;

class TextualPages extends ADMIN_Controller
{
    protected $Textual_pages_model;

    public function __construct()
    {
        $this->Textual_pages_model = new Textual_pages_model();
    }

    public function pageEdit($page = null)
    {
        $this->login_check();
        if ($page == null) {
            return redirect()->to('admin/pages');
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Pages Manage';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['page'] = $this->Textual_pages_model->getOnePageForEdit($page);
        if (empty($data['page'])) {
            return redirect()->to('admin/pages');
        }
        if (isset($_POST['updatePage'])) {
            $this->Textual_pages_model->setEditPageTranslations($_POST);
            $this->saveHistory('Page ' . $_POST['pageId'] . ' updated!');
            return redirect()->to('admin/pageedit/' . $page);
        }

        $this->saveHistory('Edit page - ' . $page);
        $page = 'textual_pages/pageEdit';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    public function changePageStatus()
    {
        $this->login_check();
        $result = $this->Textual_pages_model->changePageStatus($_POST['id'], $_POST['status']);
        if ($result == true) {
            echo 1;
        } else {
            echo 0;
        }
        $this->saveHistory('Page status Changed');
    }

}
