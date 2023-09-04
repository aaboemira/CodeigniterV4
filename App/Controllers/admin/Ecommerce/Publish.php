<?php
namespace App\Controllers\Admin\Ecommerce;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Products_model;
use App\Models\admin\Languages_model;
use App\Models\admin\Brands_model;
use App\Models\admin\Categories_model;

class Publish extends ADMIN_Controller
{


    protected $Products_model;
    protected $Languages_model;
    protected $Brands_model;
    protected $Categories_model;

    public function __construct()
    {
        $this->Products_model = new Products_model();
        $this->Languages_model = new Languages_model();
        $this->Brands_model = new Brands_model();
        $this->Categories_model = new Categories_model();
    }

    public function index($id = 0)
    {
        $this->login_check();
        $is_update = false;
        $trans_load = null;
        if ($id > 0 && $_POST == null) {
            $_POST = $this->Products_model->getOneProduct($id);
            $trans_load = $this->Products_model->getTranslations($id);
        }
        if (isset($_POST['submit'])) {
            if (isset($_GET['to_lang'])) {
                $id = 0;
            }
            $_POST['image'] = $this->uploadImage();
            $this->Products_model->setProduct($_POST, $id);
            session()->setFlashdata('result_publish', 'Product is published!');
            if ($id == 0) {
                $this->saveHistory('Success published product');
            } else {
                $this->saveHistory('Success updated product');
            }
            if (isset($_SESSION['filter']) && $id > 0) {
                $get = '';
                foreach ($_SESSION['filter'] as $key => $value) {
                    $get .= trim($key) . '=' . trim($value) . '&';
                }
                return redirect()->to(base_url('admin/products?' . $get));
            } else {
                return redirect()->to('admin/products');
            }
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Publish Product';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['id'] = $id;
        $data['trans_load'] = $trans_load;
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['shop_categories'] = $this->Categories_model->getShopCategories();
        $data['brands'] = $this->Brands_model->getBrands();
        $data['otherImgs'] = $this->loadOthersImages();
        $validation = \Config\Services::validation();
        $data['validation'] = $validation;
        $data['showBrands'] = 1;
        $data['virtualProducts'] = 1;
        $this->saveHistory('Go to publish product');
        $page = 'ecommerce/publish';
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
            $allowedMimeTypes = ['image/jpg', 'image/jpeg','image/png']; // Specify allowed MIME types
            if (in_array($fileMimeType, $allowedMimeTypes)) {
                // Set the target directory for file upload
                $newName = $file->getRandomName();
                $uploadPath = './attachments/shop_images/';
                $file->move($uploadPath, $newName);
                if(!$file->hasMoved()) {
                    $newName = '';
                }
            }
        }
        if($newName == '') {
            log_message('error', 'Image Upload Error');
        }

        return $newName;
    }

    /*
     * called from ajax
     */

    public function do_upload_others_images()
    {
        if ($this->request->isAJAX()) {
            $upath = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . $_POST['folder'] . DIRECTORY_SEPARATOR;
            if (!file_exists($upath)) {
                mkdir($upath, 0777);
            }

            $files = $_FILES;
            $cpt = count($_FILES['others']['name']);
            for ($i = 0; $i < $cpt; $i++) {
                unset($_FILES);
                $_FILES['others']['name'] = $files['others']['name'][$i];
                $_FILES['others']['type'] = $files['others']['type'][$i];
                $_FILES['others']['tmp_name'] = $files['others']['tmp_name'][$i];
                $_FILES['others']['error'] = $files['others']['error'][$i];
                $_FILES['others']['size'] = $files['others']['size'][$i];

                $file = $this->request->getFile('others');
                // Check if a file was uploaded
                if ($file->isValid()) {
                    // Validate file type using finfo
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $fileMimeType = finfo_file($finfo, $file->getPathName());
                    // Set allowed file types
                    $allowedMimeTypes = ['image/jpg', 'image/jpeg','image/png']; // Specify allowed MIME types
                    if (in_array($fileMimeType, $allowedMimeTypes)) {
                        // Set the target directory for file upload
                        $newName = $file->getRandomName();
                        $uploadPath = $upath;
                        $file->move($uploadPath, $newName);
                        if($file->hasMoved()) {
                            
                        }
                    }
                }
            }
        }
    }

    public function loadOthersImages()
    {
        $output = '';
        if (isset($_POST['folder']) && $_POST['folder'] != null) {
            $dir = 'attachments' . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . $_POST['folder'] . DIRECTORY_SEPARATOR;
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    $i = 0;
                    while (($file = readdir($dh)) !== false) {
                        if (is_file($dir . $file)) {
                            $output .= '
                                <div class="other-img" id="image-container-' . $i . '">
                                    <img src="' . base_url('attachments/shop_images/' . $_POST['folder'] . '/' . $file) . '" style="width:100px; height: 100px;">
                                    <a href="javascript:void(0);" onclick="removeSecondaryProductImage(\'' . $file . '\', \'' . $_POST['folder'] . '\', ' . $i . ')">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </div>
                               ';
                        }
                        $i++;
                    }
                    closedir($dh);
                }
            }
        }
        if ($this->request->isAJAX()) {
            echo $output;
        } else {
            return $output;
        }
    }

    /*
     * called from ajax
     */

    public function removeSecondaryImage()
    {
        if ($this->request->isAJAX()) {
            $img = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . '' . $_POST['folder'] . DIRECTORY_SEPARATOR . $_POST['image'];
            unlink($img);
        }
    }

}
