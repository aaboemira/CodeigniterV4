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
$routes->useSupportedLocalesOnly(true);
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
$routes->add('/', 'Home::index');
// Load default conrtoller when have only currency from multilanguage
$routes->get('^(\w{2})$', 'Home::index');

//Home for test
$routes->add('(\w{2})?/?home', 'Home');

//Checkout
$routes->add('(\w{2})?/?checkout/successcash', 'Checkout::successPaymentCashOnD');
$routes->add('(\w{2})?/?checkout/successbank', 'Checkout::successPaymentBank');
$routes->add('(\w{2})?/?checkout/paypalpayment', 'Checkout::paypalPayment');
$routes->add('(\w{2})?/?checkout/order-error', 'Checkout::orderError');

$routes->add('(\w{2})?/?checkout1', 'Checkout1');
$routes->add('(\w{2})?/?checkout2', 'Checkout2');
$routes->add('(\w{2})?/?checkout3', 'Checkout3');

// Ajax called. Functions for managing shopping cart
$routes->post('(\w{2})?/?manageShoppingCart', 'Home::manageShoppingCart');
$routes->add('(\w{2})?/?clearShoppingCart', 'Home::clearShoppingCart');
$routes->add('(\w{2})?/?removeFromCart', 'Home::removeFromCart');
$routes->post('(\w{2})?/?discountCodeChecker', 'Home::discountCodeChecker');

// home page paginatio
$routes->add(rawurlencode('home') . '/(:num)', "Home::index/$1");
// load javascript language file
$routes->add('loadlanguage/(:segment)', "Loader::jsFile/$1");
// load default-gradient css
$routes->add('cssloader/(:segment)', "Loader::cssStyle");

// Template Routes
$routes->add('template/imgs/(:segment)', "Loader::templateCssImage/$1");
$routes->add('templatecss/imgs/(:segment)', "Loader::templateCssImage/$1");
$routes->add('templatecss/(:segment)', "Loader::templateCss/$1");
$routes->add('templatejs/(:segment)', "Loader::templateJs/$1");

// Products urls style
$routes->add('(:segment)-(:num)', "Home::viewProduct/$2");
$routes->add('(\w{2})/(:segment)-(:num)', "Home::viewProduct/$3");
$routes->add('shop-product-(:num)', "Home::viewProduct/$3");

// blog urls style and pagination
$routes->add('blog/(:num)', "Blog::index/$1");
$routes->add('blog/(:segment)_(:num)', "Blog::viewPost/$2");
$routes->add('(\w{2})/blog/(:segment)_(:num)', "Blog::viewPost/$3");

// Shopping cart page
$routes->add('shopping-cart', "ShoppingCartPage");
$routes->add('(\w{2})/shopping-cart', "ShoppingCartPage");

// Shop page (greenlabel template)
$routes->add('shop', "Shop");
$routes->add('(\w{2})/shop', "Shop");

// Shop page (greenlabel template)
$routes->add('contacts', "Contacts");
$routes->add('(\w{2})/contacts', "Contacts");
$routes->post('contacts', "Contacts");
$routes->post('(\w{2})/contacts', "Contacts");

// Textual Pages links
$routes->add('page/(:segment)', "Page::index/$1");
$routes->add('(\w{2})/page/(:segment)', "Page::index/$2");


// Login Public Users Page
$routes->add('login', "Users::login");
$routes->add('(\w{2})/login', "Users::login");

// Get Captcha
$routes->get('captcha', "Users::captcha");

// Register Public Users Page
$routes->add('register', "Users::register");
$routes->add('(\w{2})/register', "Users::register");
$routes->post('checkout1/login', 'Checkout1::login');
$routes->post('checkout1/shopAsGuest', 'Checkout1::shopAsGuest');

$routes->add('auth/verify/(:segment)', "Users::verify/$1");
$routes->add('(\w{2})/auth/verify/(:segment)', "Users::verify/$1");

// Users Profiles Public Users Page
$routes->add('myaccount', "Users::myaccount");
$routes->add('myaccount/(:num)', "Users::myaccount/$1");
$routes->add('(\w{2})/myaccount', "Users::myaccount");
$routes->add('(\w{2})/myaccount/(:num)', "Users::myaccount/$2");

$routes->add('address', "Users::address");
$routes->add('(\w{2})/address', "Users::address");

$routes->add('smart-home', "SmartDevices::index");
$routes->get('smart-home/(:num)', 'SmartDevices::index/$1');
$routes->get('/smartdevices/add', 'SmartDevices::add');
$routes->post('/smartdevices/store', 'SmartDevices::store');
$routes->post('/smartdevices/refreshDeviceStatus', 'SmartDevices::refreshDeviceStatus');
$routes->get('/smartdevices/deleteDevice/(:num)', 'SmartDevices::deleteDevice/$1');
$routes->get('/smartdevices/editDevice/(:num)', 'SmartDevices::editDevice/$1');
$routes->post('/smartdevices/updateDevice', 'SmartDevices::updateDevice');


