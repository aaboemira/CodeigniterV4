<?php
namespace App\Controllers\Admin\Blog;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Blog_model;
use App\Models\admin\Languages_model;

class BlogPublish extends ADMIN_Controller
{

    protected $Blog_model;
    protected $Languages_model;

    public function __construct()
    {
        $this->Blog_model = new Blog_model();
        $this->Languages_model = new Languages_model();
    }


    public function index($id = 0)
    {
        $this->login_check();
        $trans_load = null;
        if ($id > 0 && $_POST == null) {
            $_POST = $this->Blog_model->getOnePost($id);
            $trans_load = $this->Blog_model->getTranslations($id);
        }
        if (isset($_POST['submit'])) {
            $_POST['image'] = $this->uploadImage();
            $this->Blog_model->setPost($_POST, $id);
            session()->setFlashdata('result_publish', lang_safe('blog_publish_success'));
            return redirect()->to('admin/blog');
        }
        $data = array();
        $head = array();
        $data['id'] = $id;
        $head['title'] = 'Administration - Publish Blog Post';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['trans_load'] = $trans_load;
        $this->saveHistory('Go to Blog Publish');
        $page = 'blog/blogpublish';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    private function uploadImage()
    {
        $newName = '';
        $file = $this->request->getFile('userfile');
        // Check if a file was uploaded
        if ($file->isValid()) {
            // Validate file type using finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileMimeType = finfo_file($finfo, $file->getPathName());
            // Set allowed file types
            $allowedMimeTypes = ['image/jpg', 'image/jpeg','image/gif', 'image/png']; // Specify allowed MIME types
            if (in_array($fileMimeType, $allowedMimeTypes)) {
                // Set the target directory for file upload
                $newName = $file->getRandomName();
                $uploadPath = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'blog_images' . DIRECTORY_SEPARATOR;
                $file->move($uploadPath, $newName);
                if(!$file->hasMoved()) {
                    $newName = '';
                }
            }
        }

        if ($newName == '') {
            log_message('error', 'Image Upload Error: ');
        }
        return $newName;
    }

}
