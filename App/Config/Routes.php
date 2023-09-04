<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
// Load default conrtoller when have only currency from multilanguage
$routes->get('^(\w{2})$', 'Home::index');

//Checkout
$routes->get('(\w{2})?/?checkout/successcash', 'Checkout::successPaymentCashOnD');
$routes->get('(\w{2})?/?checkout/successbank', 'Checkout::successPaymentBank');
$routes->get('(\w{2})?/?checkout/paypalpayment', 'Checkout::paypalPayment');
$routes->get('(\w{2})?/?checkout/order-error', 'Checkout::orderError');

$routes->add('(\w{2})?/?checkout1', 'Checkout1');
$routes->add('(\w{2})?/?checkout2', 'Checkout2');
$routes->add('(\w{2})?/?checkout3', 'Checkout3');

// Ajax called. Functions for managing shopping cart
$routes->post('(\w{2})?/?manageShoppingCart', 'Home::manageShoppingCart');
$routes->get('(\w{2})?/?clearShoppingCart', 'Home::clearShoppingCart');
$routes->get('(\w{2})?/?discountCodeChecker', 'Home::discountCodeChecker');

// home page pagination
$routes->get(rawurlencode('home') . '/(:num)', "Home::index/$1");
// load javascript language file
$routes->get('loadlanguage/(:segment)', "Loader::jsFile/$1");
// load default-gradient css
$routes->get('cssloader/(:segment)', "Loader::cssStyle");

// Template Routes
$routes->get('template/imgs/(:segment)', "Loader::templateCssImage/$1");
$routes->get('templatecss/imgs/(:segment)', "Loader::templateCssImage/$1");
$routes->get('templatecss/(:segment)', "Loader::templateCss/$1");
$routes->get('templatejs/(:segment)', "Loader::templateJs/$1");

// Products urls style
$routes->get('(:segment)_(:num)', "Home::viewProduct/$2");
$routes->get('(\w{2})/(:segment)_(:num)', "Home::viewProduct/$3");
$routes->get('shop-product_(:num)', "Home::viewProduct/$3");

// blog urls style and pagination
$routes->get('blog/(:num)', "Blog::index/$1");
$routes->get('blog/(:segment)_(:num)', "Blog::viewPost/$2");
$routes->get('(\w{2})/blog/(:segment)_(:num)', "Blog::viewPost/$3");

// Shopping cart page
$routes->get('shopping-cart', "ShoppingCartPage");
$routes->get('(\w{2})/shopping-cart', "ShoppingCartPage");

// Shop page (greenlabel template)
$routes->get('shop', "Shop");
$routes->get('(\w{2})/shop', "Shop");

// Shop page (greenlabel template)
$routes->get('contacts', "Contacts");
$routes->get('(\w{2})/contacts', "Contacts");
$routes->post('contacts', "Contacts");
$routes->post('(\w{2})/contacts', "Contacts");

// Textual Pages links
$routes->get('page/(:segment)', "Page::index/$1");
$routes->get('(\w{2})/page/(:segment)', "Page::index/$2");


// Login Public Users Page
$routes->get('login', "Users::login");
$routes->get('(\w{2})/login', "Users::login");

// Register Public Users Page
$routes->get('register', "Users::register");
$routes->get('(\w{2})/register', "Users::register");

// Users Profiles Public Users Page
$routes->get('myaccount', "Users::myaccount");
$routes->get('myaccount/(:num)', "Users::myaccount/$1");
$routes->get('(\w{2})/myaccount', "Users::myaccount");
$routes->get('(\w{2})/myaccount/(:num)', "Users::myaccount/$2");

// Logout Profiles Public Users Page
$routes->get('logout', "Users::logout");
$routes->get('(\w{2})/logout', "Users::logout");

$routes->get('sitemap.xml', "Home::sitemap");

// Confirm link
$routes->get('confirm/(:segment)', "Home::confirmLink/$1");

/*
 * Vendor Controllers Routes
 */
$routes->get('vendor/login', "vendor\auth::login");
$routes->get('(\w{2})/vendor/login', "vendor\auth::login");
$routes->get('vendor/register', "vendor\auth::register");
$routes->get('(\w{2})/vendor/register', "vendor\auth::register");
$routes->get('vendor/forgotten-password', "vendor\auth::forgotten");
$routes->get('(\w{2})/vendor/forgotten-password', "vendor\auth::forgotten");
$routes->get('vendor/me', "vendor\VendorProfile");
$routes->get('(\w{2})/vendor/me', "vendor\VendorProfile");
$routes->get('vendor/logout', "vendor\VendorProfile::logout");
$routes->get('(\w{2})/vendor/logout', "vendor\VendorProfile::logout");
$routes->get('vendor/products', "vendor\Products");
$routes->get('(\w{2})/vendor/products', "vendor\Products");
$routes->get('vendor/products/(:num)', "vendor\Products::index/$1");
$routes->get('(\w{2})/vendor/products/(:num)', "vendor\Products::index/$2");
$routes->get('vendor/add/product', "vendor\AddProduct");
$routes->get('(\w{2})/vendor/add/product', "vendor\AddProduct");
$routes->get('vendor/edit/product/(:num)', "vendor\AddProduct::index/$1");
$routes->get('(\w{2})/vendor/edit/product/(:num)', "vendor\AddProduct::index/$1");
$routes->get('vendor/orders', "vendor\Orders");
$routes->get('(\w{2})/vendor/orders', "vendor\Orders");
$routes->get('vendor/uploadOthersImages', "vendor\AddProduct::do_upload_others_images");
$routes->get('vendor/loadOthersImages', "vendor\AddProduct::loadOthersImages");
$routes->get('vendor/removeSecondaryImage', "vendor\AddProduct::removeSecondaryImage");
$routes->get('vendor/delete/product/(:num)', "vendor\products::deleteProduct/$1");
$routes->get('(\w{2})/vendor/delete/product/(:num)', "vendor\products::deleteProduct/$1");
$routes->get('vendor/view/(:segment)', "Vendor\index/0/$1");
$routes->get('(\w{2})/vendor/view/(:segment)', "Vendor\index/0/$2");
$routes->get('vendor/view/(:segment)/(:num)', "Vendor\index/$2/$1");
$routes->get('(\w{2})/vendor/view/(:segment)/(:num)', "Vendor\index/$3/$2");
$routes->get('(:segment)/(:segment)_(:num)', "Vendor\viewProduct/$1/$3");
$routes->get('(\w{2})/(:segment)/(:segment)_(:num)', "Vendor\viewProduct/$2/$4");
$routes->get('vendor/changeOrderStatus', "vendor\orders::changeOrdersOrderStatus");

