<div class="container" id="checkout-page">

    <?= purchase_steps(1, 2, 3) ?>
    <div class="row">
        <div class="col-sm-9 left-side">
            <form method="POST" id="goOrder">

			
			    <div class="title alone">
                    <span><?= lang_safe('checkout_shipping') ?></span>
                </div>
				<!-- <div class="title alone">
				Session data :  <?php echo session('shipping_type'); ?>
				</div> -->
				<?php

				if (!empty($shipments)) {
				$checked_shipping = false;
				?>
                    <div class="payment-type-box">
                        <?php
                        foreach ($shipments as $shipment) {
                            ?>
                            <div class="radio_div">
                                <input class="radio_input" type="radio" name="shipping_type" id="shipping_type" value="<?= $shipment['title'] ?>" data-price="<?= $shipment['price'] ?>" <?php if(isset($_SESSION['shipping_type'])){ if ($_SESSION['shipping_type'] == $shipment['title']) echo 'checked="checked"';} else{ if(!$checked_shipping){ $checked_shipping = true; echo 'checked="checked"';}} ?> />
                                <label for="shipping_type">  </label>
                                <span class="radio_label">   <?=  $shipment['title'] ?> | <?=  $shipment['price'] != '' ? number_format($shipment['price'], 2) : 0 ?> <?=CURRENCY ?></span>
                            </div>
                            <?php
                        } ?>
                        <input type="hidden" name="selected_shipping_price" id="selected_shipping_price" value="">

                    </div>
			<?php } else {
					?>
					<script>
					$(document).ready(function() {
						ShowNotificator('alert-info', '<?= lang_safe('no_results') ?>');
					});
					</script>
				<?php
				}
				?>
			<!-- pull down  -->
			<div style="margin-top:40px"></div>

				<div class="title alone">
                    <span><?= lang_safe('checkout_payment') ?></span>
                </div>
				<?php $checked_payment = false; ?>
				<div class="payment-type-box">

					<?php if ($cashondelivery_visibility == 1) { ?>
					<div class="radio_div">
						<input class="radio_input" type="radio" name="payment_type" id="payment_type" value="cashOnDelivery" <?php if(isset($_SESSION['payment_type'])){ if ($_SESSION['payment_type'] == 'cashOnDelivery') echo 'checked="checked';} else{ if(!$checked_payment){ $checked_payment = true; echo 'checked="checked';}} ?> />
						<label for="payment_type"> </label>
						<span class="radio_label" >  <?= lang_safe('cash_on_delivery') ?></span>
					</div>
					<?php } if (filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) { ?>
					<div class="radio_div">
						<input class="radio_input" type="radio" name="payment_type" id="payment_type" value="PayPal" <?php if(isset($_SESSION['payment_type'])){ if ($_SESSION['payment_type'] == 'PayPal') echo 'checked="checked';} else{ if(!$checked_payment){ $checked_payment = true; echo 'checked="checked';}} ?> />				
						<label for="payment_type"> </label>
						<span class="radio_label" >  <?= lang_safe('paypal') ?></span>
					</div>
					<?php } if ($bank_account['iban'] != null) { ?>
					<div class="radio_div">
						<input class="radio_input" type="radio" name="payment_type" id="payment_type" value="Bank" <?php if(isset($_SESSION['payment_type'])){ if ($_SESSION['payment_type'] == 'Bank') echo 'checked="checked';} else{ if(!$checked_payment){ $checked_payment = true; echo 'checked="checked';}} ?> />
						<label for="payment_type"> </label>
						<span class="radio_label" >  <?= lang_safe('bank_payment') ?></span>
					</div>
					<?php  } ?>
                </div>


				<!-- <div class="align-right max-675-flex-col">
                    <a class="custom-btn text-dark bg-light fw-light p-2 w-40 max-675-w-100 align-left" href="<?= LANG_URL . '/checkout1' ?>"><?= lang_safe('back_to_adressinput') ?> </a>
                    <a class="custom-btn text-light bg-black p-2 w-15 max-675-w-100 go-checkout go-order" onclick="document.getElementById('goOrder').submit();" href="javascript:void(0);"><?= lang_safe('to_checkout3') ?></a>
                </div> -->

                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 checkout-buttons">
                            <br> 
                            <br> 
                            <a class="btn btn-primary go-checkout w3-right" onclick="document.getElementById('goOrder').submit();" href="javascript:void(0);">
                                <?= lang_safe('to_checkout3') ?>
                                <i class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></i>
                            </a>
                            <a href="<?= LANG_URL . '/checkout1'?>" class="btn btn-primary go-shop">
                                <span class="glyphicon glyphicon-circle-arrow-left"></span>
                                <?= lang_safe('back_to_adressinput') ?>
                            </a>
                         </div>
                    </div>
                </div>
                <div style="margin-top:60px"></div>

				<!-- <div>
					<a href="<?= LANG_URL . '/checkout1'?>" class="btn btn-primary go-shop">
						<span class="glyphicon glyphicon-circle-arrow-left"></span>
						<?= lang_safe('back_to_adressinput') ?>
					</a>
					<a href="javascript:void(0);" class="btn btn-primary go-order"
						onclick="document.getElementById('goOrder').submit();" class="pull-left">
						<?= lang_safe('to_checkout3') ?>
						<span class="glyphicon glyphicon-circle-arrow-right"></span>
					</a>

					<div>
                        <a href="javascript:void(0);" class="add-shipping-to-cart btn-add" data-id="10" >
                            <span class="text-to-bg"><?= lang_safe('add_to_cart') ?></span>
                        </a>
                    </div>

                <div class="clearfix"></div>
            </div> -->
        </div>

    </div>
</div>
<?php
if (session('deleted')) {
    ?>
    <script>
        $(document).ready(function () {
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



