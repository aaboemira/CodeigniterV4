<?php
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Api_model extends Model {


    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

	public function getProducts($lang) {
		$builder = $this->db->table('products');
		$builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
 		$builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
 		$builder->where('products_translations.abbr', $lang);
		$builder->where('products.is_visible', '1');
 		$query = $builder->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, 
		 products.time as product_time_created, products.time_update as product_time_updated, 
		 products.visibility as product_visibility, products.shop_categorie as product_category, 
		 products.quantity as product_quantity_available, products.is_main_view_from_variant as is_main_view_from_variant, products.is_variant as is_variant, products.is_variant as is_variant, products.variant_id as product_variant_id,
		 products.article_nr as product_article_nr, products.is_visible as product_is_visible, products.shipment_destination as product_shipment_destination, products.Reserve_Produkt_03 as product_Reserve_Produkt_03,
		 products.procurement as product_procurement, 
		 products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, 
		 products.position as product_position, products_translations.title, products_translations.title2,
		  products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, 
		  products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.description, 
		  products_translations.price, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status, products_translations.basic_description')->get();
 		return $query->getResultArray();
 	}
 	public function getProduct($lang, $id) {
		$builder = $this->db->table('products');
		$builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
 		$builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
 		$builder->where('products_translations.abbr', $lang);
 		$builder->where('products.id', $id);
 		$builder->limit(1);
 		$query = $builder->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as product_visibility, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.is_main_view_from_variant as is_main_view_from_variant, products.is_variant as is_variant, products.variant_id as product_variant_id, 
		 products.article_nr as product_article_nr, products.is_visible as product_is_visible, products.shipment_destination as product_shipment_destination, products.Reserve_Produkt_03 as product_Reserve_Produkt_03,
 
		 
		products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.description, products_translations.price, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status, products_translations.basic_description')->get();
 		return $query->getRowArray();
 	}

 	public function setProduct($post) {
		if (!isset($post['brand_id'])) {
			$post['brand_id'] = null;
 		}
 		if (!isset($post['virtual_products'])) {
			$post['virtual_products'] = null;
 		}
 		$this->db->transStart();
 		$i = 0;
 		foreach ($_POST['translations'] as $translation) {
			if ($translation == MY_DEFAULT_LANGUAGE_ABBR) {
				$myTranslationNum = $i;
 			}
 			$i++;
 		}
		$builder = $this->db->table('products');
 		if (!$builder->insert( array(
 'image' => $post['image'],
 'shop_categorie' => $post['shop_categorie'],
 'quantity' => $post['quantity'],
 'is_main_view_from_variant' => $post['is_main_view_from_variant'],
 'is_variant' => $post['is_variant'],
 'variant_id' => $post['variant_id'],

 'article_nr' => $post['article_nr'],
 'is_visible' => $post['is_visible'],
 'shipment_destination' => $post['shipment_destination'],
 'Reserve_Produkt_03' => $post['Reserve_Produkt_03'],
 'in_slider' => $post['in_slider'],
 'position' => $post['position'],
 'virtual_products' => $post['virtual_products'],
 'folder' => time(),
 'brand_id' => $post['brand_id'],
 'time' => time()
 ))) {
			///log_message('error', print_r($builder->error(), true));
 		}
 		$id = $this->db->insertID();

		$builder = $this->db->table('products');
 		$builder->where('id', $id);
 		if (!$builder->update(array(
 'url' => except_letters($_POST['title'][$myTranslationNum]) . '_' . $id
 ))) {
			///log_message('error', print_r($builder->error(), true));
 		}
 		$this->setProductTranslation($post, $id);
		 if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();
		} else {
			$this->db->transCommit();
		}
 	}

 	private function setProductTranslation($post, $id) {
		$i = 0;
 		$current_trans = $this->getTranslations($id);
 		foreach ($post['translations'] as $abbr) {
			$arr = array();
 			$emergency_insert = false;
 			if (!isset($current_trans[$abbr])) {
				$emergency_insert = true;
 			}
 			$post['title'][$i] = str_replace('"', "'", $post['title'][$i]);
			$post['title2'][$i] = str_replace('"', "'", $post['title2'][$i]);
			$post['bullet1'][$i] = str_replace('"', "'", $post['bullet1'][$i]);
			$post['bullet2'][$i] = str_replace('"', "'", $post['bullet2'][$i]);
			$post['bullet3'][$i] = str_replace('"', "'", $post['bullet3'][$i]);
			$post['bullet4'][$i] = str_replace('"', "'", $post['bullet4'][$i]);
			$post['bullet5'][$i] = str_replace('"', "'", $post['bullet5'][$i]);
			$post['bullet6'][$i] = str_replace('"', "'", $post['bullet6'][$i]);
			$post['bullet7'][$i] = str_replace('"', "'", $post['bullet7'][$i]);
			$post['variant_name'][$i] = str_replace('"', "'", $post['variant_name'][$i]);
			$post['variant_description'][$i] = str_replace('"', "'", $post['variant_description'][$i]);			
 			$post['price'][$i] = str_replace(' ', '', $post['price'][$i]);
 			$post['price'][$i] = str_replace(',', '', $post['price'][$i]);

 			$arr = array(
			'title' => $post['title'][$i],
			'title2' => $post['title2'][$i],
			'bullet1' => $post['bullet1'][$i],
			'bullet2' => $post['bullet2'][$i],
			'bullet3' => $post['bullet3'][$i],
			'bullet4' => $post['bullet4'][$i],
			'bullet5' => $post['bullet5'][$i],
			'bullet6' => $post['bullet6'][$i],
			'bullet7' => $post['bullet7'][$i],
			'variant_name' => $post['variant_name'][$i],
			'variant_description' => $post['variant_description'][$i],
			'basic_description' => $post['basic_description'][$i],
			'description' => $post['description'][$i],
			'price' => $post['price'][$i],
			'old_price' => $post['old_price'][$i],
			'shipping_cost' => $post['shipping_cost'][$i],
			'shipping_time' => $post['shipping_time'][$i],
			'delivery_status' => $post['delivery_status'][$i],
			'abbr' => $abbr,
			'for_id' => $id
			);
			$builder = $this->db->table('products_translations');
 			if (!$builder->insert( $arr)) {
				///log_message('error', print_r($builder->error(), true));
 			}
 			$i++;
 		}
 	}
	 
 	private function getTranslations($id) {
		$builder = $this->db->table('products_translations');
		$builder->where('for_id', $id);
 		$query = $builder->get();
 		$arr = array();
 		foreach ($query->getResultArray() as $row) {
			$arr[$row->abbr]['title'] = $row->title;
			$arr[$row->abbr]['title2'] = $row->title2;
			$arr[$row->abbr]['bullet1'] = $row->bullet1;
			$arr[$row->abbr]['bullet2'] = $row->bullet2;
			$arr[$row->abbr]['bullet3'] = $row->bullet3;
			$arr[$row->abbr]['bullet4'] = $row->bullet4;
			$arr[$row->abbr]['bullet5'] = $row->bullet5;
			$arr[$row->abbr]['bullet6'] = $row->bullet6;
			$arr[$row->abbr]['bullet7'] = $row->bullet7;
			$arr[$row->abbr]['variant_name'] = $row->variant_name;
			$arr[$row->abbr]['variant_description'] = $row->variant_description;


 			$arr[$row->abbr]['basic_description'] = $row->basic_description;
 			$arr[$row->abbr]['description'] = $row->description;
 			$arr[$row->abbr]['price'] = $row->price;
 			$arr[$row->abbr]['old_price'] = $row->old_price;
			$arr[$row->abbr]['shipping_cost'] = $row->shipping_cost;
			$arr[$row->abbr]['shipping_time'] = $row->shipping_time;
			$arr[$row->abbr]['delivery_status'] = $row->delivery_status;
 		}
 		return $arr;
 	}

 	public function deleteProduct($id) {
		$this->db->transStart();
		$builder = $this->db->table('products_translations');
 		$builder->where('for_id', $id);
 		if (!$builder->delete()) {
			///log_message('error', print_r($builder->error(), true));
 		}

		$builder = $this->db->table('products');
 		$builder->where('id', $id);
 		if (!$builder->delete()) {
			///log_message('error', print_r($builder->error(), true));
 		}
 		if ($this->db->transStatus() === FALSE) {
			$this->db->transRollback();
 		} else {
			$this->db->transCommit();
 		}
 	}

}