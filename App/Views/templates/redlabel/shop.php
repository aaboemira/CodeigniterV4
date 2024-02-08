<?php

if (count($sliderProducts) > 0) {
?>
<!-- top artikel - caroussel -->
<div id="home-slider" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php
		$i = 0;
		while ($i < count($sliderProducts)) {
			?>
        <li data-target="#home-slider" data-slide-to="0" class="<?= $i == 0 ? 'active' : '' ?>"></li>
        <?php
			$i++;
		}
		?>
    </ol>
    <div class="container ">
        <div class="carousel-inner " role="listbox">
            <?php
			$i = 0;
			foreach ($sliderProducts as $article) {
				?>
            <div class="item  <?= $i == 0 ? 'active' : '' ?>">
                <div class="row">
                    <div class="col-sm-6 left-side">
                        <a href="<?= LANG_URL . '/' . $article['url'] ?>">
                            <img src="<?= base_url('attachments/shop_images/' . $article['image']) ?>"
                                class="img-responsive" alt="">
                        </a>
                    </div>
                    <div class="col-sm-6 right-side ">
                        <h3 class="text-right">
                            <a href="<?= LANG_URL . '/' . $article['url'] ?>">
                                <?= character_limiter($article['title'], 100) ?>
                            </a>
                        </h3>
                        <div class="description text-right">
                            <?= character_limiter(strip_tags($article['basic_description']), 150) ?>
                        </div>
                        <div class="price text-right"><?= $article['price'] . CURRENCY ?></div>
                        <div class="xs-center">
                            <?php if ($hideBuyButtonsOfOutOfStock == 0 || (int)$article['quantity'] > 0) { ?>
                            <a class="option add-to-cart" data-goto="<?=LANG_URL .'/checkout' ?>"
                                href="javascript:void(0);" data-id="<?= $article['id'] ?>">
                                <img src="<?= base_url('template/imgs/shopping-cart-icon-515.png') ?>" alt="">
                                <?= lang_safe('buy_now') ?>
                            </a>
                            <?php } ?>
                            <a class="option right-5" href="<?= LANG_URL . '/' . $article['url'] ?>">
                                <img src="<?= base_url('template/imgs/info.png') ?>" alt="">
                                <?= lang_safe('details') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
				$i++;
			}
			?>
        </div>
    </div>
    <a class="left carousel-control" href="#home-slider" role="button" data-slide="prev"></a>
    <a class="right carousel-control" href="#home-slider" role="button" data-slide="next"></a>
</div>
<?php } ?>

