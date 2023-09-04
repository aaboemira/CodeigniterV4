<?php
namespace App\Controllers\Admin\Blog;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Blog_model;

class Blog extends ADMIN_Controller
{

    private $num_rows = 10;
    protected $Blog_model;

    public function __construct()
    {
        $this->Blog_model = new Blog_model();
    }

    public function index($page = 0)
    {
        $this->login_check();
        if (isset($_GET['delete'])) {
            $this->Blog_model->deletePost($_GET['delete']);
            return redirect()->to('admin/blog');
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Blog Posts';
        $head['description'] = '!';
        $head['keywords'] = '';


        if ($this->input->get('search') !== NULL) {
            $search = $this->input->get('search');
        } else {
            $search = null;
        }
        $data = array();
        $rowscount = $this->Blog_model->postsCount($search);
        $data['posts'] = $this->Blog_model->getPosts(null, $this->num_rows, $page, $search);
        $data['links_pagination'] = pagination('admin/blog', $rowscount, $this->num_rows, 3);
        $data['page'] = $page;

        $this->saveHistory('Go to Blog');
        $page = 'blog/blogposts';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
