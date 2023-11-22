<!-- <link rel="stylesheet" href="https://dev.unitymall.in/assets/css/pages/elements.css" /> -->


<div class="container-fluid user-page">
    <div class="row custom-center">
        <div class="col-md-5 col-sm-9  ">
            <div class="alone title">
                    <span>
                    <h2><?= lang_safe('password_recover', 'password_recover') ?></h2>
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
                    <?=lang_safe("password_recover_info")?>
                </p>
            </div>
            <div class="well well-sm">
                <form method="POST" action="<?= base_url('password/recover') ?>" id="password_reset">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enter_current_password">
                                    <?= lang_safe('recover_password', ) ?>
                                </label>
                                <input type="email" name="email" class="form-control" id="name"
                                       value="<?= set_value('email') ?>"
                                       placeholder="<?= lang_safe('email', 'Enter current email') ?>"
                                       required="required"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group " style="padding-left: 5px !important;">
                                <img id="captcha" src="" alt="Captcha Image" />
                                <button type="button" id="refreshCaptcha" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> <!-- This is the Font Awesome refresh icon -->
                                </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="capcha">
                                <?= lang_safe('capcha', 'Please enter captcha') ?>
                            </label>
                            <input type="text" name="code" class="form-control"
                                   placeholder="<?= lang_safe('please_enter_capcha') ?>" required="required"/>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" name="forget_password" class="btn btn-primary btn-new pull-left" id="btnContactUs">
                                <?= lang_safe('send_email') ?>
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