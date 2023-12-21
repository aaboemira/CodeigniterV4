<?php
$countries = [
    'Deutschland',
    'Belgien',
    'Bulgarien',
    'Danemark',
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
    'Osterreich',
    'Polen',
    'Portugal',
    'Rumanien',
    'Schweden',
    'Slowakei',
    'Slowenien',
    'Spanien',
    'Tschechische Republik',
    'Ungarn',
    'Zypern',
];

$languages = [
    'deu' => 'Deutsch',
    'eng' => 'Englisch',
    'nld' => 'Nederlands',
    'fra' => 'Francais',
    'ita' => 'Italiano',
    'spa' => 'Español',
    'tur' => 'Turkce',
    'rus' => 'Russian',
    'zho' => 'Chinese',
    'lit' => 'Lietuvos',
    'ukr' => 'Ukrainian',
    'pol' => 'Polski',
    'por' => 'Portugal',
    'kor' => 'Korea',
    'lav' => 'Lettland',
    'est' => 'Estland',
];


?>
<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
            <li><?= lang_safe('my_acc') ?></li>

        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>
        <div class="col-md-9">
            <div class="alone title">
                <span>
                    <h2><?= lang_safe('my_acc') ?></h2>
                </span>
            </div>

            <!-- Success and Error Messages -->
            <?php if (session('success')) { ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if (session('error')) { ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if (validationError('registerError')) { ?>
                <div class="alert alert-danger">
                    <?= validationError('registerError') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>

            <!-- Account Update Form -->
            <div class="well well-sm">
                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Account Type -->

                            <div class="form-group">
                                <label for="account_type"><?= lang_safe('account_type') ?></label>
                                <div class="form-check">
                                    <input type="radio" name="account_type" id="private" value="private"
                                           class="form-check-input" <?= $userInfo['account_type'] == 'private' ? 'checked' : ''; ?> disabled>
                                    <label for="private" class="form-check-label"
                                           style="margin-right:3em;"><?= lang_safe('private') ?></label>
                                    <input type="radio" name="account_type" id="business" value="business"
                                           class="form-check-input" <?= $userInfo['account_type'] == 'business' ? 'checked' : ''; ?> disabled>
                                    <label for="business" class="form-check-label"><?= lang_safe('business') ?></label>
                                </div>
                            </div>


                            <!-- Name Fields -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="first_name"><?= lang_safe('first_name') ?> *</label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                               value="<?= set_value('first_name', $userInfo['first_name']) ?>"
                                               placeholder="<?= lang_safe('first_name') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name"><?= lang_safe('last_name') ?> *</label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                               value="<?= set_value('last_name', $userInfo['last_name']) ?>"
                                               placeholder="<?= lang_safe('last_name') ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="form-group">
                                <label for="email">
                                    <?= lang_safe('email_address') ?> *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                    <input type="text" name="email" class="form-control" id="email"
                                           value="<?= set_value('email', $userInfo['email']) ?>"
                                           placeholder="<?= lang_safe('enter_email') ?>" style="width: 100%" readonly />
                                </div>
                            </div>

                            <!-- Phone Field -->
                            <div class="form-group">
                                <label for="phone">
                                    <?= lang_safe('phone') ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                                    <input type="text" name="phone" class="form-control" id="phone"
                                           value="<?= set_value('phone', $userInfo['phone']) ?>"
                                           placeholder="<?= lang_safe('please_enter_phone') ?>" style="width: 100%" />
                                </div>
                            </div>

                            <!-- Mobile Field -->
                            <div class="form-group">
                                <label for="mobile">
                                    <?= lang_safe('mobile') ?>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                                    <input type="text" name="mobile" class="form-control" id="mobile"
                                           value="<?= set_value('mobile', $userInfo['mobile']) ?>"
                                           placeholder="<?= lang_safe('please_enter_mobile') ?>" style="width: 100%" />
                                </div>
                            </div>

                            <hr>
                            <div class="form-group">
                                <label for="adresse">Adresse</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="street">Straße *</label>
                                            <input type="text" name="street" id="street" class="form-control"
                                                   value="<?= set_value('street', $userInfo['billing_street']) ?>"
                                                   placeholder="<?= lang_safe('street') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="housenr"><?= lang_safe("housenr") ?> *</label>
                                            <input type="text" name="housenr" id="housenr"
                                                   value="<?= set_value('housenr', $userInfo['billing_housenr']) ?>"
                                                   placeholder="<?= lang_safe('housenr') ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country"><?= lang_safe('country') ?> *</label>
                                            <select size="1" id="country" name="country" class="form-control">
                                                <?php
                                                foreach ($countries as $countryName) {
                                                    $selected = ($countryName == $userInfo['billing_country']) ? 'selected' : '';
                                                    echo "<option value=\"$countryName\" $selected>$countryName</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="language"><?= lang_safe('lang') ?> *</label>
                                            <select size="1" id="language" name="language" class="form-control">
                                                <?php
                                                foreach ($languages as $languageKey => $languageName) {
                                                    $selected = ($languageKey == $userInfo['lang']) ? 'selected' : '';
                                                    echo "<option value=\"$languageKey\" $selected>$languageName</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="post_code"><?= lang_safe("post_code") ?> *</label>
                                            <input type="text" name="post_code" id="post_code" class="form-control"
                                                   value="<?= set_value('post_code', $userInfo['billing_post_code']) ?>"
                                                   placeholder="<?= lang_safe('post_code') ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city"><?= lang_safe("city") ?> *</label>
                                            <input type="text" name="city" id="city" class="form-control"
                                                   value="<?= set_value('city', $userInfo['billing_city']) ?>"
                                                   placeholder="<?= lang_safe('city') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <!-- Change Password Button -->
                            <div class="form-group">
                                <button type="button" id="change_password_button" class="btn btn-secondary"><?= lang_safe('change_password') ?></button>
                                <input type="hidden" id="change_password_flag" name="change_password_flag" value="0">
                            </div>

                            <!-- Password Fields, initially hidden -->
                            <div id="password_fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="current_password"><?= lang_safe('current_password') ?></label>
                                            <div class="input-group">
                                            <input type="password" class="form-control" name="current_password"
                                                   id="current_password"
                                                   placeholder="<?= lang_safe('enter_current_password') ?>" autocomplete="new-password" oninput="removeTrailingSpaces(this)" />
                                            <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default toggle-password"
                                                                data-target="current_password">
                                                            <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                                        </button>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="new_password"><?= lang_safe('new_password') ?></label>
                                            <div class="input-group">
                                            <input type="password" class="form-control" name="new_password"
                                                   id="new_password"
                                                   placeholder="<?= lang_safe('enter_new_password') ?>" autocomplete="new-password" oninput="removeTrailingSpaces(this)" />
                                            <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default toggle-password"
                                                                data-target="new_password">
                                                            <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                                        </button>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <p>To close your account and delete your data, please
                                <a href="<?= base_url('/account/delete'); ?>"  onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">click here</a>
                                .
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" name="update" class="btn btn-new pull-left" id="btnContactUs">
                                    <?= lang_safe('update') ?>
                                </button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    document.getElementById('change_password_button').addEventListener('click', function() {
        var passwordFields = document.getElementById('password_fields');
        var changePasswordFlag = document.getElementById('change_password_flag');
        if (passwordFields.style.display === 'none') {
            passwordFields.style.display = 'block';
            changePasswordFlag.value = '1';
        } else {
            passwordFields.style.display = 'none';
            changePasswordFlag.value = '0';
        }
    });
</script>
