<style>
    /* Base styling for dashboard cards */
    .dashboard-card {
        border-radius: 15px;
        background: #ffffff;
        /* White background */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        min-height: 250px;
        padding:4px;
        transition: transform 0.3s ease-in-out;
        border-left: 5px solid #4f4f4f;
        /* Sample color accent */
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        /* Hover effect */
        box-shadow: 0px 0px 15px 6px rgb(31 149 255 / 75%);
        -webkit-box-shadow: 0px 0px 15px 6px rgb(31 149 255 / 75%);
        -moz-box-shadow: 0px 0px 15px 6px rgba(31, 149, 255, 0.75);
    }


    /* New styles for the blue line between header and body */

    .dashboard-card-header {
        display: flex;
        /* Use flexbox layout */
        align-items: center;
        /* Align items vertically in the center */
        justify-content: center;
        /* Align content to the start (left) */
        padding: 10px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        padding-bottom: 15px;

        border-bottom: 2px solid #4169E1;
    }

    .dashboard-card-header img {
        width: 70px; /* Fixed width for icons */
        height: 70px; /* Fixed height for icons */
        margin-right: 10px; /* Space between the icon and the title */
    }

    .dashboard-card-header img:hover {
        transform: scale(1.1);
    }
    .dashboard-card-header h4 {
        color: #333;
        font-family: 'Arial', sans-serif;
        margin: 0;
        /* Remove default margins */
    }


        .dashboard-card .panel-body {
            flex-grow: 1; /* Allow body to grow to fill available space */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding:15px 10px !important;
        }

    .dashboard-card .panel-body p {
        color: #333;
        text-align: center;
        font-family: 'Arial', sans-serif;
        margin: 5px 0;
        font-size: 1.55rem; /* Adjust paragraph font size */

        /* Add margin to separate paragraphs */
    }


        .dashboard-card {
            flex-grow: 1; /* Cards grow to fill the available space */
            display: flex;
            flex-direction: column;
        }
    /* Other existing styles remain unchanged */
    @media only screen and (min-width: 992px) and (max-width: 1199px) {
        /* Styles for screens between 992px and 1199px */
        .dashboard-card-header img {
            width: 50px; /* Fixed width for icons */
            height: 50px; /* Fixed height for icons */
            margin-right: 10px; /* Space between the icon and the title */
        }
        .dashboard-card .panel-body p {
            font-size: 1.35rem; /* Adjust paragraph font size */
        }
    }
    @media (min-width: 768px) and (max-width: 991px) {
        .dashboard-card-header img {
            width: 75px; /* Fixed width for icons */
            height: 75px; /* Fixed height for icons */
            margin-right: 10px; /* Space between the icon and the title */
        }
        .dashboard-card .panel-body p {
            font-size: 1.75rem; /* Adjust paragraph font size */
        }
        .dashboard-card-header h4 {
            font-size: 2.2rem; /* Adjust header font size */
        }
    }
    @media (max-width: 767px) {
        .dashboard-card-header img {
            width: 80px; /* Fixed width for icons */
            height: 80px; /* Fixed height for icons */
            margin-right: 10px; /* Space between the icon and the title */
        }
        .dashboard-card .panel-body p {
            font-size: 2rem; /* Adjust paragraph font size */
        }
        .dashboard-card-header h4 {
            font-size: 2.5rem; /* Adjust header font size */
        }
    }

    @media (max-width: 480px) {
        .dashboard-card-header img {
            width: 55px; /* Fixed width for icons */
            height: 55px; /* Fixed height for icons */
            margin-right: 10px; /* Space between the icon and the title */
        }
        .dashboard-card .panel-body p {
            font-size: 1.7rem; /* Adjust paragraph font size */
        }
        .dashboard-card-header h4 {
            font-size: 1.8em; /* Adjust header font size */
        }
        .panel-body{
            padding:15px 5px
        }
        .dashboard-card{
            height:230px !important;
        }
    }
    @media (max-width: 400px) {
        .dashboard-card-header img {
            width: 55px; /* Fixed width for icons */
            height: 55px; /* Fixed height for icons */
            margin-right: 10px; /* Space between the icon and the title */
        }
        .dashboard-card .panel-body p {
            font-size: 1.5rem; /* Adjust paragraph font size */
        }
        .dashboard-card-header h4 {
            font-size: 1.8em; /* Adjust header font size */
        }
    }
</style>
</head>

