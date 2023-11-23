
<div class="container" id="checkout-page">
    <?php if (isset($cartItems['array']) && $cartItems['array'] != null) { ?>
        <?= purchase_steps(1, 1) ?>

        <div class="container">
            <div class="row">
                <div class=" col-lg-3 col-md-4 col-xs-12" style="padding-left: 0 !important; margin-bottom: 1.2em; margin-right: 2em">
                    <div class="title alone">
                        <span><?= lang_safe('billing_address') ?></span>
                    </div>
                    <div class="flex-div">
                        <?= get_form_field_value($user_data, 'billing_address', 'billing_first_name') . ' ' . get_form_field_value($user_data, 'billing_address', 'billing_last_name') ?>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="change_address">
                            <button class="change_address"><?= lang_safe('change_address') ?></button>
                        </form>
                    </div>
                    <div>
                        <?= get_form_field_value($user_data, 'billing_address', 'billing_street') . ' ' . get_form_field_value($user_data, 'billing_address', 'billing_housenr') ?>
                    </div>
                    <div>
                        <?= get_form_field_value($user_data, 'billing_address', 'billing_post_code') . ' ' . get_form_field_value($user_data, 'billing_address', 'billing_city') ?>
                    </div>
                    <div>
                        <?= get_form_field_value($user_data, 'billing_address', 'billing_country') ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-xs-12" style="padding-left: 0 !important; margin-bottom: 1.2em; ">
                    <!-- Right column for delivery address -->
                    <div class="title alone">
                        <span><?= lang_safe('shipping_address') ?></span>
                    </div>
                    <div class="flex-div">
                        <?= get_form_field_value($user_data, 'shipping_address', 'shipping_first_name') . ' ' . get_form_field_value($user_data, 'shipping_address', 'shipping_last_name') ?>
                        <form action="" method="post">
                            <input type="hidden" name="action" value="change_address">
                            <button class="change_address"><?= lang_safe('change_address') ?></button>
                        </form>
                    </div>
                    <div>
                        <?= get_form_field_value($user_data, 'shipping_address', 'shipping_street') . ' ' . get_form_field_value($user_data, 'shipping_address', 'shipping_housenr') ?>
                    </div>
                    <div>
                        <?= get_form_field_value($user_data, 'shipping_address', 'shipping_post_code') . ' ' . get_form_field_value($user_data, 'shipping_address', 'shipping_city') ?>
                    </div>
                    <div>
                        <?= get_form_field_value($user_data, 'shipping_address', 'shipping_country') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 checkout-buttons">
                    <br>
                    <br>
                    <a class="btn btn-primary btn-new go-checkout w3-right" id="checkoutButton"
                       href="javascript:submitGoCheckoutForm();">
                        <?= lang_safe('to_checkout2') ?>
                        <i class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a href="<?= LANG_URL . '/shop' ?>" class="btn btn-primary btn-new go-shop">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span>
                        <?= lang_safe('back_to_shop') ?>
                    </a>
                </div>
            </div>
        </div>
    <?php } else { ?>

        <div class="container">
            <div class="col-sm-6">
                <div class="alert alert-info">
                    <?= lang_safe('empty_cart') ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<form id="goCheckoutForm" action="" method="post" style="display: none;">
    <input type="hidden" name="user_status" value="<?= session()->has('logged_user') ? 'user' : 'guest' ?>">

    <input type="hidden" name="action" value="go_checkout">
    <input type="hidden" name="billing_first_name" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_first_name') ?>">
    <input type="hidden" name="billing_last_name" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_last_name') ?>">
    <input type="hidden" name="billing_company" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_company') ?>">

    <input type="hidden" name="billing_street" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_street') ?>">
    <input type="hidden" name="billing_housenr" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_housenr') ?>">
    <input type="hidden" name="billing_post_code" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_post_code') ?>">
    <input type="hidden" name="billing_city" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_city') ?>">
    <input type="hidden" name="billing_country" value="<?= get_form_field_value($user_data, 'billing_address', 'billing_country') ?>">
    <input type="hidden" name="shipping_first_name" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_first_name') ?>">
    <input type="hidden" name="shipping_last_name" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_last_name') ?>">
    <input type="hidden" name="shipping_company" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_company') ?>">

    <input type="hidden" name="shipping_street" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_street') ?>">
    <input type="hidden" name="shipping_housenr" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_housenr') ?>">
    <input type="hidden" name="shipping_post_code" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_post_code') ?>">
    <input type="hidden" name="shipping_city" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_city') ?>">
    <input type="hidden" name="shipping_country" value="<?= get_form_field_value($user_data, 'shipping_address', 'shipping_country') ?>">


    <input type="hidden" name="email" value="<?= esc($user_data->email ?? '') ?>">
    <input type="hidden" name="phone" value="<?= esc($user_data->phone ?? '') ?>">
    <!-- Add a hidden and checked checkbox for data protection -->
    <input type="hidden" name="post_dataprotection" value="post_dataprotection" checked>
</form>
<script type="text/javascript">
    function submitGoCheckoutForm() {
        document.getElementById('goCheckoutForm').submit();
    }
</script>
