<script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
<link href="<?= base_url('assets/css-gradient-generator/src/css-gradient-generator.css') ?>" rel="stylesheet" type="text/css" media="all">
<link href="<?= base_url('assets/css-gradient-generator/resources/icomoon/sprites.css') ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css-gradient-generator/resources/bootstrap-colorpickersliders/bootstrap.colorpickersliders.css') ?>" rel="stylesheet" type="text/css" media="all">

<h1><img src="<?= base_url('assets/imgs/pages-styling.png') ?>" class="header-img" style="margin-top:-3px;">Shipping Settings</h1>
<hr>

<div class="container">
    <form method="POST" action="<?= base_url('admin/shipping') ?>">
        <div class="form-group">
            <label for="free_shipping_germany">Free Shipping Threshold for Germany (€):</label>
            <input type="number" class="form-control" id="free_shipping_germany" name="free_shipping_germany" value="<?= $shipping_settings['free_shipping_germany'] ?? '' ?>" >
        </div>
        <div class="form-group">
            <label for="free_shipping_europe">Free Shipping Threshold for Europe (€):</label>
            <input type="number" class="form-control" id="free_shipping_europe" name="free_shipping_europe" value="<?= $shipping_settings['free_shipping_europe'] ?? '' ?>" >
        </div>
        <button type="submit" name="submit" class="btn btn-lg btn-default">Save</button>
    </form>
</div>

<script src="<?= base_url('assets/css-gradient-generator/resources/bootstrap-touchspin/bootstrap.touchspin.js') ?>"></script>
<script src="<?= base_url('assets/css-gradient-generator/resources/tinycolor/tinycolor.js') ?>"></script>
<script src="<?= base_url('assets/css-gradient-generator/resources/bootstrap-colorpickersliders/bootstrap.colorpickersliders.js') ?>"></script>
<script src="<?= base_url('assets/css-gradient-generator/src/css-gradient-generator.js') ?>"></script>
<script src="<?= base_url('assets/css-gradient-generator/resources/jquery.base64/jquery.base64.min.js') ?>"></script>
<script src="<?= base_url('assets/css-gradient-generator/resources/qrcode/qrcode.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        $('button, a').tooltip();
    });
</script>
