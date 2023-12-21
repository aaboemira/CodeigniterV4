<div class="container-fluid user-page">
    <div class="row custom-center">
        <div class="col-md-5 col-sm-9">
            <div class="alone title">
                <span>
                    <h2><?= lang_safe('password_recover', 'password_recover') ?></h2>
                </span>
            </div>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } else { // No error, show the regular content ?>
                <?php if (session('success')) { ?>
                    <div class="alert alert-success">
                        <?= session('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <?php if (validationError('error')) { ?>
                    <div class="alert alert-danger">
                        <?= validationError('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                <?php } ?>
                <div>
                    <p>
                        <?= lang_safe("password_recover_info") ?>
                    </p>
                </div>
                <div class="well well-sm">
                    <form method="POST"  id="password_reset">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="password">
                                        <?= lang_safe('password', 'Enter new password') ?>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" name="pass" class="form-control" id="password" required="required" autocomplete="new-password" oninput="removeTrailingSpaces(this)" />
                                        <span class="input-group-btn">
                                                <button class="btn btn-default toggle-password" type="button"
                                                        data-target="password">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="password_repeat">
                                        <?= lang_safe('password_repeat', 'Repeat new password') ?>
                                    </label>
                                    <div class="input-group">
                                    <input type="password" name="pass_repeat" class="form-control" id="password_repeat" required="required" autocomplete="new-password" oninput="removeTrailingSpaces(this)" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default toggle-password" type="button"
                                                    data-target="password_repeat">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" class="form-control" id="id" value="<?=$user_id?>" required="required" />
                            <div class="col-md-12">
                                <button type="submit" name="reset_password" class="btn btn-new pull-left" id="reset_password">
                                    <?= lang_safe('reset') ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
