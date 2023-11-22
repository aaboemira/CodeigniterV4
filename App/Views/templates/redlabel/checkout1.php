<div class="container" id="checkout-page">

    <?php if (isset($cartItems['array']) && $cartItems['array'] != null) { ?>
    <?php
    $countries = [
        'Deutschland',
        'Belgien',
        'Bulgarien',
        'Dänemark',
        'Estland',
        'Finnland',
        'Griechenland',
        'Kroatien',
        'Lettland',
        'Litauen',
        'Luxemburg',
        'Malta',
        'Monaco',
        'Niederlande',
        'Österreich',
        'Polen',
        'Portugal',
        'Rumänien',
        'Schweden',
        'Slowakei',
        'Slowenien',
        'Spanien',
        'Tschechische Republik',
        'Ungarn',
        'Zypern',
    ];
    ?>
    <?= purchase_steps(1) ?>

    <form method="POST" id="goOrder">
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
        <div class="row">
            <div class="title alone">
                <span><?= lang_safe('checkout_contact') ?></span>
            </div>
            <div class="row">
                <div class="col-sm-9 ">
                    <div class="form-group col-sm-6">
                        <label for="emailAddressInput"><?= lang_safe('email_address') ?>
                            <sup><?= lang_safe('required') ?></sup></label>
                        <input id="emailAddressInput" class="form-control" name="email"
                               value="<?= @$_SESSION['email'] ?>"
                               type="text" placeholder="<?= lang_safe('email_address') ?>">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="phoneInput"><?= lang_safe('phone') ?> </label>
                        <input id="phoneInput" class="form-control" name="phone" value="<?= @$_SESSION['phone'] ?>"
                               type="text" placeholder="<?= lang_safe('phone') ?>">
                    </div>
                </div>
            </div>
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
                            <input id="firstNameInput" class="form-control" name="billing_first_name"
                                   value="<?= @$_SESSION['billing_address']['first_name'] ?>" type="text"
                                   placeholder="<?= lang_safe('first_name') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lastNameInput"><?= lang_safe('last_name') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="lastNameInput" class="form-control" name="billing_last_name"
                                   value="<?= @$_SESSION['billing_address']['last_name'] ?>" type="text"
                                   placeholder="<?= lang_safe('last_name') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="companyInput"><?= lang_safe('company') ?></label>
                            <input id="companyInput" class="form-control" name="billing_company"
                                   value="<?= @$_SESSION['billing_address']['company'] ?>"
                                   type="text" placeholder="<?= lang_safe('company') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="streetInput"><?= lang_safe('street') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="streetInput" class="form-control" name="billing_street"
                                   value="<?= @$_SESSION['billing_address']['street'] ?>"
                                   type="text" placeholder="<?= lang_safe('street') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="housenrInput"><?= lang_safe('housenr') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="housenrInput" class="form-control" name="billing_housenr"
                                   value="<?= @$_SESSION['billing_address']['housenr'] ?>"
                                   type="text" placeholder="<?= lang_safe('housenr') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="country"><?= lang_safe('country') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <select size="1" id="country" name="billing_country" class="form-control">
                                <?php
                                foreach ($countries as $countryName) {
                                    $selected = isset($_SESSION['billing_address']['country']) && $_SESSION['billing_address']['country'] == $countryName ? 'selected' : '';
                                    echo "<option value=\"$countryName\" $selected>$countryName</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="postInput"><?= lang_safe('post_code') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="postInput" class="form-control" name="billing_post_code"
                                   value="<?= @$_SESSION['billing_address']['post_code'] ?>"
                                   type="text" placeholder="<?= lang_safe('post_code') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="cityInput"><?= lang_safe('city') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="cityInput" class="form-control" name="billing_city"
                                   value="<?= @$_SESSION['billing_address']['city'] ?>"
                                   type="text" placeholder="<?= lang_safe('city') ?>">
                        </div>
                    </div>
                </div>
            </div>
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
                            <input id="shippingFirstNameInput" class="form-control" name="shipping_first_name"
                                    type="text"
                                   placeholder="<?= lang_safe('first_name') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingLastNameInput"> <?= lang_safe('last_name') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingLastNameInput" class="form-control" name="shipping_last_name"
                                   type="text"
                                   placeholder="<?= lang_safe('last_name') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="shippingCompanyInput"> <?= lang_safe('company') ?></label>
                            <input id="shippingCompanyInput" class="form-control" name="shipping_company"

                                   type="text" placeholder="<?= lang_safe('company') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group col-sm-6">
                            <label for="shippingStreetInput"> <?= lang_safe('street') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingStreetInput" class="form-control" name="shipping_street"

                                   type="text" placeholder="<?= lang_safe('street') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingHousenrInput"> <?= lang_safe('housenr') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingHousenrInput" class="form-control" name="shipping_housenr"

                                   type="text" placeholder="<?= lang_safe('housenr') ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="shippingCountry"><?= lang_safe('country') ?>
                                <sup><?= lang_safe('required') ?></sup></label>

                            <select size="1" id="shippingCountry" name="shipping_country" class="form-control">
                                <?php
                                foreach ($countries as $countryName) {
                                    $selected = isset($_SESSION['shipping_address']['country']) && $_SESSION['shipping_address']['country'] == $countryName ? 'selected' : '';
                                    echo "<option value=\"$countryName\" $selected >$countryName</option>";                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingPostInput"><?= lang_safe('post_code') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingPostInput" class="form-control" name="shipping_post_code"

                                   type="text" placeholder="<?= lang_safe('post_code') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="shippingCityInput"> <?= lang_safe('city') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="shippingCityInput" class="form-control" name="shipping_city"

                                   type="text" placeholder="<?= lang_safe('city') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 ">
                    <div class="form-group col-sm-9">
                        <br><br>
                        <label for="notesInput"><?= lang_safe('notes') ?></label>
                        <textarea id="notesInput" class="form-control" name="notes"
                                  rows="3"><?= @$_SESSION['notes'] ?></textarea>
                    </div>

                    <div class="form-group col-sm-12">
                        <label>
                            <br><br><br>
                            <?= lang_safe('dataprotection_contact_accept1') ?>
                            <a href="<?= LANG_URL . '/page/' . "Datenschutz" ?>"><?= lang_safe('dataprotection_contact_accept2') ?></a>

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
            <div class="row">
                <div class="col-sm-12 checkout-buttons">
                    <br>
                    <br>
                    <a class="btn btn-primary btn-new go-checkout w3-right" id="checkoutButton"
                       href="javascript:void(0);">
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
</div>
</form>
<?php } else { ?>
    <div class="empty-cart">
        <span><?= lang_safe('empty_cart') ?></span>
    </div>
<?php } ?>
</div>

<script>
    $(document).ready(function () {
        if ($('#shippingCountry').val() === '') {
            // Set the default value to "Deutschland"
            $('#shippingCountry').val('Deutschland');
            console.log("iam here")
        }
        // Function to hide the shipping address section
        function hideShippingAddressSection() {
            $('.shipping-section').hide();
        }

        // Function to show the shipping address section
        function showShippingAddressSection() {
            $('.shipping-section').show();
        }

        $('#sameAddressCheckbox').change(function () {
            handleCopyBillingToShipping()
        });
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
        function populateShippingFromSession() {
            $('#shippingFirstNameInput').val('<?= isset($_SESSION['shipping_address']['first_name']) ? $_SESSION['shipping_address']['first_name'] : '' ?>');
            $('#shippingLastNameInput').val('<?= isset($_SESSION['shipping_address']['last_name']) ? $_SESSION['shipping_address']['last_name'] : '' ?>');
            $('#shippingCompanyInput').val('<?= isset($_SESSION['shipping_address']['company']) ? $_SESSION['shipping_address']['company'] : '' ?>');
            $('#shippingStreetInput').val('<?= isset($_SESSION['shipping_address']['street']) ? $_SESSION['shipping_address']['street'] : '' ?>');
            $('#shippingHousenrInput').val('<?= isset($_SESSION['shipping_address']['housenr']) ? $_SESSION['shipping_address']['housenr'] : '' ?>');
            $('#shippingCountry').val('<?= isset($_SESSION['shipping_address']['country']) ? $_SESSION['shipping_address']['country'] : '' ?>');
            $('#shippingPostInput').val('<?= isset($_SESSION['shipping_address']['post_code']) ? $_SESSION['shipping_address']['post_code'] : '' ?>');
            $('#shippingCityInput').val('<?= isset($_SESSION['shipping_address']['city']) ? $_SESSION['shipping_address']['city'] : '' ?>');
        }
        function clearShippingFields() {
            $('#shippingFirstNameInput').val('');
            $('#shippingLastNameInput').val('');
            $('#shippingCompanyInput').val('');
            $('#shippingStreetInput').val('');
            $('#shippingHousenrInput').val('');
            $('#shippingPostInput').val('');
            $('#shippingCityInput').val('');
        }
        $('#checkoutButton').click(function () {
            if ($('#sameAddressCheckbox').is(':checked')) {
                copyBillingToShipping();
            }
            $('#country').val()
            $('#goOrder').submit();
        });
        function handleCopyBillingToShipping() {
            if ($('#sameAddressCheckbox').is(':checked')) {
                copyBillingToShipping();
                hideShippingAddressSection();
                $('#sameShipping').val("true");

            } else {
                <?php if(isset($_SESSION['same_address']) && $_SESSION['same_address'] == "false") { ?>
                populateShippingFromSession();
                <?php } else { ?>
                clearShippingFields();
                <?php } ?>
                showShippingAddressSection();
                $('#sameShipping').val("false");
            }
        }
    });
</script>
