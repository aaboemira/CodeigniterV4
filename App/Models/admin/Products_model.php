<?php
namespace App\Models\admin;

use CodeIgniter\Model;
use Config\Database;

class Products_model extends Model
{

    protected $db;

	public function __construct()
	{
		$this->db = Database::connect();
	}

    public function deleteProduct($id)
    {
        $this->db->transStart();
        $builder = $this->db->table('products_translations');
        $builder->where('for_id', $id);
        if (!$builder->delete()) {
            ///log_message('error', print_r($this->db->error(), true));
        }

        $builder = $this->db->table('products');
        $builder->where('id', $id);
        if (!$builder->delete()) {
            ///log_message('error', print_r($this->db->error(), true));
        }
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
        } else {
            $this->db->transCommit();
        }
    }

    public function productsCount($search_title = null, $category = null)
    {
        $builder = $this->db->table('products');
        if ($search_title != null) {
            $builder->where("(products_translations.title LIKE '%$search_title%')");
        }
        if ($category != null) {
            $builder->where('shop_categorie', $category);
        }
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        return $builder->countAllResults();
    }

    public function getProducts($limit, $page, $search_title = null, $orderby = null, $category = null, $vendor = null)
    {
        $builder = $this->db->table('products');
        if ($search_title != null) {
            $search_title = trim($search_title);
            $builder->where("(products_translations.title LIKE '%$search_title%')");
        }
        if ($orderby !== null) {
            $ord = explode('=', $orderby);
            if (isset($ord[0]) && isset($ord[1])) {
                $builder->orderBy('products.' . $ord[0], $ord[1]);
            }
        } else {
            $builder->orderBy('products.position', 'asc');
        }
        if ($category != null) {
            $builder->where('shop_categorie', $category);
        }
        if ($vendor != null) {
            $builder->where('vendor_id', $vendor);
        }
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $query = $builder->select('vendors.name as vendor_name, vendors.id as vendor_id, products.*, products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.description, products_translations.price, products_translations.old_price, products_translations.delivery_status, products_translations.shipping_cost, products_translations.shipping_time, products_translations.abbr, products.url, products_translations.for_id, products_translations.basic_description')->get( $limit, $page);
        return $query->getResult();
    }

    public function numShopProducts()
    {
        $builder = $this->db->table('products');
        return $builder->countAllResults();
    }

    public function getOneProduct($id)
    {
        $builder = $this->db->table('products');
        $builder->select('vendors.name as vendor_name, vendors.id as vendor_id, products.*, products_translations.price');
        $builder->where('products.id', $id);
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'inner');
        $builder->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $query = $builder->get();
        if ($builder->countAllResults() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function productStatusChange($id, $to_status)
    {
        $builder = $this->db->table('products');
        $builder->where('id', $id);
        $result = $builder->update(array('visibility' => $to_status));
        return $result;
    }

    public function setProduct($post, $id = 0)
    {
        if (!isset($post['brand_id'])) {
            $post['brand_id'] = null;
        }
        if (!isset($post['virtual_products'])) {
            $post['virtual_products'] = null;
        }
        $this->db->transStart();
        $is_update = false;
        if ($id > 0) {
            $builder = $this->db->table('products');
            $is_update = true;
            if (!$builder->where('id', $id)->update(array(
                        'image' => $post['image'] != null ? $_POST['image'] : $_POST['old_image'],
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
                        'brand_id' => $post['brand_id'],
                        'time_update' => time()
                    ))) {
                ///log_message('error', print_r($builder->error(), true));
            }
        } else {
            /*
             * Lets get what is default tranlsation number
             * in titles and convert it to url
             * We want our plaform public ulrs to be in default 
             * language that we use
             */
            $i = 0;
            foreach ($_POST['translations'] as $translation) {
                if ($translation == MY_DEFAULT_LANGUAGE_ABBR) {
                    $myTranslationNum = $i;
                }
                $i++;
            }
            $builder = $this->db->table('products');
            if (!$builder->insert(array(
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
                        'folder' => $post['folder'],
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
        }
        $this->setProductTranslation($post, $id, $is_update);
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            //show_error(lang_safe('database_error'));
        } else {
            $this->db->transCommit();
        }
    }

    private function setProductTranslation($post, $id, $is_update)
    {
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
            $post['price'][$i] = str_replace(',', '.', $post['price'][$i]);
            $post['price'][$i] = preg_replace("/[^0-9,.]/", "", $post['price'][$i]);
            $post['old_price'][$i] = str_replace(' ', '', $post['old_price'][$i]);
            $post['old_price'][$i] = str_replace(',', '.', $post['old_price'][$i]);
            $post['old_price'][$i] = preg_replace("/[^0-9,.]/", "", $post['old_price'][$i]);
            $post['shipping_cost'][$i] = str_replace(' ', '', $post['shipping_cost'][$i]);
            $post['shipping_cost'][$i] = str_replace(',', '.', $post['shipping_cost'][$i]);
            $post['shipping_cost'][$i] = preg_replace("/[^0-9,.]/", "", $post['shipping_cost'][$i]);
            $post['shipping_time'][$i] = str_replace(' ', '', $post['shipping_time'][$i]);
            $post['shipping_time'][$i] = str_replace(',', '.', $post['shipping_time'][$i]);
            $post['shipping_time'][$i] = preg_replace("/[^0-9,.]/", "", $post['shipping_time'][$i]);
            $post['delivery_status'][$i] = str_replace(' ', '', $post['delivery_status'][$i]);
            $post['delivery_status'][$i] = str_replace(',', '.', $post['delivery_status'][$i]);
            $post['delivery_status'][$i] = preg_replace("/[^0-9,.]/", "", $post['delivery_status'][$i]);
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
            if ($is_update === true && $emergency_insert === false) {
                $abbr = $arr['abbr'];
                unset($arr['for_id'], $arr['abbr'], $arr['url']);
                $builder = $this->db->table('products_translations');
                if (!$builder->where('abbr', $abbr)->where('for_id', $id)->update($arr)) {
                    ///log_message('error', print_r($builder->error(), true));
                }
            } else {
                $builder = $this->db->table('products_translations');
                if (!$builder->insert($arr)) {
                    ///log_message('error', print_r($builder->error(), true));
                }
            }
            $i++;
        }
    }

    public function getTranslations($id)
    {
        $builder = $this->db->table('products_translations');
        $builder->where('for_id', $id);
        $query = $builder->get();
        $arr = array();
        foreach ($query->getResult() as $row) {
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
            $arr[$row->abbr]['delivery_status'] = $row->shipping_time; 
        }
        return $arr;
    }

}