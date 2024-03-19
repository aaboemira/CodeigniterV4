<?php
namespace App\Libraries;

use CodeIgniter\Config\Services;
use Config\GlobalVars;

class Loop
{

    private static $CI;

    public function __construct()
    {
        self::$CI = Services::instance();
    }

    static function getCartItems($cartItems)
    {
        if (!empty($cartItems['array'])) {
            ?>
            <li class="cleaner text-right">
                <a href="javascript:void(0);" class="btn-blue-round" onclick="clearCart()">
                    <?= lang_safe('clear_all') ?>
                </a>
            </li>
            <li class="divider"></li>
            <?php
            foreach ($cartItems['array'] as $cartItem) {
                ?>
                <li class="shop-item" data-artticle-id="<?= $cartItem['id'] ?>">
                    <span class="num_added hidden">
                        <?= $cartItem['num_added'] ?>
                    </span>
                    <div class="item">
                        <div class="item-in">
                            <div class="left-side">
                                <img src="<?= base_url('/attachments/shop_images/' . $cartItem['image']) ?>" alt="" />
                            </div>
                            <div class="right-side">
                                <a href="<?= LANG_URL . '/' . $cartItem['url'] ?>" class="item-info">
                                    <span>
                                        <?= $cartItem['title'] ?>
                                    </span>
                                    <span class="prices">
                                        <?=
                                            $cartItem['num_added'] == 1 ? $cartItem['price'] : '<span class="num-added-single">'
                                            . $cartItem['num_added'] . '</span> x <span class="price-single">'
                                            . $cartItem['price'] . '</span> - <span class="sum-price-single">'
                                            . $cartItem['sum_price'] . '</span>'
                                            ?>
                                    </span>
                                    <span class="currency">
                                        <?= CURRENCY ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div class="item-x-absolute">
                            <button class="btn btn-xs btn-danger pull-right" onclick="removeProduct(<?= $cartItem['id'] ?>)">
                                x
                            </button>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
            <li class="divider"></li>
            <li class="text-center">
                <a class="go-checkout btn btn-default btn-sm" href="<?= LANG_URL . '/checkout' ?>">
                    <?=
                        !empty($cartItems['array']) ? '<i class="fa fa-check"></i> '
                        . lang_safe('checkout') . ' - <span class="finalSum">' . $cartItems['finalSum']
                        . '</span>' . CURRENCY : '<span class="no-for-pay">' . lang_safe('no_for_pay') . '</span>'
                        ?>
                </a>
            </li>
        <?php } else {
            ?>
            <li class="text-center">
                <?= lang_safe('no_products') ?>
            </li>
            <?php
        }
    }

    static public function getProducts($products, $classes = '', $carousel = false)
    {
        if ($carousel == true) {
            ?>
            <div class="carousel slide" id="small_carousel" data-ride="carousel" data-interval="3000">
                <ol class="carousel-indicators">
                    <?php
                    $i = 0;
                    while ($i < count($products)) {
                        if ($i == 0)
                            $active = 'active';
                        else
                            $active = '';
                        ?>
                        <li data-target="#small_carousel" data-slide-to="<?= $i ?>" class="<?= $active ?>"></li>
                        <?php
                        $i++;
                    }
                    ?>
                </ol>
                <div class="carousel-inner">
                    <?php
        }
        $i = 0;
        foreach ($products as $article) {
            if ($article['is_variant'] == 0) {

                if ($i == 0 && $carousel == true) {
                    $active = 'active';
                } else {
                    $active = '';
                }
                ?>
                        <div class="product-list <?= $carousel == true ? 'item' : '' ?> <?= $classes ?> <?= $active ?>">
                            <div class="inner">
                                <div class="img-container">
                                    <a
                                        href="<?= $article['vendor_url'] == null ? LANG_URL . '/' . $article['url'] : LANG_URL . '/' . $article['vendor_url'] . '/' . $article['url'] ?>">
                                        <img src="<?= base_url('/attachments/shop_images/' . $article['image_name']) ?>"
                                            alt="<?= str_replace('"', "'", $article['title']) ?>" class="img-responsive" alt="">
                                    </a>
                                </div>
                                <h2>
                                    <a
                                        href="<?= $article['vendor_url'] == null ? LANG_URL . '/' . $article['url'] : LANG_URL . '/' . $article['vendor_url'] . '/' . $article['url'] ?>">
                                        <?= character_limiter($article['title'], 60) ?></a>
                                </h2>

                                <!-- <h3>
                    <a
                        href="<?= $article['vendor_url'] == null ? LANG_URL . '/' . $article['url'] : LANG_URL . '/' . $article['vendor_url'] . '/' . $article['url'] ?>"><?= character_limiter($article['title2'], 70) ?></a>
                </h3> -->

                                <!--
                    finde den kleinsten preis der variante

                     $low_price = 20000;
                    foreach ($products as $article_2) {                    
                        if($article['variant_id'] == $article_2['variant_id']){
                            if($article_2['price'] < $low_price){
                                $low_price = $article_2['price'];
                            }
                    }
                -->
                                <?php
                                if ($article['is_main_view_from_variant'] != 0) {
                                    ?>
									<div class="price-discount">

									<?php
									if (
										$article['old_price'] != '' && $article['old_price'] != 0 && $article['price'] != '' &&
										$article['price'] != 0 && $article['price'] != $article['old_price']
									) {

										$percent_friendly = number_format((($article['old_price'] - $article['price']) /
											$article['old_price']) * 100) . '%';
										?>

										<span class="price-discount">
											<?= $article['old_price'] != '' ? number_format($article['old_price'], 2) . ' ' . CURRENCY : '' ?>
										</span>

										<span class="price-down">
											<?= lang_safe('Save_discount') . '  ' . $percent_friendly ?>
										</span>
									<?php } ?>
                                    </div>
                                    <div class="price">
                                        <span>
                                            ab
                                            <?= $article['price'] != '' ? number_format($article['price'], 2) : 0 ?>
                                            <?= CURRENCY ?>
                                        </span>
                                    </div>

                                <?php
                                } else {
                                    ?>

                                   
									<div class="price-discount">

									<?php
									if (
										$article['old_price'] != '' && $article['old_price'] != 0 && $article['price'] != '' &&
										$article['price'] != 0 && $article['price'] != $article['old_price']
									) {

										$percent_friendly = number_format((($article['old_price'] - $article['price']) /
											$article['old_price']) * 100) . '%';
										?>

										<span class="price-discount">
											<?= $article['old_price'] != '' ? number_format($article['old_price'], 2) . ' ' . CURRENCY : '' ?>
										</span>

										<span class="price-down">
											<?= lang_safe('Save_discount') . '  ' . $percent_friendly ?>
										</span>
									<?php } ?>
                                    </div>
									<div class="price">
                                        <span>
                                            <?= $article['price'] != '' ? number_format($article['price'], 2) : 0 ?>
                                            <?= CURRENCY ?>
                                        </span>
                                    </div>

                                <?php

                                }
                                ?>

                                <div class="price-discount">
                                    <span class="mwst">
                                        <?= lang_safe('mwst') ?>
                                    </span>
                                </div>






                                <?php if ($article['delivery_status'] == 0 || $article['delivery_status'] == "") { ?>
                                    <div class="delivery_status_direct_available">
                                        <?= lang_safe('delivery_status_direct_available') ?>
                                    </div>
                                <?php } ?>
                                <?php if ($article['delivery_status'] == 1) { ?>
                                    <div class="delivery_status_available">
                                        <?= lang_safe('delivery_status_available') ?>
                                    </div>
                                <?php } ?>
                                <?php if ($article['delivery_status'] == 2) { ?>
                                    <div class="delivery_status_not_available">
                                        <?= lang_safe('delivery_status_not_available') ?>
                                    </div>
                                <?php } ?>

                                <?php if (GlobalVars::$globalVariables['publicQuantity'] == 1) { ?>
                                    <div class="quantity">
                                        <?= lang_safe('in_stock') ?>: <span>
                                            <?= $article['quantity'] ?>
                                        </span>
                                    </div>
                                <?php }
                                if (GlobalVars::$globalVariables['moreInfoBtn'] == 1) { ?>
                                    <a href="<?= $article['vendor_url'] == null ? LANG_URL . '/' . $article['url'] : LANG_URL . '/' . $article['vendor_url'] . '/' . $article['url'] ?>"
                                        class="info-btn gradient-color">
                                        <span class="text-to-bg">
                                            <?= lang_safe('info_product_list') ?>
                                        </span>
                                    </a>
                                <?php }
                                if (GlobalVars::$globalVariables['hideBuyButtonsOfOutOfStock'] == 0 || (int) $article['quantity'] > 0) {
                                    $hasRefresh = false;
                                    if (GlobalVars::$globalVariables['refreshAfterAddToCart'] == 1) {
                                        $hasRefresh = true;
                                    }
                                    ?>

                                    <!-- <div class="add-to-cart">
                    <a href="javascript:void(0);"
                        class="add-to-cart btn-add <?= $hasRefresh === true ? 'refresh-me' : '' ?>"
                        data-goto="<?= LANG_URL . '/shopping-cart' ?>" data-id="<?= $article['id'] ?>">
                        <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                        <span class="text-to-bg"><?= lang_safe('add_to_cart') ?></span>
                    </a>
                </div> -->
                                    <!-- <div class="add-to-cart">
                    <a href="javascript:void(0);" class="add-to-cart btn-add more-blue"
                        data-goto="<?= LANG_URL . '/checkout' ?>" data-id="<?= $article['id'] ?>">
                        <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                        <span class="text-to-bg"><?= lang_safe('buy_now') ?></span>
                    </a>
                </div> -->
                                <?php } else { ?>
                                    <div>
                                        Product is out of stock
                                    </div>
                                <?php } ?>
                            </div>
                        </div>



                        <?php
                        $i++;
            }
        }
        if ($carousel == true) {
            ?>
                </div>
                <a class="left carousel-control" href="#small_carousel" role="button" data-slide="prev">
                    <i class="fa fa-5x fa-angle-left" aria-hidden="true"></i>
                </a>
                <a class="right carousel-control" href="#small_carousel" role="button" data-slide="next">
                    <i class="fa fa-5x fa-angle-right" aria-hidden="true"></i>
                </a>
            </div>
            <?php
        }
    }
}