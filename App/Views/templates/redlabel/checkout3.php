<div class="container" id="checkout-page">


    <?php
     $discountCodePostedAndValid = ($codeDiscounts == 1 && isset($_POST['discountCode']));
     $finalSum = str_replace(',', '', $cartItems['finalSum']); // Remove commas if present
     $finalSum = is_numeric($finalSum) ? floatval($finalSum) : 0;
 
     $shippingPrice = is_numeric($shipping_price) ? floatval($shipping_price) : 0;
     $totalAmount = $finalSum + $shippingPrice;
     $totalAmount=number_format($totalAmount, 2);

    if (isset($cartItems['array']) && $cartItems['array'] != null) {
        ?> 
    <?= purchase_steps(1, 2, 3, 4) ?>
    <div class="row">
        <div class="col-sm-9 left-side">
            <form method="POST" id="goOrder">

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

                <div class="container mt-15" id="shopping-cart">

                    <div class="table-responsive-xxl">
                        <table class="table  table-products">
                            <thead>
                            <tr style="font-size: medium;">
                                <th class="text-uppercase text-black align-left"><?= lang_safe("product") ?></th>
                                <th class="align-center text-uppercase text-black"><?= lang_safe('quantity') ?></th>
                                <th class="text-uppercase text-black align-center menge-th"><?= lang_safe('price') ?></th>

                                <th class="align-right text-uppercase text-black"><?= lang_safe('Summe') ?></th>
                            </tr>
                            </thead>
                            <tbody style="font-size: medium;">
                            <?php foreach ($cartItems['array'] as $item) { ?>
                                <tr>
                                    <td class="v-align-top produkt">
                                        <div class="flex">
                                            <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                            <input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
                                            <div class="relative max-675-img-counter">
                                                <div>
                                                    <img class="max-675-w-100 prod-img"
                                                         src="<?= base_url('/attachments/shop_images/' . $item['image']) ?>"
                                                         alt="">
                                                    <a href="<?= base_url('home/removeFromCart?delete-product=' . $item['id'] . '&back-to=shopping-cart') ?>"
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
                    <div class="fw-bold text-black align-right min-675 fs-12 mb-1"><?= lang_safe('sub_total') ?> :
                        <span  class="ml-3"><?= $cartItems['finalSum'] . CURRENCY ?></span>
                    </div>
                    <div class="fw-bold text-black align-right min-675 fs-12 mb-1"><?= $shipping_type ?> :
                        <span  class="ml-3"><?= $shipping_price . CURRENCY ?></span>
                    </div>
                    <div id="discountdiv" class="fw-bold text-black align-right min-675 fs-12 mb-1" style="<?= $discountCodePostedAndValid ? 'display: block;' : 'display: none;' ?>">
                        <?php if ($codeDiscounts == 1) { ?>
                            <?= lang_safe('discount_code') ?> : <span id="discount" class="ml-3">0.00</span><?= CURRENCY ?>
                        <?php } ?>
                    </div>

            
                    <hr>
                    <div class="fw-bold text-black align-right min-675 fs-12 mb-1 "><?= lang_safe('total') ?> :
                        <span id="final_amount" class="ml-14 final-amount"><?=$totalAmount?></span><?= CURRENCY ?>

                        <input type="hidden" class="final-amount" name="final_amount" value="<?= $totalAmount  ?>">
                        <input type="hidden" name="amount_currency" value="<?= CURRENCY ?>">
                        <input type="hidden" name="discountAmount" value="">
                    </div>
                    <div class="text-black min-675 align-right mb-10">
                        <span><?= lang_safe('mwst') ?></span>
                    </div>

                    <div class="fw-bold text-black align-right max-675 max-675-gesamt final-amount">
                        <div class="max-675-justify-between">
                            <div><?= lang_safe('shipping_price') ?> :</div>
                            <div ><?= $cartItems['finalSum'] . CURRENCY ?></div>
                        </div>
                    </div>
                    <div class="fw-bold text-black align-right max-675 max-675-gesamt final-amount">
                        <div class="max-675-justify-between">
                            <div><?= lang_safe('sub_total') ?> :</div>
                            <div ><?= $shipping_price.CURRENCY ?></div>
                        </div>
                    </div>
                    <hr>
                    <div class="fw-bold text-black align-right max-675 max-675-gesamt final-amount">
                        <div class="max-675-justify-between">
                            <div><?= lang_safe('total') ?> :</div>
                            <div id="final_amount"><?= $totalAmount . CURRENCY ?></div>
                        </div>
                        <div class="align-right mb-5 fw-lighter">
                            <span><?= lang_safe('mwst') ?></span>
                        </div>
                    </div>

                    <?php if ($codeDiscounts == 1) { ?>
                        <div class="discount">
                            
                            <input class="form-control" name="discountCode" value="<?= @$_POST['discountCode'] ?>"
                                   placeholder="<?= lang_safe('enter_discount_code') ?>" type="text">
                            <a href="javascript:void(0);" class="btn btn-default"
                               onclick="checkDiscountCode()"><?= lang_safe('check_code') ?></a>
                        </div>
                    <?php } ?>

                    <input id="goOrder" class="form-control" style="display:none" name="goOrder"
                           value="<?= @$_POST['goOrder'] ?>"
                           type="text" placeholder="">


                    <div class="align-right max-675-flex-col">
                        <a class="custom-btn text-dark bg-light fw-light p-2 w-40 max-675-w-100 go-shop"
                           href="<?= LANG_URL . '/checkout2' ?>"><?= lang_safe('back_to_checkout2') ?> </a>
                        <a class="custom-btn text-light bg-black p-2 w-40 max-675-w-100 go-order"
                           href="javascript:void(0);"
                           onclick="document.getElementById('goOrder').submit();"><?= lang_safe('custom_order') ?></a>
                    </div>


                    
            </form>




        </div>
    </div>

</div>

<?php } else { ?>
    <div class="alert alert-info"><?= lang_safe('no_products_in_cart') ?></div>
    <?php
}
if (session('deleted')) {
    ?>
    <script>
        $(document).ready(function () {
            ShowNotificator('alert-info', '<?= session('deleted') ?>');
        });
    </script>
<?php } if ($codeDiscounts == 1 && isset($_POST['discountCode'])) { ?>
    <script>
        $(document).ready(function () {
            checkDiscountCode();
        });
    </script>
<?php } ?>



