<?php

namespace App\Controllers;
/* Set internal character encoding to UTF-8 */
mb_internal_encoding("UTF-8");
use Config\Services;
use App\Models\admin\Home_admin_model;

class Loader extends BaseController
{

    /*
     * Load language javascript file
     */

    public function jsFile($file = null)
    {
        helper('language');
        $lang = \Config\Services::language();
        // Get the Locale instance
        $locale = $lang->getLocale();
        
        $contents = file_get_contents(APPPATH.'Language/'.$locale.'/js/'.$file.'.js');
        if (!$contents) {
            header('HTTP/1.1 404 Not Found');
            return;
        }
        // Set the content type to JavaScript
        $this->response->setContentType('application/javascript');
        echo $contents;
    }

    /*
     * Load css generated from administration -> styles
     */

    public function cssStyle()
    {
        $this->Home_admin_model = new Home_admin_model();
        $style = $this->Home_admin_model->getValueStore('newStyle');
        if ($style == null) {
            $template = $this->template;
            $style = file_get_contents(VIEWS_DIR . $template . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'default-gradient.css');
            if (!$style) {
                header('HTTP/1.1 404 Not Found');
                return;
            }
        }
        $this->response->setContentType('text/css');
        echo $style;
    }

    /*
     * Load css file for template
     * Can call css file in folder /assets/css/ with templatecss/filename.css
     */

    public function templateCss($file)
    {
        $template = $this->template;
        $style = file_get_contents(VIEWS_DIR . $template . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $file.'.css');
        if (!$style) {
            header('HTTP/1.1 404 Not Found');
            return;
        }
        $this->response->setContentType('text/css');
        echo $style;
    }

    /*
     * Load js file for template
     * Can call css file in folder /assets/js/ with templatecss/filename.js
     */

    public function templateJs($file)
    {
        $template = $this->template;
        $js = file_get_contents(VIEWS_DIR . $template . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $file.'.js');
        if (!$js) {
            header('HTTP/1.1 404 Not Found');
            return;
        }
        $this->response->setContentType('application/javascript');
        echo $js;
    }

    /*
     * Load images comming with template in folder /assets/imgs/
     * Can call from view with template/imgs/filename.jpg
     */

    public function templateCssImage($file, $template = null)
    {
        if ($template == null) {
            $template = $this->template;
        } else {
            $template = DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . DIRECTORY_SEPARATOR;
        }
        $path = VIEWS_DIR . $template . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'imgs' . DIRECTORY_SEPARATOR . $file;
        $img = file_get_contents($path);
        if (!$img) {
            header('HTTP/1.1 404 Not Found');
            return;
        }
        $image_mime = null;
        if (function_exists('mime_content_type')) {
            $image_mime = mime_content_type($path);
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $image_mime = finfo_file($finfo, $path);
            finfo_close($finfo);
        }
        if ($image_mime !== null) {
            header('Content-Type: ' . $image_mime . '  charset: UTF-8');
        }
        echo $img;
    }

}
