<?php

namespace App\Controllers;

use App\Models\Public_model;
use DateTime;
use DateTimeZone;
use Mobicms\Captcha\Code;
use Mobicms\Captcha\Image;
use App\Libraries\SendMail;

class Users extends BaseController
{

    private $registerErrors = array();
    private $user_id;
    private $num_rows = 5;

    protected $Public_model;
    protected $sendmail;

    public function __construct()
    {
        $this->Public_model = new Public_model();
        $this->sendmail = new SendMail();
    }



    public function register()
    {
        if (isset($_POST['signup'])) {
            $result = $this->registerValidate();
            if ($result == false) {
                session()->setFlashdata('registerError', $this->registerErrors[0]);
            } else {
                return redirect()->to(LANG_URL . '/register');
            }
            
        }
        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $password = $_POST['pass'];
            if ($this->performLogin($email, $password)) {
                return redirect()->to(LANG_URL . '/myaccount');
            } else {
                session()->setFlashdata('loginError', lang_safe('wrong_user'));
            }
        }

        $head = array();
        $data = array();
        $head['title'] = lang_safe('user_register');
        $head['description'] = lang_safe('user_register');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('/users/signup', $head, $data);
    }

    public function sendVerificationEmail($email,$country,$name, $verificationToken)
    {
        // $emailConfig = \Config\Services::email();
        // //$emailConfig->initialize();

        // $emailConfig->setFrom('goldroger9888@gamil.com', 'nodematic');
        // $emailConfig->setTo($email);

        // $emailConfig->setSubject('Email Verification');
        // $emailConfig->setMessage('Click the link below to verify your email:\n\n <a href="'. LANG_URL.'/auth/verify/'.$verificationToken.'">'. LANG_URL.'auth/verify/'.$verificationToken.'</a>');

        // if($emailConfig->send()) {
        //     // Email sent successfully
        //     return true;
        // }
        $verificationLink = LANG_URL.'/auth/verify/'.$verificationToken;

        return $this->sendmail->sendVerficationEmail($email, $country,$name, $verificationLink);

       return false;
       // return true;
    }

    public function verify($verificationToken)
    {
        // Find the user with the provided token
        $user = $this->Public_model->findUserByToken($verificationToken);
        if ($user) {
            // Mark the email as verified
            $this->Public_model->markEmailAsVerified($user->id);
            session()->setFlashdata('success', lang_safe('verify_confirm', 'Email confirmed successfully. Please login now'));
            // Redirect to a success page or show a success message
            return redirect()->to(LANG_URL . '/register');
        } else {
            session()->setFlashdata('registerError', lang_safe('verify_failed', 'Email is not confirmed'));
            // Invalid or expired token; show an error message
            return redirect()->to(LANG_URL . '/register');
        }
    }

    public function myaccount($page = 0)
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        if (isset($_POST['update'])) {
            $_POST['id'] = $_SESSION['logged_user'];
            $count_emails = $this->Public_model->countPublicUsersWithEmail($_POST['email'], $_POST['id']);
            if ($count_emails == 0) {
                $this->Public_model->updateProfile($_POST);
            }
            return redirect()->to(LANG_URL . '/myaccount');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);

        return $this->render('user', $head, $data);
    }



    public function password()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        if (isset($_POST['update'])) {
            $validation = \Config\Services::validation();
            $validation->setRule('current_password', lang_safe('enter_password'), 'required');
            $validation->setRule('pass',lang_safe('enter_password'), 'required|min_length[6]');
            $validation->setRule('pass_repeat',lang_safe('pass_repeat_error', 'Repeat password did not match'), 'matches[pass]');
            
            if ($validation->run($this->request->getPost()))
            {
                $_POST['email'] = $_SESSION['email'];
                $_POST['pass'] = $_POST['current_password'];
                $result = $this->Public_model->checkPublicUserIsValid($_POST);
                if ($result == false) {
                    session()->setFlashdata('error', lang_safe('wrong_pass', 'Current password is wrong'));
                } else {
                    $_POST['id'] = $_SESSION['logged_user'];
                    $_POST['pass'] = $_POST['pass_repeat'];
                    $this->Public_model->updatePassword($_POST);
                    session()->setFlashdata('success', 'Success');
                    return redirect()->to(current_url());
                }
                
                
            }
            
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
        $rowscount = $this->Public_model->getUserOrdersHistoryCount($_SESSION['logged_user']);
        $data['links_pagination'] = pagination('myaccount', $rowscount, $this->num_rows, 2);
        return $this->render('password', $head, $data);
    }

    public function address()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('address', $head, $data);
    }

    public function smartHome()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('address', $head, $data);
    }

    public function newsletter()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('my_acc');
        $head['description'] = lang_safe('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        return $this->render('address', $head, $data);
    }



    public function logout()
    {
        // Destroy user session
        session()->destroy();
        return redirect()->to(LANG_URL);
    }

    private function registerValidate()
    {
        helper('text');

        $email = $_POST['email'];
        $password = $_POST['pass'];
        $result = $_POST['code'];
        $session = session()->get('code');

        $errors = array();
        if (mb_strlen(trim($_POST['first_name'])) == 0) {
            $errors[] = lang_safe('first_name_empty');
        }
        if (mb_strlen(trim($_POST['last_name'])) == 0) {
            $errors[] = lang_safe('last_name_empty');
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = lang_safe('email_empty');
        }
        if (strlen($password) < 8 || !preg_match('/[!@#$%^&*()-_+[\]{};:"\'<>,.?~`]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[a-zA-Z]/', $password)) {
            $errors[] = lang_safe('invalid_password');
        }
        if (mb_strlen(trim($_POST['pass_repeat'])) == 0) {
            $errors[] = lang_safe('password_confirm_empty');
        }
        else if ($_POST['pass'] != $_POST['pass_repeat']) {
            $errors[] = lang_safe('passwords_dont_match');
        }
        if (mb_strlen(trim($_POST['street'])) == 0) {
            $errors[] = lang_safe('invalid_street');
        }
        if (mb_strlen(trim($_POST['housenr'])) == 0) {
            $errors[] = lang_safe('invalid_housenr');
        }
        if (mb_strlen(trim($_POST['country'])) == 0) {
            $errors[] = lang_safe('invalid_country');
        }
        if (mb_strlen(trim($_POST['language'])) == 0) {
            $errors[] = lang_safe('lang_empty');
        }
        if (mb_strlen(trim($_POST['post_code'])) == 0 ) {
            $errors[] = lang_safe('invalid_post_code');
        }
        if (mb_strlen(trim($_POST['city'])) == 0) {
            $errors[] = lang_safe('invalid_city');
        }
        if (mb_strlen(trim($_POST['account_type'])) == 0) {
            $errors[] = lang_safe('invalid_account_type');
        }
        if ($result !== null && $session !== null) {
            if (strtolower($result) != strtolower($session)) {
                $errors[] = lang_safe('captcha_didnt_match', );
            }
        }
        if (!isset($_POST['data_processing_agreement'])) {
            $errors[] = lang_safe('invalid_data_processing');
        }
        $user = $this->Public_model->getUserProfileInfoByEmail($_POST['email']);
        if($user)$errors[] = lang_safe('user_email_is_taken');

        if (!empty($errors)) {
            $this->registerErrors = $errors;
            session()->setFlashdata('register_errors', $errors);
            return false;
        }
        $country=$_POST['country'];
        $fullName=$_POST['first_name'].' '. $_POST['last_name'];

        $verificationToken = random_string('alnum', 32);
        $email = $this->sendVerificationEmail($email,$country,$fullName, $verificationToken);
        //$email=true;
        if($email) {
            $_POST['verify_token'] = $verificationToken;
            $this->user_id = $this->Public_model->registerUser($_POST);
            session()->setFlashdata('resultSend', lang_safe('verify_mail', 'Verificaiton link sent to your email. Please verify.'));
            return true;
        } else {
            $errors[] = lang_safe('verify_mail_error');
            session()->setFlashdata('register_errors', $errors);
            return false;
        }
    }
    public function forgotPassword()
    {


        $head = array();
        $data = array();
        $head['title'] = lang_safe('Forget Password');
        $head['description'] = lang_safe('Forget Password ');
        $head['keywords'] = str_replace(" ", ",", $head['title']);

        if (isset($_POST['forget_password'])) {
            $result = $_POST['code'];
            if (!isset($_SESSION['code'])) {
                session()->setFlashdata('error', lang_safe('captcha_wrong', 'Captcha code not set.'));
                return $this->render('/users/forget-password', $head, $data);
            }
            $session = $_SESSION['code'];
            $email = $_POST['email'];
            // Check if the email exists in your database
            $user = $this->Public_model->getUserWithAddressesByEmail($email);

            if ($user&&$this->validateCaptcha($result,$session)) {
                // Generate a unique reset token and store it in your database
                $resetToken = $this->generateResetToken(32);
                date_default_timezone_set('Europe/Berlin');

                $expirationTime = date('Y-m-d H:i:s');

                $expirationTime = date('Y-m-d H:i:s', strtotime($expirationTime) + 2 * 3600);

                $user->expirationTime = $expirationTime;

                $this->Public_model->setResetToken($email, $resetToken, $expirationTime);

                // Send a password reset email
                $resetLink = LANG_URL . '/password/recover/reset-password?token=' . $resetToken;
                $emailSent = $this->sendmail->sendPasswordRecoveryEmail($email,$user,$resetLink);

                if ($emailSent) {
                    session()->setFlashdata('success', lang_safe('password_email_msg'));
                } else {
                    session()->setFlashdata('error', 'Failed to send the password reset email. Please try again later.');
                }
            }else if(!($this->validateCaptcha($result,$session))){
                session()->setFlashdata('error', lang_safe('captcha_wrong','hello'));
            } else {
                // If the email does not exist in your database, provide an error message
                session()->setFlashdata('success', lang_safe('password_email_msg'));
            }
        }

        return $this->render('/users/forget-password', $head, $data);
    }

    public function resetPassword() {
        $head = array();
        $data = array();
        $head['title'] = lang_safe('reset_password');
        $head['description'] = lang_safe('reset_password');
        $head['keywords'] = str_replace(" ", ",", $head['title']);

        $token = $this->request->getGet('token');


        if (isset($_POST['reset_password'])) {
            $password = $_POST['pass'];
            $passwordRepeat = $_POST['pass_repeat'];
            if ($password === $passwordRepeat) {
                // Passwords match
                if (
                    !strlen($password) < 8 &&
                    preg_match('/[!@#$%^&*()_+[\]{};:"\'<>,.?~`]/', $password) &&
                    preg_match('/\d/', $password) &&
                    preg_match('/[a-zA-Z]/', $password)
                ) {
                    // Password meets the requirements
                    $this->Public_model->updatePassword($_POST);
                    return $this->render('users/forget_success', $head, $data);
                } else {
                    // Password does not meet the requirements
                    session()->setFlashdata('error', lang_safe('invalid_password', 'Password must contain at least 1 special character, 1 number, and 1 letter'));
                }
            } else {
                // Passwords do not match
                session()->setFlashdata('error', lang_safe('passwords_dont_match', 'Passwords do not match'));
            }
        }
        $user = $this->Public_model->getUserByResetToken($token);

        if ($user) {
            $currentTime = new DateTime('now', new DateTimeZone('Europe/Berlin'));
            $tokenExpiryTime = new DateTime($user->reset_token_expiration,new DateTimeZone('Europe/Berlin'));
            if ($tokenExpiryTime > $currentTime) {
                $data['user_id']=$user->id;
                return $this->render('/users/password_reset_page', $head, $data);
            } else {
                $data['error'] = 'The token has expired. Please request a new password reset.';
                return $this->render('/users/password_reset_page', $head, $data);
            }
        } else {
            // Invalid token, show an error message
            $data['error'] = 'The token is invalid. Please request a new password reset.';
            return $this->render('/users/password_reset_page', $head, $data);
        }

        return $this->render('/users/password_reset_page', $head, $data);
    }

    public function captcha() {
        $code = (string) new Code;
        session()->set('code', $code);
        $image = new Image($code);
        $response['image'] = (string)$image;
        echo json_encode($response); die;
        echo new Image($code);
    }
    function validateCaptcha($result, $session) {
        if ($result !== null && $session !== null) {
            return strtolower($result) === strtolower($session);
        }

        return false;
    }
    function generateResetToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
    public function performLogin($email, $password)
    {
        $validation = \Config\Services::validation();
        $validation->setRule('email', 'required|valid_email', lang_safe('invalid_email'));
        $validation->setRule('pass', 'required', lang_safe('enter_password'));
        $loginData = [
            'email' => $email,
            'pass' => $password,
        ];

        $result = $this->Public_model->checkPublicUserIsValid($loginData);

        if ($result !== false) {
            if ($result['verified'] == 0) {
                session()->setFlashdata('loginError', lang_safe('verify_first', 'Please verify your email first'));
                return false;
            } else {
                session()->set([
                    'logged_user' => $result['id'],
                    'user_name' => $result['first_name'] .' '. $result['last_name'],
                    'email' => $result['email'],
                ]);
                return true;
            }
        }
        return false;
    }
}