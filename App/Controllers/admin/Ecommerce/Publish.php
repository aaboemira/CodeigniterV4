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
        $startTime = microtime(true); // Start timing

        $uploadPath = './attachments/shop_images/' . $langAbbr . '/';
    
        // Ensure upload directory exists
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
    
        $images = $this->request->getFiles();
        $imageName = $this->generateImageName($images['cover_image_' . $langAbbr], $langAbbr);
        $imageExtension = $images['cover_image_' . $langAbbr]->getClientExtension();
    
        $imageUploaded = false; // Flag to check if any image was uploaded and moved
    
        foreach ($images as $key => $image) {
            if (strpos($key, 'cover_image_' . $langAbbr) === 0) {
                $suffix = '';

                if (strpos($key, '_650') !== false) {
                    $suffix = '';
                }elseif(strpos($key, '_250')){
                    $suffix = '-250x250';
                } elseif (strpos($key, '_1200') !== false) {
                    $suffix = '-1200x1200';
                }elseif (strpos($key, '_2400') !== false) {
                    $suffix = '-2400x2400';
                }elseif (strpos($key, '_3500') !== false) {
                    $suffix = '-3500x3500';
                }
                $newName = $imageName . $suffix . '.' . $imageExtension;
                // Check if a file with the new name already exists, and delete it if it does
                if (file_exists($uploadPath . $newName)) {
                    unlink($uploadPath . $newName);
                }
    
                if ($image->isValid() && !$image->hasMoved()) {
                    $image->move($uploadPath, $newName);
                    $imageUploaded = true; // Set flag to true as an image was uploaded and moved
                }
            }
        }
        $endTime = microtime(true); // End timing
        $timeTaken = $endTime - $startTime;
        $this->logger->alert("uploadImage time taken: " . $timeTaken . " seconds.");

        // The base image name is returned for database saving only if an image was uploaded and moved
        return $imageUploaded ? $langAbbr . '/' . $imageName . '.' . $imageExtension : '';
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
         $startTime = microtime(true); // Start timing
     
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
             $uploadedImageCount = $this->request->getPost('uploaded_image_count') ?? 0;

             // Initialize the counter with the uploaded image count
             if ($files) {
                $counter = (int)$uploadedImageCount + 1;
                foreach ($files['others_' . $langAbbr] as $index => $file) {
                     if ($file->isValid() && !$file->hasMoved()) {
                         $allowedMimeTypes = ['image/jpeg', 'image/png'];
                         if (in_array($file->getMimeType(), $allowedMimeTypes)) {
                             // Use imageName with counter if not empty, otherwise use a random name
                             $fileExtension = $file->getClientExtension();
                             $baseName = !empty($imageName) ? $imageName . '-' . $counter : pathinfo($file->getRandomName(), PATHINFO_FILENAME);
                             
                             // Move the resized versions for each uploaded file
                             foreach (['250','650', '1200','2400','3500'] as $size) {
                                 if (isset($files["others_{$langAbbr}_{$size}"][$index])) {
                                     $resizedFile = $files["others_{$langAbbr}_{$size}"][$index];
                                     $resizedBaseName = $baseName . ($size == '650' ? '' : "-{$size}x{$size}");
                                     $resizedName = $resizedBaseName . '.' . $fileExtension;
                                     $resizedFile->move($upath, $resizedName);
                                 }
                             }
                             $counter++; // Increment the counter for the next file
                         }
                     }
                 }
             }
         }
         $endTime = microtime(true); // End timing
         $timeTaken = $endTime - $startTime;
         $this->logger->alert("upload OTHER Image time taken: " . $timeTaken . " seconds.");
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
             $sizes = ['250x250', '1200x1200','2400x2400','3500x3500',];
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
