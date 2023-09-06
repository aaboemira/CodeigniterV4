<?php
namespace App\Libraries;

use PHPMailer;
use App\Helpers\EmailHelper;

require 'PHPMailer/PHPMailerAutoload.php';
class SendMail
{

    public $mail;
    protected $emailHelper;
    public function __construct()
    {
		$this->mail = new PHPMailer; 
		$this->mail->isSMTP(); 
		//$this->mail->SMTPDebug = 2; 
		$this->mail->Debugoutput = 'html'; 
		$this->mail->Host = 'mx.freenet.de'; 
		$this->mail->Port = 465; 
		$this->mail->SMTPSecure = 'ssl'; 
		$this->mail->SMTPAuth = true; 
		$this->mail->Username = "Kontaktformular_ND@freenet.de"; 
		$this->mail->Password = "gQQK5Q5wuxUDJJP";
		$this->mail->CharSet = 'UTF-8';
		$this->emailHelper=new EmailHelper();
    }

    public function sendTo($toEmail, $recipientName, $subject, $msg)
    {
		$this->mail->clearAddresses();
        $this->mail->setFrom('Kontaktformular_ND@freenet.de', 'Node Devices GmbH');
        $this->mail->addAddress($toEmail, $recipientName);
		//$this->mail->addBCC('kontakt@nodedevices.de');
        //$this->mail->isHTML(true); 
        $this->mail->Subject = $subject;
        $this->mail->Body = $msg;
        if (!$this->mail->send()) {
            log_message('error', 'Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
		
        return true;
    }
	public function sendToBestellbestaetigung($toEmail, $recipientName, $subject, $msg,$data,$attachmentData,$attachmentName,$german)
    {
        $this->mail->setFrom('Kontaktformular_ND@freenet.de', 'Node Devices GmbH');
		
        $this->mail->addAddress($toEmail, $recipientName);
		$this->mail->addBCC('kontakt@nodedevices.de');

		if($german){
			$templatePath = FCPATH.'/assets/email/email_template.html';
		}else{
			$templatePath = FCPATH.'/assets/email/email_template_en.html';
		}
		$emailTemplate = file_get_contents($templatePath);
		$emailTemplate = str_replace('{ORDER_NUMBER}', $data['order_id'], $emailTemplate);
		$shippingNumber = isset($data['shipping_number']) && !empty($data['shipping_number']) ? $data['shipping_number'] : '-';
		if ($shippingNumber !== "-") {
			$shippingLink = '<a href="https://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=' . $shippingNumber . '" style="color: #930313; text-decoration: underline; margin-left: 2px;">' . $shippingNumber . '</a>';
		} else {
			$shippingLink = '<span style="font-size:16px">-</span>';
		}
		$emailTemplate = str_replace('{SHIPPING_TYPE}', $shippingLink, $emailTemplate);
		$emailTemplate = str_replace('{FULL_NAME}', $data['full_name'], $emailTemplate);

		$this->mail->Body = $emailTemplate;
		
        $this->mail->isHTML(true);
	 
        $this->mail->Subject = $subject;
        //$this->mail->Body = $msg;
		if ($attachmentName && $attachmentData) {
			// Attach the PDF
			$this->mail->addStringAttachment($attachmentData, $attachmentName, 'base64', 'application/pdf');
		}
        if (!$this->mail->send()) {
            log_message('error', 'Mailer Error: ' . $this->mail->ErrorInfo);
			var_dump($this->mail->ErrorInfo);
            return false;
        }

        return true;
    }
	
	public function orderConfirmation($toEmail, $recipientName, $subject, $msg,$data,$german)
    {
        $this->mail->setFrom('Kontaktformular_ND@freenet.de', 'Node Devices GmbH');
		
        $this->mail->addAddress($toEmail, $recipientName);
		$this->mail->addBCC('kontakt@nodedevices.de');
		if($german){
			$html=$this->emailHelper->generateEmailHTML($data);
		}else{
			$html=$this->emailHelper->generateEmailHTML_en($data);
		}
		$this->mail->Body = $html;
		
        $this->mail->isHTML(true);
	 
        $this->mail->Subject = $subject;

        if (!$this->mail->send()) {
            log_message('error', 'Mailer Error: ' . $this->mail->ErrorInfo);
			var_dump($this->mail->ErrorInfo);

            return false;
        }

        return true;
    }
    public function sendContactMail($fromEmail, $fromName,  $toEmail, $recipientName, $subject, $msg)
    {
		if (filter_var($fromEmail, FILTER_VALIDATE_EMAIL) && filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
	
			$this->mail->setFrom($fromEmail, $fromName);			
			$this->mail->addAddress($toEmail, $recipientName);
			//$this->mail->isHTML(true); 
			$this->mail->Subject = $subject;
			$this->mail->Body = $msg;
			
			if (array_key_exists('attachment', $_FILES)) {
				//Don't trust providerrrrRRd filename - same goes for MIME types
				//See http://php.net/manual/en/features.file-upload.php#114004 for more thorough upload validation
				//Extract an extension from the provided filename
				$ext = PHPMailer::mb_pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
				//Define a safe location to move the uploaded file to, preserving the extension

				//sleep(20);
				//move_uploaded_file($_FILES['attachment']['tmp_name'] , '/var/www/nodedevices.de/tmp/'. $_FILES['attachment']['name']);
				$uploadfile = tempnam('/var/www/nodedevices.de/tmp/', $_FILES['attachment']['tmp_name'] );
				  if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadfile)) {
        // Upload handled successfully
        // Now create a message
	           if ($this->mail->addAttachment($uploadfile, $_FILES['attachment']['name'])) {
					
							if ($this->mail->send()) {
				        return true;
			        }
		      }
				}	
			}
			}
				return false;		
			}
}





















