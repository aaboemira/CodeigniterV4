
<div class="container" id="checkout-page">
    <?php if (isset($cartItems['array']) && $cartItems['array'] != null) {  ?>
    <?= purchase_steps(1, 1) ?>

    <div class="container" >
        <div class="row">
            <div class=" col-lg-3 col-md-4 col-xs-12" style="padding-left: 0 !important;margin-bottom: 1.2em; margin-right: 2em">
                <div class="title alone" >
                    <span><?= lang_safe('billing_address') ?></span>
                </div>
                <div class="flex-div">
                    <?= $user_data->billing_first_name .' '.$user_data->billing_last_name  ?>
                    <form action="" method="post">
                        <input type="hidden" name="action" value="change_address">
                        <button class="change_address"><?=lang_safe('change_address')?></button>
                    </form>
                </div>
                <div>
                    <?= $user_data->billing_street . ' ' .$user_data->billing_housenr ?>
                </div>
                <div>
                    <?= $user_data->billing_post_code . ' ' . $user_data->billing_city ?>
                </div>
                <div>
                    <?= $user_data->billing_country ?>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-xs-12" style="padding-left: 0 !important;margin-bottom: 1.2em; ">
                <!-- Right column for delivery address -->
                <div class="title alone" >
                    <span><?= lang_safe('shipping_address') ?></span>
                </div>
                <div class="flex-div">
                    <?= $user_data->shipping_first_name .' '. $user_data->shipping_last_name ?>
                    <form action="" method="post">
                        <input type="hidden" name="action" value="change_address">
                        <button class="change_address"><?=lang_safe('change_address')?></button>
                    </form>
                </div>
                <div>
                    <?= $user_data->shipping_street . ' ' . $user_data->shipping_housenr ?>
                </div>
                <div>
                    <?= $user_data->shipping_post_code. ' ' . $user_data->shipping_city ?>
                </div>
                <div>
                    <?= $user_data->shipping_country ?>
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
        <input type="hidden" name="action" value="go_checkout">
    </form>
        <script type="text/javascript">
            function submitGoCheckoutForm() {
                document.getElementById('goCheckoutForm').submit();
            }
        </script>


