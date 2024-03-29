<div class="container container-sm checkout2" id="checkout-page">

    <?php
    if (!isset($_SESSION['shipping_address']) || !isset($_SESSION['billing_address'])) {
        // Redirect to checkout1
        header('Location: ' . LANG_URL . '/checkout1');
        exit; // Always call exit after a header redirect
    }
    if (isset($cartItems['array']) && $cartItems['array'] != null) {
    ?>

    <?= purchase_steps(1, 1,1) ?>
    <div class="row">
        <div class="col-sm-9 left-side" style="margin-bottom:0px !important;">
            <form method="POST" id="goOrder">


                <div class="title alone">
                    <span><?= lang_safe('checkout_shipping') ?></span>
                </div>
                <!-- <div class="title alone">
				Session data :  <?php echo session('shipping_type'); ?>
				</div> -->
                <?php if (!empty($shipments)): ?>
                <div class="payment-type-box">
                    <?php 
                        $checked_shipping = false;
                        foreach ($shipments as $index => $shipment): ?>
                    <div class="payment-div">
                        <div class="radio_div" style="margin-bottom: 10px;">
                            <input class="radio_input" type="radio" name="shipping_type"
                                value="<?= $shipment['title'] ?>" data-price="<?= $shipment['price'] ?>" <?php 
                                        // Check if this shipment option is selected or if it's the first option and no option is selected
                                        if ((isset($_SESSION['shipping_type']) && $_SESSION['shipping_type'] == $shipment['title']) || 
                                            (!isset($_SESSION['shipping_type']) && $index == 0)) {
                                            echo 'checked="checked"';
                                            $checked_shipping = true;
                                        }
                                        ?> />
                            <label for="shipping_type"> </label>
                            <span class="radio_label"><?= $shipment['title'] ?> |
                                <?= $shipment['price'] != '' ? number_format($shipment['price'], 2) : 0 ?>
                                <?= CURRENCY ?></span>
                        </div>
                        <?php if (!empty($shipment['description'])): ?>
                        <p class="radio_text"><?= strip_tags($shipment['description'], '<br><strong>') ?></p>
                        <?php endif; ?>
                        <?php if ($shipment['free_shipping_enabled'] == 1 && !$shipment['eligible_for_free_shipping']): ?>
                        <p class="radio_text">
                            <?= lang_safe('shipping_free_add') ?> <?= $shipment['additional_amount_for_free'] ?>
                            <?= lang_safe('shipping_free_text') ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <script>
                $(document).ready(function() {
                    ShowNotificator('alert-info', '<?= lang_safe('no_results') ?>');
                });
                </script>
                <?php endif; ?>
                <!-- pull down  -->
                <div style="margin-top:40px"></div>

                <div class="title alone">
                    <span><?= lang_safe('checkout_payment') ?></span>
                </div>
                <?php $checked_payment = false; ?>
                <div class="payment-type-box">

                    <?php if ($cashondelivery_visibility == 1) { ?>
                    <div class="payment-div">
                        <div class="radio_div" style="margin-bottom: 10px;">
                            <input class="radio_input" type="radio" name="payment_type" id="payment_type"
                                value="cashOnDelivery"
                                <?php if(isset($_SESSION['payment_type'])){ if ($_SESSION['payment_type'] == 'cashOnDelivery') echo 'checked="checked';} else{ if(!$checked_payment){ $checked_payment = true; echo 'checked="checked';}} ?> />
                            <label for="payment_type"> </label>
                            <span class="radio_label"> <?= lang_safe('cash_on_delivery') ?></span>
                        </div>
                        <p class="radio_text">
                            <?= lang_safe('cash_on_delivery_text','Sie zahlen einfach vorab und erhalten die Ware bequem und günstig bei Zahlu ...') ?>
                        </p>
                    </div>
                    <?php } if (filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) { ?>
                    <div class="payment-div">
                        <div class="radio_div" style="margin-bottom: 10px;">
                            <input class="radio_input" type="radio" name="payment_type" id="payment_type" value="PayPal"
                                <?php if(isset($_SESSION['payment_type'])){ if ($_SESSION['payment_type'] == 'PayPal') echo 'checked="checked';} else{ if(!$checked_payment){ $checked_payment = true; echo 'checked="checked';}} ?> />
                            <label for="payment_type"> </label>
                            <span class="radio_label"> <?= lang_safe('paypal') ?></span>
                        </div>
                        <p class="radio_text">
                            <?= lang_safe('paypal_text','Bezahlung per PayPal - einfach, schnell und sicher.') ?></p>
                    </div>
                    <?php } if ($bank_account['iban'] != null) { ?>
                    <div class="payment-div">
                        <div class="radio_div" style="margin-bottom: 10px;">
                            <input class="radio_input" type="radio" name="payment_type" id="payment_type" value="Bank"
                                <?php if(isset($_SESSION['payment_type'])){ if ($_SESSION['payment_type'] == 'Bank') echo 'checked="checked';} else{ if(!$checked_payment){ $checked_payment = true; echo 'checked="checked';}} ?> />
                            <label for="payment_type"> </label>
                            <span class="radio_label"> <?= lang_safe('bank_payment') ?></span>
                        </div>
                        <p class="radio_text">
                            <?= lang_safe('bank_text','Sie zahlen einfach vorab und erhalten die Ware bequem und günstig bei Zahlu ') ?>
                        </p>
                    </div>
                    <?php  } ?>
                </div>

                <input type="hidden" name="selected_shipping_price" id="selected_shipping_price" value="">

                <!-- <div class="align-right max-675-flex-col">
                    <a class="custom-btn text-dark bg-light fw-light p-2 w-40 max-675-w-100 align-left" href="<?= LANG_URL . '/checkout1' ?>"><?= lang_safe('back_to_adressinput') ?> </a>
                    <a class="custom-btn text-light bg-black p-2 w-15 max-675-w-100 go-checkout go-order" onclick="document.getElementById('goOrder').submit();" href="javascript:void(0);"><?= lang_safe('to_checkout3') ?></a>
                </div> -->
        </div>
    </div>
    <div class="row" style="margin: 0;">
        <div class="col-sm-12 checkout-buttons" >
            <br>
            <br>
            <a class="btn btn-primary btn-new go-checkout w3-right"
                onclick="document.getElementById('goOrder').submit();" href="javascript:void(0);">
                <?= lang_safe('to_checkout3') ?>
                <i class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></i>
            </a>
            <a href="<?= LANG_URL . '/checkout1'?>" class="btn btn-primary btn-new go-shop">
                <span class="glyphicon glyphicon-circle-arrow-left"></span>
                <?= lang_safe('back_to_adressinput') ?>
            </a>
        </div>
    </div>
    </div>

</div>
<?php } else { ?>
<div class="alert alert-info"><?= lang_safe('no_products_in_cart') ?></div>
<?php
    }?>
</div>
<?php
if (session('deleted')) {
    ?>
<script>
$(document).ready(function() {
    ShowNotificator('alert-info', '<?= session('deleted') ?>');
});
</script>
<?php } ?>

<script>
$(document).ready(function() {
    // Function to handle radio button changes
    function handleRadioChange() {
        // Get the selected shipment price from the data-price attribute
        var selectedPrice = $('input[name="shipping_type"]:checked').data('price');
        // Update the hidden input field value with the selected price
        $('#selected_shipping_price').val(selectedPrice);
    }

    // Trigger the change event on page load
    handleRadioChange();

    // When a shipping option is selected
    $('input[name="shipping_type"]').on('change', handleRadioChange);
});
</script>