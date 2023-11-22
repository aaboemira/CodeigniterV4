
<!-- <link rel="stylesheet" href="https://dev.unitymall.in/assets/css/pages/elements.css" /> -->


<div class="container-fluid user-page">
    <div class="row">
            <ol class="breadcrumb">
                <li><a href="<?= LANG_URL ?>">Home</a></li>
                <li><a href="<?= LANG_URL ?>/myaccount">Account</a></li>
                <li>Data</li>
            </ol>
            <?= view('templates/redlabel/_parts/sidebar'); ?>
            <div class="col-md-9">

            <div class="alone title">
                    <span>
                    <h2><?= lang_safe('my_acc') ?></h2>
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
                <?php if(validationError('registerError')) { ?>
                <div class="alert alert-danger">
                    <?= validationError('registerError') ?>
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
                                    <label for="name">
                                        <?= lang_safe('name') ?>
                                    </label>
                                    <input type="text" name="name" class="form-control" id="name" value="<?= $userInfo['first_name'] ?>"
                                        placeholder="<?= lang_safe('enter_name') ?>" required="required" />
                                </div>
                                <div class="form-group">
                                    <label for="email">
                                        <?= lang_safe('email_address') ?>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span
                                                class="glyphicon glyphicon-envelope"></span>
                                        </span>
                                        <input type="email" name="email" class="form-control_email" id="email" value="<?= $userInfo['email'] ?>"
                                            placeholder="<?= lang_safe('enter_email') ?>" required="required" readonly style="width:100%" />
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
                                        <input type="text" name="phone" class="form-control_email" id="phone" value="<?= $userInfo['phone'] ?>"
                                            placeholder="<?= lang_safe('please_enter_phone') ?>" required="required" style="width:100%" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="update" class="btn btn-primary pull-left" id="btnContactUs">
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