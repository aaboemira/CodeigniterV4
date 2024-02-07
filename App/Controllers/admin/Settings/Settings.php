<?php
namespace App\Controllers\Admin\Settings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Languages_model;
use App\Models\admin\Settings_model;
use App\Models\admin\Home_admin_model;

class Settings extends ADMIN_Controller
{

    protected $Languages_model;
    protected $Settings_model;
    protected $Home_admin_model;

    public function __construct()
    {
        $this->Languages_model = new Languages_model();
        $this->Settings_model = new Settings_model();
        $this->Home_admin_model = new Home_admin_model();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Settings';
        $head['description'] = '';
        $head['keywords'] = '';

        $this->postChecker();

        $value_stores = $this->getValueStores();
        if(!is_null($value_stores)) {
            // Map to data
            foreach($value_stores as $value_s) {
                if (!array_key_exists($value_s['thekey'], $data)) {
                    $data[$value_s['thekey']] = $value_s['value'];
                }
            }
            unset($value_stores);
        }
        
        $data['cookieLawInfo'] = $this->getCookieLaw();
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['law_themes'] = array_diff(scandir('.' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'imgs' . DIRECTORY_SEPARATOR . 'cookie-law-themes' . DIRECTORY_SEPARATOR), array('..', '.'));
        $data['cookieLawInfo'] = $this->getCookieLaw();
        $this->saveHistory('Go to Settings Page');
        $page = 'settings/settings';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    private function getValueStores()
    {
        $values = $this->Settings_model->getValueStores();
        if(is_array($values) && count($values) > 0) {
            return $values;
        }
        return null;
    }

    private function postChecker()
    {
        if (isset($_POST['uploadimage'])) {


            $file = $this->request->getFile('sitelogo');
            // Check if a file was uploaded
            if ($file->isValid()) {
                // Validate file type using finfo
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $fileMimeType = finfo_file($finfo, $file->getPathName());
                // Set allowed file types
                $allowedMimeTypes = ['image/jpg', 'image/jpeg','image/png']; // Specify allowed MIME types
                if (in_array($fileMimeType, $allowedMimeTypes)) {
                    // Set the target directory for file upload
                    $newName = $file->getRandomName();
                    $uploadPath = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'site_logo' . DIRECTORY_SEPARATOR;
                    $file->move($uploadPath, $newName);

                    if($file->hasMoved()) {
                        $this->Home_admin_model->setValueStore('sitelogo', $newName);
                        $this->saveHistory('Change site logo');
                        session()->setFlashdata('resultSiteLogoPublish', lang_safe('site_logo_change_success'));
                    } else {
                        session()->setFlashdata('resultSiteLogoPublish', lang_safe('site_logo_change_failed'));
                    }
                }
            }


            return redirect()->to('admin/settings');
        }
        if (isset($_POST['naviText'])) {
            $this->Home_admin_model->setValueStore('navitext', $_POST['naviText']);
            session()->setFlashdata('resultNaviText', lang_safe('navigation_text_change_success'));
            $this->saveHistory('Change navigation text');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['footerCopyright'])) {
            $this->Home_admin_model->setValueStore('footercopyright', $_POST['footerCopyright']);
            session()->setFlashdata('resultFooterCopyright', lang_safe('footer_copyright_change_success'));
            $this->saveHistory('Change footer copyright');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['contactsPage'])) {
            $this->Home_admin_model->setValueStore('contactspage', $_POST['contactsPage']);
            session()->setFlashdata('resultContactspage', lang_safe('contacts_page_update_success'));
            $this->saveHistory('Change contacts page');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['footerContacts'])) {
            $this->Home_admin_model->setValueStore('footerContactAddr', $_POST['footerContactAddr']);
            $this->Home_admin_model->setValueStore('footerContactPhone', $_POST['footerContactPhone']);
            $this->Home_admin_model->setValueStore('footerContactEmail', $_POST['footerContactEmail']);
            session()->setFlashdata('resultfooterContacts', lang_safe('footer_contacts_update_success'));
            $this->saveHistory('Change footer contacts');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['footerSocial'])) {
            $this->Home_admin_model->setValueStore('footerSocialFacebook', $_POST['footerSocialFacebook']);
            $this->Home_admin_model->setValueStore('footerSocialTwitter', $_POST['footerSocialTwitter']);
            $this->Home_admin_model->setValueStore('footerSocialGooglePlus', $_POST['footerSocialGooglePlus']);
            $this->Home_admin_model->setValueStore('footerSocialPinterest', $_POST['footerSocialPinterest']);
            $this->Home_admin_model->setValueStore('footerSocialYoutube', $_POST['footerSocialYoutube']);
            session()->setFlashdata('resultfooterSocial', lang_safe('footer_social_links_update_success'));
            $this->saveHistory('Change footer contacts');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['googleMaps'])) {
            $this->Home_admin_model->setValueStore('googleMaps', $_POST['googleMaps']);
            $this->Home_admin_model->setValueStore('googleApi', $_POST['googleApi']);
            session()->setFlashdata('resultGoogleMaps', lang_safe('google_maps_update_success'));
            $this->saveHistory('Update Google Maps Coordinates and Api Key');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['footerAboutUs'])) {
            $this->Home_admin_model->setValueStore('footerAboutUs', $_POST['footerAboutUs']);
            session()->setFlashdata('resultFooterAboutUs', lang_safe('footer_about_us_change_success'));
            $this->saveHistory('Change footer about us info');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['contactsEmailTo'])) {
            $this->Home_admin_model->setValueStore('contactsEmailTo', $_POST['contactsEmailTo']);
            session()->setFlashdata('resultEmailTo', lang_safe('contact_email_to_change_success'));
            $this->saveHistory('Change where going emails from contact form');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['shippingOrder'])) {
            $this->Home_admin_model->setValueStore('shippingOrder', $_POST['shippingOrder']);
            session()->setFlashdata('shippingOrder', lang_safe('shipping_order_change_success'));
            $this->saveHistory('Change Shipping free for order more than ' . $_POST['shippingOrder']);
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['addJs'])) {
            $this->Home_admin_model->setValueStore('addJs', $_POST['addJs']);
            session()->setFlashdata('addJs', lang_safe('additional_js_code_success'));
            $this->saveHistory('Add JS to website');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['publicQuantity'])) {
            $this->Home_admin_model->setValueStore('publicQuantity', $_POST['publicQuantity']);
            session()->setFlashdata('publicQuantity', lang_safe('public_quantity_visibility_change_success'));
            $this->saveHistory('Change publicQuantity visibility');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['publicDateAdded'])) {
            $this->Home_admin_model->setValueStore('publicDateAdded', $_POST['publicDateAdded']);
            session()->setFlashdata('publicDateAdded', lang_safe('public_date_added_visibility_change_success'));
            $this->saveHistory('Change public date added visibility');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['outOfStock'])) {
            $this->Home_admin_model->setValueStore('outOfStock', $_POST['outOfStock']);
            session()->setFlashdata('outOfStock', lang_safe('out_of_stock_visibility_change_success'));
            $this->saveHistory('Change visibility of final checkout page');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['moreInfoBtn'])) {
            $this->Home_admin_model->setValueStore('moreInfoBtn', $_POST['moreInfoBtn']);
            session()->setFlashdata('moreInfoBtn', lang_safe('more_info_button_visibility_change_success'));
            $this->saveHistory('Change visibility of More Information button in products list');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['showBrands'])) {
            $this->Home_admin_model->setValueStore('showBrands', $_POST['showBrands']);
            session()->setFlashdata('showBrands', lang_safe('brands_visibility_change_success'));
            $this->saveHistory('Brands visibility changed');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['virtualProducts'])) {
            $this->Home_admin_model->setValueStore('virtualProducts', $_POST['virtualProducts']);
            session()->setFlashdata('virtualProducts', lang_safe('virtual_products_visibility_change_success'));
            $this->saveHistory('Virtual products visibility changed');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['showInSlider'])) {
            $this->Home_admin_model->setValueStore('showInSlider', $_POST['showInSlider']);
            session()->setFlashdata('showInSlider', lang_safe('show_in_slider_change_success'));
            $this->saveHistory('In Slider products visibility changed');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['multiVendor'])) {
            $this->Home_admin_model->setValueStore('multiVendor', $_POST['multiVendor']);
            session()->setFlashdata('multiVendor', lang_safe('multi_vendor_support_change_success'));
            $this->saveHistory('Multi Vendor Support changed');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['setCookieLaw'])) {
            unset($_POST['setCookieLaw']);
            $this->setCookieLaw($_POST);
            session()->setFlashdata('setCookieLaw', lang_safe('cookie_law_info_change_success'));

            $this->saveHistory('Cookie law information changed');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['hideBuyButtonsOfOutOfStock'])) {
            $this->Home_admin_model->setValueStore('hideBuyButtonsOfOutOfStock', $_POST['hideBuyButtonsOfOutOfStock']);
            session()->setFlashdata('hideBuyButtonsOfOutOfStock', lang_safe('hide_buy_buttons_of_out_of_stock_change_success'));
            $this->saveHistory('Buy buttons visibility changed for out of stock products');
            return redirect()->to('admin/settings');
        }
        if (isset($_POST['refreshAfterAddToCart'])) {
            $this->Home_admin_model->setValueStore('refreshAfterAddToCart', $_POST['refreshAfterAddToCart']);
            session()->setFlashdata('refreshAfterAddToCart', lang_safe('refresh_after_add_to_cart_change_success'));
            $this->saveHistory('Option to open shopping cart after click add to cart button changed');
            return redirect()->to('admin/settings');
        }
    }

    private function setCookieLaw($post)
    {
        $this->Home_admin_model->setCookieLaw($post);
    }

    private function getCookieLaw()
    {
        return $this->Home_admin_model->getCookieLaw();
    }

}
