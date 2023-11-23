<!DOCTYPE html>
<html lang="<?= MY_LANGUAGE_ABBR ?>">

<head>
    <!-- <script>
    var gaProperty = 'G-X9N04MYG16';
    var disableStr = 'ga-disable-' + gaProperty;
    if (document.cookie.indexOf(disableStr + '=true') > -1) {
        window[disableStr] = true;
    }

    function gaOptout() {
        document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
        window[disableStr] = true;
    }
    </script> -->


    <!-- Global site tag (gtag.js) - Google Analytics
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X9N04MYG16">
    </script>

     <script>
        // window.dataLayer = window.dataLayer || [];

        // function gtag() {
            // dataLayer.push(arguments);
        // }
        // gtag('js', new Date());
        // gtag('config', 'G-X9N04MYG16');
        // gtag('config', 'AW-428847483');
     </script>-->

    <?php
    header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
    ?>

    <!-- <meta http-equiv="cache-control" content="no-cache" /> -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="<?= $description ?>"/>
    <meta name="keywords" content="<?= $keywords ?>"/>
    <meta property="og:title" content="<?= $title ?>"/>
    <meta property="og:description" content="<?= $description ?>"/>
    <meta property="og:url" content="<?= LANG_URL ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image"
          content="<?= isset($image) && !is_null($image) ? $image : base_url('assets/img/site-overview.png') ?>"/>
    <title>
        <?= $title ?>
    </title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="<?= base_url('ico/favicon.ico') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/w3.css') ?>"/>

    <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>"/>

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap-select-1.12.1/bootstrap-select.min.css') ?>"/>

    <link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('assets/templatecss/custom.css') ?>" rel="stylesheet"/>
    <link href="<?= base_url('cssloader/theme') ?>" rel="stylesheet"/>


    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('loadlanguage/all') ?>"></script>


    <!-- cookie 2 -->
    <link href="/dist/cookieconsent.css" rel="stylesheet"/>
    <script src="/dist/cookieconsent.js"></script>
    <script var cookieconsent=initCookieConsent(); cookieconsent.run({ onAccept: function(cookies){
            if(cc.allowedCategory(
    'analytics_cookies')){ cc.loadScript('https://www.google-analytics.com/analytics.js',
    function(){ ga('create', 'UA-XXXXXXXX-Y' , 'auto' ); //replace UA-XXXXXXXX-Y with your tracking code
    ga('send', 'pageview' ); }); } }, });></script>

    <?php if ($cookieLaw != false) { ?>
        <script type="text/javascript">
            window.cookieconsent_options = {
                "message": "<?= $cookieLaw['message'] ?>",
                "dismiss": "<?= $cookieLaw['button_text'] ?>",
                "learnMore": "<?= $cookieLaw['learn_more'] ?>",
                "link": "<?= base_url($cookieLaw['link']) ?>",
                "theme": "<?= $cookieLaw['theme'] ?>"
            };
        </script>
        <script type="text/javascript" src="<?= base_url('assets/js/cookieconsent.min.js') ?>">
        </script>

        <script type="text/javascript">
            window.addEventListener("resize", () => {
                document.body.classList.add("resize-animation-stopper");
            });
        </script>
        <script>
            /*if (window.sessionStorage.getItem("stopanimate") == null) {

                window.sessionStorage.setItem("stopanimate" ,1);
            }
            else{
                document.getElementById("float_phone").classList.add("animate_once");
            }*/
        </script>

    <?php } ?>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<?php if (isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    if ($ID == '2021000100011') {
        header('Location: ' . base_url('ND_GC_1S_9'));
        die();
    }
    if ($ID == '0') {
        header('Location: ' . base_url('/admin'));
        die();
    }
}

// header('Location: ' . base_url('/shop'));
// die();
?>

<body>
<a id="float_wa" class="float_wa " href="https://wa.me/+4971125286437">
    <img class="wa_widget_img" alt="WhatsApp us" src="<?= base_url('png/wa_widget.png') ?>"/>
</a>
<a id="float_phone" class="float_phone" href="tel:+4971125286437">
    <img class="phone_widget_img" alt="Call us" src="<?= base_url('png/phone_widget.png') ?>"/>
