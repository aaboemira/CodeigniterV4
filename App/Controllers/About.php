<?php

namespace App\Controllers;

use App\Libraries\SendMail;
use App\Models\Public_model;
use App\Models\admin\Home_admin_model;

class About extends BaseController
{
    protected $SendMail;
    protected $Home_admin_model;
    protected $Public_model;

    public function __construct()
    {
        $this->SendMail = new SendMail();
        $this->Public_model = new Public_model();
        $this->Home_admin_model = new Home_admin_model();
    }

    public function index()
    {
        $head = array();
        $data = array();
        if (isset($_POST['message'])) {
            $result = $this->sendEmail();
            if ($result) {
                session()->setFlashdata('resultSend', lang_safe('about_email_success'));
            } else {
                session()->setFlashdata('resultSend', lang_safe('about_email_error'));
            }
            redirect('about');
        }
        $data['googleMaps'] = $this->Home_admin_model->getValueStore('googleMaps');
        $data['googleApi'] = $this->Home_admin_model->getValueStore('googleApi');
        $arrSeo = $this->Public_model->getSeo('contacts');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('about', $head, $data);
    }

    private function sendEmail()
    {					
		$myEmail = $this->Home_admin_model->getValueStore('contactsEmailTo');
		if (filter_var($myEmail, FILTER_VALIDATE_EMAIL) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$result = $this->sendmail->sendContactMail($_POST['email'], $_POST['name'], $myEmail, 'ND', $_POST['subject'], $_POST['message']);
                    return $result;
					
					
					
					
			// $result = $this->sendmail->sendTo($myEmail, 'Dear Customer', 'Virtual products', $_POST['name']);
                    // return $result;
					
			 // //First handle the upload
			// if (array_key_exists('attachment', $_FILES)) {
				// //Don't trust provided filename - same goes for MIME types
				// //See http://php.net/manual/en/features.file-upload.php#114004 for more thorough upload validation
				// //Extract an extension from the provided filename
				// $ext = PHPMailer::mb_pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
				// //Define a safe location to move the uploaded file to, preserving the extension
				// $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['attachment']['name'])) . '.' . $ext;
				
				
				// $result = $this->sendmail->sendContactMail($_POST['email'], $_POST['name'], $myEmail, 'ND', $_POST['subject'], $_POST['message'], $uploadfile );
                    // return $result;
			// }
			// else{
				// return false;
			// }
			
        return false;
		}
	}
}	
	
	// public function sendEmail($email,$subject,$message)
    // {
		// $config = Array(
		  // 'protocol' => 'smtp',
		  // 'smtp_host' => 'ssl://smtp.strato.de',
		  // 'smtp_port' => 465,
		  // 'smtp_user' => 'ontakt@nodedevices.de', 
		  // 'smtp_pass' => '9@xQr2AZreE82s7', 
		  // 'mailtype' => 'html',
		  // 'charset' => 'iso-8859-1',
		  // 'wordwrap' => TRUE
		// );


			  // $this->load->library('email', $config);
			  // $this->email->set_newline("\r\n");
			  // $this->email->from($email);
			  // $this->email->to( "kontakt@nodedevices.de");
			  // $this->email->subject($subject);
			  // $this->email->message($message);
				// $this->email->attach('C:\Users\xyz\Desktop\images\abc.png');
			  // if($this->email->send())
			 // {
			  // echo 'Email send.';
			 // }
	// }
       // else
        // {
         // show_error($this->email->print_debugger());
        // }

    // }

     //   $myEmail = $this->Home_admin_model->getValueStore('contactsEmailTo');
        // if (filter_var($myEmail, FILTER_VALIDATE_EMAIL) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
// /* 
			//$this->load->library('email');
			
            //$this->email->from($_POST['email'], $_POST['name']);
           // $this->email->to($myEmail);
//$this->email->to($myEmail);
			

		   
			
            //$this->email->subject($_POST['subject']);
         //   $this->email->message($_POST['message']);

