<style>
    .registration-page .panel {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .registration-page .panel-heading {
        font-size: 24px;
        font-weight: bold;
        color: #0056b3;
        background-color: transparent !important;
        margin-bottom: 15px;
    }

    .registration-page h1 {
        font-size: 28px;
        color: #0056b3;
    }

    .registration-page p {
        font-size: 16px;
    }

    .registration-page ul {
        padding-left: 20px;
    }

    .registration-page ul li {
        margin-bottom: 5px;
    }


    .registration-page .btn-container {
        display: flex;
        gap: 10px; /* Space between buttons */
        flex-wrap: wrap; /* Allow wrapping to the next line */
        margin-bottom: 10px;
    }
    .registration-page .btn-container .btn {
        width:48%;
    }

    .registration-page .form-control {
        border-radius: 4px;
        font-size: 16px;
    }


    .registration-page .row {
        margin-left: -15px;
        margin-right: -15px;
    }
</style>

<div class="container registration-page" >
    <?= purchase_steps(1) ?>
    <div class="row">
        <!-- Registration Section -->
        <div class="col-md-6">
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
                <form method="POST"  action="<?= base_url('/checkout1/login') ?>">
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
                                    <input type="email" name="email" class="form-control_email" id="email"
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
                                     style="width:48%;" id="btnContactUs">
                                <?= lang_safe('login') ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>


        </div>

        <div class="col-md-6 registration-section">
            <h1>Anmeldung</h1>

            <div >
                <p>Not a member yet?</p>
                <p>Create an account with us and enjoy a personalized shopping experience. Benefit from a faster checkout process and keep a complete record of all your transactions.</p>
                <p>Register now and take advantage of:</p>
                <ul>
                    <li>Quick and easy checkout</li>
                    <li>Access to your order history and status</li>
                    <li>Exclusive promotions and discounts</li>
                </ul>
                <div class="btn-container">
                    <a class="btn btn-primary btn-new " href="<?= base_url('/register') ?>">Create Account</a>
                    <form method="post" action="<?= base_url('/checkout1') ?>" style="width:48%;">
                        <button style="width: 100%;" type="submit" name="guest_checkout" class="btn btn-default btn-new">Shop as Guest</button>
                    </form>

                </div>
            </div>
        </div>
    </div>