<style>
    .nav .nav-item {
        
        color: #606060;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .nav .nav-item .nav-link {
        font-size: 18px;
        font-weight: 700;
        color: #4f4f4f;
        text-transform: capitalize;
        padding: 18px 16px;
        border-radius: 5px;
        
    }
    .nav .nav-link.active {
        color: #337ab7;
        border-left: 3px solid #337ab7;
    }
    .nav .nav-item a {
        background-color: #f8f8f8;
    }
    .thumbnail {
        border: none;
    }

    .thumbnail .thumb-icon {
        font-size: 100px;
        text-align: center;
    }

    .thumbnail .caption > a {
        float: right;
        font-size: 20px;
    }
    .card-text {
        width: 80%;
        display: inline-block;
        font-size: 18px;
        color: #656565;
    }
    .user-page {
        padding: 0 100px;
    }
    .breadcrumb {
        font-size: 18px;
    }
</style>

<?php
// Load the uri service
$uri = service('uri');
$var = [];
// Retrieve the segments from the URL
$var[] = $uri->getSegment(1); // First segment
$var[] = $uri->getSegment(2); // Second segment ?>

<div class="left-sidebar col-md-3">
                <div class="sticky-sidebar">
                    <ul class="nav list-unstyled nav-sidebar doc-nav">
                        <li class="nav-item direct">
                            <a class="nav-link <?php if(in_array('myaccount',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/myaccount' ?>"><i class="fa fa-th" aria-hidden="true"></i> Overview</a>
                        </li>
                        <li class="nav-item direct">
                            <a class="nav-link <?php if(in_array('address',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/address' ?>"><i class="fa fa-address-book" aria-hidden="true"></i> Address</a>
                        </li>
                        <li class="nav-item direct">
                            <a class="nav-link <?php if(in_array('orders',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/orders' ?>"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Orders</a>
                        </li>
                        <li class="nav-item direct">
                            <a class="nav-link <?php if(in_array('smart-home',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/smart-home' ?>"><i class="fa fa-h-square" aria-hidden="true"></i> Smart Home</a>
                        </li>
                        <li class="nav-item direct">
                            <a class="nav-link <?php if(in_array('newsletter',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/newsletter' ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Newsletter</a>
                        </li>
                        <li class="nav-item direct">
                            <a class="nav-link <?php if(in_array('account',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/account' ?>"><i class="fa fa-user" aria-hidden="true"></i> Account</a>
                        </li>
                        <li class="nav-item direct">
                            <a class="nav-link <?php if(in_array('password',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/password' ?>"><i class="fa fa-key" aria-hidden="true"></i> Password</a>
                        </li>
                        <li class="nav-item direct">
                            <a class="nav-link" href="<?= LANG_URL . '/logout' ?>"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>