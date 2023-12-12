<script src="<?= base_url('assets/ckeditor/ckeditor.js') ?>"></script>
<h1><img src="<?= base_url('assets/imgs/shop-cart-add-icon.png') ?>" class="header-img" style="margin-top:-3px;">
    Publish product</h1>
<hr>
<?php $validation = \Config\Services::validation(); ?>
<?php
$timeNow = time();
if (!empty($validation->getErrors())) {
    ?>
<hr>
<div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<hr>
<?php
}
if (session('result_publish')) {
    ?>
<hr>
<div class="alert alert-success"><?= session('result_publish') ?></div>
<hr>
<?php
}
?>
<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" value="<?= isset($_POST['folder']) ? htmlspecialchars($_POST['folder']) : $timeNow ?>"
        name="folder">
    <div class="form-group available-translations">
        <b>Languages</b>
        <?php foreach ($languages as $language) { ?>
        <button type="button" data-locale-change="<?= $language->abbr ?>"
            class="btn btn-default locale-change text-uppercase <?= $language->abbr == MY_DEFAULT_LANGUAGE_ABBR ? 'active' : '' ?>">
            <img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">
            <?= $language->abbr ?>
        </button>
        <?php } ?>
    </div>
    <?php
    $i = 0;
    foreach ($languages as $language) {
        ?>
    <div class="locale-container locale-container-<?= $language->abbr ?>"
        <?= $language->abbr == MY_DEFAULT_LANGUAGE_ABBR ? 'style="display:block;"' : '' ?>>
        <input type="hidden" name="translations[]" value="<?= $language->abbr ?>">
        <div class="form-group">
            <label>Title (<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="title[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['title']) ? $trans_load[$language->abbr]['title'] : '' ?>"
                class="form-control">

            <label>Title2 (<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="title2[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['title2']) ? $trans_load[$language->abbr]['title2'] : '' ?>"
                class="form-control">

            <label>Bullet1(<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="bullet1[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['bullet1']) ? $trans_load[$language->abbr]['bullet1'] : '' ?>"
                class="form-control">

            <label>Bullet2(<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="bullet2[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['bullet2']) ? $trans_load[$language->abbr]['bullet2'] : '' ?>"
                class="form-control">

            <label>Bullet3(<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="bullet3[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['bullet3']) ? $trans_load[$language->abbr]['bullet3'] : '' ?>"
                class="form-control">

            <label>Bullet4(<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="bullet4[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['bullet4']) ? $trans_load[$language->abbr]['bullet4'] : '' ?>"
                class="form-control">

            <label>Bullet5(<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="bullet5[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['bullet5']) ? $trans_load[$language->abbr]['bullet5'] : '' ?>"
                class="form-control">

            <label>Bullet6(<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="bullet6[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['bullet6']) ? $trans_load[$language->abbr]['bullet6'] : '' ?>"
                class="form-control">

            <label>Bullet7(<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="bullet7[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['bullet7']) ? $trans_load[$language->abbr]['bullet7'] : '' ?>"
                class="form-control">

            <label>variant_name(<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <input type="text" name="variant_name[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['variant_name']) ? $trans_load[$language->abbr]['variant_name'] : '' ?>"
                class="form-control">

            <label>variant_description(<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <input type="text" name="variant_description[]"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['variant_description']) ? $trans_load[$language->abbr]['variant_description'] : '' ?>"
                class="form-control">

        </div>

        <div class="form-group">
            <a href="javascript:void(0);" class="btn btn-default showSliderDescrption" data-descr="<?= $i ?>">Show
                Slider Description <span class="glyphicon glyphicon-circle-arrow-down"></span></a>
        </div>
        <div class="theSliderDescrption" id="theSliderDescrption-<?= $i ?>"
            <?= isset($_POST['in_slider']) && $_POST['in_slider'] == 1 ? 'style="display:block;"' : '' ?>>
            <div class="form-group">
                <label for="basic_description<?= $i ?>">Slider Description (<?= $language->name ?><img
                        src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
                <textarea name="basic_description[]" id="basic_description<?= $i ?>" rows="50"
                    class="form-control"><?= $trans_load != null && isset($trans_load[$language->abbr]['basic_description']) ? $trans_load[$language->abbr]['basic_description'] : '' ?></textarea>
                <script>
                CKEDITOR.replace('basic_description<?= $i ?>');
                CKEDITOR.config.entities = false;
                </script>
            </div>
        </div>
        <div class="form-group">
            <label for="description<?= $i ?>">Description (<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <textarea name="description[]" id="description<?= $i ?>" rows="50"
                class="form-control"><?= $trans_load != null && isset($trans_load[$language->abbr]['description']) ? $trans_load[$language->abbr]['description'] : '' ?></textarea>
            <script>
            CKEDITOR.replace('description<?= $i ?>');
            CKEDITOR.config.entities = false;
            </script>
        </div>
        <div class="form-group for-shop">
            <label>Price (<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>"
                    alt="">)</label>
            <input type="text" name="price[]" placeholder="without currency at the end"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['price']) ? $trans_load[$language->abbr]['price'] : '' ?>"
                class="form-control">
        </div>
        <div class="form-group for-shop">
            <label>Old Price (<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <input type="text" name="old_price[]" placeholder="without currency at the end"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['old_price']) ? $trans_load[$language->abbr]['old_price'] : '' ?>"
                class="form-control">
        </div>
        <div class="form-group for-shop">
            <label>shipping_cost (<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <input type="text" name="shipping_cost[]" placeholder="shipping_cost"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['shipping_cost']) ? $trans_load[$language->abbr]['shipping_cost'] : '' ?>"
                class="form-control">
        </div>
        <div class="form-group for-shop">
            <label>shipping_time (<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <input type="text" name="shipping_time[]" placeholder="shipping_time days"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['shipping_time']) ? $trans_load[$language->abbr]['shipping_time'] : '' ?>"
                class="form-control">
        </div>
        <div class="form-group for-shop">
            <label>delivery_status (<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <input type="text" name="delivery_status[]" placeholder="delivery_status text"
                value="<?= $trans_load != null && isset($trans_load[$language->abbr]['delivery_status']) ? $trans_load[$language->abbr]['delivery_status'] : '' ?>"
                class="form-control">
        </div>
    </div>
    <?php
        $i++;
    }
    ?>
    <div class="form-group bordered-group">
        <?php
        if (isset($_POST['image']) && $_POST['image'] != null) {
            $image = 'attachments/shop_images/' . htmlspecialchars($_POST['image']);
            if (!file_exists($image)) {
                $image = 'attachments/no-image.png';
            }
            ?>
        <p>Current image:</p>
        <div>
            <img src="<?= base_url($image) ?>" class="img-responsive img-thumbnail"
                style="max-width:300px; margin-bottom: 5px;">
        </div>
        <input type="hidden" name="old_image" value="<?= htmlspecialchars($_POST['image']) ?>">
        <?php if (isset($_GET['to_lang'])) { ?>
        <input type="hidden" name="image" value="<?= htmlspecialchars($_POST['image']) ?>">
        <?php
            }
        }
        ?>
        <label for="userfile">Cover Image</label>
        <input type="file" id="userfile" name="userfile">
    </div>
    <div class="form-group bordered-group">
        <div class="others-images-container">
            <?= $otherImgs ?>
        </div>
        <a href="javascript:void(0);" data-toggle="modal" data-target="#modalMoreImages" class="btn btn-default">Upload
            more images</a>
    </div>
    <div class="form-group for-shop">
        <label>Shop Categories</label>
        <select class="selectpicker form-control show-tick show-menu-arrow" name="shop_categorie">
        <?php 
        foreach ($shop_categories as $key_cat => $shop_categorie) {
            // Find the current language category name
            $category_name = '';
            foreach ($shop_categorie['info'] as $nameAbbr) {
                if ($nameAbbr['abbr'] == config('config')->language_abbr) {
                    $category_name = $nameAbbr['name'];
                    break;
                }
            }

            // Determine the index based on the current language for parent category
            $index = config('config')->language_abbr == 'de' ? 0 : 1;
            
            // Check if there's a parent category and append it to the option text if it exists
            $parent_name = isset($shop_categorie['sub'][$index]) ? ' - ' . $shop_categorie['sub'][$index] : '';

            // Generate the option text
            $option_text = $category_name . $parent_name;
            ?>
            <option <?= isset($_POST['shop_categorie']) && $_POST['shop_categorie'] == $key_cat ? 'selected=""' : '' ?> value="<?= $key_cat ?>">
                <?= $option_text ?>
            </option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group for-shop">
        <label>Quantity</label>
        <input type="text" placeholder="number" name="quantity"
            value="<?= isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : '' ?>" class="form-control"
            id="quantity">

        <label>Ist Hauptansicht von der Variante mit ID</label>
        <input type="text" placeholder="number" name="is_main_view_from_variant"
            value="<?= isset($_POST['is_main_view_from_variant']) ? htmlspecialchars($_POST['is_main_view_from_variant']) : '' ?>"
            class="form-control" id="is_main_view_from_variant">

        <label>ist eine Variante 0/1</label>
        <input type="text" placeholder="number" name="is_variant"
            value="<?= isset($_POST['is_variant']) ? htmlspecialchars($_POST['is_variant']) : '' ?>"
            class="form-control" id="is_variant">

        <label>Variante ID</label>
        <input type="text" placeholder="number" name="variant_id"
            value="<?= isset($_POST['variant_id']) ? htmlspecialchars($_POST['variant_id']) : '' ?>"
            class="form-control" id="variant_id">

        <label>Artikelnummer</label>
        <input type="text" placeholder="number" name="article_nr"
            value="<?= isset($_POST['article_nr']) ? htmlspecialchars($_POST['article_nr']) : '' ?>"
            class="form-control" id="article_nr">

        <label>is_visible</label>
        <input type="text" placeholder="number" name="is_visible"
            value="<?= isset($_POST['is_visible']) ? htmlspecialchars($_POST['is_visible']) : '' ?>"
            class="form-control" id="is_visible">

        <label>shipment_destination</label>
        <input type="text" placeholder="number" name="shipment_destination"
            value="<?= isset($_POST['shipment_destination']) ? htmlspecialchars($_POST['shipment_destination']) : '' ?>"
            class="form-control" id="shipment_destination">

        <label>Reserve_Produkt_03</label>
        <input type="text" placeholder="number" name="Reserve_Produkt_03"
            value="<?= isset($_POST['Reserve_Produkt_03']) ? htmlspecialchars($_POST['Reserve_Produkt_03']) : '' ?>"
            class="form-control" id="Reserve_Produkt_03">
    </div>
    <?php if ($showBrands == 1) { ?>
    <div class="form-group for-shop">
        <label>Brand</label>
        <select class="selectpicker" name="brand_id">
            <?php foreach ($brands as $brand) { ?>
            <option <?= isset($_POST['brand_id']) && $_POST['brand_id'] == $brand['id'] ? 'selected' : '' ?>
                value="<?= $brand['id'] ?>"><?= $brand['name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <?php } if ($virtualProducts == 1) { ?>
    <div class="form-group for-shop">
        <label>Virtual Products <a href="javascript:void(0);" data-toggle="modal" data-target="#virtualProductsHelp"><i
                    class="fa fa-question-circle" aria-hidden="true"></i></a></label>
        <textarea class="form-control"
            name="virtual_products"><?= isset($_POST['virtual_products']) ? htmlspecialchars($_POST['virtual_products']) : '' ?></textarea>
    </div>
    <?php } ?>
    <div class="form-group for-shop">
        <label>In Slider</label>
        <select class="selectpicker" name="in_slider">
            <option value="1" <?= isset($_POST['in_slider']) && $_POST['in_slider'] == 1 ? 'selected' : '' ?>>Yes
            </option>
            <option value="0"
                <?= isset($_POST['in_slider']) && $_POST['in_slider'] == 0 || !isset($_POST['in_slider']) ? 'selected' : '' ?>>
                No</option>
        </select>
    </div>
    <div class="form-group for-shop">
        <label>Position</label>
        <input type="text" placeholder="Position number" name="position"
            value="<?= isset($_POST['position']) ? htmlspecialchars($_POST['position']) : '' ?>" class="form-control">
    </div>
    <button type="submit" name="submit" class="btn btn-lg btn-default btn-publish">Publish</button>
    <?php if (request()->uri->getSegment(3) !== null) { ?>
    <a href="<?= base_url('admin/products') ?>" class="btn btn-lg btn-default">Cancel</a>
    <?php } ?>
</form>
<!-- Modal Upload More Images -->
<div class="modal fade" id="modalMoreImages" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload more images</h4>
            </div>
            <div class="modal-body">
                <form id="uploadImagesForm">
                    <input type="hidden"
                        value="<?= isset($_POST['folder']) ? htmlspecialchars($_POST['folder']) : $timeNow ?>"
                        name="folder">
                    <label for="others">Select images</label>
                    <input type="file" name="others[]" id="others" multiple />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default finish-upload">
                    <span class="finish-text">Finish</span>
                    <img src="<?= base_url('assets/imgs/load.gif') ?>" class="loadUploadOthers" alt="">
                </button>
            </div>
        </div>
    </div>
</div>
<!-- virtualProductsHelp -->
<div class="modal fade" id="virtualProductsHelp" tabindex="-1" role="dialog" aria-labelledby="virtualProductsHelp">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">What are virtual products?</h4>
            </div>
            <div class="modal-body">
                Sometimes we want to sell products that are for electronic use such as books. In the box below, you can
                enter links to products that can be downloaded after you confirm the order as "Processed" through the
                "Orders" tab, an email will be sent to the customer entered with the entire text entered in the "virtual
                products" field.
                We have left only the possibility to add links in this field because sometimes it is necessary that the
                electronic stuff you provide for downloading will be uploaded to other servers. If you want, you can add
                your files to "file manager" and take the links to them to add to the "virtual products"
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>