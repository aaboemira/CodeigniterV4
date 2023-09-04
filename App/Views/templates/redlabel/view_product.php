

<!-- <div id="top-part"> -->
    <div class="w3-container">
        <div class="row">
            <!-- Einkaufwagen -->
            <!-- <?php if( is_numeric($cartItems) && (int)$cartItems == 0 ? 0 : $sumOfItems )
			{?>
            <div class="col-sm-6 col-md-4 col-lg-3 ">
                <div class="basket-box">
                    <table>
                        <tr>
                            <td>
                                <img src="<?= base_url('template/imgs/green-basket.png') ?>" class="green-basket" alt=""
                                    style="margin-left:50px">
                            </td>
                            <td>
                                <div class="center">
                                    <h4><?= lang_safe('your_basket') ?></h4>
                                    <a href="<?= LANG_URL . '/checkout' ?>"><?= lang_safe('checkout_top_header') ?></a> |
                                    <a href="<?= LANG_URL . '/shopping-cart' ?>"><?= lang_safe('shopping_cart_only') ?></a>
                                </div>
                            </td>
                            <td>
                                <ul class="shop-dropdown">
                                    <li class="dropdown text-center">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                            aria-expanded="false">
                                            <div><span
                                                    class="sumOfItems"><?= is_numeric($cartItems) && (int)$cartItems == 0 ? 0 : $sumOfItems ?></span>
                                                <?= lang_safe('items') ?></div>
                                            <img src="<?= base_url('template/imgs/shopping-cart-icon-515.png') ?>"
                                                alt="">
                                            <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right dropdown-cart" role="menu">
                                            <?= $load::getCartItems($cartItems) ?>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php } ?> -->
        </div>
    </div>
