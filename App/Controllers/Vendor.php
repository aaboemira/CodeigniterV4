<?php

/*
 * Only vendors class
 */
namespace App\Controllers;

use App\Models\vendor\Vendorprofile_model;

class Vendor extends BaseController
{

    private $num_rows = 20;

    protected $Vendorprofile_model;

    public function __construct()
    {
        $this->Vendorprofile_model = new Vendorprofile_model();
    }

    public function index($page = 0, $vendor)
    {
        $vendorInfo = $this->Vendorprofile_model->getVendorByUrlAddress($vendor);
        if ($vendorInfo == null) {
            $this->show_404();
        }
        $data = array();
        $head = array();
        $head['title'] = $vendorInfo['name'];
        $head['description'] = lang_safe('vendor_view') . $vendorInfo['name'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $head['vendor_url'] = LANG_URL . '/vendor/view/' . $vendor;
        $all_categories = $this->Public_model->getShopCategories();

        /*
         * Tree Builder for categories menu
         */

        function buildTree(array $elements, $parentId = 0)
        {
            $branch = array();
            foreach ($elements as $element) {
                if ($element['sub_for'] == $parentId) {
                    $children = buildTree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
            return $branch;
        }

        $data['home_categories'] = $tree = buildTree($all_categories);
        $data['all_categories'] = $all_categories;
        $data['vendorInfo'] = $vendorInfo;
        $data['products'] = $this->Public_model->getProducts($this->num_rows, $page, $_GET, $vendorInfo['id']);
        $rowscount = $this->Public_model->productsCount($_GET);
        $data['links_pagination'] = pagination('vendor/view/' . $vendor, $rowscount, $this->num_rows, 4);
        return $this->render('vendor', $head, $data);
    }

    public function viewProduct($vendor, $id)
    {
        $vendorInfo = $this->Vendorprofile_model->getVendorByUrlAddress($vendor);
        if ($vendorInfo == null) {
            $this->show_404();
        }
        $data = array();
        $head = array();
        $data['product'] = $this->Public_model->getOneProduct($id);
        $data['sameCagegoryProducts'] = $this->Public_model->sameCagegoryProducts($data['product']['shop_categorie'], $id, $vendorInfo['id']);
        if ($data['product'] === null) {
            $this->show_404();
        }
        $vars['publicDateAdded'] = $this->Home_admin_model->getValueStore('publicDateAdded');
        $this->load->vars($vars);
        $head['title'] = $vendorInfo['name'] . ' - ' . $data['product']['title'];
        $description = url_title(character_limiter(strip_tags($data['product']['description']), 130));
        $description = str_replace("-", " ", $description) . '..';
        $head['description'] = $description;
        $head['keywords'] = str_replace(" ", ",", $data['product']['title']);
        $data['vendorInfo'] = $vendorInfo;
        return $this->render('view_product_vendor', $head, $data);
    }

}
