<?php

namespace App\Controllers;

use App\Models\admin\Blog_model;
use App\Models\Public_model;

class Blog extends BaseController
{

    private $num_rows = 20;

    protected $Public_model;
    protected $Blog_model;
    protected $arhives;

    public function __construct()
    {
        if (!in_array('blog', $this->nonDynPages)) {
            $this->show_404();
        }
        
        $this->load->helper(array('pagination'));
        $this->Public_model = new Public_model();
        $this->Blog_model = new Blog_model();
        $this->arhives = $this->Public_model->getArchives();
    }

    public function index($page = 0)
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('blog');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        if (isset($_GET['find'])) {
            $find = $_GET['find'];
        } else {
            $find = null;
        }
        if (isset($_GET['from']) && isset($_GET['to'])) {
            $month = $_GET;
        } else {
            $month = null;
        }
        $data['posts'] = $this->Public_model->getPosts($this->num_rows, $page, $find, $month);
        $data['archives'] = $this->getBlogArchiveHtml();
        $data['bestSellers'] = $this->Public_model->getbestSellers();
        $rowscount = $this->Blog_model->postsCount($find);
        $data['links_pagination'] = pagination('blog', $rowscount, $this->num_rows);
        return $this->render('blog', $head, $data);
    }

    public function viewPost($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $this->show_404();
        }
        $data = array();
        $head = array();
        $data['article'] = $this->Public_model->getOnePost($id);
        if ($data['article'] == null) {
            $this->show_404();
        }
        $data['archives'] = $this->getBlogArchiveHtml();
        $head['title'] = $data['article']['title'];
        $head['description'] = url_title(character_limiter(strip_tags($data['article']['description']), 130));
        $head['keywords'] = str_replace(" ", ",", $data['article']['title']);
        return $this->render('view_blog_post', $head, $data);
    }

    private function getBlogArchiveHtml()
    {
        $html = '
		<div class="alone title cloth-bg-color">
					<span>' . lang_safe('archive') . '</span>
				</div>
				';
        if ($this->arhives !== false) {

            $html .= '<ul class="blog-artchive">';

            foreach ($this->arhives as $archive) {
                $html .= '
					<li class="item">» <a href="' . LANG_URL . '/blog?from='
                        . $archive['mintime'] . '&to=' . $archive['maxtime'] . '">'
                        . $archive['month'] . '</a></li>
				';
            }
            $html .= '</ul>';
        } else {
            $html = '<div class="alert alert-info">' . lang_safe('no_archives') . '</div>';
        }
        return $html;
    }

}