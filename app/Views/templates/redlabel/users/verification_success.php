<div class="container-fluid user-page">
    <div class="row custom-center">
        <div class="col-md-5 col-sm-10">
            <div class="alone title">
                    <span>
                        <?= lang_safe('user_register') ?>
                    </span>
            </div>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span>
                    <?= session('success') ?>
                </span>
            </div>
            <div>
                <a href="<?= LANG_URL.'/register' ?>" class="btn btn-new">
                    <?= lang_safe('login') ?> <!-- Replace with your language label for 'Login' -->
                </a>
            </div>
        </div>
    </div>
</div>