<?php
namespace App\Controllers\Admin\Ecommerce;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Languages_model;
use App\Models\admin\Categories_model;

class ShopCategories extends ADMIN_Controller
{

    private $num_rows = 10;

    protected $Languages_model;
    protected $Categories_model;

    public function __construct()
    {
        $this->Languages_model = new Languages_model();
        $this->Categories_model = new Categories_model();
    }

    public function index($page = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Home Categories';
        $head['description'] = '!';
        $head['keywords'] = '';


        $rowscount = $this->Categories_model->categoriesCount();
        $totalPages = ceil($rowscount / $this->num_rows);
        $page = max(1, min($page, $totalPages));

        $data['shop_categories'] = $this->Categories_model->getShopCategories($this->num_rows, $page);
        $data['languages'] = $this->Languages_model->getLanguages();
        $rowscount = $this->Categories_model->categoriesCount();
        // Create pagination links
        $data['paginationLinks'] = '';
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = $page == $i ? 'active' : '';
            $href = base_url("admin/shopcategories/$i"); // Generating the URL
            $data['paginationLinks'] .= "<li class='page-item $active'><a class='page-link' href='{$href}'>$i</a></li>";
        }
        if (isset($_GET['delete'])) {
            $this->saveHistory('Delete a shop categorie');
            $this->Categories_model->deleteShopCategorie($_GET['delete']);
            session()->setFlashdata('result_delete', lang_safe('shop_category_delete_success'));
            return redirect()->to('admin/shopcategories');
        }
        if (isset($_POST['submit'])) {
            $this->Categories_model->setShopCategorie($_POST);
            session()->setFlashdata('result_add', lang_safe('shop_category_add_success'));
            return redirect()->to('admin/shopcategories');
        }
        if (isset($_POST['editSubId'])) {
            $result = $this->Categories_model->editShopCategorieSub($_POST);
            if ($result === true) {
                session()->setFlashdata('result_add', lang_safe('subcategory_change_success'));
                $this->saveHistory('Change subcategory for category id - ' . $_POST['editSubId']);
            } else {
                session()->setFlashdata('result_add', lang_safe('shop_category_change_problem'));
            }
            return redirect()->to('admin/shopcategories');
        }
        $this->saveHistory('Go to shop categories');
        $page = 'ecommerce/shopcategories';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    /*
     * Called from ajax
     */

    public function editShopCategorie()
    {
        $this->login_check();
        $result = $this->Categories_model->editShopCategorie($_POST);
        $this->saveHistory('Edit shop categorie to ' . $_POST['name']);
    }

    /*
     * Called from ajax
     */

    public function changePosition()
    {
        $this->login_check();
        $result = $this->Categories_model->editShopCategoriePosition($_POST);
        $this->saveHistory('Edit shop categorie position ' . $_POST['name']);
    }

}
