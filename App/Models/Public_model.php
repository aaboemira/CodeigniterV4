<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;
use App\Models\admin\Home_admin_model;

class Public_model extends Model
{

    private $showOutOfStock;
    private $showInSliderProducts;
    private $multiVendor;

    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->showOutOfStock = (new Home_admin_model())->getValueStore('outOfStock');
        $this->showInSliderProducts = (new Home_admin_model())->getValueStore('showInSlider');
        $this->multiVendor = (new Home_admin_model())->getValueStore('multiVendor');
    }

    public function productsCount($big_get)
    {
        $builder = $this->db->table('products');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        if (!empty($big_get) && isset($big_get['category'])) {
            $this->getFilter($builder, $big_get);
        }
        $builder->where('visibility', 1);
        $builder->where('is_visible', 1);
        $builder->where('is_main_view_from_variant', 0);
        if ($this->showOutOfStock == 0) {
            $builder->where('quantity >', 0);
        }
        if ($this->showInSliderProducts == 0) {
            $builder->where('in_slider', 0);
        }
        if ($this->multiVendor == 0) {
            $builder->where('vendor_id', 0);
        }
        return $builder->countAll();
    }


    public function getNewProducts()
    {
        $builder = $this->db->table('products');
        $builder->select('vendors.url as vendor_url, products.id, products.quantity, products.image, products.url, products_translations.price, products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.variant_name, products_translations.variant_description, products_translations.old_price');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        $builder->where('products.in_slider', 0);
        $builder->where('visibility', 1);
        $builder->where('is_visible', 1);
        if ($this->showOutOfStock == 0) {
            $builder->where('quantity >', 0);
        }
        $builder->orderBy('products.id', 'desc');
        $builder->limit(5);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getLastBlogs()
    {
        $builder = $this->db->table('blog_posts');
        $builder->limit(5);
        $builder->join('blog_translations', 'blog_translations.for_id = blog_posts.id', 'left');
        $builder->where('blog_translations.abbr', MY_LANGUAGE_ABBR);
        $query = $builder->select('blog_posts.id, blog_translations.title, blog_translations.description, blog_posts.url, blog_posts.time, blog_posts.image')->get();
        return $query->getResultArray();
    }

    public function getPosts($limit, $page, $search = null, $month = null)
    {
        $builder = $this->db->table('blog_posts');
        if ($search !== null) {
            $builder->where("(blog_translations.title LIKE '%$search%' OR blog_translations.description LIKE '%$search%')");
        }
        if ($month !== null) {
            $from = intval($month['from']);
            $to = intval($month['to']);
            $builder->where("time BETWEEN $from AND $to");
        }
        $builder->join('blog_translations', 'blog_translations.for_id = blog_posts.id', 'left');
        $builder->where('blog_translations.abbr', MY_LANGUAGE_ABBR);
        $query = $builder->select('blog_posts.id, blog_translations.title, blog_translations.description, blog_posts.url, blog_posts.time, blog_posts.image')->get($limit, $page);
        return $query->getResultArray();
    }

    public function getProducts($limit = null, $start = null, $big_get = null, $vendor_id = false)
    {
        $builder = $this->db->table('products');
        if ($limit !== null && $start !== null) {
            $builder->limit($limit, $start);
        }
        if (!empty($big_get) && isset($big_get['category'])) {
            $this->getFilter($builder, $big_get);
        }
        $builder->select('vendors.url as vendor_url, products.id,products.image, products.quantity, products.is_main_view_from_variant, products.is_variant, products.variant_id, 
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.price, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status, products.url');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        $builder->where('visibility', 1);
        $builder->where('is_visible', 1);
        if ($vendor_id !== false) {
            $builder->where('vendor_id', $vendor_id);
        }
        if ($this->showOutOfStock == 0) {
            $builder->where('quantity >', 0);
        }
        if ($this->showInSliderProducts == 0) {
            $builder->where('in_slider', 0);
        }
        if ($this->multiVendor == 0) {
            $builder->where('vendor_id', 0);
        }
        $builder->orderBy('position', 'asc');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getVariants($variant_id)
    {
        $builder = $this->db->table('products');
        $builder->select('products.id, products.is_main_view_from_variant, products.is_variant, products.variant_id, 
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.variant_name, products_translations.variant_description, products.url');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->where('products.variant_id', $variant_id);
        $builder->where('is_visible', 1);

        $builder->orderBy('position', 'asc');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getShippings()
    {
        $builder = $this->db->table('products');
        $builder->select('vendors.url as vendor_url, products.id,products.image, products.quantity, products.is_main_view_from_variant, products.is_variant, products.variant_id, 
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.price, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status, products.url');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        $builder->where('shipment_destination >', 0);

        $builder->orderBy('position', 'asc');
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getShippingsByDist($dist)
    {
        $builder = $this->db->table('products');
        $builder->select('vendors.url as vendor_url, products.id,products.image, products.quantity, products.is_main_view_from_variant, products.is_variant, products.variant_id, 
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.price, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status, products.url');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        $builder->where('shipment_destination =', $dist);

        $builder->orderBy('position', 'asc');
        $query = $builder->get();

        return $query->getResultArray();
    }

    public function getOneLanguage($myLang)
    {
        $builder = $this->db->table('languages');
        return $builder->select('*')->where('abbr', $myLang)->get()->getRowArray();
    }

    private function getFilter($builder, $big_get)
    {
        if ($big_get['category'] != '') {
            (int)$big_get['category'];
            $findInIds = array();
            $findInIds[] = $big_get['category'];
            $query = $this->db->query('SELECT id FROM shop_categories WHERE sub_for = ' . $big_get['category']);
            foreach ($query->getResultArray() as $row) {
                $findInIds[] = $row['id'];
            }
            $builder->whereIn('products.shop_categorie', $findInIds);
        }
        if ($big_get['in_stock'] != '') {
            if ($big_get['in_stock'] == 1)
                $sign = '>';
            else
                $sign = '=';
            $builder->where('products.quantity ' . $sign, '0');
        }
        if ($big_get['search_in_title'] != '') {
            $builder->like('products_translations.title', $big_get['search_in_title']);
        }
        if ($big_get['search_in_body'] != '') {
            $builder->like('products_translations.description', $big_get['search_in_body']);
        }
        if ($big_get['order_price'] != '') {
            $builder->orderBy('products_translations.price', $big_get['order_price']);
        }
        if ($big_get['order_procurement'] != '') {
            $builder->orderBy('products.procurement', $big_get['order_procurement']);
        }
        if ($big_get['order_new'] != '') {
            $builder->orderBy('products.id', $big_get['order_new']);
        } else {
            $builder->orderBy('products.id', 'DESC');
        }
        if ($big_get['quantity_more'] != '') {
            $builder->where('products.quantity > ', $big_get['quantity_more']);
        }
        if ($big_get['quantity_more'] != '') {
            $builder->where('products.quantity > ', $big_get['quantity_more']);
        }
        if ($big_get['brand_id'] != '') {
            $builder->where('products.brand_id = ', $big_get['brand_id']);
        }
        if ($big_get['added_after'] != '') {
            $time = strtotime($big_get['added_after']);
            $builder->where('products.time > ', $time);
        }
        if ($big_get['added_before'] != '') {
            $time = strtotime($big_get['added_before']);
            $builder->where('products.time < ', $time);
        }
        if ($big_get['price_from'] != '') {
            $builder->where('products_translations.price >= ', $big_get['price_from']);
        }
        if ($big_get['price_to'] != '') {
            $builder->where('products_translations.price <= ', $big_get['price_to']);
        }
        return $builder;
    }

    public function getShopCategories()
    {
        $query = $this->db->table('shop_categories_translations')->select('shop_categories.sub_for, shop_categories.id, shop_categories_translations.name')
            ->where('abbr', MY_LANGUAGE_ABBR)
            ->orderBy('position', 'asc')
            ->join('shop_categories', 'shop_categories.id = shop_categories_translations.for_id', 'INNER')
            ->get();
        $arr = array();
        if ($query !== false) {
            foreach ($query->getResultArray() as $row) {
                $arr[] = $row;
            }
        }
        return $arr;
    }


    public function getSeo($page)
    {
        $query = $this->db->table('seo_pages_translations')->where('page_type', $page)
            ->where('abbr', MY_LANGUAGE_ABBR)
            ->get();
        $arr = array();
        if ($query !== false) {
            foreach ($query->getResultArray() as $row) {
                $arr['title'] = $row['title'];
                $arr['description'] = $row['description'];
            }
        }
        return $arr;
    }

    public function getOneProduct($id)
    {
        $builder = $this->db->table('products');
        $builder->where('products.id', $id);

        $builder->select('vendors.url as vendor_url, products.*, products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.description, products_translations.price, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status, products.url, shop_categories_translations.name as categorie_name');

        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);

        $builder->join('shop_categories_translations', 'shop_categories_translations.for_id = products.shop_categorie', 'inner');
        $builder->where('shop_categories_translations.abbr', MY_LANGUAGE_ABBR);
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->where('visibility', 1);
        $builder->where('is_visible', 1);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getCountQuantities()
    {
        $query = $this->db->query('SELECT SUM(IF(quantity<=0,1,0)) as out_of_stock, SUM(IF(quantity>0,1,0)) as in_stock FROM products WHERE visibility = 1');
        return $query->getRowArray();
    }

    public function getShopItems($array_items)
    {
        $builder = $this->db->table('products');
        $builder->select('products.id, products.image, products.shop_categorie, products.url, products.quantity, products.is_main_view_from_variant, products.is_variant, products.variant_id, 
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.price, products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description');
        //$builder->from('products');
        if (count($array_items) > 1) {
            $i = 1;
            $where = '';
            foreach ($array_items as $id) {
                $i == 1 ? $open = '(' : $open = '';
                $i == count($array_items) ? $or = '' : $or = ' OR ';
                $where .= $open . 'products.id = ' . $id . $or;
                $i++;
            }
            $where .= ')';
            $builder->where($where);
        } else {
            $builder->where('products.id =', current($array_items));
        }
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'inner');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        $query = $builder->get();
        return $query->getResultArray();
    }

    /*
     * Users for notification by email
     */

    public function getNotifyUsers()
    {
        $result = $this->db->query('SELECT email FROM users WHERE notify = 1');
        $arr = array();
        foreach ($result->getResultArray() as $email) {
            $arr[] = $email['email'];
        }
        return $arr;
    }

    public function setOrder($post)
    {
        $q = $this->db->query('SELECT MAX(order_id) as order_id FROM orders');
        $rr = $q->getRowArray();
        if ($rr['order_id'] == 0) {
            $rr['order_id'] = 1233;
        }

        $post['order_id'] = $rr['order_id'] + 1;

        $i = 0;
        $post['products'] = array();
        foreach ($post['id'] as $product) {
            $post['products'][$product] = $post['quantity'][$i];
            $i++;
        }
        unset($post['id'], $post['quantity']);
        $post['date'] = time();
        $products_to_order = [];
        if (!empty($post['products'])) {
            foreach ($post['products'] as $pr_id => $pr_qua) {
                $products_to_order[] = [
                    'product_info' => $this->getOneProductForSerialize($pr_id),
                    'product_quantity' => $pr_qua
                ];
            }
        }
        $post['products'] = serialize($products_to_order);


        $this->db->transStart();
        $builder = $this->db->table('orders');
        if (!$builder->insert(array(
            'order_id' => $post['order_id'],
            'products' => $post['products'],
            'date' => $post['date'],
            'referrer' => $post['referrer'],
            'clean_referrer' => $post['clean_referrer'],
            'payment_type' => $post['payment_type'],
            'paypal_status' => @$post['paypal_status'],
            'discount_code' => @$post['discountCode'],
            'user_id' => $post['user_id'],
            'shipping_price' => $post['shipping_price'], // Insert shipping price
            'shipping_type' => $post['shipping_type'],
            'order_status'=>$post['status']
        ))) {
//            log_message('error', print_r($builder->error(), true));
        }


        $lastId = $this->db->insertID();
        $builder = $this->db->table('orders_clients');
        if (!$builder->insert(array(
            'for_id' => $lastId,
            'first_name' => $post['billing_address']['billing_first_name'],
            'last_name' => $post['billing_address']['billing_last_name'],
            'company' => $post['billing_address']['billing_company'],
            'email' => $post['email'],
            'phone' => $post['phone'],
            'city' => $post['billing_address']['billing_city'],
            'street' => $post['billing_address']['billing_street'],
            'housenr' => $post['billing_address']['billing_housenr'],
            'country' => $post['billing_address']['billing_country'],
            'post_code' => $post['billing_address']['billing_post_code'],
            'notes' => $post['notes']
        ))) {
            // ///log_message('error', print_r($builder->error(), true));
        }
        $builder = $this->db->table('orders_shipping');
        if (!$builder->insert(array(
            'for_id' => $lastId,
            'first_name' => $post['shipping_address']['shipping_first_name'],
            'last_name' => $post['shipping_address']['shipping_last_name'],
            'company' => $post['shipping_address']['shipping_company'],
            'city' => $post['shipping_address']['shipping_city'],
            'street' => $post['shipping_address']['shipping_street'],
            'housenr' => $post['shipping_address']['shipping_housenr'],
            'country' => $post['shipping_address']['shipping_country'],
            'post_code' => $post['shipping_address']['shipping_post_code'],
        ))) {
            // ///log_message('error', print_r($builder->error(), true));
        }
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            return false;
        } else {
            $this->db->transCommit();
            return $post['order_id'];
        }
    }

    public function getOneProductForSerialize($id)
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

    public function setVendorOrder($post)
    {
        $i = 0;
        $post['products'] = array();
        foreach ($post['id'] as $product) {
            $post['products'][$product] = $post['quantity'][$i];
            $i++;
        }

        /*
         * Loop products and check if its from vendor - save order for him
         */
        foreach ($post['products'] as $product_id => $product_quantity) {
            $productInfo = $this->getOneProduct($product_id);
            if ($productInfo['vendor_id'] > 0) {

                $q = $this->db->query('SELECT MAX(order_id) as order_id FROM vendors_orders');
                $rr = $q->getRowArray();
                if ($rr['order_id'] == 0) {
                    $rr['order_id'] = 1233;
                }
                $post['order_id'] = $rr['order_id'] + 1;


                unset($post['id'], $post['quantity']);
                $post['date'] = time();
                $post['products'] = serialize(array($product_id => $product_quantity));
                $this->db->transStart();
                $builder = $this->db->table('vendors_orders');
                if (!$builder->insert(array(
                    'order_id' => $post['order_id'],
                    'products' => $post['products'],
                    'date' => $post['date'],
                    'referrer' => $post['referrer'],
                    'clean_referrer' => $post['clean_referrer'],
                    'payment_type' => $post['payment_type'],
                    'paypal_status' => @$post['paypal_status'],
                    'discount_code' => @$post['discountCode'],
                    'vendor_id' => $productInfo['vendor_id']
                ))) {
                    // ///log_message('error', print_r($builder->error(), true));
                }
                $lastId = $this->db->insertID();
                $builder = $this->db->table('vendors_orders_clients');
                if (!$builder->insert(array(
                    'for_id' => $lastId,
                    'first_name' => $post['first_name'],
                    'last_name' => $post['last_name'],
                    'company' => $post['company'],
                    'city' => $post['city'],
                    'email' => $post['email'],
                    'phone' => $post['phone'],
                    'street' => $post['street'],
                    'housenr' => $post['housenr'],
                    'country' => $post['country'],
                    'city' => $post['city'],
                    'post_code' => $post['post_code'],
                    'notes' => $post['notes']
                ))) {
                    //  ///log_message('error', print_r($builder->error(), true));
                }
                if ($this->db->transStatus() === FALSE) {
                    $this->db->transRollback();
                    return false;
                } else {
                    $this->db->transCommit();
                }
            }
        }
    }

    public function setActivationLink($link, $orderId)
    {
        $builder = $this->db->table('confirm_links');
        $result = $builder->insert(array('link' => $link, 'for_order' => $orderId));
        return $result;
    }

    public function getSliderProducts()
    {
        $builder = $this->db->table('products');
        $builder->select('vendors.url as vendor_url, products.id, products.quantity, products.image, products.url, products.is_main_view_from_variant, products.is_variant, products.variant_id,
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.price, products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.basic_description, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        $builder->where('visibility', 1);
        $builder->where('is_visible', 1);
        $builder->where('in_slider', 1);
        if ($this->showOutOfStock == 0) {
            $builder->where('quantity >', 0);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getbestSellers($categorie = 0, $noId = 0)
    {
        $builder = $this->db->table('products')->select('vendors.url as vendor_url, products.id, products.quantity, products.image, products.url, products.is_main_view_from_variant, products.is_variant, products.variant_id,
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.price, products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        if ($noId > 0) {
            $builder->where('products.id !=', $noId);
        }
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        if ($categorie != 0) {
            $builder->where('products.shop_categorie !=', $categorie);
        }
        $builder->where('visibility', 1);
        $builder->where('is_visible', 1);
        if ($this->showOutOfStock == 0) {
            $builder->where('quantity >', 0);
        }
        $builder->orderBy('products.procurement', 'desc');
        $builder->limit(5);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function sameCagegoryProducts($categorie, $noId, $vendor_id = false)
    {
        $builder = $this->db->table('products');
        $builder->select('vendors.url as vendor_url, products.id, products.quantity, products.image, products.url, products.is_main_view_from_variant, products.is_variant, products.variant_id, 
        products.article_nr, products.is_visible, products.shipment_destination, products.Reserve_Produkt_03,
        products_translations.price, products_translations.title, products_translations.title2, products_translations.bullet1, products_translations.bullet2, products_translations.bullet3, products_translations.bullet4, products_translations.bullet5, products_translations.bullet6, products_translations.bullet7, products_translations.variant_name, products_translations.variant_description, products_translations.old_price, products_translations.shipping_cost, products_translations.shipping_time, products_translations.delivery_status');
        $builder->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $builder->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $builder->where('products.id !=', $noId);
        $builder->where('products.is_variant !=', 1);

        if ($vendor_id !== false) {
            $builder->where('vendor_id', $vendor_id);
        }
        $builder->where('products.shop_categorie =', $categorie);
        $builder->where('products_translations.abbr', MY_LANGUAGE_ABBR);
        $builder->where('visibility', 1);
        $builder->where('is_visible', 1);
        if ($this->showOutOfStock == 0) {
            $builder->where('quantity >', 0);
        }
        $builder->orderBy('products.id', 'desc');
        $builder->limit(5);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getOnePost($id)
    {
        $builder = $this->db->table('blog_posts');
        $builder->select('blog_translations.title, blog_translations.description, blog_posts.image, blog_posts.time');
        $builder->where('blog_posts.id', $id);
        $builder->join('blog_translations', 'blog_translations.for_id = blog_posts.id', 'left');
        $builder->where('blog_translations.abbr', MY_LANGUAGE_ABBR);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getArchives()
    {
        $result = $this->db->query("SELECT DATE_FORMAT(FROM_UNIXTIME(time), '%M %Y') as month, MAX(time) as maxtime, MIN(time) as mintime FROM blog_posts GROUP BY DATE_FORMAT(FROM_UNIXTIME(time), '%M %Y')");
        if ($result->countAllResults() > 0) {
            return $result->getResultArray();
        }
        return false;
    }

    public function getFooterCategories()
    {
        $builder = $this->db->table('shop_categories_translations');
        $builder->select('shop_categories.id, shop_categories_translations.name');
        $builder->where('abbr', MY_LANGUAGE_ABBR);
        $builder->where('shop_categories.sub_for =', 0);
        $builder->join('shop_categories', 'shop_categories.id = shop_categories_translations.for_id', 'INNER');
        $builder->limit(10);
        $query = $builder->get();
        $arr = array();
        if ($query !== false) {
            foreach ($query->getResultArray() as $row) {
                $arr[$row['id']] = $row['name'];
            }
        }
        return $arr;
    }

    public function setSubscribe($array)
    {
        $builder = $this->db->table('subscribed');
        $num = $builder->where('email', $arr['email'])->countAllResults();
        if ($num == 0) {
            $builder->insert($array);
        }
    }

    public function getDynPagesLangs($dynPages)
    {
        if (!empty($dynPages)) {
            $result = $this->db->table('active_pages')->join('textual_pages_tanslations', 'textual_pages_tanslations.for_id = active_pages.id', 'left')
                ->whereIn('active_pages.name', $dynPages)
                ->where('textual_pages_tanslations.abbr', MY_LANGUAGE_ABBR)
                ->select('textual_pages_tanslations.name as lname, active_pages.name as pname')
                ->get();
            $ar = array();
            $i = 0;
            foreach ($result->getResultArray() as $arr) {
                $ar[$i]['lname'] = $arr['lname'];
                $ar[$i]['pname'] = $arr['pname'];
                $i++;
            }
            return $ar;
        } else
            return $dynPages;
    }

    public function getOnePage($page)
    {
        $builder = $this->db->table('active_pages');
        $builder->join('textual_pages_tanslations', 'textual_pages_tanslations.for_id = active_pages.id', 'left');
        $builder->where('textual_pages_tanslations.abbr', MY_LANGUAGE_ABBR);
        $builder->where('active_pages.name', $page);
        $result = $builder->select('textual_pages_tanslations.description as content, textual_pages_tanslations.name')->get();
        return $result->getRowArray();
    }

    public function changePaypalOrderStatus($order_id, $status)
    {
        $processed = 0;
        if ($status == 'canceled') {
            $processed = 2;
        }
        $builder = $this->db->table('orders');
        $builder->where('order_id', $order_id);
        if (!$builder->update(array(
            'paypal_status' => $status,
            'processed' => $processed
        ))) {
            //   ///log_message('error', print_r($builder->error(), true));
        }
    }

    public function getCookieLaw()
    {
        $builder = $this->db->table('cookie_law')
            ->join('cookie_law_translations', 'cookie_law_translations.for_id = cookie_law.id', 'inner')
            ->where('cookie_law_translations.abbr', MY_LANGUAGE_ABBR)
            ->where('cookie_law.visibility', '1')
            ->select('link, theme, message, button_text, learn_more');
        $query = $builder->get();
        if ($builder->countAllResults() > 0) {
            return $query->getRowArray();
        } else {
            return false;
        }
    }

    public function confirmOrder($md5)
    {
        $builder = $this->db->table('confirm_links');
        $builder->limit(1);
        $builder->where('link', $md5);
        $result = $builder->get();
        $row = $result->getRowArray();
        if (!empty($row)) {
            $orderId = $row['for_order'];
            $builder = $this->db->table('orders');
            $builder->limit(1);
            $builder->where('order_id', $orderId);
            $result = $builder->update(array('confirmed' => '1'));
            return $result;
        }
        return false;
    }

    public function getValidDiscountCode($code)
    {
        $builder = $this->db->table('discount_codes');
        $time = time();
        $builder->select('type, amount');
        $builder->where('code', $code);
        $builder->where($time . ' BETWEEN valid_from_date AND valid_to_date');
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function countPublicUsersWithEmail($email, $id = 0)
    {
        $builder = $this->db->table('users_public');
        if ($id > 0) {
            $builder->where('id !=', $id);
        }
        $builder->where('email', $email);
        return $builder->countAllResults();
    }

    public function registerUser($post)
    {
        // Begin the transaction
        $this->db->transStart();

        $hashedPassword = hash('sha256', $post['pass']);

        // Insert into users_public
        $userData = [
            'email' => $post['email'],
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'password' => $hashedPassword,
            'phone' => $post['phone'],
            'mobile' => $post['mobile'],
            'lang' => $post['language'],
            'account_type' => $post['account_type'],
            'verify_token' => $post['verify_token']
        ];
        $this->db->table('users_public')->insert($userData);
        $userId = $this->db->insertID();

        // Prepare address data
        $addressData = [
            'first_name' => $post['first_name'],
            'last_name' => $post['last_name'],
            'street' => $post['street'],
            'post_code' => $post['post_code'],
            'country' => $post['country'],
            'city' => $post['city'],
            'housenr' => $post['housenr']
        ];

        // Insert into users_billing_addresses
        $this->db->table('users_billing_addresses')->insert($addressData);
        $billingAddressId = $this->db->insertID();

        // Insert into users_shipping_addresses
        $this->db->table('users_shipping_addresses')->insert($addressData);
        $shippingAddressId = $this->db->insertID();

        // Update users_public with the address IDs
        $this->db->table('users_public')->where('id', $userId)->update([
            'billing_address_id' => $billingAddressId,
            'shipping_address_id' => $shippingAddressId
        ]);

        // Complete the transaction
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        }

        return $userId;
    }

    public function setResetToken($email, $resetToken, $expirationTime)
    {
        $builder = $this->db->table('users_public');
        $builder->where('email', $email);

        $data = [
            'reset_token' => $resetToken,
            'reset_token_expiration' => $expirationTime,
        ];

        $builder->update($data);

        return $this->db->affectedRows();
    }

    public function getUserByResetToken($token)
    {
        $builder = $this->db->table('users_public');
        $builder->select('*');
        $builder->where('reset_token', $token);
        $query = $builder->get();
        $row = $query->getRow();

        if ($row) {
            return $row;
        } else {
            return null; // Token not found
        }
    }

    public function updateProfile($userId, $userData)
    {
        // Begin the transaction
        $this->db->transStart();

        // Update users_public table
        $publicData = [
            'email' => $userData['email'],
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'phone' => $userData['phone'],
            'mobile' => $userData['mobile'],
            'lang' => $userData['language'],
        ];
        $this->db->table('users_public')->where('id', $userId)->update($publicData);
        $billingAddressData = [
            'first_name' => $userData['first_name'],
            'last_name' => $userData['last_name'],
            'street' => $userData['street'],
            'post_code' => $userData['post_code'],
            'country' => $userData['country'],
            'city' => $userData['city'],
            'housenr' => $userData['housenr'],
        ];
        $this->db->table('users_billing_addresses')->where('billing_id', $userData['billing_id'])->update($billingAddressData);

        // Complete the transaction
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            // log_message('error', print_r($this->db->error(), true));
            dd(print_r($this->db->error(), true));
            return false;
        }

        return true;
    }

    public function updateShippingAddress($userId, $shippingData)
    {
        // Retrieve shipping_id from users_public table
        $shippingId = $this->db->table('users_public')
            ->select('shipping_address_id')
            ->where('id', $userId)
            ->get()
            ->getRow()
            ->shipping_address_id;

        // Update the node_users_shipping_addresses table
        $builder = $this->db->table('users_shipping_addresses');
        $builder->where('shipping_id', $shippingId);
        return $builder->update($shippingData); // Ensure to check the result of update
    }

    public function updateBillingAddress($userId, $billingData)
    {
        // Retrieve billing_id from users_public table
        $billingId = $this->db->table('users_public')
            ->select('billing_address_id')
            ->where('id', $userId)
            ->get()
            ->getRow()
            ->billing_address_id;

        // Update the node_users_billing_addresses table
        $builder = $this->db->table('users_billing_addresses');
        $builder->where('billing_id', $billingId);
        return $builder->update($billingData); // Ensure to check the result of update
    }

    public function updatePassword($post)
    {

        $hashedPassword = hash('sha256', $post['pass']);
        $array = array(
            'password' => $hashedPassword
        );
        $builder = $this->db->table('users_public');
        $builder->where('id', $post['id']);
        $builder->update($array);
    }

    public function checkPublicUserIsValid($post)
    {
        $hashedPassword = hash('sha256', $post['pass']);

        $builder = $this->db->table('users_public');
        $builder->where('email', $post['email']);
        $builder->where('password', $hashedPassword);
        $query = $builder->get();
        $result = $query->getRowArray();
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }

    public function getUserWithAddressesByID($id)
    {
        $builder = $this->db->table('users_public');
        $builder->select('users_public.*, billing.first_name as billing_first_name, billing.last_name as billing_last_name,billing.company as billing_company, billing.street as billing_street, billing.post_code as billing_post_code, billing.country as billing_country, billing.city as billing_city, billing.housenr as billing_housenr, shipping.first_name as shipping_first_name, shipping.last_name as shipping_last_name,shipping.company as shipping_company, shipping.street as shipping_street, shipping.post_code as shipping_post_code, shipping.country as shipping_country, shipping.city as shipping_city, shipping.housenr as shipping_housenr');
        $builder->join('users_billing_addresses as billing', 'users_public.billing_address_id = billing.billing_id', 'left');
        $builder->join('users_shipping_addresses as shipping', 'users_public.shipping_address_id = shipping.shipping_id', 'left');
        $builder->where('users_public.id', $id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function getUserProfileInfoByEmail($email)
    {
        $builder = $this->db->table('users_public');
        $builder->where('email', $email);
        $query = $builder->get();
        return $query->getRow();
    }

    public function getUserWithAddressesByEmail($email)
    {
        $builder = $this->db->table('users_public');
        $builder->select('users_public.*, billing.first_name as billing_first_name, billing.last_name as billing_last_name,billing.company as billing_company, billing.street as billing_street, billing.post_code as billing_post_code, billing.country as billing_country, billing.city as billing_city, billing.housenr as billing_housenr, shipping.first_name as shipping_first_name, shipping.last_name as shipping_last_name,shipping.company as shipping_company, shipping.street as shipping_street, shipping.post_code as shipping_post_code, shipping.country as shipping_country, shipping.city as shipping_city, shipping.housenr as shipping_housenr');
        $builder->join('users_billing_addresses as billing', 'users_public.billing_address_id = billing.billing_id', 'left');
        $builder->join('users_shipping_addresses as shipping', 'users_public.shipping_address_id = shipping.shipping_id', 'left');
        $builder->where('users_public.email', $email);
        $query = $builder->get();
        return $query->getRow();
    }

    public function findUserByToken($verificationToken)
    {
        $builder = $this->db->table('users_public');
        $builder->where('verify_token', $verificationToken);
        $query = $builder->get();
        return $query->getRow();
    }

    public function markEmailAsVerified($id)
    {
        $array = array(
            'verified' => 1
        );
        $builder = $this->db->table('users_public');
        $builder->where('id', $id);
        $builder->update($array);
    }

    public function getUserProfileInfo($id)
    {
        $builder = $this->db->table('users_public');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public function sitemap()
    {
        $builder = $this->db->table('products');
        $query = $builder->select('url')->get();
        return $query;
    }

    public function sitemapBlog()
    {
        $builder = $this->db->table('blog_posts');
        $query = $builder->select('url')->get();
        return $query;
    }

    public function getUserOrdersHistoryCount($userId)
    {
        $builder = $this->db->table('orders');
        $builder->where('user_id', $userId);
        return $builder->countAllResults();
    }


    public function getOrderById($orderId)
    {
        $builder = $this->db->table('orders');
        $builder->where('orders.order_id', $orderId);

        // Select fields from the orders table and related client and shipping tables
        $builder->select('orders.*, '
            . 'clients.first_name as billing_first_name, clients.last_name as billing_last_name, '
            . ' clients.company as billing_company,clients.notes as notes,'
            . 'clients.street as billing_street, clients.housenr as billing_housenr, clients.country as billing_country, '
            . 'clients.city as billing_city, clients.post_code as billing_post_code, clients.notes as billing_notes, '
            . 'shipping.first_name as shipping_first_name, shipping.last_name as shipping_last_name, '
            . 'shipping.company as shipping_company, shipping.street as shipping_street, '
            . 'shipping.housenr as shipping_housenr, shipping.country as shipping_country, '
            . 'shipping.city as shipping_city, shipping.post_code as shipping_post_code, '
            . 'discount_codes.type as discount_type, discount_codes.amount as discount_amount');

        // Join with the orders_clients and orders_shippings tables
        $builder->join('orders_clients as clients', 'clients.for_id = orders.id', 'inner');
        $builder->join('orders_shipping as shipping', 'shipping.for_id = orders.id', 'inner');
        $builder->join('discount_codes', 'discount_codes.code = orders.discount_code', 'left');

        $result = $builder->get();

        return $result->getRowArray(); // Return a single row
    }


    public function deleteUserAccount($userId)
    {
        // Begin the transaction
        $this->db->transStart();

        // Get the user's billing and shipping address IDs
        $user = $this->db->table('users_public')->select('billing_address_id, shipping_address_id')->where('id', $userId)->get()->getRow();
        if ($user) {
            // Delete user data from related tables first to maintain referential integrity
            if ($user->billing_address_id) {
                $this->db->table('users_billing_addresses')->where('billing_id', $user->billing_address_id)->delete();
            }
            if ($user->shipping_address_id) {
                $this->db->table('users_shipping_addresses')->where('shipping_id', $user->shipping_address_id)->delete();
            }
        }

        $this->db->table('users_public')->where('id', $userId)->delete();

        // Complete the transaction
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            // handle the error...
            log_message('error', 'Failed to delete user account for user ID: ' . $userId);
            return false;
        }
        return true;
    }
    public function getSmartHomeDevicesByUID($uid, $limit, $page)
    {
        $offset = ($page - 1) * $limit; // Correctly calculate the offset

        $builder = $this->db->table('SmartHomeDevices');
        $builder->select('DeviceID, SerialNumber, UID, DeviceName, State, IsConnected');
        $builder->where('UID', $uid);
        $builder->limit($limit, $offset);
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function countSmartHomeDevicesByUID($uid)
    {
        $builder = $this->db->table('SmartHomeDevices');
        $builder->where('UID', $uid);
        return $builder->countAllResults();
    }
}