// Site Multilanguage
$routes->get('^(\w{2})/(.*)$', '$2');

/*
 * Admin Controllers Routes
 */
// HOME / LOGIN
$routes->add('admin', "admin\home\Login");
$routes->add('admin/home', "admin\home\Home");

// ECOMMERCE GROUP
$routes->add('admin/publish', "admin\Ecommerce\Publish");
$routes->add('admin/publish/(:num)', "admin\Ecommerce\Publish::index/$1");
$routes->add('admin/removeSecondaryImage', "admin\Ecommerce\Publish::removeSecondaryImage");
$routes->add('admin/products', "admin\Ecommerce\Products");
$routes->add('admin/products/(:num)', "admin\Ecommerce\Products::index/$1");
$routes->add('admin/productStatusChange', "admin\Ecommerce\Products::productStatusChange");
$routes->add('admin/shopcategories', "admin\Ecommerce\ShopCategories");
$routes->add('admin/shopcategories/(:num)', "admin\Ecommerce\ShopCategories::index/$1");
$routes->add('admin/editshopcategorie', "admin\Ecommerce\ShopCategories::editShopCategorie");
$routes->add('admin/orders', "admin\Ecommerce\Orders");
$routes->add('admin/orders/(:num)', "admin\Ecommerce\Orders::index/$1");
$routes->add('admin/changeOrdersOrderStatus', "admin\Ecommerce\Orders::changeOrdersOrderStatus");
$routes->add('admin/brands', "admin\Ecommerce\Brands");
$routes->add('admin/changePosition', "admin\Ecommerce\ShopCategories::changePosition");
$routes->add('admin/discounts', "admin\Ecommerce\Discounts");
$routes->add('admin/discounts/(:num)', "admin\Ecommerce\Discounts::index/$1");
// BLOG GROUP
$routes->add('admin/blogpublish', "admin\blog\BlogPublish");
$routes->add('admin/blogpublish/(:num)', "admin\blog\BlogPublish::index/$1");
$routes->add('admin/blog', "admin\Blog\Blog");
$routes->add('admin/blog/(:num)', "admin\Blog\Blog::index/$1");
// SETTINGS GROUP
$routes->add('admin/settings', "admin\Settings\Settings");
$routes->add('admin/styling', "admin\Settings\Styling");
$routes->add('admin/templates', "admin\Settings\Templates");
$routes->add('admin/titles', "admin\Settings\Titles");
$routes->add('admin/pages', "admin\Settings\Pages");
$routes->add('admin/emails', "admin\Settings\Emails");
$routes->add('admin/emails/(:num)', "admin\Settings\Emails::index/$1");
$routes->add('admin/history', "admin\Settings\History");
$routes->add('admin/history/(:num)', "admin\Settings/History::index/$1");
// ADVANCED SETTINGS
$routes->add('admin/languages', "admin\AdvancedSettings\Languages");
$routes->add('admin/filemanager', "admin\AdvancedSettings\Filemanager");
$routes->add('admin/adminusers', "admin\AdvancedSettings\Adminusers");
// TEXTUAL PAGES
$routes->add('admin/pageedit/(:segment)', "admin\Textual_pages\TextualPages::pageEdit/$1");
$routes->add('admin/changePageStatus', "admin\Textual_pages\TextualPages::changePageStatus");
// LOGOUT
$routes->add('admin/logout', "admin\Home\Home::logout");
// Admin pass change ajax
$routes->add('admin/changePass', "admin\Home\Home::changePass");
$routes->add('admin/uploadOthersImages', "admin\Ecommerce\Publish::do_upload_others_images");
$routes->add('admin/loadOthersImages', "admin\Ecommerce\Publish::loadOthersImages");

/*
  | -------------------------------------------------------------------------
  | Sample REST API Routes
  | -------------------------------------------------------------------------
 */
$routes->add('api/products/(\w{2})/get', 'Api\Products::all/$1');
$routes->add('api/product/(\w{2})/(:num)/get', 'Api\Products::one/$1/$2');
$routes->add('api/product/set', 'Api\Products::set');
$routes->add('api/product/(\w{2})/delete', 'Api\Products::productDel/$1');

$routes->add('404_override', '');
//$routes->get('translate_uri_dashes') = false;


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
