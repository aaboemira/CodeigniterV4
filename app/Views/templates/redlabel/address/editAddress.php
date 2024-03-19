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
            <div class="well well-sm">
            <form method="POST" action="<?= base_url('/address/update') ?>" id="updateAddresses" name="addressesForm">
                <div id="user-section">
                    <div class="title alone">
                        <span>
                            <?= lang_safe('user_address') ?>
                        </span>
                    </div>
                    <div class="row">
                        <!-- User Address Form Inputs -->
                        <div class="form-group col-sm-6">
                            <label for="userFirstNameInput">
                                <?= lang_safe('first_name') ?><sup>
                                    <?= lang_safe('required') ?>
                                </sup>
                            </label>
                            <input id="userFirstNameInput" class="form-control"
                                placeholder="<?= lang_safe('first_name') ?>" type="text" name="user_first_name"
                                value="<?= isset($userAddresses['user_address_first_name']) ? esc($userAddresses['user_address_first_name']) : '' ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="userLastNameInput">
                                <?= lang_safe('last_name') ?><sup>
                                    <?= lang_safe('required') ?>
                                </sup>
                            </label>
                            <input id="userLastNameInput" class="form-control"
                                placeholder="<?= lang_safe('last_name') ?>" type="text" name="user_last_name"
                                value="<?= isset($userAddresses['user_address_last_name']) ? esc($userAddresses['user_address_last_name']) : '' ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="userStreetInput">
                                <?= lang_safe('street') ?><sup>
                                    <?= lang_safe('required') ?>
                                </sup>
                            </label>
                            <input id="userStreetInput" class="form-control"
                                placeholder="<?= lang_safe('street') ?>" type="text" name="user_street"
                                value="<?= isset($userAddresses['user_address_street']) ? esc($userAddresses['user_address_street']) : '' ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="userHousenrInput">
                                <?= lang_safe('housenr') ?><sup>
                                    <?= lang_safe('required') ?>
                                </sup>
                            </label>
                            <input id="userHousenrInput" class="form-control"
                                placeholder="<?= lang_safe('housenr') ?>" type="text" name="user_housenr"
                                value="<?= isset($userAddresses['user_address_housenr']) ? esc($userAddresses['user_address_housenr']) : '' ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="userCountry"><?= lang_safe('country') ?><sup><?= lang_safe('required') ?></sup></label>
                            <select size="1" id="userCountry" name="user_country" class="form-control">
                                <?php foreach ($countries as $countryName): ?>
                                    <option value="<?= esc($countryName) ?>" <?= isset($userAddresses['user_address_country']) && $userAddresses['user_address_country'] === $countryName ? 'selected' : '' ?>>
                                        <?= esc($countryName) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="userPostInput">
                                <?= lang_safe('post_code') ?><sup>
                                    <?= lang_safe('required') ?>
                                </sup>
                            </label>
                            <input id="userPostInput" class="form-control"
                                placeholder="<?= lang_safe('post_code') ?>" type="text" name="user_post_code"
                                value="<?= isset($userAddresses['user_address_post_code']) ? esc($userAddresses['user_address_post_code']) : '' ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="userCityInput">
                                <?= lang_safe('city') ?><sup>
                                    <?= lang_safe('required') ?>
                                </sup>
                            </label>
                            <input id="userCityInput" class="form-control"
                                placeholder="<?= lang_safe('city') ?>" type="text" name="user_city"
                                value="<?= isset($userAddresses['user_address_city']) ? esc($userAddresses['user_address_city']) : '' ?>">
                        </div>
                    </div>
                </div>
                    
                    <div id="billing-section">
                        <div class="title alone">
                            <span>
                                <?= lang_safe('billing_address') ?>
                            </span>
                        </div>
                        <div class="row">
                            <!-- Billing Address Form Inputs -->
                            <div class="form-group col-sm-6">
                                <label for="billingFirstNameInput">
                                    <?= lang_safe('first_name') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="billingFirstNameInput" class="form-control"
                                    placeholder="<?= lang_safe('first_name') ?>" type="text" name="billing_first_name"
                                    value="<?= isset($userAddresses['billing_first_name']) ? esc($userAddresses['billing_first_name']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="billingLastNameInput">
                                    <?= lang_safe('last_name') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="billingLastNameInput" class="form-control"
                                    placeholder="<?= lang_safe('last_name') ?>" type="text" name="billing_last_name"
                                    value="<?= isset($userAddresses['billing_last_name']) ? esc($userAddresses['billing_last_name']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="billingCompanyInput">
                                    <?= lang_safe('company') ?>
                                </label>
                                <input id="billingCompanyInput" class="form-control"
                                    placeholder="<?= lang_safe('company') ?>" type="text" name="billing_company"
                                    value="<?= isset($userAddresses['billing_company']) ? esc($userAddresses['billing_company']) : '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="billingStreetInput">
                                    <?= lang_safe('street') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="billingStreetInput" class="form-control"
                                    placeholder="<?= lang_safe('street') ?>" type="text" name="billing_street"
                                    value="<?= isset($userAddresses['billing_street']) ? esc($userAddresses['billing_street']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="billingHousenrInput">
                                    <?= lang_safe('housenr') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="billingHousenrInput" class="form-control"
                                    placeholder="<?= lang_safe('housenr') ?>" type="text" name="billing_housenr"
                                    value="<?= isset($userAddresses['billing_housenr']) ? esc($userAddresses['billing_housenr']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="billingCountry"><?= lang_safe('country') ?><sup><?= lang_safe('required') ?></sup></label>
                                <select size="1" id="billingCountry" name="billing_country" class="form-control">
                                    <?php foreach ($countries as $countryName): ?>
                                        <option value="<?= esc($countryName) ?>" <?= isset($userAddresses['billing_country']) && $userAddresses['billing_country'] === $countryName ? 'selected' : '' ?>>
                                            <?= esc($countryName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="billingPostInput">
                                    <?= lang_safe('post_code') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="billingPostInput" class="form-control"
                                    placeholder="<?= lang_safe('post_code') ?>" type="text" name="billing_post_code"
                                    value="<?= isset($userAddresses['billing_post_code']) ? esc($userAddresses['billing_post_code']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="billingCityInput">
                                    <?= lang_safe('city') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="billingCityInput" class="form-control" placeholder="<?= lang_safe('city') ?>"
                                    type="text" name="billing_city"
                                    value="<?= isset($userAddresses['billing_city']) ? esc($userAddresses['billing_city']) : '' ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address Section -->

                    <div id="shipping-section">
                        <div class="title alone">
                            <span>
                                <?= lang_safe('shipping_address') ?>
                            </span>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="shippingFirstNameInput">
                                    <?= lang_safe('first_name') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="shippingFirstNameInput" class="form-control"
                                    placeholder="<?= lang_safe('first_name') ?>" type="text" name="shipping_first_name"
                                    value="<?= isset($userAddresses['shipping_first_name']) ? esc($userAddresses['shipping_first_name']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="shippingLastNameInput">
                                    <?= lang_safe('last_name') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="shippingLastNameInput" class="form-control"
                                    placeholder="<?= lang_safe('last_name') ?>" type="text" name="shipping_last_name"
                                    value="<?= isset($userAddresses['shipping_last_name']) ? esc($userAddresses['shipping_last_name']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="shippingCompanyInput">
                                    <?= lang_safe('company') ?>
                                </label>
                                <input id="shippingCompanyInput" class="form-control"
                                    placeholder="<?= lang_safe('company') ?>" type="text" name="shipping_company"
                                    value="<?= isset($userAddresses['shipping_company']) ? esc($userAddresses['shipping_company']) : '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="shippingStreetInput">
                                    <?= lang_safe('street') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="shippingStreetInput" class="form-control"
                                    placeholder="<?= lang_safe('street') ?>" type="text" name="shipping_street"
                                    value="<?= isset($userAddresses['shipping_street']) ? esc($userAddresses['shipping_street']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="shippingHousenrInput">
                                    <?= lang_safe('housenr') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="shippingHousenrInput" class="form-control"
                                    placeholder="<?= lang_safe('housenr') ?>" type="text" name="shipping_housenr"
                                    value="<?= isset($userAddresses['shipping_housenr']) ? esc($userAddresses['shipping_housenr']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="shippingCountry"><?= lang_safe('country') ?><sup><?= lang_safe('required') ?></sup></label>
                                <select size="1" id="shippingCountry" name="shipping_country" class="form-control">
                                    <?php foreach ($countries as $countryName): ?>
                                        <option value="<?= esc($countryName) ?>" <?= isset($userAddresses['shipping_country']) && $userAddresses['shipping_country'] === $countryName ? 'selected' : '' ?>>
                                            <?= esc($countryName) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="shippingPostInput">
                                    <?= lang_safe('post_code') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="shippingPostInput" class="form-control"
                                    placeholder="<?= lang_safe('post_code') ?>" type="text" name="shipping_post_code"
                                    value="<?= isset($userAddresses['shipping_post_code']) ? esc($userAddresses['shipping_post_code']) : '' ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="shippingCityInput">
                                    <?= lang_safe('city') ?><sup>
                                        <?= lang_safe('required') ?>
                                    </sup>
                                </label>
                                <input id="shippingCityInput" class="form-control"
                                    placeholder="<?= lang_safe('city') ?>" type="text" name="shipping_city"
                                    value="<?= isset($userAddresses['shipping_city']) ? esc($userAddresses['shipping_city']) : '' ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-new">
                                <?= lang_safe('update_addresses') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