<div id="top-part-shop">
    <div class="w3-container">
        <div class="row">
            <!-- Suche nach text, versteckt, muss erhalten bliben, sonst funktioniert die kateorisierung nicht mehr -->
            <div class="col-sm-6 col-md-5 col-lg-5 w3-hide-ever">
                <div class="input-group" id="adv-search">
                    <input type="text" value="<?= isset($_GET['search_in_title']) ? $_GET['search_in_title'] : '' ?>"
                        id="search_in_title" class="form-control"
                        placeholder="<?= lang_safe('search_by_keyword_title') ?>" />
                    <div class="input-group-btn">
                        <div class="btn-group" role="group">
                            <div class="dropdown dropdown-lg">
                                <button type="button" class="button-more dropdown-toggle mine-color"
                                    data-toggle="dropdown" aria-expanded="false"><?= lang_safe('more') ?> <span
                                        class="caret"></span></button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    <form class="form-horizontal" method="GET"
                                        action="<?= isset($vendor_url) ? $vendor_url : LANG_URL . '/shop'  ?>"
                                        id="bigger-search">
                                        <input type="hidden" name="category"
                                            value="<?= isset($_GET['category']) ? htmlspecialchars($_GET['category']) : '' ?>">
                                        <input type="hidden" name="in_stock"
                                            value="<?= isset($_GET['in_stock']) ? htmlspecialchars($_GET['in_stock']) : '' ?>">
                                        <input type="hidden" name="search_in_title"
                                            value="<?= isset($_GET['search_in_title']) ? htmlspecialchars($_GET['search_in_title']) : '' ?>">
                                        <input type="hidden" name="order_new"
                                            value="<?= isset($_GET['order_new']) ? htmlspecialchars($_GET['order_new']) : '' ?>">
                                        <input type="hidden" name="order_price"
                                            value="<?= isset($_GET['order_price']) ? htmlspecialchars($_GET['order_price']) : '' ?>">
                                        <input type="hidden" name="order_procurement"
                                            value="<?= isset($_GET['order_procurement']) ? htmlspecialchars($_GET['order_procurement']) : '' ?>">
                                        <input type="hidden" name="brand_id"
                                            value="<?= isset($_GET['brand_id']) ? htmlspecialchars($_GET['brand_id']) : '' ?>">
                                        <div class="form-group">
                                            <label for="quantity_more"><?= lang_safe('quantity_more_than') ?></label>
                                            <input type="text"
                                                value="<?= isset($_GET['quantity_more']) ? htmlspecialchars($_GET['quantity_more']) : '' ?>"
                                                name="quantity_more" id="quantity_more"
                                                placeholder="<?= lang_safe('type_a_number') ?>" class="form-control">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="added_after"><?= lang_safe('added_after') ?></label>
                                                    <div class="input-group date">
                                                        <input type="text"
                                                            value="<?= isset($_GET['added_after']) ? htmlspecialchars($_GET['added_after']) : '' ?>"
                                                            name="added_after" id="added_after"
                                                            class="form-control"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-th"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="added_before"><?= lang_safe('added_before') ?></label>
                                                    <div class="input-group date">
                                                        <input type="text"
                                                            value="<?= isset($_GET['added_before']) ? htmlspecialchars($_GET['added_before']) : '' ?>"
                                                            name="added_before" id="added_before"
                                                            class="form-control"><span class="input-group-addon"><i
                                                                class="glyphicon glyphicon-th"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="search_in_body"><?= lang_safe('search_by_keyword_body') ?></label>
                                            <input class="form-control"
                                                value="<?= isset($_GET['search_in_body']) ? htmlspecialchars($_GET['search_in_body']) : '' ?>"
                                                name="search_in_body" id="search_in_body" type="text" />
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="price_from"><?= lang_safe('price_from') ?></label>
                                                    <input type="text"
                                                        value="<?= isset($_GET['price_from']) ? htmlspecialchars($_GET['price_from']) : '' ?>"
                                                        name="price_from" id="price_from" class="form-control"
                                                        placeholder="<?= lang_safe('type_a_number') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="price_to"><?= lang_safe('price_to') ?></label>
                                                    <input type="text" name="price_to"
                                                        value="<?= isset($_GET['price_to']) ? htmlspecialchars($_GET['price_to']) : '' ?>"
                                                        id="price_to" class="form-control"
                                                        placeholder="<?= lang_safe('type_a_number') ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-inner-search">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                        <a class="btn btn-default" id="clear-form"
                                            href="javascript:void(0);"><?= lang_safe('clear_form') ?></a>
                                    </form>
                                </div>
                            </div>
                            <button type="button" onclick="submitForm()" class="btn-go-search mine-color">
                                <img src="<?= base_url('template/imgs/search-ico.png') ?>" alt="Search">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="w3-container" id="home-page">
    <div class="row">
         <div class="col-md-2">
            <div class="filter-sidebar">
                <div class="title">
                    <span><?= lang_safe('categories') ?></span>
                    <?php if (isset($_GET['category']) && $_GET['category'] != '') { ?>
                    <a href="javascript:void(0);" class="clear-filter" data-type-clear="category" data-toggle="tooltip"
                        data-placement="right" title="<?= lang_safe('clear_the_filter') ?>"><i class="fa fa-times"
                            aria-hidden="true"></i></a>
                    <?php } ?>
                </div>
                <a href="javascript:void(0)" id="show-xs-nav" class="visible-xs visible-sm">
                    <span class="show-sp"><?= lang_safe('showXsNav') ?><i class="fa fa-arrow-circle-o-down"
                            aria-hidden="true"></i></span>
                    <span class="hidde-sp"><?= lang_safe('hideXsNav') ?><i class="fa fa-arrow-circle-o-up"
                            aria-hidden="true"></i></span>
                </a>
                <div id="nav-categories">
                    <?php

                    function loop_tree($pages, $is_recursion = false)
                    {
                        ?>
                    <ul class="<?= $is_recursion === true ? 'children' : 'parent' ?>">
                        <?php
                            foreach ($pages as $page) {
                                $children = false;
                                if (isset($page['children']) && !empty($page['children'])) {
                                    $children = true;
                                }
                                ?>
                        <li>
                            <?php if ($children === true) {
                                        ?>
                            <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            <?php } else { ?>
                            <i class="fa fa-circle-o" aria-hidden="true"></i>
                            <?php } ?>
                            <a href="javascript:void(0);" data-categorie-id="<?= $page['id'] ?>"
                                class="go-category left-side <?= isset($_GET['category']) && $_GET['category'] == $page['id'] ? 'selected' : '' ?>">
                                <?= $page['name'] ?>
                            </a>
                            <?php
                            if ($children === true) {
                                loop_tree($page['children'], true);
                            } else {
                            ?>
                        </li>
                        <?php
                                }
                            }
                            ?>
                    </ul>
                    <?php
                        if ($is_recursion === true) {
                            ?>
                    </li>
                    <?php
                        }
                    }

                    loop_tree($home_categories);
                    ?>
                </div>
            </div>
            <?php if ($showBrands == 1) { ?>
            <div class="filter-sidebar">
                <div class="title">
                    <span><?= lang_safe('brands') ?></span>
                    <?php if (isset($_GET['brand_id']) && $_GET['brand_id'] != '') { ?>
                    <a href="javascript:void(0);" class="clear-filter" data-type-clear="brand_id" data-toggle="tooltip"
                        data-placement="right" title="<?= lang_safe('clear_the_filter') ?>"><i class="fa fa-times"
                            aria-hidden="true"></i></a>
                    <?php } ?>
                </div>
                <ul>
                    <?php foreach ($brands as $brand) { ?>
                    <li>
                        <i class="fa fa-chevron-right" aria-hidden="true"></i> <a href="javascript:void(0);"
                            data-brand-id="<?= $brand['id'] ?>"
                            class="brand <?= isset($_GET['brand_id']) && $_GET['brand_id'] == $brand['id'] ? 'selected' : '' ?>"><?= $brand['name'] ?></a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            
            <?php } if ($shippingOrder != 0 && $shippingOrder != null) { ?>
            <div class="filter-sidebar">
                <div class="title">
                    <span><?= lang_safe('freeShippingHeader') ?></span>
                </div>
                <div class="oaerror info">
                    <strong><?= lang_safe('promo') ?></strong> -
                    <?= str_replace(array('%price%', '%currency%'), array($shippingOrder, CURRENCY), lang_safe('freeShipping')) ?>!
                </div>
            </div>
            <?php } ?>
        </div> 
        <div class="col-md-10 eqHeight" id="products-side">
            <div class="alone title">
                <span><?= lang_safe('products') ?></span>
            </div>
            <!-- Produkt sortieung, unsichtbar, da zu wenig Produkte im Shop -->
            <div class="product-sort gradient-color w3-hide-ever">
                <div class="row">
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_new">
                                <option
                                    <?= isset($_GET['order_new']) && $_GET['order_new'] == "desc" ? 'selected' : '' ?>
                                    <?= !isset($_GET['order_new']) || $_GET['order_new'] == "" ? 'selected' : '' ?>
                                    value="desc"><?= lang_safe('new') ?> </option>
                                <option
                                    <?= isset($_GET['order_new']) && $_GET['order_new'] == "asc" ? 'selected' : '' ?>
                                    value="asc"><?= lang_safe('old') ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_price"
                                title="<?= lang_safe('price_title') ?>..">
                                <option label="<?= lang_safe('not_selected') ?>"></option>
                                <option
                                    <?= isset($_GET['order_price']) && $_GET['order_price'] == "asc" ? 'selected' : '' ?>
                                    value="asc"><?= lang_safe('price_low') ?> </option>
                                <option
                                    <?= isset($_GET['order_price']) && $_GET['order_price'] == "desc" ? 'selected' : '' ?>
                                    value="desc"><?= lang_safe('price_high') ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_procurement"
                                title="<?= lang_safe('procurement_title') ?>..">
                                <option label="<?= lang_safe('not_selected') ?>"></option>
                                <option
                                    <?= isset($_GET['order_procurement']) && $_GET['order_procurement'] == "desc" ? 'selected' : '' ?>
                                    value="desc"><?= lang_safe('procurement_desc') ?> </option>
                                <option
                                    <?= isset($_GET['order_procurement']) && $_GET['order_procurement'] == "asc" ? 'selected' : '' ?>
                                    value="asc"><?= lang_safe('procurement_asc') ?> </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Produkt Auflistung -->
                <?php
                if (!empty($products)) {            
                        $load::getProducts($products, 'col-sm-6 col-md-3', false);
                } else {
                    ?>
                <script>
                $(document).ready(function() {
                    ShowNotificator('alert-info', '<?= lang_safe('no_results') ?>');
                });
                </script>
                <?php
                }
                ?>
        </div>
    </div>
    <?php if ($links_pagination != '') { ?>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <?= $links_pagination ?>
        </div>
    </div>
    <?php } ?>
</div>




