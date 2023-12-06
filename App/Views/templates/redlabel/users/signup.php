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
<div id="register">
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <div class="alone title">
                    <span>
                        <?= lang_safe('user_login') ?>
                    </span>
                </div>

                <p style="font-size:16px;">
                    <?= lang_safe('contact_us_text') ?>
                </p>
                <?php if (validationError('loginError')) { ?>
                    <div class="alert alert-danger">
                        <?= validationError('loginError') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="well well-sm">
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="email">
                                        <?= lang_safe('email_address') ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-envelope"></span>
                                        </span>
                                        <input type="email" name="email" class="form-control_email" id="email_login"
                                               value="<?= set_value('email') ?>"
                                               placeholder="<?= lang_safe('enter_email') ?>" required="required"
                                               style="width:100%"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password">
                                        <?= lang_safe('password') ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="pass" class="form-control" id="password_login"
                                               value="<?= set_value('pass') ?>"
                                               placeholder="<?= lang_safe('please_enter_password') ?>" required="required"/>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default toggle-password" type="button"
                                                    data-target="password_login">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 0.5em">
                                <a href="password/recover">
                                    <?= lang_safe('forget_password') ?>
                                </a>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="login" class="btn btn-primary btn-new pull-left"
                                        >
                                    <?= lang_safe('login') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>

            <div class="col-md-8 register">
                <div class="alone title">
                    <span>
                        <?= lang_safe('user_register') ?>
                    </span>
                </div>

                <p style="font-size:16px;">
                    <?= lang_safe('contact_us_text') ?>
                </p>

                <?php
                if (session('resultSend')) {
                    ?>
                    <hr>
                    <div class="alert alert-success">
                        <?= session('resultSend') ?>
                    </div>
                    <hr>
                <?php }
                ?>
                <?php if (session('success')) { ?>
                    <div class="alert alert-success">
                        <?= session('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <?php
                if (session('register_errors')) {
                    ?>
                    <hr>
                    <div class="row">
                        <div class="alert alert-danger">
                            <h4><span class="glyphicon glyphicon-alert"></span> <?= lang_safe('finded_errors') ?></h4>
                            <?php
                            foreach (session('register_errors') as $error) {
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
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="account_type"><?= lang_safe('account_type') ?></label>
                                    <div class="form-check">
                                        <input type="radio" name="account_type" id="private" value="private"
                                               class="form-check-input" checked>
                                        <label for="private" class="form-check-label"
                                               style="margin-right:3em;"><?= lang_safe('private') ?></label>
                                        <input type="radio" name="account_type" id="business" value="business"
                                               class="form-check-input">
                                        <label for="business"
                                               class="form-check-label"><?= lang_safe('business') ?></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="vorname"><?= lang_safe('first_name') ?> *</label>
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                   value="<?= set_value('first_name') ?>"
                                                   placeholder="<?= lang_safe('first_name') ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nachname"><?= lang_safe('last_name') ?> *</label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                   value="<?= set_value('last_name') ?>"
                                                   placeholder="<?= lang_safe('last_name') ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">
                                        <?= lang_safe('email_address') ?> *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-envelope"></span>
                                        </span>
                                        <input type="text" name="email" class="form-control_email" id="email_register"
                                               value="<?= set_value('email') ?>"
                                               placeholder="<?= lang_safe('enter_email') ?>" =""
                                        style="width: 100%"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">
                                        <?= lang_safe('phone') ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-phone"></span>
                                        </span>
                                        <input type="text" name="phone" class="form-control_email"
                                               value="<?= set_value('phone') ?>"
                                               placeholder="<?= lang_safe('please_enter_phone') ?>"
                                        style="width: 100%"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">
                                        <?= lang_safe('mobile') ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                    class="glyphicon glyphicon-phone"></span>
                                        </span>
                                        <input type="text" name="mobile" class="form-control_email"
                                               value="<?= set_value('mobile') ?>"
                                               placeholder="<?= lang_safe('please_enter_mobile') ?>"
                                        style="width: 100%"/>
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group">
                                    <label for="adresse">Adresse</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="strasse">Straße *</label>
                                                <input type="text" name="street" id="street" class="form-control"
                                                       value="<?= set_value('street') ?>"
                                                       placeholder="<?= lang_safe('street') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hausnummer"><?= lang_safe("housenr") ?> *</label>
                                                <input type="text" name="housenr" id="housenr"
                                                       value="<?= set_value('housenr') ?>"
                                                       placeholder="<?= lang_safe('housenr') ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country"><?= lang_safe('country') ?> *</label>
                                                <select size="1" id="country" name="country" class="form-control">
                                                    <?php
                                                    foreach ($countries as $countryName) {
                                                        echo "<option value=\"$countryName\" >$countryName</option>";
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
                                                            echo "<option value=\"$languageKey\">$languageName</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="plz"><?= lang_safe("post_code") ?> *</label>
                                                <input type="text" name="post_code" id="post_code" class="form-control"
                                                       value="<?= set_value('post_code') ?>"
                                                       placeholder="<?= lang_safe('post_code') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city"><?= lang_safe("city") ?> *</label>
                                                <input type="text" name="city" id="city" class="form-control"
                                                       value="<?= set_value('city') ?>"
                                                       placeholder="<?= lang_safe('city') ?>">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_register"><?= lang_safe('password') ?> *</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="pass"
                                                       id="password_register"
                                                       value="<?= set_value('pass') ?>"
                                                       placeholder="<?= lang_safe('please_enter_password') ?>"autocomplete
                                                       />
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default toggle-password" type="button"
                                                            data-target="password_register">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_repeat">
                                                <?= lang_safe('password_repeat') ?> *
                                            </label>
                                            <div class="input-group">
                                                <input type="password" name="pass_repeat" class="form-control"
                                                       id="password_repeat"
                                                       value="<?= set_value('pass_repeat') ?>"
                                                       placeholder="<?= lang_safe('please_repeat_password') ?>" autocomplete
                                                />
                                                <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default toggle-password"
                                                                data-target="password_repeat">
                                                            <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                                        </button>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="checkbox" id="subscribe_newsletter"
                                           name="subscribe_newsletter" <?php if (isset($_POST['subscribe_newsletter']) && $_POST['subscribe_newsletter'] == 'on') echo 'checked'; ?>>
                                    <label class="form-check-label" for="subscribe_newsletter">
                                        <?= lang_safe('newsletter_agreement') ?>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="checkbox"
                                               name="data_processing_agreement" <?php if (isset($_POST['data_processing_agreement']) && $_POST['data_processing_agreement'] == 'on') echo 'checked'; ?>>
                                        <?= lang_safe('data_process_agreement') ?>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <img alt="Verification code" id="captcha" src="">
                                    <button type="button" id="refreshCaptcha" class="btn btn-secondary">
                                        <i class="fa fa-refresh"></i> <!-- This is the Font Awesome refresh icon -->
                                    </button>

                                </div>
                                <div class="form-group">
                                    <label for="capcha">
                                        <?= lang_safe('capcha', 'Please enter captcha') ?>
                                    </label>
                                    <input type="text" name="code" class="form-control"
                                           placeholder="<?= lang_safe('please_enter_capcha') ?>" =""/>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="signup" class="btn btn-primary btn-new pull-left"
                                        >
                                    <?= lang_safe('register_me') ?>
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

    fetch('<?= base_url('captcha') ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('captcha').setAttribute('src', data.image);
        })
    document.addEventListener('DOMContentLoaded', function () {
        // Function to refresh the captcha image
        function refreshCaptcha() {
            fetch('<?= base_url('captcha') ?>')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha').setAttribute('src', data.image);
                })
                .catch(error => console.error('Error refreshing captcha:', error));
        }

        // Add click event for the "Refresh" button
        const refreshButton = document.getElementById('refreshCaptcha');
        refreshButton.addEventListener('click', refreshCaptcha);
    });
</script>

