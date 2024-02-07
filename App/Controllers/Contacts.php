<?php

namespace App\Controllers;

use App\Models\admin\Home_admin_model;
use CodeIgniter\Email\Email;
use App\Libraries\SendMail;

class Contacts extends BaseController
{

    protected $Home_admin_model;
    protected $sendmail;
    
    public function __construct()
    {
        $this->Home_admin_model = new Home_admin_model();
        $this->sendmail = new SendMail();
    }

    public function index()
    {
        $head = array();
        $data = array();
        if (isset($_POST['message'])) {
            
                $result = $this->sendEmail();
                if ($result) {
                    session()->setFlashdata('resultSend', lang_safe('contacts_email_success'));
                } else {
                    session()->setFlashdata('resultSend', lang_safe('contacts_email_error'));
                }
                return redirect()->to('contacts');
        }


        // if (isset($_POST['message'])) {
        //     if (isset($_POST['dataprotection'])) {
        //         $result = $this->sendEmail();
        //         if ($result) {
        //             session()->setFlashdata('resultSend', 'Email is Sent!');
        //         } else {
        //             session()->setFlashdata('resultSend', 'Email send error!');
        //         }
        //         return redirect()->to('contacts');
        //     }
        //     else {
        //         session()->setFlashdata('resultSend', 'Datenschutz zustimmen!');
        //     }
          
        // }
        $data['googleMaps'] = $this->Home_admin_model->getValueStore('googleMaps');
        $data['googleApi'] = $this->Home_admin_model->getValueStore('googleApi');
        $arrSeo = $this->Public_model->getSeo('contacts');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('contacts', $head, $data);
    }
    // private function sendEmail()
    // {					
	// 	$myEmail = $this->Home_admin_model->getValueStore('contactsEmailTo');
	// 	if (filter_var($myEmail, FILTER_VALIDATE_EMAIL) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	// 		$result = $this->sendmail->sendContactMail($_POST['email'], $_POST['name'], $myEmail, 'ND', $_POST['subject'], $_POST['message']);
    //             return $result;
			
    //     return false;
	// 	}
	// }
    private function sendEmail()
    {
        $myEmail = $this->Home_admin_model->getValueStore('contactsEmailTo');
        if (filter_var($myEmail, FILTER_VALIDATE_EMAIL) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $result = $this->sendmail->sendTo($myEmail, $_POST['email'] . ',', $_POST['subject'], $_POST['email'] . ' ' . $_POST['name'] . ' -> ' . $_POST['message']);
            return  $result;
        }
        return false;
    }
    // $users = $this->Public_model->getNotifyUsers();
    // $myDomain = $this->config->item('base_url');
    // if (!empty($users)) {
    //     //$this->sendmail->clearAddresses();
    //     foreach ($users as $user) {
    //         $this->sendmail->sendTo($user, 'Admin', 'New order in ' . $myDomain, 'Hello, you have new order. Can check it in /admin/orders');
    //     }
    // }
}