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
    .nav .nav-link.active  .nav-icon{
        fill: #337ab7;
    }
    .nav .nav-link.active  {
        padding-left: 13px !important;
    }
    .nav .nav-item a {
        background-color: #f8f8f8;
    }
    .nav .nav-item .nav-icon {
        width: 24px; /* Fixed width */
        height: 24px; /* Fixed height */
        margin-right: 10px; /* Space between the icon and the text */
    }
    .title.alone {
        width: fit-content !important;
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
    /* }
    .user-page {
        padding: 0 100px;
    } */
    .breadcrumb {
        font-size: 18px;
    }

}

</style>

<?php
// Load the uri service
$uri = service('uri');
$var = [];
// Retrieve the segments from the URL
$var[] = $uri->getSegment(1); // First segment
$var[] = $uri->getSegment(2); // Second segment ?>
<style>
    @media screen and (max-width: 768px) {
        .login-dropdown-li .login-icon img:hover {
            background: none !important; /* Removes background gradient */
            box-shadow: none !important; /* Removes box shadow */
            color: inherit !important; /* Resets to default link color, adjust as needed */
            text-shadow: none !important; /* Removes text shadow */
            /* Add any other properties to reset hover styles */
    }
    @media (max-width: 768px) {
    .title.alone span h2{
        font-size: 2rem !important;
    }
}
</style>

<div class="left-sidebar col-md-3 min-675 { display: none; } ">
    <div class="sticky-sidebar">
        <ul class="nav list-unstyled nav-sidebar doc-nav">
            <!-- Overview -->
            <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('myaccount',$var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/myaccount' ?>"><i class="fa fa-th nav-icon" aria-hidden="true"></i> Overview</a>
            </li>
            <!-- <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('myaccount', $var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/myaccount' ?>">
                    <img src="<?= base_url('png/dashboard/overview.svg') ?>" alt="Overview" class="nav-icon" /> Overview
                </a>
            </li> -->
            <!-- Account -->
            <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('account', $var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/account' ?>">
                    <img src="<?= base_url('png/dashboard/account.svg') ?>" alt="Account" class="nav-icon" /> Account
                </a>
            </li>
            <!-- Orders -->
            <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('orders', $var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/orders' ?>">
                    <img src="<?= base_url('png/dashboard/orders.svg') ?>" alt="Orders" class="nav-icon" /> Orders
                </a>
            </li>
            <!-- Address -->
            <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('address', $var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/address' ?>">
                    <img src="<?= base_url('png/dashboard/address.svg') ?>" alt="Address" class="nav-icon" /> Address
                </a>
            </li>
            <!-- Smart Home -->
            <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('smart-home', $var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/smart-home' ?>">
                    <img src="<?= base_url('png/dashboard/smart.svg') ?>" alt="Smart Home" class="nav-icon" /> Smart Home
                </a>
            </li>
            <!-- Password -->
            <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('password', $var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/password' ?>">
                    <img src="<?= base_url('png/dashboard/password.svg') ?>" alt="Password" class="nav-icon" /> Password
                </a>
            </li>
            <!-- Newsletter -->
            <li class="nav-item direct">
                <a class="nav-link <?php if(in_array('newsletter', $var)) { echo 'active'; } ?>" href="<?= LANG_URL . '/newsletter' ?>">
                    <img src="<?= base_url('png/dashboard/newsletter.svg') ?>" alt="Newsletter" class="nav-icon" /> Newsletter
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item direct">
                <a class="nav-link" href="<?= LANG_URL . '/logout' ?>">
                    <img src="<?= base_url('png/dashboard/logout.svg') ?>" alt="Logout" class="nav-icon" /> Logout
                </a>
            </li>
        </ul>
    </div>
</div>