<!-- </div> -->

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

            <h1><?= $product['title'] ?></h1>
            <!-- <h2><?= $product['title2'] ?></h2> -->
            <?php
             if( $product['is_main_view_from_variant'] == 0 )
            {?>

            <span> <?= lang_safe('articlenumber') ?> <?= $product['article_nr'] ?></span>

            <?php } ?>
            <!-- Preis -->

            <div class="row row-info">
                <!-- <div class="col-sm-12 border-bottom"></div> -->
                <div class="col-sm-12 price">

                    <?php
                if( $product['is_main_view_from_variant'] != 0 ){?>
                    ab
                    <?php } ?>
                    <?= $product['price'] .' ' . CURRENCY ?> </div>
            </div>

            <?php
                if( $product['is_main_view_from_variant'] == 0 ){?>

            <div class="col-sm-12  price-discount">

                <?php 
if ($product['old_price'] != '' && $product['old_price'] != 0 && $product['price'] != '' &&
$product['price'] != 0 && $product['price'] != $product['old_price']) {

$percent_friendly = number_format((($product['old_price'] - $product['price']) /
$product['old_price']) * 100) . '%';
?>

                <span class="price-discount">
                    <?= $product['old_price'] != '' ? number_format($product['old_price'], 2) . ' '. CURRENCY : '' ?>
                </span>

                <span class="price-down">-<?= $percent_friendly ?></span>
                <?php } ?>
            </div>

            <div class="price-discount">
                <span class="mwst"> <?= lang_safe('mwst') ?> </span>

            </div>
            <?php } ?>
            <div class="col-sm-6 border-bottom"></div>
            <!-- varianten -->

            <?php 
            if( $product['variant_id'] != 0 )
            {
                $all_products = $model->getProducts($product['variant_id']);
                ?>
            <div class="row row-info">
                <div class="col-sm-6 variant_choice_label">
                    <label for="variant_choice"><?= $product['variant_name'] ?> </label>
                    <select name="variant_choice" id="variant_choice" size="1" onchange="variant_selected()">
                        <?php 
                
                if( $product['is_main_view_from_variant'] != 0 )
                {
                    echo "<option value='".$product['url']."'>"  . "Bitte auswählen" .  "</option>" ;
                }
                foreach ($all_products as $product_) 
                {   
                    
                    if($product_['is_variant'] == 1)
                    {
                        if($product_['variant_id'] == $product['variant_id'])
                        {
                            
                            if($product_['id']==$product['id'])
                            {
                                echo "<option selected='selected' value='$product_[url]'>"  . "$product_[variant_description]" .  "</option>" ;
                                //echo "<option selected='selected' value='".$product_[url]."'>".$product_[variant_description]."</option>";
                            }
													else if($product_['quantity'] == 0)
												{
                                                    
                                echo "<option value='$product_[url]'>"  . "$product_[variant_description]" . ' ' . " | " . lang_safe('sold_out') .  "</option>" ;
                         
                            }
                            else
                            {
                                echo "<option value='$product_[url]'>"  . "$product_[variant_description]" . ' ' . " | $product_[price]" . ' ' . CURRENCY . ' '.  "</option>" ;
                                //echo "<option value='$product_[url]'>"  . "$product_[variant_description]" .  "</option>" ;
                                //echo "<option value='".$value."'>".$name."</option>";
                            }
                            
                        }
                    }
                    else
                    {    
                        
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
                    <?php if ($product['quantity'] > 0) {  ?>
                        
                    <!-- <a href="javascript:void(0);" data-id="<?= $product['id'] ?>"
                        data-goto="<?= LANG_URL . '/checkout' ?>" class="add-to-cart btn-add">
                        <span class="text-to-bg"><?= lang_safe('buy_now') ?></span>
                    </a> -->
                    <?php 
                if( $product['is_main_view_from_variant'] == 0 ) //keine hauptansicht von Varianten
                {
                    
                ?>
                    
                    <div>
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#myModal"  data-id="<?= $product['id'] ?>"
                            class="add-to-cart btn-add">
                            <span class="text-to-bg"><?= lang_safe('add_to_cart') ?></span>
                        </a>
                    </div>
                    <!-- <div>
                        <a href="javascript:void(0);" data-id="<?= $product['id'] ?>"
                            data-goto="<?= LANG_URL . '/shopping-cart' ?>" class="add-to-cart btn-add">
                            <span class="text-to-bg"><?= lang_safe('add_to_cart') ?></span>
                        </a>
                    </div> -->
                    <?php
                }
                else
                {
                    
                }
            ?>
                    <?php } else { ?>
                    <div class="alert alert-info"><?= lang_safe('out_of_stock_product') ?></div>
                    <?php } ?>
                </div>
                <div class="col-sm-6 border-bottom"></div>
            </div>




            <!-- Highlights -->
            <?php if ($product['bullet1'] != '') { ?>
            <div class="row row-info">
                <div class="col-sm-6 highlights"><?= lang_safe('product_description_highlights') ?></div>
                <?php if ($product['bullet1'] != '') { ?><div class="col-sm-12"><a class="bullet_haken">✓
                    </a><a class="bullet_text"><?= $product['bullet1'] ?></a> </div><?php }?>
                <?php if ($product['bullet2'] != '') { ?><div class="col-sm-12"><a class="bullet_haken">✓
                    </a><a class="bullet_text"><?= $product['bullet2'] ?></a> </div><?php }?>
                <?php if ($product['bullet3'] != '') { ?><div class="col-sm-12"><a class="bullet_haken">✓
                    </a><a class="bullet_text"><?= $product['bullet3'] ?></a> </div><?php }?>
                <?php if ($product['bullet4'] != '') { ?><div class="col-sm-12"><a class="bullet_haken">✓
                    </a><a class="bullet_text"><?= $product['bullet4'] ?></a> </div><?php }?>
                <?php if ($product['bullet5'] != '') { ?><div class="col-sm-12"><a class="bullet_haken">✓
                    </a><a class="bullet_text"><?= $product['bullet5'] ?></a> </div><?php }?>
                <?php if ($product['bullet6'] != '') { ?><div class="col-sm-12"><a class="bullet_haken">✓
                    </a><a class="bullet_text"><?= $product['bullet6'] ?></a> </div><?php }?>
                <?php if ($product['bullet7'] != '') { ?><div class="col-sm-12"><a class="bullet_haken">✓
                    </a><a class="bullet_text"><?= $product['bullet7'] ?></a> </div><?php }?>
                <div class="col-sm-3 border-bottom"></div>
            </div>
            <?php }?>

            <?php if ($publicQuantity == 1) { ?>
            <div class="row row-info">
                <div class="col-sm-6">
                    <b><?= lang_safe('in_stock') ?>:</b>
                </div>
                <div class="col-sm-6"><?= $product['quantity'] ?></div>
                <div class="col-sm-3 border-bottom"></div>
            </div>
            <?php } ?>


        </div>

        <div class="row row-info">
            <div class="col-xs-12 description"><?= lang_safe('description') ?></div>
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
                <span><?= lang_safe('oder_from_category') ?></span>
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
            <a href="<?= LANG_URL . '/shop'?>" class="btn btn-primary go-shop">
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


<!-- The Modal einkauskorb-->
<!-- <div id="myModal" class="modal " role="dialog">
    <div class="modal-dialog" style="width:50% ;">
        <div class="modal-content">
            <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                
            </div>
            <div class="modal-body ">

                <div class="col-xm-6 col-md-9">
                    <a class="modal_title" ><span class="glyphicon glyphicon-ok"></span> <?= lang_safe('modal_product_added') ?></a> 
                    <ul class="w3-left">  
                        <li><img class="product-image_modal" src="<?= base_url('/attachments/shop_images/' . $product['image']) ?>" alt=""></li>
                        <li><a><?= $product['title'] ?></a></li>
                        <li><a>1x <?= $product['price'] . CURRENCY ?></a></li>
                    </ul>
                </div> 
                 
                <div class="col-xm-6 col-md-9">
                    <a><?= lang_safe('modal_cart_total') ?></a>
                    <?php if( is_numeric($cartItems) && (int)$cartItems != 0  )
			        {?>
                    <a><?= $cartItems['finalSum'] . CURRENCY ?></a>
                    <?php } else {?>
                        <a><?= $product['price']  . CURRENCY ?></a>
                    <?php } ?>
                    <a class="btn btn-primary go-checkout" href="<?= LANG_URL . '/shopping-cart' ?>">
                    <?= lang_safe('modal_show_cart') ?></a>  
                </div>
            </div>
             <div class="modal-footer">
                 <a href="<?= LANG_URL . '/shop' ?>" class="btn btn-primary go-shop">
                    <span class="glyphicon glyphicon-circle-arrow-left"></span>
                    <?= lang_safe('modal_continue_shopping') ?>
                </a> -

            </div> 
        </div>
    </div>
</div>  -->
<!-- Modal end-->