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
<form method="POST" id="publish" action="" enctype="multipart/form-data">
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
    <div class="form-group">
            <label>Fields to update in all variants:</label>
            <select class="selectpicker" name="variants_fields[]" multiple data-actions-box="true" data-live-search="true">
                <option value="bullet1">Bullet 1 </option>
                <option value="bullet2">Bullet 2 </option>
                <option value="bullet3">Bullet 3 </option>
                <option value="bullet4">Bullet 4 </option>
                <option value="bullet5">Bullet 5</option>
                <option value="bullet6">Bullet 6 </option>
                <option value="bullet7">Bullet 7 </option>
                <option value="variant_name">Variant Name </option>
                <option value="variant_description">Variant Description </option>
                <option value="price">Price</option>
                <option value="description">Description</option>
            </select>
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
        <?php
        $imageNameValue = '';
        if ($trans_load != null && isset($trans_load[$language->abbr]['images']['image_name'])) {
            $imagePath = $trans_load[$language->abbr]['images']['image_name'];
            $imageNameValue = pathinfo($imagePath, PATHINFO_FILENAME);
        }
        ?>
        <div class="form-group for-shop">
        <label >image_name (<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
        <input type="text" id="imageName" name="image_name[]" placeholder="image_name text" class="form-control"
        value="<?= $imageNameValue ?>"
        >
        </div>
        <div class="form-group for-shop">
            <label>Image_text (<?= $language->name ?><img
                    src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
            <input type="text" name="image_text[]" placeholder="image_card text" class="form-control" value="<?= $trans_load[$language->abbr]['image_text'] ?? '' ?>">
        </div>
        <div class="form-group bordered-group">
            <?php
            // Check if there's an image for the current language and the image name is not empty
            if (isset($trans_load[$language->abbr]['images']) && !empty($trans_load[$language->abbr]['images']['image_name'])) {
                $imageDetails = $trans_load[$language->abbr]['images'];
                $imagePath = 'attachments/shop_images/'  . $imageDetails['image_name'];
                if (file_exists($imagePath)) {
                    $imageUrl = base_url($imagePath);
                } else {
                    $imageUrl = base_url('attachments/no-image.png');
                }
            ?>
            <p>Current image:</p>
            <div>
                <img src="<?= $imageUrl ?>" class="img-responsive img-thumbnail" style="max-width:300px; margin-bottom: 5px;">
            </div>
            <input type="hidden"  name="old_image[<?= $language->abbr ?>]" value="<?= $imageDetails['image_name'] ?>">
            <?php } else { ?>
            <!-- Show 'no image' placeholder if there's no image or the image name is empty -->
            <div>
                <img src="<?= base_url('attachments/no-image.png') ?>" class="img-responsive img-thumbnail" style="max-width:300px; margin-bottom: 5px;">
            </div>
            <input type="hidden" name="old_image[<?= $language->abbr ?>]" value="">
            <?php } ?>
            <label for="userfile">Cover Image</label>
            <input type="file" id="cover_image" name="cover_image_<?= $language->abbr ?>" class="form-control">
        </div>

        <div class="form-group bordered-group">
            <div class="others-images-container_<?= $language->abbr ?>">
                <?= $otherImgs[$language->abbr] ?? '' ?>
            </div>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#modalMoreImages_<?= $language->abbr ?>" class="btn btn-default">Upload more images</a>
        </div>

    </div>

    <?php
        $i++;
    }
    ?>

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
<?php foreach ($languages as $language) { ?>
    <div class="modal fade" id="modalMoreImages_<?= $language->abbr ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Upload more images (<?= $language->name ?>)</h4>
                </div>
                <div class="modal-body">
                    <form id="uploadImagesForm_<?= $language->abbr ?>">
                        <input type="hidden" value="<?= isset($_POST['folder']) ? htmlspecialchars($_POST['folder']) : $timeNow ?>" name="folder">
                        <input type="hidden" name="lang_abbr" value="<?= $language->abbr ?>">
                        <label for="others_<?= $language->abbr ?>">Select images</label>
                        <input type="file" name="others_<?= $language->abbr ?>[]" id="others_<?= $language->abbr ?>" multiple />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default finish-upload" data-lang-abbr="<?= $language->abbr ?>">
                        <span class="finish-text">Finish</span>
                        <img src="<?= base_url('assets/imgs/load.gif') ?>" class="loadUploadOthers" alt="">
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
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
<input type="hidden" name="hiddenImageName">
<script>
$('#imageName').change(function () {
    // Copy the value from the imageText input to the hiddenImageName input
    var imageTextValue = document.getElementById('imageName').value;

    document.getElementById('hiddenImageName').value = imageTextValue;
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('publish');

    form.addEventListener('change', function (e) {
        if (e.target.matches('input[id^="cover_image"]')) {
            const parts = e.target.name.split('_');
            const lang = parts.pop(); // Gets the last element, the language abbreviation
            resizeAndAppendImage(e.target.files[0], lang, form);
        }
    });

    function resizeAndAppendImage(file, lang, form) {
        const sizes = [250,650, 1200,2400,3500];
        const reader = new FileReader();
        reader.onload = function (event) {
            const img = new Image();
            img.onload = function () {
                sizes.forEach(size => {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    const scaleFactor = size / Math.max(img.width, img.height);
                    canvas.width = img.width * scaleFactor;
                    canvas.height = img.height * scaleFactor;
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    canvas.toBlob(function (blob) {
                        let fileExtension = blob.type.split('/')[1];
                        if (fileExtension === 'jpeg') fileExtension = 'jpg';
                        const resizedFileName = `cover_image_${lang}_${size}.${fileExtension}`;
                        const resizedFile = new File([blob], resizedFileName, { type: blob.type });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(resizedFile);
                        const fileInput = document.createElement('input');
                        fileInput.type = 'file';
                        fileInput.name = `cover_image_${lang}_${size}`;
                        fileInput.style.display = 'none';
                        fileInput.files = dataTransfer.files;
                        form.appendChild(fileInput);
                    }, file.type);
                });
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
});

</script>


