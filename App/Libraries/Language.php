<?php

namespace App\Libraries;

use App\Models\admin\Home_admin_model;
use App\Models\Public_model;
use Config\Config;

class Language
{

    protected $CI;
    private $urlAbbrevation;
    protected $Home_admin_model;
    protected $Public_model;
    public function __construct()
    {
        $uri = service('uri');
        $this->Home_admin_model = new Home_admin_model();
        $this->Public_model = new Public_model();
        $this->urlAbbrevation = strtolower($uri->getSegment(1));
        $this->setLanguage();
    }

    private function setLanguage()
    {
        $defaultLanguageName = $language = config(Config::class)->language;
        $defaultLanguageAbbr = $myLanguage = strtolower(config(Config::class)->language_abbr);
        $currency = config(Config::class)->currency;
        $currencyKey = config(Config::class)->currencyKey;
        $langLinkStart = '';
        
        /*
         * If try to select default language
         * Go refresh clean url.. to dont have duplicate pages for google!
         * Else get the language
         */
        if ($this->urlAbbrevation == $defaultLanguageAbbr) {
            //redirect(base_url());
            //return redirect()->to(base_url());
        } else {

            $myLang = $this->Public_model->getOneLanguage($this->urlAbbrevation);
            if ($myLang != null) {
                $myLanguage = $myLang['abbr'];
                $language = $myLang['name'];
                $currency = $myLang['currency'];
                $currencyKey = $myLang['currencyKey'];
                $langLinkStart = $myLanguage . '/';
                //$session = \Config\Services::session();
                $lang = \Config\Services::language();
                $lang->setLocale($this->urlAbbrevation);
            }
        }
        //$this->CI->lang->load("site", $language);
        
        define('MY_LANGUAGE_FULL_NAME', $language);
        define('MY_LANGUAGE_ABBR', $myLanguage);
        define('MY_DEFAULT_LANGUAGE_ABBR', $defaultLanguageAbbr);
        define('MY_DEFAULT_LANGUAGE_NAME', $defaultLanguageName);
        define('CURRENCY', $currency);
        define('CURRENCY_KEY', $currencyKey);
        define('LANG_URL', rtrim(base_url($langLinkStart), '/'));

        
    }

}
