<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/w3.css') ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>"/>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>"/>
    <link href="<?= base_url('assets/templatecss/custom.css') ?>" rel="stylesheet"/>
    <title>Login or Authorization</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background: #fff;
            border-radius: 8px;
        }

        .auth-form {
            margin-top: 20px;
        }

        .form-header {
            text-align: center;
            color: #333;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .authorization-statement {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .form-group {
            margin: 10px 0 10px 0 !important;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .permissions-list {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
        }

        .permissions-list li {
            background: #fafafa;
            border-left: 4px solid #007bff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        .actions .btn-primary, .actions .btn-danger {
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 48%;
            border: none;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .alert {
            padding: 10px;
            background-color: #f44336;
            color: white;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .alert .close {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 20px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .alert .close:hover {
            color: #bbb;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="row">
            <div class="auth-form">
                <div class="text-center">
                    <img class="nav_logo" alt="Brand" src="<?= base_url('jpg/Node_Devices.jpg') ?>" style="max-width: 100%; height:auto; max-height: 100px; margin-bottom: 20px;">
                </div>
                <form class="form-horizontal" method="post" action="<?= site_url('oauthalexa/authorize') ?>">

                    <!-- Error Message -->
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>

                    <!-- Login or Authorization Header -->

                    <p class="authorization-statement">
                        <?= lang_safe('oauth_authorization_statement') ?>
                    </p>

                    <!-- Dynamic Fields for Login or Authorization -->
                    <?php if (!$isLoggedIn): ?>
                        <!-- Login Fields -->
                        <div class="form-group">
                            <label for="email" class="form-label"><?= lang_safe('oauth_email_label') ?></label>
                            <input type="text" class="form-input" id="email" name="email" placeholder="<?= lang_safe('oauth_email_placeholder') ?>">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label"><?= lang_safe('oauth_password_label') ?></label>          
                            <input type="password" class="form-input" id="password" name="password" placeholder="<?= lang_safe('oauth_password_placeholder') ?>">
                        </div>
                        <?php else: ?>
                            <!-- Authorization Fields -->
                            <p><?= lang_safe('oauth_welcome_message') ?></p>
                            <p><?= lang_safe('oauth_signed_in_as') ?> <b><?= esc($username) ?></b> </p>
                            <button type="button" onclick="changeAccount()" class="btn btn-info"><?= lang_safe('oauth_change_account_button') ?></button>
                            <p><?= lang_safe('oauth_account_linking_message') ?></p>
                            <ul class="permissions-list">
                                <li><strong><?= lang_safe('oauth_smart_device_control') ?></strong> <?= lang_safe('oauth_smart_device_control_desc') ?></li>
                                <li><strong><?= lang_safe('oauth_public_user_info') ?></strong> <?= lang_safe('oauth_public_user_info_desc') ?></li>
                            </ul>
                            <p><?= lang_safe('oauth_consent_query') ?></p>
                            <p><a href="https://policies.google.com/privacy"><?= lang_safe('oauth_google_privacy_policy') ?></a></p>
                        <?php endif; ?>

                    <!-- Option to Go Back or Cancel -->
                    <div class="form-group actions">
                        <?php if ($isLoggedIn): ?>
                            <a href=" <?=$redirect_uri."?error=access_denied" ?>" class="btn btn-danger"><?= lang_safe('oauth_cancel_linking_button') ?></a>
                            <button type="submit" class="btn btn-primary"><?=lang_safe('oauth_authorize_button')?></button>
                        <?php else: ?>
                            <button type="submit" class=" login-btn btn btn-primary"><?=lang_safe('oauth_login_button') ?></button>
                            <p><?=lang_safe('oauth_register')?><a href='<?=LANG_URL.'/register'?>' target='_blank'> <?=lang_safe('oauth_register_link')?></a></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($isLoggedIn): ?>
                        <input type="hidden" name="consent" value="yes" required> 
                    <?php endif; ?>
                    <!-- Common Hidden Fields -->
                    <input type="hidden" name="redirect_uri" value="<?= esc($redirect_uri) ?>">
                    <input type="hidden" name="client_id" value="<?= esc($client_id) ?>">
                    <input type="hidden" name="response_type" value="<?= esc($response_type) ?>">
                    <input type="hidden" name="state" value="<?= esc($state) ?>">
                </form>
            </div>
        </div>
    </div>
</body>
<script>

    function changeAccount() {
        const queryParams = new URLSearchParams(window.location.search);

        fetch("<?= site_url('oauthalexa/changeAccount') ?>?" + queryParams, {
            method: 'POST',
            // Additional headers and options as needed
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to update the state (show login form)
                window.location.href = "<?= site_url('oauthalexa/authorize') ?>?" + queryParams;
            } else {
                // Handle errors, show messages, etc.
                console.error('Change account failed');
            }
        })
        .catch(error => console.error('Error:', error));
    }


</script>
</html>
