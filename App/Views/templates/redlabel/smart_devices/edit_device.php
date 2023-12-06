<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
            <li><?= lang_safe('my_smart_home_devices') ?></li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9">
            <h1><?= lang_safe('edit_device') ?></h1>
            <?php if (session('error')): ?>
                <div class="alert alert-danger"><?= session('error') ?></div>
            <?php endif; ?>
            <?php if (session('success')): ?>
                <div class="alert alert-success"><?= session('success') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('/smartdevices/updateDevice') ?>" method="post">
                <input type="hidden" name="device_id" value="<?= $device['device_id'] ?>">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="device_name"><?= lang_safe('device_name') ?></label>
                        <input type="text" class="form-control" id="device_name" name="device_name"
                               value="<?= $device['device_name'] ?>" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="serialNumber"><?= lang_safe('serial_number') ?></label>
                        <input type="text" class="form-control" id="serialNumber" name="serial_number"
                               value="<?= $device['serial_number'] ?>" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="uid"><?= lang_safe('uid') ?></label>
                        <input type="text" class="form-control" id="uid" name="uid" value="<?= $device['UID'] ?>"
                               required>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-new"><?= lang_safe('update_device') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

