<?php
namespace App\Controllers\Admin\Settings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Emails_model;

class Emails extends ADMIN_Controller
{

    private $num_rows = 20;
    protected $Emails_model;

    public function __construct()
    {
        $this->Emails_model = new Emails_model();
    }

    public function index($page = 0)
    {
        $this->login_check();
        if (isset($_POST['export'])) {
            $rowscount = $this->Emails_model->emailsCount();
            header("Content-Disposition: attachment; filename=online-shop-$rowscount-emails-export.txt");
            $all_emails = $this->Emails_model->getSuscribedEmails(0, 0);
            foreach ($all_emails->getResult() as $row) {
                echo $row->email . "\n";
            }
            exit;
        }
        if (isset($_GET['delete'])) {
            $data = $this->Emails_model->deleteEmail($_GET['delete']);
            session()->setFlashdata('emailDeleted', lang_safe('email_address_delete_success'));
            return redirect()->to('admin/emails');
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Subscribed Emails';
        $head['description'] = '!';
        $head['keywords'] = '';
        $rowscount = $this->Emails_model->emailsCount();
        $data['links_pagination'] = pagination('admin/emails', $rowscount, $this->num_rows, 3);
        $data['emails'] = $this->Emails_model->getSuscribedEmails($this->num_rows, $page);
        if ($page == 0) {
            $this->saveHistory('Go to Subscribed Emails');
        }
        $page = 'settings/emails';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

}
