<?php
use Config\Config;

function getTextualPages($activePages)
{
    $arr = config(Config::class)->no_dynamic_pages;
    $withDuplicates = array_merge($activePages, $arr);
    if (empty($activePages)) {
        return $activePages;
    }
    return array_diff($withDuplicates, array_diff_assoc($withDuplicates, array_unique($withDuplicates)));
}
