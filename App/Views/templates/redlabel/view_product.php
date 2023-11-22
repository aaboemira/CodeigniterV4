<div class="container" id="view-product">
    <div class="row">
        <div class="col-sm-6">
            <div <?= $product['folder'] != null ? 'style="margin-bottom:20px;"' : '' ?>>
                <img src="<?= base_url('/attachments/shop_images/' . $product['image']) ?>"
                    style="width:auto; height:auto;" data-num="0"
                    class="other-img-preview img-responsive img-sl the-image"
                    alt="<?= str_replace('"', "'", $product['title']) ?>">
            </div>
            <?php
            if ($product['folder'] != null) {
                $dir = "attachments/shop_images/" . $product['folder'] . '/';
                ?>
                <div class="row">
                    <?php
                    if (is_dir($dir)) {
                        if ($dh = opendir($dir)) {
                            $i = 1;
                            while (($file = readdir($dh)) !== false) {
                                if (is_file($dir . $file)) {
                                    ?>
                                    <div class="col-xs-4 col-sm-6 col-md-4 text-center">
                                        <img src="<?= base_url($dir . $file) ?>" data-num="<?= $i ?>"
                                            class="other-img-preview img-sl img-thumbnail the-image"
                                            alt="<?= str_replace('"', "'", $product['title']) ?>">
                                    </div>
                                    <?php
                                    $i++;
                                }
                            }
                            closedir($dh);
                        }
                    }
                    ?>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="col-sm-6">

            <h1>
                <?= $product['title'] ?>
            </h1>
            <!-- <h2><?= $product['title2'] ?></h2> -->
            <?php
            if ($product['is_main_view_from_variant'] == 0) { ?>

                <span>
                    <?= lang_safe('articlenumber') ?>
                    <?= $product['article_nr'] ?>
                </span>

            <?php } ?>
            <!-- Preis -->

            <div class="row row-info">
                <!-- <div class="col-sm-12 border-bottom"></div> -->
                <div class="col-sm-12 price">

                    <?php
                    if ($product['is_main_view_from_variant'] != 0) { ?>
                        ab
                    <?php } ?>
                    <?= $product['price'] . ' ' . CURRENCY ?>
                </div>
            </div>

            <?php
            if ($product['is_main_view_from_variant'] == 0) { ?>

                <div class="col-sm-12  price-discount">

                    <?php
                    if (
                        $product['old_price'] != '' && $product['old_price'] != 0 && $product['price'] != '' &&
                        $product['price'] != 0 && $product['price'] != $product['old_price']
                    ) {

                        $percent_friendly = number_format((($product['old_price'] - $product['price']) /
                            $product['old_price']) * 100) . '%';
                        ?>

                        <span class="price-discount">
                            <?= $product['old_price'] != '' ? number_format($product['old_price'], 2) . ' ' . CURRENCY : '' ?>
                        </span>

                        <span class="price-down">-
                            <?= $percent_friendly ?>
                        </span>
                    <?php } ?>
                </div>

                <div class="price-discount">
                    <span class="mwst">
                        <?= lang_safe('mwst') ?>
                    </span>

                </div>
            <?php } ?>
            <div class="col-sm-6 border-bottom"></div>
            <!-- varianten -->

            <?php
            if ($product['variant_id'] != 0) {
                $all_products = $model->getProducts($product['variant_id']);
                ?>
                <div class="row row-info">
                    <div class="col-sm-6 variant_choice_label">
                        <label for="variant_choice">
                            <?= $product['variant_name'] ?>
                        </label>
                        <select name="variant_choice" id="variant_choice" size="1" onchange="variant_selected()">
                            <?php

                            if ($product['is_main_view_from_variant'] != 0) {
                                echo "<option value='" . $product['url'] . "'>" .  lang_safe('product_select_variant') . "</option>";
                            }
                            foreach ($all_products as $product_) {

                                if ($product_['is_variant'] == 1) {
                                    if ($product_['variant_id'] == $product['variant_id']) {

                                        if ($product_['id'] == $product['id']) {
                                            echo "<option selected='selected' value='$product_[url]'>" . "$product_[variant_description]" . "</option>";
                                            //echo "<option selected='selected' value='".$product_[url]."'>".$product_[variant_description]."</option>";
                                        } else if ($product_['quantity'] == 0) {

                                            echo "<option value='$product_[url]'>" . "$product_[variant_description]" . ' ' . " | " . lang_safe('sold_out') . "</option>";

                                        } else {
                                            echo "<option value='$product_[url]'>" . "$product_[variant_description]" . ' ' . " | $product_[price]" . ' ' . CURRENCY . ' ' . "</option>";
                                            //echo "<option value='$product_[url]'>"  . "$product_[variant_description]" .  "</option>" ;
                                            //echo "<option value='".$value."'>".$name."</option>";
                                        }

                                    }
                                } else {

                                }

                            }

                            ?>
                        </select>
                    </div>
                </div>
                <?php

            }


            ?>

            <script>
                function variant_selected() {
                    var e = document.getElementById("variant_choice");
                    var strUser = e.value;
                    var strUser_text = e.options[e.selectedIndex].text;
                    if (strUser_text != '') {
                        //document.location.href = "<?= $product['url'] . '?var=' ?>" + strUser;
                        document.location.href = strUser;
                    }
                }
            </script>

            <div class="row row-info">
                <div class="col-sm-6"></div>
                <div class="col-sm-12 manage-buttons">
                    <?php if ($product['quantity'] > 0) { ?>

                        <!-- <a href="javascript:void(0);" data-id="<?= $product['id'] ?>"
                        data-goto="<?= LANG_URL . '/checkout' ?>" class="add-to-cart btn-add">
                        <span class="text-to-bg"><?= lang_safe('buy_now') ?></span>
                    </a> -->
                        <?php
                        if ($product['is_main_view_from_variant'] == 0) //keine hauptansicht von Varianten
                        {

                            ?>

                            <div>
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal"
                                    data-id="<?= $product['id'] ?>" class="add-to-cart btn-add">
                                    <span class="text-to-bg">
                                        <?= lang_safe('add_to_cart') ?>
                                    </span>
                                </a>
                            </div>
                            <!-- <div>
                        <a href="javascript:void(0);" data-id="<?= $product['id'] ?>"
                            data-goto="<?= LANG_URL . '/shopping-cart' ?>" class="add-to-cart btn-add">
                            <span class="text-to-bg"><?= lang_safe('add_to_cart') ?></span>
                        </a>
                    </div> -->
                            <?php
                        } else {

                        }
                        ?>
                    <?php } else { ?>
                        <div class="alert alert-info">
                            <?= lang_safe('out_of_stock_product') ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-6 border-bottom"></div>
            </div>




            <!-- Highlights -->
            <?php if ($product['bullet1'] != '') { ?>
                <div class="row row-info">
                    <div class="col-sm-6 highlights">
                        <?= lang_safe('product_description_highlights') ?>
                    </div>
                    <?php if ($product['bullet1'] != '') { ?>
                        <div class="col-sm-12"><a class="bullet_haken">✓
                            </a><a class="bullet_text">
                                <?= $product['bullet1'] ?>
                            </a> </div>
                    <?php } ?>
                    <?php if ($product['bullet2'] != '') { ?>
                        <div class="col-sm-12"><a class="bullet_haken">✓
                            </a><a class="bullet_text">
                                <?= $product['bullet2'] ?>
                            </a> </div>
                    <?php } ?>
                    <?php if ($product['bullet3'] != '') { ?>
                        <div class="col-sm-12"><a class="bullet_haken">✓
                            </a><a class="bullet_text">
                                <?= $product['bullet3'] ?>
                            </a> </div>
                    <?php } ?>
                    <?php if ($product['bullet4'] != '') { ?>
                        <div class="col-sm-12"><a class="bullet_haken">✓
                            </a><a class="bullet_text">
                                <?= $product['bullet4'] ?>
                            </a> </div>
                    <?php } ?>
                    <?php if ($product['bullet5'] != '') { ?>
                        <div class="col-sm-12"><a class="bullet_haken">✓
                            </a><a class="bullet_text">
                                <?= $product['bullet5'] ?>
                            </a> </div>
                    <?php } ?>
                    <?php if ($product['bullet6'] != '') { ?>
                        <div class="col-sm-12"><a class="bullet_haken">✓
                            </a><a class="bullet_text">
                                <?= $product['bullet6'] ?>
                            </a> </div>
                    <?php } ?>
                    <?php if ($product['bullet7'] != '') { ?>
                        <div class="col-sm-12"><a class="bullet_haken">✓
                            </a><a class="bullet_text">
                                <?= $product['bullet7'] ?>
                            </a> </div>
                    <?php } ?>
                    <div class="col-sm-3 border-bottom"></div>
                </div>
            <?php } ?>

            <?php if ($publicQuantity == 1) { ?>
                <div class="row row-info">
                    <div class="col-sm-6">
                        <b>
                            <?= lang_safe('in_stock') ?>:
                        </b>
                    </div>
                    <div class="col-sm-6">
                        <?= $product['quantity'] ?>
                    </div>
                    <div class="col-sm-3 border-bottom"></div>
                </div>
            <?php } ?>


        </div>

        <div class="row row-info">
            <div class="col-xs-12 description">
                <?= lang_safe('description') ?>
            </div>
        </div>
        <div id="description">
            <?= $product['description'] ?>
        </div>

    </div>




    <?php
    if (!empty($sameCagegoryProducts)) {
        ?>
        <div class="row orders-from-category" id="products-side">
            <div class="filter-sidebar">
                <div class="title">
                    <span>
                        <?= lang_safe('oder_from_category') ?>
                    </span>
                </div>
            </div>
            <?php
            $load::getProducts($sameCagegoryProducts, 'col-sm-4 col-md-3', false);
            ?>
        </div>
        <?php
    } else {
        ?>

        <?php
    }
    ?>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <br>
            <a href="<?= LANG_URL . '/shop' ?>" class="btn btn-primary btn-new go-shop">
                <span class="glyphicon glyphicon-circle-arrow-left"></span>
                <?= lang_safe('back_to_shop') ?>
            </a>
        </div>
    </div>
</div>
<div id="modalImagePreview" class="modal">
    <div class="image-preview-container">
        <div class="modal-content">
            <div class="inner-prev-container">
                <img id="img01" alt="">
                <span class="close">&times;</span>
                <span class="img-series"></span>
            </div>
        </div>
        <a href="javascript:void(0);" class="inner-next"></a>
        <a href="javascript:void(0);" class="inner-prev"></a>
    </div>
    <div id="caption"></div>
</div>
<script src="<?= base_url('assets/js/image-preveiw.js') ?>"></script>

<div id="myModal" class="modal" role="dialog">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content" style="padding: 0 !important; border-radius: 5px !important;">
            <div class="modal-header" style="padding: 1.4em; background-color: #3e89c9; color: white">
                <button type="button" class="close" style="color:white; opacity:1;" data-dismiss="modal">&times;</button><br>
                <h4 class="modal-title" style="text-align:left"><span class="glyphicon glyphicon-ok"></span> &nbsp; &nbsp; <?= lang_safe('modal_product_added') ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding:5px;">
                    <div class="left-col-product col-md-6 border-right">
                        <div class="col-md-5">
                            <img class="" style="width: 100%; margin-top: -20px"
                                src="<?= base_url('/attachments/shop_images/' . $product['image']) ?>" alt="product image">

                        </div>
                        <div class="col-md-7">
                            <p style="font-weight:bold; -webkit-hyphens: none; -ms-hyphens: none; hyphens: none;">
                                <?= $product['title'] ?>
                            </p>
                            <p>
                                <?= $product['price'] . CURRENCY ?>
                            </p>
					
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-12">
                            <hr class="max-675">
                            <div class="col-6" style="font-weight:bold;">
                                <p style="font-weight:bold;">
                                    <?= lang_safe('modal_cart_total') ?><br><span style="font-weight:normal;" id="cartTotal">
                                        <?php if ((int) $cartItems != 0) {
                                            $finalSum = str_replace(',', '', $cartItems['finalSum']);

                                            // Add $finalSum to $product['price']
                                            $totalPrice = $product['price'] + (float) $finalSum;

                                            // Format the result to 2 decimal points
                                            $formattedPrice = number_format($totalPrice, 2);
                                            ?>
                                            <?= $formattedPrice  ?>
                                        </span>
                                    <span style="font-weight:normal;"><?=CURRENCY?></span>
                                    </p>
                                <?php } else { ?>
                                <?= $product['price'] ?></span>
                                    <span style="font-weight:normal;"><?=CURRENCY?></span>
                                    </p>
                                    <?php } ?>
                            </div>
                            <div class="col-12">
                                <a class="btn btn-primary btn-new go-shop" style="margin-top: 10px; width:80%;" href="<?= LANG_URL . '/shopping-cart' ?>">
                                    <?= lang_safe('modal_show_cart') ?>
                                </a>
							</div>
							<div class="col-12">
								<a class="btn btn-primary btn-new go-checkout" style="margin-top: 5px; width:80%;" href="<?= LANG_URL . '/checkout1' ?>">
                                    <?= lang_safe('modal_checkout') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="modal-footer">
                <a style="text-align:left" href="<?= LANG_URL . '/shop' ?>" class="btn btn-primary go-shop">
                    <span class="glyphicon glyphicon-circle-arrow-left"></span>
                    <?= lang_safe('modal_continue_shopping') ?>
                </a>
            </div>-->
            </div>
        </div>
    </div>
    