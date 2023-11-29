<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\ShoppingCart;
use App\Libraries\Language;
use App\Libraries\Loop;
use App\Models\admin\Settings_model;
use App\Models\admin\Languages_model;
use App\Models\admin\Pages_model;
use App\Models\admin\Home_admin_model;
use Config\Config;
use Config\Services;
use Config\GlobalVars;
use App\Models\Public_model;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

    public $nonDynPages = array();
    private $dynPages = array();
    protected $template;
    protected $shoppingcart;
    protected $Language;
    protected $Public_model;
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->Language = new Language();
        $this->loop = new Loop();
        $this->getActivePages();
        $this->checkForPostRequests();
        $this->setReferrer();
        //set selected template
        $this->loadTemplate();
        $this->shoppingcart = new ShoppingCart();
        
    }


    


        /*
     * Render page from controller
     * it loads header and footer auto
     */

     public function render($view, $head, $data = [], $footer = [])
     {
         $head['cartItems'] = $this->shoppingcart->getCartItems();
         $head['sumOfItems'] = $this->shoppingcart->sumValues;
         $vars = $this->loadVars();
         $data = array_merge($data,$vars);
         $head = array_merge($head,$vars);
         $footer = array_merge($footer,$vars);
         //$this->load->vars($vars);
         $all_categories = $this->Public_model->getShopCategories();
 

         $head['nav_categories'] = $tree = $this->buildTree1($all_categories);
         return view($this->template . '_parts/template', ['page'=> $view, 'data' => $data ,'head' => $head, 'footer' => $footer]);
     }

     function buildTree1(array $elements, $parentId = 0)
     {
         $branch = array();
         foreach ($elements as $element) {
             if ($element['sub_for'] == $parentId) {
                 $children = $this->buildTree1($elements, $element['id']);
                 if ($children) {
                     $element['children'] = $children;
                 }
                 $branch[] = $element;
             }
         }
         return $branch;
     }
 
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
                     GlobalVars::$globalVariables[$value['thekey']] = htmlentities($value['value']);
                 }
             }
         }
         
         $vars['allLanguages'] = $this->getAllLangs();
         $vars['load'] = $this->loop;
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
 
     /*
      * Active pages for navigation
      * Managed from administration
      */
 
     private function getActivePages()
     {
         $this->Pages_model = new Pages_model();
         $activeP = $this->Pages_model->getPages(true);
         $dynPages = config(Config::class)->no_dynamic_pages;
         $actDynPages = [];
         foreach ($activeP as $acp) {
             if (($key = array_search($acp, $dynPages)) !== false) {
                 $actDynPages[] = $acp;
             }
         }
         $this->nonDynPages = $actDynPages;
         $dynPages = getTextualPages($activeP);
         $this->Public_model = new Public_model();
         $this->dynPages = $this->Public_model->getDynPagesLangs($dynPages);
     }
 
     /*
      * Email subscribe form from footer
      */
 
     private function checkForPostRequests()
     {
         if (isset($_POST['subscribeEmail'])) {
             $arr = array();
             $arr['browser'] = $_SERVER['HTTP_USER_AGENT'];
             $arr['ip'] = $_SERVER['REMOTE_ADDR'];
             $arr['time'] = time();
             $arr['email'] = $_POST['subscribeEmail'];
             if (filter_var($arr['email'], FILTER_VALIDATE_EMAIL) && !session('email_added')) {
                 session()->set('email_added', 1);
                 $this->Public_model->setSubscribe($arr);
                 session()->setFlashdata('emailAdded', lang_safe('email_added'));
             }
             if (!headers_sent()) {
                 redirect();
             } else {
                 echo 'window.location = "' . base_url() . '"';
             }
         }
     }
 
     /*
      * Set referrer to save it in orders
      */
 
     private function setReferrer()
     {
         if (session('referrer') == null) {
             if (!isset($_SERVER['HTTP_REFERER'])) {
                 $ref = 'Direct';
             } else {
                 $ref = $_SERVER['HTTP_REFERER'];
             }
             session()->set('referrer', $ref);
         }
     }
 
     /*
      * Check for selected template 
      * and set it in config if exists
      */
 
     private function loadTemplate()
     {
        $this->Home_admin_model = new Home_admin_model();
         $template = $this->Home_admin_model->getValueStore('template');
         if ($template == null) {
             $template = config(Config::class)->template;
         } else {
            config(Config::class)->template = $template;
         }
         if (!is_dir(TEMPLATES_DIR . $template)) {
             show_error('The selected template does not exists!');
         }
         $this->template = 'templates' . DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR;
     }
 
}
