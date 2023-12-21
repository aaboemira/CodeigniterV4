
<!-- <link rel="stylesheet" href="https://dev.unitymall.in/assets/css/pages/elements.css" /> -->

<div class="container-fluid user-page">
    <div class="row">
            <ol class="breadcrumb">
                <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
                <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
                <li>Reset Password</li>
            </ol>
            <?= view('templates/redlabel/_parts/sidebar'); ?>
            <div class="col-md-9">

            <div class="alone title">
                    <span>
                    <h2><?= lang_safe('reset_password', 'Reset Password') ?></h2>
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
                <?php if(validationError('error')) { ?>
                <div class="alert alert-danger">
                    <?= validationError('error') ?>
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
                                    <label for="enter_current_password">
                                        <?= lang_safe('current_password','Cuurent Password') ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="current_password" class="form-control" id="current_password" value="<?= set_value('current_password') ?>"
                                               placeholder="<?= lang_safe('enter_current_password', 'Enter current password') ?>" required="required" oninput="removeTrailingSpaces(this)" />
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default toggle-password"
                                                data-target="current_password">
                                            <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password">
                                        <?= lang_safe('password') ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="pass" class="form-control" id="pass" value="<?= set_value('pass') ?>"
                                               placeholder="<?= lang_safe('please_enter_password') ?>" required="required" autocomplete="new-password" oninput="removeTrailingSpaces(this)" />
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default toggle-password"
                                                data-target="pass">
                                            <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                        </button>
                                    </span>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="password_repeat">
                                        <?= lang_safe('password_repeat') ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="pass_repeat" class="form-control" id="pass_repeat" value="<?= set_value('pass_repeat') ?>"
                                               placeholder="<?= lang_safe('please_repeat_password') ?>" required="required" autocomplete="new-password"/>
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-default toggle-password"
                                                data-target="pass_repeat">
                                            <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
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