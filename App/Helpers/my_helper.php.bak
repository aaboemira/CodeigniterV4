<?php
// app/Helpers/my_helper.php
if (! function_exists('lang_safe')) {
    function lang_safe($key)
    {
        $language = service('language');
        $value = $language->getLine('site_lang.'.$key);

        return ($value !== 'site_lang.'.$key) ? $value : '';
    }
}
