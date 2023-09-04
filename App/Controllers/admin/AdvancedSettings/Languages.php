<?php
namespace App\Controllers\Admin\AdvancedSettings;
/*
 * @Author:    Kiril Kirkov
 *  Gitgub:    https://github.com/kirilkirkov
 */
use App\Controllers\admin\ADMIN_Controller;
use App\Models\admin\Languages_model;

class Languages extends ADMIN_Controller
{

    protected $Languages_model;

    public function __construct()
    {
        $this->Languages_model = new Languages_model();;
    }


    public function index()
    {
        $this->login_check();
        if (isset($_GET['delete'])) {
            $result = $this->Languages_model->deleteLanguage($_GET['delete']);
            if ($result == true) {
                $this->saveHistory('Delete language id - ' . $_GET['delete']);
                session()->setFlashdata('result_delete', 'Language is deleted!');
            } else {
                session()->setFlashdata('result_delete', 'Problem with language delete!');
            }
            return redirect()->to('admin/languages');
        }
        if (isset($_GET['editLang'])) {
            $num = $this->Languages_model->countLangs($_GET['editLang']);
            if ($num == 0) {
                return redirect()->to('admin/languages');
            }
            $langFiles = $this->getLangFolderForEdit();
        }
        if (isset($_POST['goDaddyGo'])) {
            $this->saveLanguageFiles();
            return redirect()->to('admin/languages');
        }
        if (!is_writable('application' . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR)) {
            $data['writable'] = 'Languages folder is not writable!';
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Languages';
        $head['description'] = '!';
        if (isset($langFiles)) {
            $data['arrPhpFiles'] = $langFiles[0];
            $data['arrJsFiles'] = $langFiles[1];
        }
        $head['keywords'] = '';
        $data['languages'] = $this->Languages_model->getLanguages();

        if (isset($_POST['name']) && isset($_POST['abbr'])) {
            $dublicates = $this->Languages_model->countLangs($_POST['name'], $_POST['abbr']);
            if ($dublicates == 0) {
                $file = $this->request->getFile('userfile');
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
                        $uploadPath = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'lang_flags' . DIRECTORY_SEPARATOR . ''; // Specify your upload path here
                        $file->move($uploadPath, $newName);
                        if($file->hasMoved()) {
                            $_POST['flag'] = $newName;
                        }
                    }
                }
                $this->Languages_model->setLanguage($_POST);
                $this->createLangFolders();
                session()->setFlashdata('result_add', 'Language is added!');
                $this->saveHistory('Create language - ' . $_POST['abbr']);
            } else
                session()->setFlashdata('result_add', 'This language exsists!');
                return redirect()->to('admin/languages');
        }
        $data['max_input_vars'] = ini_get('max_input_vars');
        $this->saveHistory('Go to languages');
        $page = 'advanced_settings/languages';
        return view('templates/admin/_parts/template', ['page'=> $page, 'head' => $head ,'data' => $data, 'footer' => []]);
    }

    private function saveLanguageFiles()
    {
        $i = 0;
        $prevFile = 'none';
        $phpFileInclude = "<?php \n";
        foreach ($_POST['php_files'] as $phpFile) {
            if ($phpFile != $prevFile && $i > 0) {
                savefile($prevFile, $phpFileInclude);
                $phpFileInclude = "<?php \n";
            }
            $php_value = str_replace("'", '&#39;', $_POST['php_values'][$i]);
			$php_value = str_replace('"', '&#34;', $php_value);
            $phpFileInclude .= '$lang[\'' . htmlentities($_POST['php_keys'][$i]) . '\'] = \'' . $php_value . '\';' . "\n";
            $prevFile = $phpFile;
            $i++;
        }
        savefile($phpFile, $phpFileInclude);


        $i = 0;
        $prevFile = 'none';
        $jsFileInclude = "var lang = { \n";
        foreach ($_POST['js_files'] as $jsFile) {
            if ($jsFile != $prevFile && $i > 0) {
                $jsFileInclude .= "};";
                savefile($prevFile, $jsFileInclude);
                $jsFileInclude = "var lang = { \n";
            }
            $jsFileInclude .= htmlentities($_POST['js_keys'][$i]) . ':' . '"' . htmlentities($_POST['js_values'][$i]) . '",' . "\n";
            $prevFile = $jsFile;
            $i++;
        }
        $jsFileInclude .= "};";
        savefile($jsFile, $jsFileInclude);
    }

    private function getLangFolderForEdit()
    {
        $langFiles = array();
        $files = rreadDir(APPPATH  . 'Language' . DIRECTORY_SEPARATOR . '' . $_GET['editLang'] . DIRECTORY_SEPARATOR);
        $arrPhpFiles = $arrJsFiles = array();
        foreach ($files as $ext => $filesLang) {
            foreach ($filesLang as $fileLang) {
                if ($ext == 'php') {
                    require $fileLang;
                    if (isset($lang)) {
                        $arrPhpFiles[$fileLang] = $lang;
                        unset($lang);
                    }
                }
                if ($ext == 'js') {
                    $jsTrans = file_get_contents($fileLang);
                    preg_match_all('/(.+?)"(.+?)"/', $jsTrans, $PMA);
                    $arrJsFiles[$fileLang] = $PMA;
                    unset($PMA);
                }
            }
        }
        $langFiles[0] = $arrPhpFiles;
        $langFiles[1] = $arrJsFiles;
        return $langFiles;
    }

    private function createLangFolders()
    {
        $newLang = strtolower(trim($_POST['name']));
        if ($newLang != '') {
            $from = 'application' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . MY_DEFAULT_LANGUAGE_NAME;
            $to = 'application' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . $newLang;
            rcopy($from, $to);
        }
    }

}
