<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
            <li><?= lang_safe('my_smart_home_devices') ?></li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9">
            <div class="alone title" style="margin-bottom:20px;">
                <span>
                    <h2>
                        <?= lang_safe('edit_device') ?>
                    </h2>
                </span>
            </div>
            <?php if (session('errors')) {
                ?>
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
                <?php
            }
            ?>
            <?php if (session('error')): ?>
                <div class="alert alert-danger"><?= session('error') ?></div>
            <?php endif; ?>
            <?php if (session('success')): ?>
                <div class="alert alert-success"><?= session('success') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('/smartdevices/updateDevice') ?>" method="post">
            <div class="row" style="margin:0px;">

                <input type="hidden" name="device_id" value="<?= $device['device_id'] ?>">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="device_name"><?= lang_safe('device_name') ?></label>
                        <input type="text" class="form-control" id="device_name" name="device_name"
                               value="<?= $device['device_name'] ?>"  maxlength="16" placeholder="<?= lang_safe('enter_name') ?>">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="serialNumber"><?= lang_safe('serial_number') ?></label>
                        <input type="text" class="form-control" id="serialNumber" maxlength="16" name="serial_number"
                               value="<?= $device['serial_number'] ?>" placeholder="<?= lang_safe('enter_serial_number') ?>">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="uid"><?= lang_safe('uid') ?></label>
                        <input type="text" class="form-control" id="uid" maxlength="32" name="uid" value="<?= $device['UID'] ?>"
                        placeholder="<?= lang_safe('enter_uid') ?>">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="password">
                            <?= lang_safe('password') ?>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password"  value="<?= $device['password'] ?>"
                                oninput="removeTrailingSpaces(this)" maxlength="20">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default toggle-password"
                                        data-target="password">
                                    <i class="fa fa-eye" id="eye-icon-repeat"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin: 0;">
                <!-- PIN Enabled Checkbox -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="pin_enabled"><?= lang_safe('edit_smart_device_enable_pin_text') ?></label>
                        <select class="form-control" id="pin_enabled" name="pin_enabled">
                            <option value="1" <?= $device['pin_enabled'] ? 'selected' : '' ?>><?= lang_safe('edit_smart_device_enable_pin') ?></option>
                            <option value="0" <?= !$device['pin_enabled'] ? 'selected' : '' ?>><?= lang_safe('edit_smart_device_disable_pin') ?></option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="speech_pin_hidden" id="speech_pin_hidden" value="<?= $device['pin_code'] ?>">

                <!-- Speech PIN Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="speech_pin">
                            <?= lang_safe('edit_smart_device_speech_pin') ?>
                        </label>
                        <input type="text" class="form-control" id="speech_pin" name="speech_pin" maxlength="6"
                            value="<?= $device['pin_code'] ?>" placeholder="<?= lang_safe('enter_speech_pin') ?>" pattern="\d{4,6}" title="<?= lang_safe('pin_validation_title') ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-new"><?= lang_safe('update_device') ?></button>
                </div>
            </form>
            <div class="modal " id="speechPinWarningModal" tabindex="-1" role="dialog" aria-labelledby="speechPinWarningModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        <div class="modal-header">
                            
                            <h3 class="modal-title" id="speechPinWarningModalLabel"><?= lang_safe('edit_smart_device_security_warning_title') ?></h5>
                        </div>
                        <div class="modal-body">
                            <p>
                                <?= lang_safe('edit_smart_device_security_warning_message') ?>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang_safe('edit_smart_device_model_cancel') ?></button>
                            <button type="button" class="btn btn-primary" id="confirmDisablePin"><?= lang_safe('edit_smart_device_model_confirm') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
