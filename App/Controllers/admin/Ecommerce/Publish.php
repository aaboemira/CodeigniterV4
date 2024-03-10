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
            $trans_load = $this->Products_model->getTranslationsWithImages($id);
        }
        $languages = $this->Languages_model->getLanguagesAbbr(); // Get language abbreviations
        if (isset($_POST['submit'])) {
            if (isset($_GET['to_lang'])) {
                $id = 0;
            }
            if($id>0)$_POST['image']=$_POST['old_image']['de'];
            else $_POST['image']='0';
            $productID=$this->Products_model->setProduct($_POST, $id);
            foreach ($languages as $langAbbr) {
                $data['otherImgs'][$langAbbr] = $this->loadOthersImages($langAbbr);
                $imageName = $this->uploadImage($langAbbr);
                if($langAbbr=='de'&& $imageName !=''){
                    $this->Products_model->updateMainImage($productID,$imageName);
                }
                if ($imageName) {
                    $imageData = [
                        'product_id' => $productID,
                        'language' => $langAbbr,
                        'image_name' => $imageName,
                        'folder_name'=>$_POST['folder']
                    ];
                    if($id > 0){
                        $imgID =$this->Products_model->getImageId($productID,$langAbbr);
                        if($imgID)$this->Products_model->updateProductImage($imgID,$imageData);
                        else $this->Products_model->insertProductImage($imageData);
                    }else{
                        $this->Products_model->insertProductImage($imageData);
                    }

                }
            }


            // Check if the product has variants and if any fields are selected for updating
            if ( isset($_POST['variants_fields']) && !empty($_POST['variants_fields'])) {
                // Get the IDs of all variants for the current product
                $variantIDs = $this->Products_model->getVariantIDs($_POST['variant_id']);
                $this->logger->info($variantIDs);

                // Prepare the selected fields array
                $selectedFields = $_POST['variants_fields'];
            
                // Loop through each variant ID
                foreach ($variantIDs as $variantID) {
                    // Prepare the data to be updated for each variant
                    $variantData = [];
                    foreach ($selectedFields as $field) {
                        if (isset($_POST[$field])) {
                            $variantData[$field] = $_POST[$field];
                        }
                    }
            
                    // Prepare translations data for each language
                    $variantData['translations'] = [];
                    foreach ($languages as $language) {
                        $langData = [];
                        $langIndex = $language == 'de' ? 0 : 1; // Adjust the index based on the language abbreviation
                        foreach ($selectedFields as $field) {
                            if (isset($_POST[$field][$langIndex])) {
                                $langData[$field] = $_POST[$field][$langIndex];
                            }
                        }
                        $variantData['translations'][$language] = $langData;
                    }
            
                    // Update the variant with the new data
                   $this->logger->info( $this->Products_model->updateVariant($variantID, $variantData, $selectedFields));

                }
            }
            
            
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
        $data['otherImgs'] = [];
        foreach ($languages as $language) {
            $data['otherImgs'][$language] = $this->loadOthersImages($language);
        }
        $validation = \Config\Services::validation();
        $data['validation'] = $validation;
        $data['showBrands'] = 1;
        $data['virtualProducts'] = 1;
        $this->saveHistory('Go to publish product');
        $page = 'ecommerce/publish';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    private function uploadImage($langAbbr)
    {
        $file = $this->request->getFile('cover_image_' . $langAbbr);
        $newName = '';
        if ($file->isValid() && !$file->hasMoved()) {
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (in_array($file->getMimeType(), $allowedMimeTypes)) {
                $uploadPath = './attachments/shop_images/' . $langAbbr . '/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                $extension = $file->getClientExtension();
                $imageName = $this->generateImageName($file, $langAbbr);
                $newName  = $imageName . '.' . $extension;
    
                

                // Move and resize the original image to 650x650
                $file->move($uploadPath, $newName, true);
                // Save the original image with the -org suffix
                $originalName = $imageName . '-org.' . $extension;
                copy($uploadPath . $newName, $uploadPath . $originalName);
                $this->resizeAndSaveImage($uploadPath, $newName, 650, 650, false);
    
                // Create a resized version of 1200x1200
                $this->resizeAndSaveImage($uploadPath, $newName, 1200, 1200, true);
                $this->resizeAndSaveImage($uploadPath, $newName, 2400, 2400, true);


            }
        }
        return $newName ? $langAbbr . '/' . $newName : '';
    }
    
    
    
    
    private function resizeAndSaveImage($uploadPath, $filename, $width, $height, $keepOriginal)
    {
        $imageService = \Config\Services::image();
        $fileInfo = pathinfo($filename);
        $newFilename = $keepOriginal ? "{$fileInfo['filename']}-{$width}x{$height}.{$fileInfo['extension']}" : $filename;
    
        if ($keepOriginal && file_exists($uploadPath . $newFilename)) {
            unlink($uploadPath . $newFilename);
        }
    
        $imageService->withFile($uploadPath . $filename)
        ->resize($width, $height, true, 'width')
        ->save($uploadPath . $newFilename, 100); // Set quality to 90 for JPEG images

        // If not keeping the original, rename the resized file to the original filename
        if (!$keepOriginal) {
            rename($uploadPath . $newFilename, $uploadPath . $filename);
        }
    }
    
    private function generateImageName($file, $langAbbr)
    {
        $index = $langAbbr == 'de' ? 0 : ($langAbbr == 'en' ? 1 : null);
        $imageName = $index !== null && isset($_POST['image_name'][$index]) && !empty($_POST['image_name'][$index])
                     ? str_replace(' ', '-', $_POST['image_name'][$index])
                     : pathinfo($file->getRandomName(), PATHINFO_FILENAME);
        return $imageName;
    }
        
    
    
    /*
     * called from ajax
     */

     public function do_upload_others_images()
     {
         if ($this->request->isAJAX()) {
             $langAbbr = $this->request->getPost('lang_abbr'); // Get the language abbreviation from the POST data
             $upath = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images' . DIRECTORY_SEPARATOR . $langAbbr . DIRECTORY_SEPARATOR . $this->request->getPost('folder') . DIRECTORY_SEPARATOR;
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
                 foreach ($files['others_'.$langAbbr] as $file) {
                     if ($file->isValid() && !$file->hasMoved()) {
                         $allowedMimeTypes = ['image/jpeg', 'image/png'];
                         if (in_array($file->getMimeType(), $allowedMimeTypes)) {
                             // Use imageName with counter if not empty, otherwise use a random name
                             $fileExtension = $file->getClientExtension();
                             $baseName = !empty($imageName) ? $imageName . '(' . $counter . ')' : pathinfo($file->getRandomName(), PATHINFO_FILENAME);
                             $newName = $baseName . '.' . $fileExtension;
                             $file->move($upath, $newName);
                             $originalName = $baseName . '-org.' . $fileExtension;
                             copy($upath . $newName, $upath . $originalName);
     
                             // Create resized versions for each uploaded file
                             $this->resizeAndSaveImage($upath, $newName, 650, 650, false);
                             $this->resizeAndSaveImage($upath, $newName, 1200, 1200, true);
     
                             // Increment the counter for the next file
                             $counter++;
                         }
                     }
                 }
             }
         }
     }
     
     
     

     

     public function loadOthersImages($langAbbr = null)
     {
         $output = '';
         $folder = $_POST['folder'] ?? null;
     
         // If it's an AJAX request, use the langAbbr from POST data
         if ($this->request->isAJAX() && !$langAbbr) {
             $langAbbr = $this->request->getPost('lang_abbr');
         }
     
         if ($folder) {
             $dirPath = 'attachments/shop_images/';
             $dirPath .= $langAbbr ? $langAbbr . '/' : '';
             $dirPath .= $folder . '/';
     
             if (is_dir($dirPath)) {
                 if ($dh = opendir($dirPath)) {
                     $i = 0;
                     while (($file = readdir($dh)) !== false) {
                         if (is_file($dirPath . $file) && !preg_match('/-\d+x\d+/', $file)&& strpos($file, '-org') === false) {
                             $output .= '
                                 <div class="other-img" id="image-container-' . $i . '">
                                     <img src="' . base_url($dirPath . $file) . '" style="width:100px; height: 100px;">
                                     <a href="javascript:void(0);" onclick="removeSecondaryProductImage(\'' . $file . '\', \'' . $folder . '\', \'' . $langAbbr . '\', ' . $i . ')">
                                         <span class="glyphicon glyphicon-remove"></span>
                                     </a>
                                 </div>
                             ';
                             $i++;
                         }
                     }
                     closedir($dh);
                 }
             }
         }
     
         if ($this->request->isAJAX()) {
             echo $output;
             exit;
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
            $langAbbr = $this->request->getPost('abbr'); // Get the language abbreviation from POST data

             $baseDir = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images'.DIRECTORY_SEPARATOR.$langAbbr . DIRECTORY_SEPARATOR . $_POST['folder'] . DIRECTORY_SEPARATOR;
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
             $sizes = ['org', '1200x1200'];
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
