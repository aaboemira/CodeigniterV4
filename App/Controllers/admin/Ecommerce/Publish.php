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
            session()->setFlashdata('result_publish', lang_safe('product_publish_success'));
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
        $file = $this->request->getFile('userfile');
        $newName = '';
        // Check if a file was uploaded and is valid
        if ($file->isValid() && !$file->hasMoved()) {
            // Validate MIME type
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            $this->logger->info($file->getMimeType());
            
            
            if (in_array($file->getMimeType(), $allowedMimeTypes)) {
                $uploadPath = './attachments/shop_images/'; // Ensure this path is correct and writable
                $extension = $file->getClientExtension(); // Extract the file extension
                if((!empty($_POST['image_name'][0])))
                $originalName = $_POST['image_name'][0] . '.' . $extension;
                else $originalName = $file->getRandomName(); // Append the extension to the provided name
                // Move the original file
                $file->move($uploadPath, $originalName);
                $newName = $originalName; // Keep track of the new name
                
                // Proceed to create resized versions
                $this->resizeAndSaveImage($uploadPath, $originalName, 380, 380);
                $this->resizeAndSaveImage($uploadPath, $originalName, 1200, 1200);
            } else {
                log_message('error', 'Unsupported image type: ' . $file->getMimeType());
            }
        }
    
        if ($newName == '') {
            log_message('error', 'Image Upload Error');
        }
        return $newName;
    }
    
    private function resizeAndSaveImage($uploadPath, $filename, $width, $height)
    {
        $imageService = \Config\Services::image();
        $fileInfo = pathinfo($filename);
        $newFilename = "{$fileInfo['filename']}-{$width}x{$height}.{$fileInfo['extension']}";
        
        $imageService->withFile($uploadPath . $filename)
                     ->fit($width, $height, 'center')
                     ->save($uploadPath . $newFilename);
        
    }
    
    /*
     * called from ajax
     */

     public function do_upload_others_images()
{
    if ($this->request->isAJAX()) {
        $upath = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . $this->request->getPost('folder') . DIRECTORY_SEPARATOR;
        if (!file_exists($upath)) {
            mkdir($upath, 0777, true);
        }

        // Get all uploaded files
        $files = $this->request->getFiles();
        // Get the image name from POST request
        $imageName = $this->request->getPost('image_name');
        // Initialize a counter
        $counter = 1;

        if ($files) {
            foreach ($files['others'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $allowedMimeTypes = ['image/jpeg', 'image/png'];
                    if (in_array($file->getMimeType(), $allowedMimeTypes)) {
                        // Use imageName with counter if not empty, otherwise use a random name
                        $fileExtension = $file->getClientExtension();
                        $newName = !empty($imageName) ? $imageName . '(' . $counter . ').' . $fileExtension : $file->getRandomName();

                        $file->move($upath, $newName);

                        // Create resized versions for each uploaded file
                        $this->resizeAndSaveImage($upath, $newName, 380, 380);
                        $this->resizeAndSaveImage($upath, $newName, 1200, 1200);

                        // Increment the counter for the next file
                        $counter++;
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
                        // Skip the resized images based on naming convention
                        if (is_file($dir . $file) && !preg_match('/-\d+x\d+/', $file)) {
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
             $baseDir = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . $_POST['folder'] . DIRECTORY_SEPARATOR;
             $originalImg = $baseDir . $_POST['image'];
             
             // Delete the original image
             if (file_exists($originalImg)) {
                 unlink($originalImg);
             }
             
             // Attempt to delete resized versions of the image
             $fileInfo = pathinfo($originalImg);
             $filenameWithoutExt = $fileInfo['filename'];
             $extension = $fileInfo['extension'];
     
             // Define the sizes you want to check for and delete
             $sizes = ['380x380', '1200x1200'];
             foreach ($sizes as $size) {
                 // Construct the filename for the resized image
                 $resizedImg = "{$baseDir}{$filenameWithoutExt}-{$size}.{$extension}";
                 if (file_exists($resizedImg)) {
                     unlink($resizedImg); // Delete the resized image
                 }
             }
         }
     }
     

}