</a>
<a id="float_mail" class="float_mail" href="mailto:kontakt@nodedevices.de">
    <img class="mail_widget_img" alt="Mail us" src="<?= base_url('png/mail_widget.png') ?>"/>
</a>

<div id="contactModal" class="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color:white; opacity:1;"
                        id="closeModalButton">&times;
                </button>
                <h4 class="modal-title" style="text-align: left"><span class="glyphicon "></span>
                    &#10; <?= lang_safe('Modal_contact_options_header') ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding: 5px;">
                    <!-- Add a checkbox for policy acceptance -->

                    <div class="col-md-12" style="text-align:left; margin-bottom: 15px;">
                        <p style="text-align:left;"><?= lang_safe('contact_policy') ?></p>
                        <label style="text-align:left;" for="acceptPolicyCheckbox">
                            <?= lang_safe('dataprotection_contact_accept1') ?>
                            <a href="<?= LANG_URL . '/page/' . "Datenschutz" ?>"><?= lang_safe('dataprotection_contact_accept2') ?></a>
                            <?= lang_safe('dataprotection_contact_accept3') ?>
                            <sup>
                                <?= lang_safe('required') ?>
                            </sup>
                        </label>
                        <input type="checkbox" id="acceptPolicyCheckbox">
                    </div>

                    <ul id="contactOptionsList" style="list-style: none; font-size: large;">
                        <li>
                            <a href="https://wa.me/+4971125286437" id="whatsappLink">
                                <img class="wa_widget_img" alt="Chat on WhatsApp"
                                     src="<?= base_url('png/wa_widget.png') ?> "/>
                                <span class="contactOptionText"><?= lang_safe('contact_text_whatsapp') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="tel:+4971125286437" id="phoneLink">
                                <img class="phone_widget_img" alt="Call" src="<?= base_url('png/phone_widget.png') ?>"/>
                                <span class="contactOptionText"><?= lang_safe('contact_text_phone') ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:kontakt@nodedevices.de" id="emailLink">
                                <img class="mail_widget_img" alt="Email" src="<?= base_url('png/mail_widget.png') ?>"/>
                                <span class="contactOptionText"><?= lang_safe('contact_text_email') ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<div id="wrapper">
    <div id="content">
        <div class="top-part " id="top-part">
            <!-- <div id="languages-bar">
						<?php
            $num_langs = count($allLanguages);
            if ($num_langs > 0) {
                ?>
							<ul class="header_lang_pull-right">
								<?php
                $i = 1;
                $lang_last = '';
                foreach ($allLanguages as $key_lang => $lang) {
                    ?>
									<li <?= $i == $num_langs ? 'class="last-item"' : '' ?>>
										<img src="<?= base_url('attachments/lang_flags/' . $lang['flag']) ?>"
											alt="Language-<?= MY_LANGUAGE_ABBR ?>"><a href="<?= base_url($key_lang) ?>/shop"><?= $lang['name'] ?></a>
									</li>
									<?php
                    $i++;
                }
                ?>
							</ul>
						<?php } ?>
					</div>
					-->

            <nav class="navbar navbar-custom" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle " data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand custom-a" href="<?= LANG_URL ?>">
                            <img class="nav_logo " alt="Brand"
                                 src="<?= base_url('jpg/NODEMATIC-BRAND-WEB-171-650.jpg') ?>">
                        </a>
                        <div class="shopping-cart-dropdown-wrapper">
                            <!-- Existing Shopping Cart Icon -->
                            <a class="shopping_cart_div custom-a" href="<?= base_url() ?>shopping-cart">
                                <img class="shopping_cart_img" alt="shopping cart"
                                     src="<?= base_url('png/cart_white.png') ?>" >
                                <span class="shopping_cart_counter sumOfItems"><?= is_numeric($cartItems) && (int)$cartItems == 0 ? 0 : $sumOfItems ?></span>
                            </a>
                            <!-- Dropdown Menu -->
                            <div class="shopping-dropdown-menu text-center"> <!-- Center-align the content -->
                                <div class="col-12">

                                    <?php if (empty($cartItems)) : ?>
                                        <!-- Show "Empty Cart" message -->
                                        <div class="col-6" style="font-weight:bold; font-size: 1.5rem; text-align:center;">
                                            <p class="alert alert-info " style="font-weight:bold; font-size:1.3rem; ">
                                                <?= lang_safe('empty_cart') ?>
                                            </p>
                                        </div>
                                    <?php else : ?>
                                        <!-- Show cart summary when cart is not empty -->
                                        <div class="col-6" style="font-weight:bold; font-size: 1.5rem; text-align:center;">
                                            <p style="font-weight:bold;">
                                                <?= lang_safe('modal_cart_total') ?>
                                                <br>
                                                <br>
                                                <span style="font-weight:normal; font-size: 1.8rem" id="cartTotal2">
                                                    <?= $cartItems['finalSum']  ?>
                                                </span><span style="font-weight:normal; font-size: 1.8rem;"><?=CURRENCY?></span>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($cartItems)) : ?>
                                        <!-- Show buttons when cart is not empty -->
                                        <div class="col-12" style="text-align: center;">
                                            <a class="btn btn-primary btn-new go-shop"
                                               style="margin-top: 10px; width:80%;"
                                               href="<?= LANG_URL . '/shopping-cart' ?>">
                                                <?= lang_safe('modal_show_cart') ?>
                                            </a>
                                        </div>
                                        <div class="col-12" style="text-align: center;">
                                            <a class="btn btn-primary btn-new go-checkout"
                                               style="margin-top: 5px; width:80%;"
                                               href="<?= LANG_URL . '/checkout1' ?>">
                                                <?= lang_safe('modal_checkout') ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- /.container-fluid -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a class="custom-a" href="<?= LANG_URL ?>"><?= lang_safe('nav_startseite') ?></a></li>
                        <li><a class="custom-a" href="<?= LANG_URL . '/shop' ?>"><?= lang_safe('nav_shop') ?></a></li>
                        <?php
                        if (!empty($nonDynPages)) {
                            foreach ($nonDynPages as $addonPage) { ?>
                                <li><a class="custom-a"
                                       href="<?= LANG_URL . $addonPage ?>"><?= mb_ucfirst(lang_safe($addonPage)) ?></a>
                                </li>
                                <?php
                            }
                        }
                        ?>

                        <li><a class="custom-a" href="<?= LANG_URL . '/contacts' ?>"><?= lang_safe('nav_kontakt') ?></a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right login-nav">
                        <li class="dropdown login-dropdown-li">

                            <a id="myAccountLink" class="icon login-icon" href="#" data-toggle="dropdown" role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <img src="<?= base_url('png/myaccount_white.png') ?>" alt="login" class="dropdown-image"
                                    >
                                <?php if (isset($_SESSION['logged_user'])) { ?>

                                    <span class="logged-user-name_mobile no-hover">
                                                    <?php echo $_SESSION['user_name']; ?>
                                        </span>
                                <?php } ?>
                            </a>
                            <!--                                    <a class="icon shopping_cart_div max-675 hidden"  href="-->
                            <? //= base_url() ?><!--shopping-cart">-->
                            <!--                                        <img class="dropdown-image " alt="shopping cart" src="-->
                            <? //= base_url('png/cart_white128.png') ?><!--">-->
                            <!--                                        <span class="shopping_cart_counter sumOfItems"> -->
                            <? //= is_numeric($cartItems) && (int) $cartItems == 0 ? 0 : $sumOfItems ?><!--</span>-->
                            <!--                                        </img>-->
                            <!--                                    </a>-->
                            <!-- Shopping Cart Dropdown -->


                            <div class="dropdown-menu" id="hover-dropdown-menu">
                                <?php if (!isset($_SESSION['logged_user'])) { ?>
                                    <a class="btn btn-primary btn-new " href="<?= base_url("/register") ?>"
                                       title="nodematic Login">

                                        <?= lang_safe('nodematic_login') ?>
                                    </a>
                                    <hr class="no-hover" style="margin:10px;">
                                    <div class="account-menu-register">
                                        <a class=" register-link" style="margin-top: 10px;"
                                           href="<?= base_url("/register") ?>" title="Registrierung">
                                            <span><?= lang_safe('register') ?></span>
                                            <span class="icon icon-arrow-right ">
                                                            <svg viewBox="0 0 24 24" version="1.1"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                <g id="Page-1" stroke="none" stroke-width="1"
                                                                   fill="none" fill-rule="evenodd">
                                                                    <g id="Desktop-HD"
                                                                       transform="translate(0.000000, -384.000000)">
                                                                        <g id="arrow_right"
                                                                           transform="translate(1.000000, 384.000000)"
                                                                           fill="#000000"
                                                                           fill-rule="nonzero">
                                                                            <path
                                                                                    d="M10.1231887,0.710064646 L21.2720179,12.0187229 L10.1231887,23.3273812 L8.69894628,21.9232681 L17.4660248,13.0290646 L0.752024771,13.03 L0.752024771,11.03 L17.4860248,11.0290646 L8.69894628,2.11417775 L10.1231887,0.710064646 Z"
                                                                                    id="Combined-Shape"></path>
                                                                        </g>
                                                                        <g id="slices"></g>
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                        </span>
                                        </a>
                                    </div>
                                <?php } else { ?>
                                    <!-- Display logout button -->


                                    <!-- Display logged user's name -->
                                    <p class="dropdown-item username_item_web no-hover ">
                                                <span class="logged-user-name">
                                                    <?php echo $_SESSION['user_name']; ?>
                                                </span>
                                    </p>

                                    <!-- Add a horizontal line -->
                                    <div class="dropdown-item hr2_div no-hover">
                                        <hr class="account-divider">
                                    </div>
                                    <!-- Display links for managing the account -->
                                    <?php
                                    $accountPages = array(
                                        array('title' => 'Overview', 'url' => base_url('myaccount'), 'icon' => 'fa fa-th'),
                                        array('title' => 'Address', 'url' => base_url('address'), 'icon' => 'fa fa-address-book'),
                                        array('title' => 'Orders', 'url' => base_url('orders'), 'icon' => 'fa fa-shopping-bag'),
                                        array('title' => 'Smart Home', 'url' => base_url('smart-home'), 'icon' => 'fa fa-h-square'),
                                        array('title' => 'Newsletter', 'url' => base_url('newsletter'), 'icon' => 'fa fa-newspaper-o'),
                                        array('title' => 'Account', 'url' => base_url('account'), 'icon' => 'fa fa-user'),
                                        array('title' => 'Password', 'url' => base_url('password'), 'icon' => 'fa fa-key'),
                                    );


                                    foreach ($accountPages as $page) { ?>
                                        <a class="account-page-link dropdown-item  " href="<?php echo $page['url']; ?>">
                                            <span class="icon <?php echo $page['icon']; ?>"></span><?php echo $page['title']; ?>
                                        </a>
                                    <?php } ?>

                                    <div class="dropdown-item no-hover">
                                        <hr class="account-divider hr-2 no-hover">
                                    </div>
                                    <a class=" btn btn-primary btn-new  logout-button" style="text-align: center"
                                       href="<?= base_url('logout') ?>">
                                        Logout
                                    </a>
                                <?php } ?>
                            </div>

                        </li>

                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
        </div>
        <!--<script>-->
        <!--    document.addEventListener('DOMContentLoaded', function() {-->
        <!--        var navbarToggle = document.querySelector('.navbar-toggle');-->
        <!--        var defaultCart = document.querySelector('.shopping_cart_div.custom-a');-->
        <!--        var mobileCart = document.querySelector('.icon.shopping_cart_div.max-675');-->
        <!---->
        <!--        navbarToggle.addEventListener('click', function() {-->
        <!--            var isExpanded = navbarToggle.getAttribute('aria-expanded') !== 'true';-->
        <!---->
        <!--            // Toggle the cart icons based on the expanded state of the navbar-->
        <!--            if (isExpanded) {-->
        <!--                // Navbar is expanded, show the mobile cart and hide the default-->
        <!--                defaultCart.classList.add('hidden');-->
        <!--                mobileCart.classList.remove('hidden');-->
        <!--            } else {-->
        <!--                // Navbar is collapsed, show the default cart and hide the mobile-->
        <!--                defaultCart.classList.remove('hidden');-->
        <!--                mobileCart.classList.add('hidden');-->
        <!--            }-->
        <!--        });-->
        <!--    });-->
        <!---->
        <!--</script>-->