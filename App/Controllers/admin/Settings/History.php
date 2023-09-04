<?php
namespace App\Controllers\Admin\Settings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\History_model;

class History extends ADMIN_Controller
{

    private $num_rows = 20;

    protected $History_model;

    public function __construct()
    {
        $this->History_model = new History_model();
    }

    public function index($page = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - History';
        $head['description'] = '!';
        $head['keywords'] = '';

        $rowscount = $this->History_model->historyCount();
        $data['actions'] = $this->History_model->getHistory($this->num_rows, $page);
        $data['links_pagination'] = pagination('admin/history', $rowscount, $this->num_rows, 3);
        $data['history'] = $this->history;
        if ($page == 0) {
            $this->saveHistory('Go to History');
        }

        $page = 'settings/history';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