<body>
<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>
        <div class="col-md-9">
            <div class="alone title" style="margin-bottom:20px;">
                <span><h2><?= lang_safe('overview', 'Overview') ?></h2></span>
            </div>
            <!-- Cards with Icons -->
            <div class="row placeholders">
                <!-- User Profile Card -->
                <a href="<?=LANG_URL."/account"?>">
                    <div class="col-xs-12 col-sm-6 col-md-4 placeholder">
                        <div class="panel panel-default dashboard-card">
                            <div class="dashboard-card-header">
                                    <img src="<?=base_url('png/dashboard/account.svg')?>" alt="Account" />
                            
                                <h4><?= lang_safe('account_title', 'Account') ?></h4>
                            </div>
                            <div class="panel-body">
                                <p><?= lang_safe('account_info_1', 'Manage your personal information and settings.') ?></p>
                                <p><?= lang_safe('account_info_2', 'Additional information about the account.') ?></p>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- Orders Card -->
                <a href="<?= LANG_URL."/orders" ?>">
                <div class="col-xs-12 col-sm-6 col-md-4 placeholder">
                    <div class="panel panel-default dashboard-card">
                        <div class="dashboard-card-header">
                                <img src="<?=base_url('png/dashboard/orders.svg')?>" alt="Account" />
                            <h4><?= lang_safe('orders_title', 'Orders') ?></h4>
                        </div>
                        <div class="panel-body">
                            <p><?= lang_safe('orders_info_1', 'View your recent orders and track shipping.') ?></p>
                            <p><?= lang_safe('orders_info_2', 'Additional information about orders.') ?></p>
                        </div>
                    </div>
                </div>
                </a>

                <!-- Smart Home Card -->
                <a href="<?= LANG_URL."/smart-home" ?>">
                    <div class="col-xs-12 col-sm-6 col-md-4 placeholder">
                        <div class="panel panel-default dashboard-card">
                            <div class="dashboard-card-header">
                                    <img src="<?=base_url('png/dashboard/smart.svg')?>" alt="Account" />
                                <h4><?= lang_safe('smart_home_title', 'Smart Home') ?></h4>
                            </div>
                            <div class="panel-body">
                                <p><?= lang_safe('smart_home_info_1', 'Manage your smart home devices.') ?></p>
                                <p><?= lang_safe('smart_home_info_2', 'Additional information about smart home.') ?></p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Address Card -->
                <a href="<?= LANG_URL."/address" ?>">
                    <div class="col-xs-12 col-sm-6 col-md-4 placeholder">
                        <div class="panel panel-default dashboard-card">
                            <div class="dashboard-card-header">
                                    <img src="<?=base_url('png/dashboard/address.svg')?>" alt="Account" />
                                <h4><?= lang_safe('address_title', 'Address') ?></h4>
                            </div>
                            <div class="panel-body">
                                <p><?= lang_safe('address_info_1', 'Manage your address.') ?></p>
                                <p><?= lang_safe('address_info_2', 'Additional information about addresses.') ?></p>
                                
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Newsletter Card -->
                <a href="<?= LANG_URL."/newsletter" ?>">
                    <div class="col-xs-12 col-sm-6 col-md-4 placeholder">
                        <div class="panel panel-default dashboard-card">
                            <div class="dashboard-card-header">
                                    <img src="<?=base_url('png/dashboard/newsletter.svg')?>" alt="Account" />
                                <h4><?= lang_safe('newsletter_title', 'Newsletter') ?></h4>
                            </div>
                            <div class="panel-body">
                                <p><?= lang_safe('newsletter_info_1', 'Subscribe to newsletters.') ?></p>
                                <p><?= lang_safe('newsletter_info_2', 'Additional information about newsletters.') ?></p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Password Card -->
                <a href="<?= LANG_URL."/password" ?>">
                    <div class="col-xs-12 col-sm-6 col-md-4 placeholder">
                        <div class="panel panel-default dashboard-card">
                            <div class="dashboard-card-header">
                                    <img src="<?=base_url('png/dashboard/password.svg')?>" alt="Account" />
                                <h4><?= lang_safe('password_title', 'Password') ?></h4>
                            </div>
                            <div class="panel-body">
                                <p><?= lang_safe('password_info_1', 'Change your password.') ?></p>
                                <p><?= lang_safe('password_info_2', 'Additional information about passwords.') ?></p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<script>
        $(document).ready(function() {
            let maxHeight = 0;

            // Find the tallest card
            $('.dashboard-card').each(function() {
                const thisHeight = $(this).outerHeight();
                if (thisHeight > maxHeight) {
                    maxHeight = thisHeight;
                }
            });

            // Set all cards to the height of the tallest card
            $('.dashboard-card').each(function() {
                $(this).outerHeight(maxHeight);
            });
        });
    </script>