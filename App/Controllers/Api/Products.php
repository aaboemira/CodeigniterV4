<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Products extends REST_Controller {
	private $allowed_img_types;

 	function __construct() {
		parent::__construct();
 		$this->methods['all_get']['limit'] = 500;
 		// 500 requests per hour per user/key
 		$this->methods['one_get']['limit'] = 500;
 		// 500 requests per hour per user/key
 		$this->methods['set_post']['limit'] = 100;
 		// 100 requests per hour per user/key
 		$this->methods['productDel_delete']['limit'] = 50;
 		// 50 requests per hour per user/key
 		$this->load->model(array('Api_model', 'admin/Products_model'));
 		$this->allowed_img_types = config('config')->allowed_img_types;
 	}

 /*
 * Get All Products
 */

 public function all_get($lang) {
		$products = $this->Api_model->getProducts($lang);

 		// Check if the products data store contains products (in case the database result returns NULL)
 		if ($products) {
			// Set the response and exit
 			$this->response($products, REST_Controller::HTTP_OK);
 			// OK (200) being the HTTP response code
 		} else {
			// Set the response and exit
 			$this->response([
 'status' => FALSE,
 'message' => 'No products were found'
 ], REST_Controller::HTTP_NOT_FOUND);
 			// NOT_FOUND (404) being the HTTP response code
 		}
 	}

 /*
 * Get One Product
 */

 public function one_get($lang, $id) {
		$product = $this->Api_model->getProduct($lang, $id);

 		// Check if the products data store contains products (in case the database result returns NULL)
 		if ($product) {
			// Set the response and exit
 			$this->response($product, REST_Controller::HTTP_OK);
 			// OK (200) being the HTTP response code
 		} else {
			// Set the response and exit
 			$this->response([
 'status' => FALSE,
 'message' => 'No product were found'
 ], REST_Controller::HTTP_NOT_FOUND);
 			// NOT_FOUND (404) being the HTTP response code
 		}
 	}

 /*
 * Set Product
 */

 public function set_post() {
		$errors = [];
 		$_POST['image'] = $this->uploadImage();
 		if (!isset($_POST['translations']) || empty($_POST['translations'])) {
			$errors[] = 'No translations array or empty';
 		}
 		if (!isset($_POST['title']) || empty($_POST['title'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['title2']) || empty($_POST['title2'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['bullet1']) || empty($_POST['bullet1'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['bullet2']) || empty($_POST['bullet2'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['bullet3']) || empty($_POST['bullet3'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['bullet4']) || empty($_POST['bullet4'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['bullet5']) || empty($_POST['bullet5'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['bullet6']) || empty($_POST['bullet6'])) {
			$errors[] = 'No title array or empty';
 		}
		if (!isset($_POST['bullet7']) || empty($_POST['bullet7'])) {
			$errors[] = 'No title array or empty';
 		}
		 if (!isset($_POST['variant_id']) || empty($_POST['variant_id'])) {
			$errors[] = 'No title array or empty';
 		}
		 if (!isset($_POST['variant_name']) || empty($_POST['variant_name'])) {
			$errors[] = 'No title array or empty';
 		}
		 if (!isset($_POST['variant_description']) || empty($_POST['variant_description'])) {
			$errors[] = 'No title array or empty';
 		}
 		if (!isset($_POST['basic_description']) || empty($_POST['basic_description'])) {
			$errors[] = 'No basic_description array or empty';
 		}
 		if (!isset($_POST['description']) || empty($_POST['description'])) {
			$errors[] = 'No description array or empty';
 		}
 		if (!isset($_POST['price']) || empty($_POST['price'])) {
			$errors[] = 'No price array or empty';
 		}
 		if (!isset($_POST['old_price']) || empty($_POST['old_price'])) {
			$errors[] = 'No old_price array or empty';
 		}
		if (!isset($_POST['shipping_cost']) || empty($_POST['shipping_cost'])) {
			$errors[] = 'No shipping_cost array or empty';
 		}
		if (!isset($_POST['shipping_time']) || empty($_POST['shipping_time'])) {
			$errors[] = 'No shipping_time array or empty';
 		}
		if (!isset($_POST['delivery_status']) || empty($_POST['delivery_status'])) {
			$errors[] = 'No delivery_status array or empty';
 		}
 		if (!isset($_POST['shop_categorie'])) {
			$errors[] = 'shop_categorie not found';
 		}
 		if (!isset($_POST['quantity'])) {
			$errors[] = 'quantity not found';
 		}
 		if (!isset($_POST['in_slider'])) {
			$errors[] = 'in_slider not found';
 		}
 		if (!isset($_POST['position'])) {
			$errors[] = 'position not found';
 		}
 		if (!empty($errors)) {
			$error = implode(", ", $errors);
 			$message = [
 'message' => $error
 ];
 		} else {
			$this->Api_model->setProduct($_POST);
 			$message = [
 'message' => 'Added a resource'
 ];
 		}
 		$this->set_response($message, REST_Controller::HTTP_CREATED);
 		// CREATED (201) being the HTTP response code
 	}

 	private function uploadImage() {
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

 	public function productDel_delete($id) {
		$id = (int) $id;
 		// Validate the id.
 		if ($id <= 0) {
			// Set the response and exit
 			$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
 			// BAD_REQUEST (400) being the HTTP response code
 		}
 		$this->Api_model->deleteProduct($id);
 		$message = [
 'id' => $id,
 'message' => 'Deleted the resource'
 ];
 		$this->set_response($message, REST_Controller::HTTP_NO_CONTENT);
 		// NO_CONTENT (204) being the HTTP response code
 	}

}