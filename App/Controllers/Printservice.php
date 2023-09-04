<?php

namespace App\Controllers;
use App\Libraries\SendMail;

class Printservice extends BaseController
{

    protected $SendMail;

    public function __construct()
    {
        $this->SendMail = new SendMail();
    }

    public function index()
    {
        $head = array();
        $data = array();
        if (isset($_POST['message'])) {
            $result = $this->sendEmail();
            if ($result) {
                session()->setFlashdata('resultSend', 'Email is sent!');
            } else {
                session()->setFlashdata('resultSend', 'Email send error!');
            }
            return redirect()->to('printservice');
        }
        $data['googleMaps'] = $this->Home_admin_model->getValueStore('googleMaps');
        $data['googleApi'] = $this->Home_admin_model->getValueStore('googleApi');
        $arrSeo = $this->Public_model->getSeo('contacts');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('printservice', $head, $data);
    }

    private function sendEmail()
    {					
		$myEmail = $this->Home_admin_model->getValueStore('contactsEmailTo');
		if (filter_var($myEmail, FILTER_VALIDATE_EMAIL) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$result = $this->sendmail->sendContactMail($_POST['email'], $_POST['name'], $myEmail, 'ND', $_POST['subject'], $_POST['message']);
                return $result;
			
        return false;
		}
	}
}	
	