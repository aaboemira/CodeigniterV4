<?php
namespace App\Controllers\Admin\Ecommerce;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Discounts_model;
use App\Models\admin\Home_admin_model;

class Discounts extends ADMIN_Controller
{

    private $num_rows = 10;
    protected $Discounts_model;
    protected $Home_admin_model;

    public function __construct()
    {
        $this->Discounts_model = new Discounts_model();
        $this->Home_admin_model = new Home_admin_model();
    }

    public function index($page = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Discounts';
        $head['description'] = '!';
        $head['keywords'] = '';
        if (isset($_POST['code'])) {
            $this->setDiscountCode();
        }
        if (session('post')) {
            $_POST = session('post');
        }
        if (isset($_GET['edit'])) {
            $_POST = $this->Discounts_model->getDiscountCodeInfo($_GET['edit']);
            if (empty($_POST)) {
                return redirect()->to('admin/discounts');
            }
            $_POST['valid_from_date'] = date('d.m.Y', $_POST['valid_from_date']);
            $_POST['valid_to_date'] = date('d.m.Y', $_POST['valid_to_date']);
            $_POST['update'] = $_POST['id'];
        }
        if (isset($_GET['tostatus']) && isset($_GET['codeid'])) {
            $this->Discounts_model->changeCodeDiscountStatus($_GET['codeid'], $_GET['tostatus']);
            return redirect()->to('admin/discounts');
        }
        if (isset($_POST['codeDiscounts'])) {
            $this->Home_admin_model->setValueStore('codeDiscounts', $_POST['codeDiscounts']);
            return redirect()->to('admin/discounts');
        }
        $data['codeDiscounts'] = $this->Home_admin_model->getValueStore('codeDiscounts');
        $rowscount = $this->Discounts_model->discountCodesCount();
        $data['discountCodes'] = $this->Discounts_model->getDiscountCodes($this->num_rows, $page);
        $data['links_pagination'] = pagination('admin/discounts', $rowscount, $this->num_rows, 3);

        if ($page == 0) {
            $this->saveHistory('Go to discounts page');
        }

        $page = 'ecommerce/discounts';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    private function setDiscountCode()
    {
        $isValid = $this->validateCode();
        if ($isValid === true) {
            if ($_POST['update'] == 0) {
                $this->Discounts_model->setDiscountCode($_POST);
            } else {
                $this->Discounts_model->updateDiscountCode($_POST);
            }
            session()->setFlashdata('success', lang_safe('discount_changes_success'));
        } else {
            session()->setFlashdata('error', $isValid);
            session()->setFlashdata('post', $_POST);
        }
        return redirect()->to('admin/discounts');
    }

    private function validateCode()
    {
        $errors = array();
        if ($_POST['type'] != 'percent' && $_POST['type'] != 'float') {
            $errors[] = 'Type of discount is not valid!';
        }
        if ((float) $_POST['amount'] == 0) {
            $errors[] = 'Discount amount is 0!';
        }
        if (mb_strlen(trim($_POST['code'])) < 3) {
            $errors[] = 'Discount code is lower than 3 symbols!';
        } else {
            $isFree = $this->Discounts_model->discountCodeTakenCheck($_POST);
            if ($isFree === false) {
                $errors[] = 'Discount code taken!';
            }
        }
        if (strtotime($_POST['valid_from_date']) === false) {
            $errors[] = 'From date is invalid!';
        }
        if (strtotime($_POST['valid_to_date']) === false) {
            $errors[] = 'To date is invalid!';
        }
        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

}
