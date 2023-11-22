<div class="container" id="checkout-page">


    <?php
    if (!isset($_SESSION['shipping_address']) || !isset($_SESSION['billing_address'])) {
        // Redirect to checkout1
        header('Location: ' . LANG_URL . '/checkout1');
        exit; // Always call exit after a header redirect
    }
    if (isset($cartItems['array']) && $cartItems['array'] != null) {
        ?>

        <?php
        $shippingAddress = session('shipping_address');
        $billingAddress = session('billing_address');
        $discountCodePostedAndValid = ($codeDiscounts == 1 && isset($_POST['discountCode']));
        $finalSum = str_replace(',', '', $cartItems['finalSum']); // Remove commas if present
        $finalSum = is_numeric($finalSum) ? floatval($finalSum) : 0;
        $discountAmount = 0;
        $discount = 0;;
        if ($codeDiscounts == 1 && session('discountCodeResult')) {
            $discountCodeResult = session('discountCodeResult');
            $discountType = $discountCodeResult['type'];
            $discount = isset($discountCodeResult['amount']) ? floatval($discountCodeResult['amount']) : 0;

            if ($discountType === 'percent') {
                // If the discount type is percentage, calculate the discount amount as a percentage of finalSum
                $discountPercent = $discount / 100;
                $discountAmount = $finalSum * $discountPercent;
            } else {
                // If the discount type is not percentage, multiply it directly with finalSum
                $discountAmount = $discount * $finalSum;
            }
        }
        $shippingPrice = is_numeric($shipping_price) ? floatval($shipping_price) : 0;
        $shippingPrice = number_format($shipping_price, 2);
        $totalAmount = round($finalSum, 2) + $shippingPrice - round($discountAmount, 2);
        $totalAmount = number_format(round($totalAmount, 2), 2);
        $discountAmount = number_format($discountAmount, 2);
        ?>
        <?= purchase_steps(1, 1, 1,1) ?>
        <div class="row">
            <div class="col-sm-9 left-side">
                <form method="POST" id="goOrder">
                    <div class="container" >
                        <div class="row">
                            <div class="col-md-3 col-xs-12" style="padding-left: 0 !important;margin-bottom: 1.2em; margin-right: 2em">
                                <div class="title alone" >
                                    <span><?= lang_safe('billing_address') ?></span>
                                </div>
                                <div class="flex-div">
                                    <?= $billingAddress['billing_first_name'] .' '. $billingAddress['billing_last_name'] ?>
                                    <?php if (session()->has('logged_user')):  ?>
                                    <a class="change_address" onclick="changeAddress()"><?=lang_safe('change_address')?></a>
                                    <?php endif?>
                                </div>
                                <div>
                                    <?= $billingAddress['billing_street'] . ' ' . $billingAddress['billing_housenr'] ?>
                                </div>
                                <div>
                                    <?= $billingAddress['billing_post_code'] . ' ' . $billingAddress['billing_city'] ?>
                                </div>
                                <div>
                                    <?= $billingAddress['billing_country'] ?>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12" style="padding-left: 0 !important;margin-bottom: 1.2em; ">
                                <!-- Right column for delivery address -->
                                <div class="title alone" >
                                    <span><?= lang_safe('shipping_address') ?></span>
                                </div>
                                <div class="flex-div">
                                    <?= $shippingAddress['shipping_first_name'] .' '. $shippingAddress['shipping_last_name'] ?>
                                        <?php if (session()->has('logged_user')):  ?>
                                        <a class="change_address" onclick="changeAddress()"><?=lang_safe('change_address')?></a>
                                        <?php endif?>
                                </div>
                                <div>
                                    <?= $shippingAddress['shipping_street'] . ' ' . $shippingAddress['shipping_housenr'] ?>
                                </div>
                                <div>
                                    <?= $shippingAddress['shipping_post_code'] . ' ' . $shippingAddress['shipping_city'] ?>
                                </div>
                                <div>
                                    <?= $shippingAddress['shipping_country'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="title alone">
                        <span><?= lang_safe('checkout_overview') ?></span>
                    </div>
                    <?php
                    if (session('submit_error')) {
                        ?>
                        <hr>
                        <div class="alert alert-danger">
                            <h4><span class="glyphicon glyphicon-alert"></span> <?= lang_safe('finded_errors') ?></h4>
                            <?php
                            foreach (session('submit_error') as $error) {
                                echo $error . '<br>';
                            }
                            ?>
                        </div>
                        <hr>
                        <?php
                    }
                    ?>

                    <div class="container mt-15" id="shopping-cart" style="padding-left: 0 !important;">

                        <div class="table-responsive-xxl">
                            <table class="table  table-products">
                                <thead>
                                <tr style="font-size: medium;">
                                    <th class="text-uppercase text-black align-left" ><?= lang_safe("product") ?></th>
                                    <th class="align-center text-uppercase text-black"><?= lang_safe('quantity') ?></th>
                                    <th class="text-uppercase text-black align-center menge-th"><?= lang_safe('price') ?></th>

                                    <th class="align-right text-uppercase text-black"><?= lang_safe('Summe') ?></th>
                                </tr>
                                </thead>
                                <tbody style="font-size: medium;">
                                <?php foreach ($cartItems['array'] as $item) { ?>
                                    <tr>
                                        <td class="v-align-top produkt" style="padding-left: 0 !important;">
                                            <div class="flex">
                                                <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="quantity[]"
                                                       value="<?= $item['num_added'] ?>">
                                                <div class="relative max-675-img-counter">
                                                    <div>
                                                        <img class="max-675-w-100 prod-img"
                                                             src="<?= base_url('/attachments/shop_images/' . $item['image']) ?>"
                                                             alt="">
                                                        <a onclick="removeProduct(<?= $item['id'] ?>, true,true)"
                                                           class="btn btn-xs btn-danger remove-product rounded-xl color-white bg-black border-black">
                                                            <span class="glyphicon glyphicon-remove top-2"></span>
                                                        </a>
                                                    </div>
                                                    <div class="max-675-counter max-675">
                                                        <a class="btn btn-xs bg-white text-black "
                                                           onclick="removeProduct(<?= $item['id'] ?>, true)"
                                                           href="javascript:void(0);">
                                                <span>
                                                <span class="">
                                                    <svg class="max-675-wh-minus" width="14" height="12"
                                                         viewBox="0 0 58 56" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <line y1="27.5" x2="58" y2="27.5" stroke="black"
                                                              stroke-width="5"/>
                                                    </svg>
                                                </span>
                                                </span>
                                                        </a>

                                                        <span class="quantity-num max-675-price-fs">
                                                <?= $item['num_added'] ?>
                                            </span>
                                                        <a
                                                                class="btn btn-xs refresh-me add-to-cart bg-white text-black border-none <?= $item['quantity'] <= $item['num_added'] ? 'disabled' : '' ?>"
                                                                data-id="<?= $item['id'] ?>" href="javascript:void(0);">
                                                <span>
                                                    <svg class="max-675-wh-plus" width="14" height="12"
                                                         viewBox="0 0 58 56" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <line y1="27.5" x2="58" y2="27.5" stroke="black"
                                                              stroke-width="5"/>
                                                        <line x1="29.5" y1="56" x2="29.5" stroke="black"
                                                              stroke-width="5"/>
                                                    </svg>
                                                </span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col ml-5 max-675-item-price">
                                                    <span class="text-uppercase text-gray fw-light"> <?= $item['shop_category'] ?></span>
                                                    <a class="text-underlined-none text-black"
                                                       href="<?= LANG_URL . '/' . $item['url'] ?>"><span
                                                                class="text-uppercase  fs-15 "><?= $item['title'] ?></span></a>
                                                    <div class="align-right v-align-top gesamt text-gray fw-light max-675-price max-675"><?= number_format((float)($item['price']) * ($item['num_added']), 2, '.', '') . CURRENCY ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-center v-align-top menge min-675">
                                            <a
                                                    class="btn btn-xs bg-white text-black"
                                                    onclick="removeProduct(<?= $item['id'] ?>, true)">
                                            <span>
                                            <span>
                                                <svg class="minus-svg-min-675" width="14" height="12"
                                                     viewBox="0 0 58 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <line y1="27.5" x2="58" y2="27.5" stroke="black" stroke-width="5"/>
                                                </svg>
                                            </span>
                                            </span>
                                            </a>

                                            <span class="quantity-num fs-md-sm">
                                            <?= $item['num_added'] ?>
                                        </span>
                                            <a class="btn btn-xs refresh-me add-to-cart bg-white text-black border-none <?= $item['quantity'] <= $item['num_added'] ? 'disabled' : '' ?>"
                                               data-id="<?= $item['id'] ?>" href="javascript:void(0);"
                                               href="javascript:void(0);">
                                            <span>
                                                <svg class="plus-svg-min-675" width="14" height="12" viewBox="0 0 58 56"
                                                     fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <line y1="27.5" x2="58" y2="27.5" stroke="black" stroke-width="5"/>
                                                    <line x1="29.5" y1="56" x2="29.5" stroke="black" stroke-width="5"/>
                                                </svg>
                                            </span>
                                            </a>
                                        </td>
                                        <td class="align-center v-align-top gesamt text-gray fw-light fs-12 min-675"><?= $item['price'] . CURRENCY ?></td>
                                        <td class="align-right v-align-top gesamt text-gray fw-light fs-12 min-675"><?= number_format((float)($item['price']) * ($item['num_added']), 2, '.', '') . CURRENCY ?></td>

                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="fw-light text-black align-right min-675 fs-12 mb-1"><?= lang_safe('sub_total') ?> :
                            <span class="ml-3 sum-amount"><?= $cartItems['finalSum'] . CURRENCY ?></span>
                        </div>
                        <div class="fw-light text-black align-right min-675 fs-12 mb-1"><?= $shipping_type ?> :
                            <span class="ml-3"><?= $shipping_price . CURRENCY ?></span>
                        </div>
                        <?php if ($codeDiscounts == 1 && session('discountCodeResult') !== false && $discountAmount != 0) { ?>
                            <div id="discountdiv"
                                 class="fw-light text-black align-right min-675 fs-12 mb-1 discountdiv-large">
                                <?= lang_safe('discount_code') ?> : - <span id="discount1"
                                                                            class="ml-3"><?= $discountAmount ?><?= CURRENCY ?></span>
                            </div>
                        <?php } ?>


                        <hr>
                        <input type="hidden" class="final-amount" name="final_amount" value="<?= $totalAmount ?>">
                        <input type="hidden" name="amount_currency" value="<?= CURRENCY ?>">
                        <input type="hidden" name="discountAmount" value=<?= $discountAmount ?>>
                        <input type="hidden" name="discount" value=<?= $discount ?>>

                        <div class="fw-bold text-black align-right min-675 fs-12 mb-1 "><?= lang_safe('total') ?> :
                            <span id="final_amount" class="ml-14 final-amount"><?= $totalAmount ?></span><?= CURRENCY ?>
                        </div>
                        <div class="text-black min-675 align-right mb-10">
                            <span><?= lang_safe('mwst') ?></span>
                        </div>

                        <div class="fw-light text-black align-right max-675 max-675-gesamt fs-10  ">
                            <div class="max-675-justify-between">
                                <div class="fs-12"><?= lang_safe('sub_total') ?> :</div>
                                <span class="ml-3 fs-12 sub-amount"><?= $cartItems['finalSum'] . CURRENCY ?></span>
                            </div>
                        </div>
                        <?php if ($codeDiscounts == 1 && session('discountCodeResult') !== false && $discountAmount != 0) { ?>
                            <div id="discountdiv"
                                 class="fw-light text-black align-right max-675 max-675-gesamt fs-10 discountdiv-small">
                                <div class="max-675-justify-between fs-12">
                                    <?php if (session('discountCodeResult')['type'] == 'percent') { ?>
                                        <div class="fs-12"><?= lang_safe('discount_code') ?> :</div>
                                        <div class="fs-12">- <span id="discount2"
                                                                   class="ml-3"><?= $discountAmount ?><?= CURRENCY ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="fw-light text-black align-right max-675 max-675-gesamt fs-10 ">
                            <div class="max-675-justify-between">
                                <div class="fs-12"><?= $shipping_type ?> :</div>
                                <div class="fs-12"><?= $shipping_price . CURRENCY ?></div>
                            </div>
                        </div>
                        <br><br>
                        <div class="fw-bold text-black align-right max-675 max-675-gesamt">
                            <div class="max-675-justify-between">
                                <div class="fs-12"><?= lang_safe('total') ?> :</div>
                                <div class="fs-12 final-amount" id="final_amount"><?= $totalAmount . CURRENCY ?></div>
                            </div>
                            <div class="align-right mb-5 fw-lighter">
                                <span><?= lang_safe('mwst') ?></span>
                            </div>
                        </div>

                        <?php if ($codeDiscounts == 1) { ?>
                            <div class="discount align-right">
                                <input class="form-control discount-form align-right" name="discountCode"
                                       value="<?= @$_POST['discountCode'] ?>"
                                       placeholder="<?= lang_safe('enter_discount_code') ?>" type="text">
                                <a href="javascript:void(0);" class="btn btn-default"
                                   onclick="checkDiscountCode()"><?= lang_safe('check_code') ?></a>
                            </div>
                        <?php } ?>

                        <input id="goOrder" class="form-control" style="display:none" name="goOrder"
                               value="<?= @$_POST['goOrder'] ?>"
                               type="text" placeholder="">


                        <div class="container checkout-container">
                            <div class="row">
                                <div class="col-sm-12 checkout-buttons">
                                    <br>
                                    <br>
                                    <a class="btn btn-primary btn-new go-order w3-right"
                                       onclick="document.getElementById('goOrder').submit();"
                                       href="javascript:void(0);">
                                        <?= lang_safe('custom_order') ?>
                                        <i class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></i>
                                    </a>
                                    <a href="<?= LANG_URL . '/checkout2' ?>" class="btn btn-primary btn-new"
                                       href="<?= LANG_URL . '/checkout2' ?>">
                                        <span class="glyphicon glyphicon-circle-arrow-left"></span>
                                        <?= lang_safe('back_to_checkout2') ?>
                                    </a>
                                </div>
                            </div>
                        </div>


                </form>


            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-info"><?= lang_safe('no_products_in_cart') ?></div>
        <?php
    } ?>
</div>

<?php
if (session('deleted')) {
    ?>
    <script>
        $(document).ready(function () {
            ShowNotificator('alert-info', '<?= session('deleted') ?>');
        });
    </script>
<?php }
if ($codeDiscounts == 1 && isset($_POST['discountCode'])) { ?>
    <script>
        $(document).ready(function () {
            checkDiscountCode();
        });
    </script>
<?php } ?>
<form id="changeAddress" action="" method="post" style="display: none;">
    <input type="hidden" name="action" value="change_address">
</form>
<script type="text/javascript">
    function changeAddress() {
        document.getElementById('changeAddress').submit();
    }
</script>


