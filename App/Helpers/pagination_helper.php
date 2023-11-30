<?php


function pagination($url, $rowscount, $per_page, $segment = 2) {
    return '';

    $request = \Config\Services::request(); // Get the request instance
    $pager = \Config\Services::pager(); // Get the pager service

    $pager->setPath($url); // Set the base URL for pagination

    $config = [
        'baseURL'            => base_url($url),
        'totalRows'          => $rowscount,
        'perPage'            => $per_page,
        'uriSegment'         => $segment,
        'fullTagOpen'        => '<nav><ul class="pagination">',
        'fullTagClose'       => '</ul></nav>',
        'attributes'         => ['class' => 'page-link'],
        'numTagOpen'         => '<li>',
        'numTagClose'        => '</li>',
        'curTagOpen'         => '<li class="active"><a>',
        'curTagClose'        => '</a></li>',
        'nextTagOpen'        => '<li>',
        'nextTagClose'       => '</li>',
        'prevTagOpen'        => '<li>',
        'prevTagClose'       => '</li>',
        'firstLink'          => lang_safe('first'),
        'firstTagOpen'       => '<li>',
        'firstTagClose'      => '</li>',
        'lastLink'           => lang_safe('last'),
        'lastTagOpen'        => '<li>',
        'lastTagClose'       => '</li>',
        'nextLink'           => lang_safe('next'),
        'prevLink'           => lang_safe('previous'),
        'reuseQueryString'   => TRUE,
    ];

    $pager->setConfig($config); // Set the pagination configuration

    return $pager->links(); // Create and return pagination links
}

