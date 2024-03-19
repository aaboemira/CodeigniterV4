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
                        <?= lang_safe('add_device') ?>
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
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if (session('success')) { ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>
            <form action="<?= base_url('/smartdevices/store') ?>" method="post">
                <div class="row" style="margin:0px;">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="device_name">
                                <?= lang_safe('device_name') ?>
                            </label>
                            <input type="text" class="form-control" id="device_name" name="device_name" maxlength="16"
                                value="<?= old('device_name') ?>"  placeholder="<?= lang_safe('enter_name') ?>">
                        </div>
                    </div>
                <!-- Serial Number Field -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="serialNumber">
                            <?= lang_safe('serial_number') ?>
                        </label>
                        <input type="text" class="form-control" id="serialNumber" name="serial_number" maxlength="16"
                            value="<?= old('serial_number') ?>"  placeholder="<?= lang_safe('enter_serial_number') ?>">
                    </div>
                </div>

                <!-- UID Field -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="uid">
                            <?= lang_safe('uid') ?>
                        </label>
                        <input type="text" class="form-control" id="uid" name="uid" maxlength="32"
                            value="<?= old('uid') ?>" placeholder="<?= lang_safe('enter_uid') ?>" >
                    </div>
                </div>
                <!-- Password Field -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="password">
                            <?= lang_safe('password') ?>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required
                            oninput="removeTrailingSpaces(this)" placeholder="<?= lang_safe('enter_password') ?>" maxlength="20">
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
                        <label for="pin_enabled"><?= lang_safe('create_smart_device_enable_pin_text') ?></label>
                        <select class="form-control" id="pin_enabled" name="pin_enabled">
                            <option value="1" selected><?= lang_safe('create_smart_device_enable_pin') ?></option>
                            <option value="0" ><?= lang_safe('create_smart_device_disable_pin') ?></option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="speech_pin_hidden" id="speech_pin_hidden" >

                <!-- Speech PIN Field -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="speech_pin">
                            <?= lang_safe('create_smart_device_speech_pin') ?>
                        </label>
                        <input type="text" class="form-control" id="speech_pin" name="speech_pin" maxlength="6"
                            value="<?= old('speech_pin', $randomPin) ?>" placeholder="<?= lang_safe('enter_speech_pin') ?>"  title="<?= lang_safe('pin_validation_title') ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-new">
                        <?= lang_safe('add_device') ?>
                    </button>
                </div>
            </form>
            <!-- Security Risk Warning Modal -->
            <!-- Speech Pin Security Warning Modal -->
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