$routes->get('/smartdevices/accessControl/(:num)', 'SmartDevices::accessControl/$1');
$routes->post('/smartdevices/addGuest', 'SmartDevices::addGuest');
$routes->get('/smartdevices/deleteGuest/(:num)', 'SmartDevices::deleteGuest/$1');
$routes->post('/smartdevices/updateGuest', 'SmartDevices::updateGuest');

$routes->add('(\w{2})/smart-home', "Users::smartHome");

$routes->add('newsletter', "Newsletter::index");
$routes->add('(\w{2})/newsletter', "Newsletter::newsletter");
$routes->post('/newsletter/subscribe', 'Newsletter::subscribe');
$routes->post('/newsletter/unsubscribe', 'Newsletter::unsubscribe');
$routes->get('orders', 'Orders::orders');
$routes->get('orders/(:num)', 'Orders::orders/$1');
$routes->add('(\w{2})/orders', "Orders::orders");
$routes->get('/orders/show/(:num)', 'Orders::showOrder/$1');
$routes->get('/generate-invoice/(:num)', 'Orders::generateInvoice/$1');


$routes->add('account', "Account::account");
$routes->add('(\w{2})/account', "Account::account");
$routes->get('/account/delete', 'Account::delete');



$routes->add('password', "Users::password");
$routes->add('(\w{2})/password', "Users::password");
$routes->add('password/recover', 'Users::forgotPassword');
$routes->add('password/recover/reset-password', 'Users::resetPassword');


// Logout Profiles Public Users Page
$routes->add('logout', "Users::logout");
$routes->add('(\w{2})/logout', "Users::logout");

$routes->add('sitemap.xml', "Home::sitemap");

// Confirm link
$routes->add('confirm/(:segment)', "Home::confirmLink/$1");

/*
 * Vendor Controllers Routes
 */
$routes->add('vendor/login', "vendor\auth::login");
$routes->add('(\w{2})/vendor/login', "vendor\auth::login");
$routes->add('vendor/register', "vendor\auth::register");
$routes->add('(\w{2})/vendor/register', "vendor\auth::register");
$routes->add('vendor/forgotten-password', "vendor\auth::forgotten");
$routes->add('(\w{2})/vendor/forgotten-password', "vendor\auth::forgotten");
$routes->add('vendor/me', "vendor\VendorProfile");
$routes->add('(\w{2})/vendor/me', "vendor\VendorProfile");
$routes->add('vendor/logout', "vendor\VendorProfile::logout");
$routes->add('(\w{2})/vendor/logout', "vendor\VendorProfile::logout");
$routes->add('vendor/products', "vendor\Products");
$routes->add('(\w{2})/vendor/products', "vendor\Products");
$routes->add('vendor/products/(:num)', "vendor\Products::index/$1");
$routes->add('(\w{2})/vendor/products/(:num)', "vendor\Products::index/$2");
$routes->add('vendor/add/product', "vendor\AddProduct");
$routes->add('(\w{2})/vendor/add/product', "vendor\AddProduct");
$routes->add('vendor/edit/product/(:num)', "vendor\AddProduct::index/$1");
$routes->add('(\w{2})/vendor/edit/product/(:num)', "vendor\AddProduct::index/$1");
$routes->add('vendor/orders', "vendor\Orders");
$routes->add('(\w{2})/vendor/orders', "vendor\Orders");
$routes->add('vendor/uploadOthersImages', "vendor\AddProduct::do_upload_others_images");
$routes->add('vendor/loadOthersImages', "vendor\AddProduct::loadOthersImages");
$routes->add('vendor/removeSecondaryImage', "vendor\AddProduct::removeSecondaryImage");
$routes->add('vendor/delete/product/(:num)', "vendor\products::deleteProduct/$1");
$routes->add('(\w{2})/vendor/delete/product/(:num)', "vendor\products::deleteProduct/$1");
$routes->add('vendor/view/(:segment)', "Vendor\index/0/$1");
$routes->add('(\w{2})/vendor/view/(:segment)', "Vendor\index/0/$2");
$routes->add('vendor/view/(:segment)/(:num)', "Vendor\index/$2/$1");
$routes->add('(\w{2})/vendor/view/(:segment)/(:num)', "Vendor\index/$3/$2");
$routes->add('(:segment)/(:segment)_(:num)', "Vendor\viewProduct/$1/$3");
$routes->add('(\w{2})/(:segment)/(:segment)_(:num)', "Vendor\viewProduct/$2/$4");
$routes->add('vendor/changeOrderStatus', "vendor\orders::changeOrdersOrderStatus");

// Site Multilanguage
$routes->add('^(\w{2})/(.*)$', '$2');

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
