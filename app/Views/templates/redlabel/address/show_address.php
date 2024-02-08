<style>
    .address-div{
        font-size: 1.8rem;
        border:1px solid #D9D9D9;
        padding: 20px;
    }
    .address-div .title{
        font-size: 2rem;
    }
    .flex-address{
    display: flex;
    justify-content: space-between;
    align-items: center;
    }
    @media (max-width: 768px) { 
        .address-div{
        padding:20px 10px;
        }
    }
</style>


<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>">
                    <?= lang_safe('home') ?>
                </a></li>
            <li><a href="<?= LANG_URL ?>/myaccount">
                    <?= lang_safe('my_account') ?>
                </a></li>
            <li>
                <?= lang_safe('address') ?>
            </li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>
        <div class="col-md-9">
            <div class="alone title">
                <span>
                    <h2> Update Address </h2>
                </span>
            </div>
            <hr>
            <?php if (session('success')) { ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php
            if (session('submit_error')) {
                ?>
                <hr>
                <div class="row">
                    <div class="alert alert-danger">
                        <h4><span class="glyphicon glyphicon-alert"></span>
                            <?= lang_safe('finded_errors') ?>
                        </h4>
                        <?php
                        foreach (session('submit_error') as $error) {
                            echo $error . '<br>';
                        }
                        ?>
                    </div>
                </div>
                <hr>
                <?php
            }
            ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-xs-12 address-div"
                        style="margin-bottom: 1.2em;">
                        <div class="title alone">
                            <span>
                                <?= lang_safe('billing_address') ?>
                            </span>
                        </div>
                        <div class="flex-address" >
                            <?= $userAddresses['billing_first_name'] . ' ' . $userAddresses['billing_last_name'] ?>
                                <a href="<?= LANG_URL.'/address/edit' ?>" class="change_address">
                                    <?= lang_safe('change_address') ?>
                                </a>
                        </div>
                        <?php if (isset($userAddresses['billing_company']) && !empty($userAddresses['billing_company'])): ?>
                            <div>
                                <?= $userAddresses['billing_company'] ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <?= $userAddresses['billing_street'] . ' ' . $userAddresses['billing_housenr'] ?>
                        </div>
                        <div>
                            <?= $userAddresses['billing_post_code'] . ' ' . $userAddresses['billing_city'] ?>
                        </div>
                        <div>
                            <?= $userAddresses['billing_country'] ?>
                        </div>
                    </div>
                    <div class="col-md-7 col-xs-12 address-div"
                        style="margin-bottom: 1.2em; ">
                        <!-- Right column for delivery address -->
                        <div class="title alone">
                            <span>
                                <?= lang_safe('shipping_address') ?>
                            </span>
                        </div>
                        <div class="flex-address">
                            <?= $userAddresses['shipping_first_name'] . ' ' . $userAddresses['shipping_last_name'] ?>
                            <a href="<?= LANG_URL.'/address/edit' ?>" class="change_address">
                                <?= lang_safe('change_address') ?>
                            </a>
                        </div>
                        <?php if (isset($userAddresses['shipping_company']) && !empty($userAddresses['shipping_company'])): ?>
                            <div>
                                <?= $userAddresses['shipping_company'] ?>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <?= $userAddresses['shipping_street'] . ' ' . $userAddresses['shipping_housenr'] ?>
                        </div>
                        <div>
                            <?= $userAddresses['shipping_post_code'] . ' ' . $userAddresses['shipping_city'] ?>
                        </div>
                        <div>
                            <?= $userAddresses['shipping_country'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>