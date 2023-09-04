<?php
namespace App\Controllers\Admin\Ecommerce;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Products_model;
use App\Models\admin\Languages_model;
use App\Models\admin\Categories_model;

class Products extends ADMIN_Controller
{

    private $num_rows = 100;

    protected $Products_model;
    protected $Languages_model;
    protected $Categories_model;

    public function __construct()
    {
        $this->Products_model = new Products_model();
        $this->Languages_model = new Languages_model();
        $this->Categories_model = new Categories_model();
    }

    public function index($page = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - View products';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_GET['delete'])) {
            $this->Products_model->deleteProduct($_GET['delete']);
            session()->setFlashdata('result_delete', 'product is deleted!');
            $this->saveHistory('Delete product id - ' . $_GET['delete']);
            return redirect()->to('admin/products');
        }

        unset($_SESSION['filter']);
        $search_title = null;
        if (request()->getPost('search_title') !== NULL) {
            $search_title = request()->getPost('search_title');
            $_SESSION['filter']['search_title'] = $search_title;
            $this->saveHistory('Search for product title - ' . $search_title);
        }
        $orderby = null;
        if (request()->getPost('order_by') !== NULL) {
            $orderby = request()->getPost('order_by');
            $_SESSION['filter']['order_by '] = $orderby;
        }
        $category = null;
        if (request()->getPost('category') !== NULL) {
            $category = request()->getPost('category');
            $_SESSION['filter']['category '] = $category;
            $this->saveHistory('Search for product code - ' . $category);
        }
        $vendor = null;
        if (request()->getPost('show_vendor') !== NULL) {
            $vendor = request()->getPost('show_vendor');
        }
        $data['products_lang'] = $products_lang = session('admin_lang_products');
        $rowscount = $this->Products_model->productsCount($search_title, $category);
        $data['products'] = $this->Products_model->getproducts($this->num_rows, $page, $search_title, $orderby, $category, $vendor);
        $data['links_pagination'] = pagination('admin/products', $rowscount, $this->num_rows, 3);
        $data['num_shop_art'] = $this->Products_model->numShopproducts();
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['shop_categories'] = $this->Categories_model->getShopCategories(null, null, 2);
        $this->saveHistory('Go to products');
        $page = 'ecommerce/products';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    public function getProductInfo($id, $noLoginCheck = false)
    {
        /* 
         * if method is called from public(template) page
         */
        if ($noLoginCheck == false) {
            $this->login_check();
        }
        return $this->Products_model->getOneProduct($id);
    }

    /*
     * called from ajax
     */

    public function productStatusChange()
    {
        $this->login_check();
        $result = $this->Products_model->productStatusChange($_POST['id'], $_POST['to_status']);
        if ($result == true) {
            echo 1;
        } else {
            echo 0;
        }
        $this->saveHistory('Change product id ' . $_POST['id'] . ' to status ' . $_POST['to_status']);
    }

}
