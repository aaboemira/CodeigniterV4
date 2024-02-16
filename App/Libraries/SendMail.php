<?php
namespace App\Libraries;

use App\Helpers\PasswordResetEmailHelper;
use App\Helpers\VerifcationEmailHelper;
use PHPMailer;
use App\Helpers\EmailHelper;

require 'PHPMailer/PHPMailerAutoload.php';
class SendMail
{

    public $mail;
    protected $emailHelper;
    protected $passwordEmailHelper;
    protected $verifcationEmailHelper;
    public function __construct()
    {
        $this->mail = new PHPMailer;
        $this->mail->isSMTP();
        //$this->mail->SMTPDebug = 2;
        $this->mail->Debugoutput = 'html';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 465;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "a.aboemira1@gmail.com";
        $this->mail->Password = "hflqrrqhrfgogxcf";
        $this->mail->CharSet = 'UTF-8';
		$this->emailHelper=new EmailHelper();
        $this->passwordEmailHelper=new PasswordResetEmailHelper();
        $this->verifcationEmailHelper=new VerifcationEmailHelper();
    }


    public function sendTo($toEmail, $recipientName, $subject, $msg)
    {
		$this->mail->clearAddresses();
        //$this->mail->setFrom('kontakt@nodedevices.de', 'Node Devices');
        $this->mail->setFrom('kontakt@nodedevices.de', 'Node Devices');

        //$this->mail->addReplyTo('kontakt@nodedevices.de', 'Node Devices');
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
        $this->mail->setFrom('kontakt@nodedevices.de', 'Node Devices');

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
            return false;
        }

        return true;
    }
	
	public function orderConfirmation($toEmail, $recipientName, $subject,$data,$german)
    {
		$this->mail->clearAddresses();
        $this->mail->setFrom('kontakt@nodedevices.de', 'Node Devices');
		
        $this->mail->addAddress($toEmail);

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
            return false;
        }

        return true;
    }

    public function sendPasswordRecoveryEmail($toEmail, $data,$resetLink)
    {
        $german=false;

        //$this->mail->setFrom('kontakt@nodedevices.de', 'Node Devices');
        $this->mail->setFrom('a.aboemira1@gmail.com', 'Node Devices');
        $this->mail->addAddress($toEmail);
        if ($data->lang=='deu')$german=true;
        // Email subject
        if($german){
            $html=$this->passwordEmailHelper->generateEmailHTML($data,$resetLink);
            $subject='Passwortanderung fur die Nodematic Website';
        }else{
            $html=$this->passwordEmailHelper->generateEmailHTML_en($data,$resetLink);
            $subject='Password Recovery for Nodematic Website ';
        }
        $this->mail->Body = $html;

        $this->mail->isHTML(true);

        $this->mail->Subject = $subject;

        if (!$this->mail->send()) {
            log_message('error', 'Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }

        return true;

    }

    public function sendVerficationEmail($toEmail, $country,$name,$verifyLink)
    {
        $german=false;

        $this->mail->setFrom('kontakt@nodedevices.de', 'Node Devices');
        $this->mail->addAddress($toEmail);
        if ($country=='Deutschland')$german=true;
        // Email subject
        if($german){
            $html=$this->verifcationEmailHelper->generateEmailHTML($verifyLink,$name);
            $subject='E-Mail-Verifizierung die Nodematic Website';
        }else{
            $html=$this->verifcationEmailHelper->generateEmailHTML_en($verifyLink,$name);
            $subject='Email Verfication for Nodematic website';
        }
        $this->mail->Body = $html;

        $this->mail->isHTML(true);

        $this->mail->Subject = $subject;

        if (!$this->mail->send()) {
            log_message('error', 'Mailer Error: ' . $this->mail->ErrorInfo);
            die();
            return false;
        }

        return true;

    }
}




















