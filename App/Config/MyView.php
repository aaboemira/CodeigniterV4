<?php
namespace Config;

use CodeIgniter\Config\View as BaseView;
use App\Models\admin\Settings_model;
use App\Models\admin\Languages_model;

class MyView extends BaseView
{

    public $nonDynPages = array();
    private $dynPages = array();
    
    protected $globalData = [
        'siteName' => 'My Website',
        'companyName' => 'My Company'
    ];

     /*
      * Load variables from values-store
      * texts, social media links, logos, etc.
      */
 
      private function loadVars()
      {
          $vars = array();
          $vars['nonDynPages'] = $this->nonDynPages;
          $vars['dynPages'] = $this->dynPages;
          $vars['footerCategories'] = $this->Public_model->getFooterCategories();
  
          $this->Settings_model = new Settings_model();
          $values = $this->Settings_model->getValueStores();
          if(is_array($values) && count($values) > 0) {
              foreach($values as $value) {
                  if (!array_key_exists($value['thekey'], $vars)) {
                      $vars[$value['thekey']] = htmlentities($value['value']);
                  }
              }
          }
          
          $vars['allLanguages'] = $this->getAllLangs();
          //$vars['load'] = $this->loop;
          $vars['cookieLaw'] = $this->Public_model->getCookieLaw();
          return $vars;
      }

           /*
      * Get all added languages from administration
      */
 
     private function getAllLangs()
     {
         $arr = array();
         $this->Languages_model = new Languages_model();
         $langs = $this->Languages_model->getLanguages();
         foreach ($langs as $lang) {
             $arr[$lang->abbr]['name'] = $lang->name;
             $arr[$lang->abbr]['flag'] = $lang->flag;
         }
         return $arr;
     }
}
