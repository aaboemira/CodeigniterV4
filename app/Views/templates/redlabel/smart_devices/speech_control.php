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
                <?= lang_safe('my_smart_home_devices') ?>
            </li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9">
            <div class="alone title" style="margin-bottom:20px;">
                <span>
                    <h2>
                        <?= lang_safe('speech_control_title') ?>
                    </h2>
                </span>
            </div>
            <?php if (session('errors')) { ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4><span class="glyphicon glyphicon-alert"></span>
                        <?= lang_safe('finded_errors') ?>
                    </h4>
                    <?php
                    foreach (session('errors') as $error) {
                        echo htmlspecialchars($error) . '<br>';
                    }
                    ?>
                </div>
            <?php } ?>
            <?php if (session()->getFlashdata('error')) { ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <?php if (session('success')) { ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <form action="<?= base_url('/smartdevices/speechControl') ?>" method="post">
            <input type="hidden" name="is_guest" value="<?= $device['is_guest']?>">
            <input type="hidden" name="device_id" value="<?= $device['device_id']?>">
            <input type="hidden" name="speech_pin_hidden" id="speech_pin_hidden" value="<?= $device['pin_code'] ?>">

                <div class="row" style="margin:0px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pin_enabled"><?= lang_safe('speech_control_enable_pin_text') ?></label>
                            <select class="form-control" id="pin_enabled" name="pin_enabled">
                                <option value="1" <?= $device['pin_enabled'] ? 'selected' : '' ?>><?= lang_safe('speech_control_enable_pin') ?></option>
                                <option value="0" <?= !$device['pin_enabled'] ? 'selected' : '' ?>><?= lang_safe('speech_control_disable_pin') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="speech_pin"><?= lang_safe('speech_control_speech_pin') ?></label>
                            <input type="text" class="form-control" id="speech_pin" name="speech_pin" value="<?= $device['pin_code'] ?>" maxlength="6" placeholder="<?= lang_safe('enter_speech_pin') ?>" pattern="\d{4,6}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-new"><?= lang_safe('update') ?></button>
                    </div>
                </div>
            </form>
            <div class="modal " id="speechPinWarningModal" tabindex="-1" role="dialog" aria-labelledby="speechPinWarningModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        <div class="modal-header">
                            
                            <h3 class="modal-title" id="speechPinWarningModalLabel"><?= lang_safe('create_smart_device_security_warning_title') ?></h5>
                        </div>
                        <div class="modal-body">
                            <p>
                                <?= lang_safe('create_smart_device_security_warning_message') ?>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang_safe('create_smart_device_model_cancel') ?></button>
                            <button type="button" class="btn btn-primary" id="confirmDisablePin"><?= lang_safe('create_smart_device_model_confirm') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
