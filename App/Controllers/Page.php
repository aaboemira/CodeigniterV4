<?php

namespace App\Controllers;

use App\Models\Public_model;

class Page extends BaseController
{
    protected $Public_model;

    public function __construct()
    {
        $this->Public_model = new Public_model();
    }

    public function index($page = null)
    {
        $this->goOut($page);
        $page = $this->Public_model->getOnePage($page);
        $this->goOut($page);
        $data = array();
        $head = array();
        $head['title'] = $page['name'];
        $head['description'] = character_limiter(strip_tags(trim($page['content'])), 120);
        $head['keywords'] = str_replace(" ", ",", $page['name']);
        $data['content'] = $page['content'];
        return $this->render('dynPage', $head, $data);
    }

    private function goOut($page)
    {
        if ($page == null) {
            return redirect()->to();
        }
    }

}
