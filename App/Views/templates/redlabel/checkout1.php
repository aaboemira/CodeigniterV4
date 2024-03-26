<div class="container container-sm" id="checkout-page">

    <?php if (isset($cartItems['array']) && $cartItems['array'] != null) {  ?>


    <?= purchase_steps(2, 1) ?>

    <form method="POST" id="goOrder" name="checkout1">
        <input type="hidden" name="user_status" value="<?= session()->has('logged_user') ? 'user' : 'guest' ?>">

        <?php
        if (session('submit_error')) {
            ?>
        <hr>
        <div class="row">
            <div class="alert alert-danger">
                <h4><span class="glyphicon glyphicon-alert"></span> <?= lang_safe('finded_errors') ?></h4>
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
        <div class="row" style="margin: 0 -10px;">
            <?php if (!session()->has('logged_user')): ?>
	
            <div class="title alone">
                <span><?= lang_safe('checkout_contact') ?></span>
            </div>

            <div class="row">
                <div class="col-sm-9 ">
                    <div class="form-group col-sm-6">
                        <label for="emailAddressInput"><?= lang_safe('email_address') ?>
                            <sup><?= lang_safe('required') ?></sup></label>
                        <input id="emailAddressInput" class="form-control"
                            placeholder="<?= lang_safe('email_address') ?>" type="text" name="email"
                            value="<?= session()->has('logged_user') && isset($userData) ? $userData->email : (session()->has('email') ? session()->get('email') : '') ?>">

                    </div>
                    <div class="form-group col-sm-6">
                        <label for="phoneInput"><?= lang_safe('phone') ?> </label>
                        <input id="phoneInput" class="form-control" placeholder="<?= lang_safe('phone') ?>" type="text"
                            name="phone"
                            value="<?= session()->has('logged_user') && isset($userData) ? $userData->phone : (session()->has('phone') ? session()->get('phone') : '') ?>">

                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (session()->has('logged_user')):  ?>
            <input type="hidden" name="email" value="<?= esc($user_data->email ?? '') ?>">
            <input type="hidden" name="phone" value="<?= esc($user_data->phone ?? '') ?>">
            <?php endif; ?>

            <div class="billing-section" id="billing-section">
                <div class="title alone">
                    <br>
                    <span><?= lang_safe('checkout_adress') ?></span>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="firstNameInput"><?= lang_safe('first_name') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="firstNameInput" class="form-control" placeholder="<?= lang_safe('first_name') ?>"
                                type="text" name="billing_first_name"
                                value="<?= get_form_field_value($user_data ?? null, 'billing_address', 'billing_first_name') ?>">

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lastNameInput"><?= lang_safe('last_name') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="lastNameInput" class="form-control" placeholder="<?= lang_safe('first_name') ?>"
                                type="text" name="billing_last_name"
                                value="<?= get_form_field_value($user_data ?? null, 'billing_address', 'billing_last_name') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="companyInput"><?= lang_safe('company') ?></label>
                            <input id="companyInput" class="form-control" placeholder="<?= lang_safe('company') ?>"
                                type="text" name="billing_company"
                                value="<?= get_form_field_value($user_data ?? null, 'billing_address', 'billing_company') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="streetInput"><?= lang_safe('street') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <input id="streetInput" class="form-control" placeholder="<?= lang_safe('street') ?>"
                                type="text" name="billing_street"
                                value="<?= get_form_field_value($user_data ?? null, 'billing_address', 'billing_street') ?>">

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="housenrInput"><?= lang_safe('housenr') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <input id="housenrInput" class="form-control" placeholder="<?= lang_safe('housenr') ?>"
                                type="text" name="billing_housenr"
                                value="<?= get_form_field_value($user_data ?? null, 'billing_address', 'billing_housenr') ?>">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="country"><?= lang_safe('country') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <select size="1" id="country" name="billing_country" class="form-control">
                                <?php foreach ($countries as $countryName): ?>
                                <?php
                                    $currentCountry = get_form_field_value($user_data ?? null, 'billing_address', 'billing_country');
                                    $selected = $countryName === $currentCountry ? 'selected' : '';
                                    ?>
                                <option value="<?= esc($countryName) ?>" <?= $selected ?>><?= esc($countryName) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="postInput"><?= lang_safe('post_code') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="postInput" class="form-control" placeholder="<?= lang_safe('post_code') ?>"
                                type="text" name="billing_post_code"
                                value="<?= get_form_field_value($user_data ?? null, 'billing_address', 'billing_post_code') ?>">

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="cityInput"><?= lang_safe('city') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <input id="cityInput" class="form-control" placeholder="<?= lang_safe('city') ?>"
                                type="text" name="billing_city"
                                value="<?= get_form_field_value($user_data ?? null, 'billing_address', 'billing_city') ?>">

                        </div>
                    </div>
                </div>
            </div>
            <?php if (session()->has('logged_user')): ?>
            <!-- Checkbox to save permanent billing address -->
            <div class="form-group checkboxDiv col-sm-12">
                <label for="saveBillingAddressCheckbox">
                    <input type="checkbox" name="save_billing_address" id="saveBillingAddressCheckbox">
                    Save permanent billing address
                </label>
            </div>

            <?php endif; ?>
            <!-- Checkbox to indicate if shipping address is the same as billing address -->
            <div class="form-group checkboxDiv col-sm-12">
                <label for="sameAddressCheckbox">
                    <input type="checkbox" name="same_address" id="sameAddressCheckbox" checked>
                    <?= lang_safe('shippingIsBilling') ?>
                </label>
                <input type="hidden" id="sameShipping" name="sameShipping" value="true">
            </div>

            <div class="shipping-section" id="shipping-section" style="display: none;">

                <div class="title alone">
                    <br>
                    <span><?= lang_safe('shipping_address') ?></span>
                </div>
                <!-- Shipping address fields -->
                <div class="row ">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="shippingFirstNameInput"> <?= lang_safe('first_name') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <input id="shippingFirstNameInput" class="form-control"
                                placeholder="<?= lang_safe('first_name') ?>" type="text" name="shipping_first_name"
                                value="<?= get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_first_name') ?>">

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingLastNameInput"> <?= lang_safe('last_name') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingLastNameInput" class="form-control"
                                placeholder="<?= lang_safe('last_name') ?>" type="text" name="shipping_last_name"
                                value="<?= get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_last_name') ?>">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="shippingCompanyInput"> <?= lang_safe('company') ?></label>
                            <input id="shippingCompanyInput" class="form-control"
                                placeholder="<?= lang_safe('company') ?>" type="text" name="shipping_company"
                                value="<?= get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_company') ?>">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="shippingStreetInput"> <?= lang_safe('street') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingStreetInput" class="form-control"
                                placeholder="<?= lang_safe('street') ?>" type="text" name="shipping_street"
                                value="<?= get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_street') ?>">

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingHousenrInput"> <?= lang_safe('housenr') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingHousenrInput" class="form-control"
                                placeholder="<?= lang_safe('housenr') ?>" type="text" name="shipping_housenr"
                                value="<?= get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_housenr') ?>">

                        </div>

                        <div class="form-group col-sm-6">
                            <label for="shippingCountry"><?= lang_safe('country') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <select size="1" id="shippingCountry" name="shipping_country" class="form-control">
                                <?php foreach ($countries as $countryName): ?>
                                <?php
                                    $currentShippingCountry = get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_country');
                                    $selected = $countryName === $currentShippingCountry ? 'selected' : '';
                                    ?>
                                <option value="<?= esc($countryName) ?>" <?= $selected ?>><?= esc($countryName) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingPostInput"><?= lang_safe('post_code') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <input id="shippingPostInput" class="form-control"
                                placeholder="<?= lang_safe('post_code') ?>" type="text" name="shipping_post_code"
                                value="<?= get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_post_code') ?>">

                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingCityInput"> <?= lang_safe('city') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingCityInput" class="form-control" placeholder="<?= lang_safe('city') ?>"
                                type="text" name="shipping_city"
                                value="<?= get_form_field_value($user_data ?? null, 'shipping_address', 'shipping_city') ?>">

                        </div>
                    </div>
                </div>
            </div>
            <?php if (session()->has('logged_user')): ?>
            <!-- Checkbox to save permanent shipping address -->
            <div class="form-group checkboxDiv col-sm-12">
                <label for="saveShippingAddressCheckbox">
                    <input type="checkbox" name="save_shipping_address" id="saveShippingAddressCheckbox">
                    Save permanent shipping address
                </label>
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-sm-12 ">
                    <?php if (!session()->has('logged_user')): ?>
                    <div class="form-group col-sm-9">
                        <br><br>
                        <label for="notesInput"><?= lang_safe('notes') ?></label>
                        <textarea id="notesInput" class="form-control" name="notes"
                            rows="3"><?= @$_SESSION['notes'] ?></textarea>
                    </div>
                    <?php endif; ?>

                    <div class="form-group col-sm-12">
                        <label>
                            <br><br><br>
                            <?= lang_safe('dataprotection_contact_accept1') ?>
                            <a
                                href="<?= LANG_URL . '/page/' . "Datenschutz" ?>"><?= lang_safe('dataprotection_contact_accept2') ?></a>

                            <?= lang_safe('dataprotection_contact_accept3') ?>
                            <sup><?= lang_safe('required') ?> </sup>

                        </label>

                        <input style="transform: scale(1.5); margin-left: 10px;" type="checkbox"
                            name="post_dataprotection" id="post_dataprotection" required="required"
                            value="post_dataprotection"
                            <?php if (isset($_SESSION['post_dataprotection'])) echo 'checked="checked"'; ?> />
                    </div>

                    <div class="form-group col-sm-12">
                        <label>
                            <?= lang_safe('agb_accept1') ?>
                            <a href="<?= LANG_URL . '/' . "AGB" ?>"><?= lang_safe('agb_accept2') ?></a>.
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin: 0;">
            <div class="col-sm-12 checkout-buttons">
                <br>
                <br>
                <a class="btn btn-primary btn-new go-checkout w3-right" id="checkoutButton" href="javascript:void(0);">
                    <?= lang_safe('to_checkout2') ?>
                    <i class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="<?= LANG_URL . '/checkout0' ?>" class="btn btn-primary btn-new go-shop">
                    <span class="glyphicon glyphicon-circle-arrow-left"></span>
                    <?= lang_safe('back_to_register') ?>
                </a>
            </div>

        </div>
</div>
</form>

<?php } else { ?>

<div class="container">
    <div class="col-sm-6">
        <div class="alert alert-info">
            <?= lang_safe('empty_cart') ?>
        </div>
    </div>
</div>
<?php } ?>
<script>
$(document).ready(function() {
    // Function to copy billing address to shipping address
    function copyBillingToShipping() {
        $('#shippingFirstNameInput').val($('#firstNameInput').val());
        $('#shippingLastNameInput').val($('#lastNameInput').val());
        $('#shippingCompanyInput').val($('#companyInput').val());
        $('#shippingStreetInput').val($('#streetInput').val());
        $('#shippingHousenrInput').val($('#housenrInput').val());
        $('#shippingCountry').val($('#country').val());
        $('#shippingPostInput').val($('#postInput').val());
        $('#shippingCityInput').val($('#cityInput').val());
    }

    // Function to populate shipping address from user data or session
    function populateShippingFields() {
        <?php if (session()->has('logged_user')): ?>
        $('#shippingFirstNameInput').val('<?= esc($user_data->shipping_first_name ?? '') ?>');
        $('#shippingLastNameInput').val('<?= esc($user_data->shipping_last_name ?? '') ?>');
        $('#shippingCompanyInput').val('<?= esc($user_data->shipping_company ?? '') ?>');
        $('#shippingStreetInput').val('<?= esc($user_data->shipping_street ?? '') ?>');
        $('#shippingHousenrInput').val('<?= esc($user_data->shipping_housenr ?? '') ?>');
        $('#shippingCountry').val('<?= esc($user_data->shipping_country ?? '') ?>');
        $('#shippingPostInput').val('<?= esc($user_data->shipping_post_code ?? '') ?>');
        $('#shippingCityInput').val('<?= esc($user_data->shipping_city ?? '') ?>');

        <?php elseif (isset($_SESSION['shipping_address'])): ?>
        $('#shippingFirstNameInput').val('<?= esc($_SESSION['shipping_address']['first_name'] ?? '') ?>');
        $('#shippingLastNameInput').val('<?= esc($_SESSION['shipping_address']['last_name'] ?? '') ?>');
        $('#shippingCompanyInput').val('<?= esc($_SESSION['shipping_address']['company'] ?? '') ?>');
        $('#shippingStreetInput').val('<?= esc($_SESSION['shipping_address']['street'] ?? '') ?>');
        $('#shippingHousenrInput').val('<?= esc($_SESSION['shipping_address']['housenr'] ?? '') ?>');
        $('#shippingCountry').val('<?= esc($_SESSION['shipping_address']['country'] ?? '') ?>');
        $('#shippingPostInput').val('<?= esc($_SESSION['shipping_address']['post_code'] ?? '') ?>');
        $('#shippingCityInput').val('<?= esc($_SESSION['shipping_address']['city'] ?? '') ?>');

        <?php endif; ?>
    }

    // Function to handle the change event of the checkbox
    function handleCheckboxChange() {
        if ($('#sameAddressCheckbox').is(':checked')) {
            copyBillingToShipping();
            $('.shipping-section').hide();
            $('#sameShipping').val("true");
        } else {
            populateShippingFields();
            $('.shipping-section').show();
            $('#sameShipping').val("false");
        }
    }

    // Initialize the form based on user status and session data
    function initializeForm() {
        <?php if (session()->has('logged_user')): ?>
        $('#sameAddressCheckbox').prop('checked', false);
        <?php elseif (isset($_SESSION['same_address']) && $_SESSION['same_address'] == "false"): ?>
        $('#sameAddressCheckbox').prop('checked', false);

        <?php else: ?>
        $('#sameAddressCheckbox').prop('checked', true);
        copyBillingToShipping();
        <?php endif; ?>
        $('.shipping-section').toggle(!$('#sameAddressCheckbox').is(':checked'));
    }

    initializeForm();
    $('#sameAddressCheckbox').change(handleCheckboxChange);

    $('#checkoutButton').click(function() {
        if ($('#sameAddressCheckbox').prop('checked') == true)
            copyBillingToShipping();
        $('#goOrder').submit();
    });
});
</script>