<?php
// app/Helpers/my_helper.php
if (! function_exists('lang_safe')) {
    function lang_safe($key, $message = '')
    {
        $language = service('language');
        $value = $language->getLine('site_lang.'.$key);

        return ($value !== 'site_lang.'.$key) ? $value : $message;
    }
}

if (! function_exists('validationError')) {
    function validationError($key = null)
    {
        $validation = \Config\Services::validation();
        $error = $validation->getErrors();
        if ($error) {
            return reset($error) ;
        }
        
        $successMessage = session()->getFlashdata(($key)?$key:'error');
        if($successMessage) {
            return $successMessage;
        }
        return '';
    }
}