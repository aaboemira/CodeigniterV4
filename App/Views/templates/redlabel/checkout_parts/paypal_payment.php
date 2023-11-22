<div class="container">
    <?php
    $sandbox = '.';
    if ($paypal_sandbox == 1) {
        $sandbox = '.sandbox.';
    }
    if (!empty($cartItems['array'])) {
        ?>

<div class="container">
    <?= purchase_steps(1, 1, 1,1) ?>
    <div class="alert alert-success">
        <?= lang_safe('you_choose_paypal') ?>
    </div>
</div>
    <!-- <div class="row">
        <div class="col-sm-6 col-sm-offset-4">
            <img src="<?=base_url('png/PayPal.png')?>" class="img-responsive paypal-image">
        </div>
    </div> -->
    <!-- <div class="alert alert-info text-center"><?= lang_safe('you_choose_paypal') ?></div> -->
    <hr>
    <form action="https://www<?= $sandbox ?>paypal.com/cgi-bin/webscr" method="post" target="_top"
        class="paypal-form text-center">
        <input type="hidden" name="cmd" value="_cart">
        <input type="hidden" value="<?= $paypal_email ?>" name="business">
        <input type="hidden" name="upload" value="1">
        <?php
            if ($_SESSION['discountAmount'] == '' || $_SESSION['discountAmount'] == 0) {
                $discount = false;
            } else {
                $discount = $_SESSION['discountAmount'] / count($cartItems['array']); // discount for each item
            }
            $i = 1;
        foreach ($cartItems['array'] as $item) {
            ?>
            <input type="hidden" name="item_name_<?= $i ?>" value="<?= $item['title'] ?>">
            <input type="hidden" name="amount_<?= $i ?>" value="<?= $item['price'] ?>">
            <input type="hidden" name="discount_amount_<?= $i ?>" value="<?= $discount !== false ? $discount : 0 ?>">
            <input type="hidden" name="quantity_<?= $i ?>" value="<?= $item['num_added'] ?>">
            <?php
            $i++;
        }

        if (isset($shipping_price)) {
            ?>
            <input type="hidden" name="item_name_<?= $i ?>" value="Shipping">
            <input type="hidden" name="amount_<?= $i ?>" value="<?= $shipping_price ?>">
            <input type="hidden" name="discount_amount_<?= $i ?>" value="0">
            <input type="hidden" name="quantity_<?= $i ?>" value="1">
            <?php
        }
        ?>
        <input type="hidden" name="currency_code" value="<?= CURRENCY_KEY ?>">
        <input type="hidden" value="utf-8" name="charset">
        <input type="hidden" value="<?= base_url('checkout/paypal_success') ?>" name="return">
        <input type="hidden" value="<?= base_url('checkout') ?>" name="cancel_return">
        <input type="hidden" value="authorization" name="paymentaction">
        <a href="<?= base_url('checkout3') ?>" class="btn btn-lg btn-danger btm-10"><?= lang_safe('cancel_payment') ?></a>
        <button type="submit" class="btn btn-lg btn-success btm-10"><?= lang_safe('go_to_paypal') ?> <i
                class="fa fa-cc-paypal" aria-hidden="true"></i></button>
    </form>

    <!-- pull down  -->
    <div style="margin-top:70px"></div>
    <?php
    } else {
        redirect(base_url('shop'));
    }
    ?>
</div>

<!-- Event snippet for Bezahlvorgang starten conversion page -->
<script>
gtag('event', 'conversion', {
    'send_to': 'AW-428847483/z16ECLWjkZEDEPvivswB'
});
</script>