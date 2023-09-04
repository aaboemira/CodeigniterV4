<?php
namespace App\Controllers\admin;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\BaseController;

class ADMIN_Controller extends BaseController
{

    protected $username;
    protected $activePages;
    protected $allowed_img_types;
    protected $history;

    public function __construct()
    {
        $this->Home_admin_model = new \App\Models\admin\Home_admin_model();
        //$this->history = $this->config->item('admin_history');
        $this->activePages = $vars['activePages'] = $this->getActivePages();
        $numNotPreviewOrders = $this->Home_admin_model->newOrdersCheck();
        //$this->allowed_img_types = $this->config->item('allowed_img_types');
        $vars['textualPages'] = getTextualPages($this->activePages);
        //$vars['nonDynPages'] = $this->config->item('no_dynamic_pages');
        $vars['numNotPreviewOrders'] = $numNotPreviewOrders;
        $vars['warnings'] = $this->warningChecker();
        $vars['showBrands'] = $this->Home_admin_model->getValueStore('showBrands');
        $vars['virtualProducts'] = $this->Home_admin_model->getValueStore('virtualProducts');
        //$this->load->vars($vars);
    }

    protected function login_check()
    {
        if (!session('logged_in')) {
            header("Location:".base_url('admin'));
            exit;
        }
        $this->username = session('logged_in');
    }

    protected function saveHistory($activity)
    {
        if ($this->history === true) {
            $this->History_model = new \App\Models\admin\History_model();
            $usr = $this->username;
            $this->History_model->setHistory($activity, $usr);
        }
    }

    public function getActivePages()
    {
        $this->Pages_model = new \App\Models\admin\Pages_model();
        return $this->Pages_model->getPages(true, false);
    }

    private function warningChecker()
    {
        $errors = array();

        // Check application/language folder is writable
        if (!is_writable(APPPATH . 'language')) {
            $errors[] = 'Language folder is not writable!';
        }

        // Check application/logs folder is writable
        if (!is_writable(APPPATH . 'logs')) {
            $errors[] = 'Logs folder is not writable!';
        }

        // Check attachments folder is writable
        if (!is_writable('.' . DIRECTORY_SEPARATOR . 'attachments')) {
            $errors[] = 'Attachments folder is not writable!';
        } else {
            /*
             *  Check attachment directories exsists..
             *  ..and create him if no exsists
             */
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'blog_images')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'blog_images', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'lang_flags')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'lang_flags', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'shop_images', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'site_logo')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'site_logo', 0777, true);
                umask($old);
            }
        }
        return $errors;
    }

}
