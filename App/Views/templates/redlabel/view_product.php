<style>
.img-zoom-result {
    border: 1px solid #d4d4d4;
    width: 70%; /* Adjust as needed */
    aspect-ratio: 1 / 1; /* Set the aspect ratio to 1:1 */
    position: absolute; /* Position the zoom result on top of the column */
    z-index: 2; /* Ensure it's above other elements */
    top: 0; /* Adjust as needed */
    visibility: hidden;
    background-color: white;
}
.zoom-in {
  cursor: zoom-in;
}

.zoom-out {
  cursor: zoom-out;
}
.zoom-mode {
    cursor: zoom-out;
    transform: scale(2); /* Adjust the scale value as needed */
    transform-origin: center center;
}

/* Base styles for the table */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #dddddd;
    text-align: center;
    padding: 5px; /* Reduced padding */
}

th {
    background-color: #f2f2f2;
}
.sale-box {
    position: absolute;
    top: 10px;
    left: 0;
    background-color: #ff0000; /* Red background */
    color: black; /* White text color */
    padding: 10px;
    font-size: 16px;
    box-shadow: 2px 2px 5px rgba(0,0,0,0.3); /* Slight shadow for depth */
    background-color: #FFC107; /* Orange background */

    z-index: 2; /* Ensure it's above the image */
}
@media (min-width:768px){
    .image-preview-container .inner-prev-container{
        overflow: hidden;
    }
}
/* Styles for mobile devices */
@media only screen and (max-width: 767px) {
    table{
        width: 100% !important; /* Fixed width for mobile */
        display: block !important;
    }
    th, td {
        min-width: auto !important;
        font-size: 14px; /* Smaller font size for mobile */
        padding: 12px 4px  !important; /* Even smaller padding for mobile */
        word-wrap: break-word; /* Breaks words to next line */
    }
    th span , td span{
        font-size: 14px !important; /* Smaller font size for mobile */

    }

    th {
        position: sticky;
        top: 0;
        z-index: 2;
    }
        /* Assign percentage widths to specific columns if needed */
    th:nth-child(1), td:nth-child(1) { width: 25%; } /* Example for first column */
    th:nth-child(2), td:nth-child(2) { width: 25%; } /* Example for second column */
    th:nth-child(3), td:nth-child(3) { width: 25%; } /* Example for first column */
    th:nth-child(4), td:nth-child(4) { width: 25%; } /* Example for second column */
    /* Continue as needed for each column */
        .img-zoom-lens, .img-zoom-result {
        visibility: hidden !important;
    }
}

/* Additional styles for very small screens */
@media only screen and (max-width: 350px) {
    th span, td span {
        font-size: 12px !important; /* Smaller font size for very small screens */
        padding: 2px; /* Minimal padding for very small screens */
    }
}
@media only screen and (max-width: 330px) {
    th span, td span {
        font-size: 11px !important; /* Smaller font size for very small screens */
    }
}
.img-zoom-container {
  position: relative;
}
.img-zoom-lens {
    width: 90px;
    height: 90px;
    position: absolute;
    border: 1px solid #d4d4d4;
    visibility: hidden;
    pointer-events: none;
}
</style>
<div class="container" id="view-product">
    <div class="row" style="margin: 0-10px;">
        <div class="col-sm-6 " id="img-zoom-container"style="position:relative;">
            <div <?= $product['folder'] != null ? 'style="margin-bottom:20px; position:relative;"' : '' ?>>
            <?php if (!empty($product['image_text'])):  ?>
                <div class="sale-box"><?= $product['image_text'] ?></div>
            <?php endif; ?>
                <?php
                    
                    $imageName = $product['image_name'];
                    $lastDotPosition = strrpos($imageName, '.');
                    $imageUrl = base_url('/attachments/shop_images/' . $imageName);
                    $imageNameWithResolution_2400 = substr($imageName, 0, $lastDotPosition) . '-2400x2400' . substr($imageName, $lastDotPosition);
                    $imageUrl_2400 = base_url('/attachments/shop_images/' . $imageNameWithResolution_2400);
                ?>
                <img id="myimage"  src="<?= base_url('/attachments/shop_images/'.$product['image_name']) ?>"
                    data-src="<?=$imageUrl_2400?>"
                    data-src-model-2400="<?=$imageUrl_2400?>"
                    data-src-model="<?=$imageUrl?>"

                    style="width:auto; height:auto;"  data-num="0"
                    class="other-img-preview img-responsive img-sl the-image"
                    alt="<?= str_replace('"', "'", $product['title']) ?>">
                    <div class="img-zoom-lens" id="img-zoom-lens"></div>

            </div>
            <!-- Container for the zoomed image, if you want a static position -->
            

            <?php
            if ($product['folder'] != null) {
                $lang = (strpos(LANG_URL, '/en') !== false) ? 'en' : 'de';
                $dir = "attachments/shop_images/" . $lang . "/" . $product['folder'] . '/';

                ?>
                <div class="row">
                    <?php
                    if (is_dir($dir)) {
                        if ($dh = opendir($dir)) {
                            $i = 1;
                            while (($file = readdir($dh)) !== false) {
                                if (is_file($dir . $file) && !preg_match('/-\d+x\d+/', $file)&&strpos($file, '-org') === false) {
                                    // Get the file extension
                                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                                    // Get the file name without the extension
                                    $filenameWithoutExt = pathinfo($file, PATHINFO_FILENAME);
                                    // Construct the filename for the 1200x1200 version
                                    $file250 = $filenameWithoutExt . '-250x250.' . $ext;
                                    // Check if the 1200x1200 version of the image exists
                                    $dataSrc_250 =file_exists($dir . $file250) ? base_url($dir . $file250) : base_url($dir . $file);


                                    $file2400 = $filenameWithoutExt . '-2400x2400.' . $ext;
                                    // Check if the 1200x1200 version of the image exists
                                    $dataSrc_2400 = file_exists($dir . $file2400) ? base_url($dir . $file2400) : base_url($dir . $file);

                                    $dataSrc_650=$dir . $file;
                                    ?>
                                    <div class="col-xs-4 col-sm-6 col-md-4 text-center">
                                        <img src="<?= base_url($dataSrc_250) ?>"
                                            data-num="<?= $i ?>"
                                            data-src-model="<?=base_url($dataSrc_650)?>"
                                            data-src-model-2400="<?=base_url($dataSrc_2400)?>"

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
        <div id="myresult" class="img-zoom-result"></div>

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
                        let url='<?=LANG_URL?>'+"/"+strUser
                        document.location.href = url;
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
                                    data-id="<?= $product['id'] ?>" class="add-to-cart btn-add" style="min-width: 266px;">
                                    <span class="text-to-bg">
                                        <?= lang_safe('add_to_cart') ?>
                                    </span>
                                </a>
                            </div>
                            <div id="paypal-button-container1" style="position:relative;z-index:0; width: 266px;margin-top:6px;"></div>
                            <div
                                data-pp-message
                                data-pp-placement="product"
                                data-pp-amount="<?=$product['price']?>"
                                data-pp-style-text-color="black"

                                ></div>
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
    <div class="row" style="margin:0;">
        <div class="col-sm-12 checkout-buttons" >
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
            <div class="inner-prev-container" >
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
								<a class="btn btn-primary btn-new go-checkout" style="margin-top: 10px; width:80%;" href="<?= LANG_URL . '/checkout1' ?>">
                                    <?= lang_safe('modal_checkout') ?>
                                </a>
                            </div>
                            <div class="col-12" style="margin-top:11px;">
                                <div id="paypal-button-container2" style="margin: auto;position:relative;z-index:0; width:80%;"></div>
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
</div>

    <script src="https://www.paypal.com/sdk/js?client-id=ASTEf-iIF0JeRpMAPTfUOSdumIWKWcHnMpdjDSFCxodtXstVStSyUvdzpBXwnKvVVKUbe2V-wlKMuDf1&currency=EUR&components=buttons,messages,applepay&enable-funding=paylater&disable-funding=card,giropay,sepa,sofort&buyer-country=DE" data-sdk-integration-source="integrationbuilder_sc"></script>
    <script>
        let currentOrderId = null; // Variable to store the order ID
        let currentTotalAmount = 0; // Variable to store

    paypal.Buttons({
        createOrder: function(data, actions) {
            return fetch('<?= site_url('/paypal/create-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    products: [{ id: '<?= $product['id'] ?>', price: '<?= $product['price'] ?>', name: '<?= $product['title'] ?>', quantity: 1 }]
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }
                return response.json();
            })
            .then(function(orderData) {
                if (orderData.error) {
                    throw new Error(orderData.error);
                }
                console.log(orderData)
                console.log('-----------------------1')
                currentTotalAmount = orderData.purchase_units[0].amount.value;

                // Extract and store the total amount from the response
                if (orderData.purchase_units && orderData.purchase_units[0].amount) {
                    currentTotalAmount = orderData.purchase_units[0].amount.value;
                }

                return orderData.id;
            })
            .catch(function(error) {
                console.error('Error:', error);
                ShowNotificator('alert-danger', 'There was an error processing your request ') ;
            });
        },
        onApprove: function(data, actions) {
            return fetch('<?= site_url('/paypal/capture-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }

                return response.json();
            })
            .then(function(orderData) {
                if (orderData.error) {
                    throw new Error(orderData.error);
                }
                console.log(orderData)
                // Call the server endpoint to save the order
                return fetch('<?= site_url('/paypal/save-order') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData) // send the order data received from PayPal
                });
            })
            .then(function(saveOrderResponse) {
                if (!saveOrderResponse.ok) {
                    throw new Error('Network response was not OK');
                }
                return saveOrderResponse.json();
            })
            .then(function(saveOrderData) {
                console.log('Order saved successfully', saveOrderData);
                currentOrderId = saveOrderData.order_id; // Store the saved order ID
                // Call the server endpoint for post-payment processing
                return fetch('<?= site_url('/paypal/postPayment') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type:"product",
                        orderID: currentOrderId // Sending the saved order ID
                    })
                });
            })
            .then(function(postPaymentResponse) {
                if (!postPaymentResponse.ok) {
                    throw new Error('Network response was not OK during post payment processing');
                }
                return postPaymentResponse.json();
            })
            .then(function(postPaymentData) {
                console.log('Post-payment processing completed', postPaymentData);
                window.location.href = '<?= base_url('/paypal/success') ?>';

            })
            .catch(function(error) {
                console.error('Error:', error);
                ShowNotificator('alert-danger', 'There was an error processing your request ') ;
            });
        },
        onShippingChange: function(data, actions) {
        return fetch('<?= site_url('/paypal/calculate-shipping') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                shippingAddress: data.shipping_address
            })
        }).then(function(res) {
            return res.json();
        }).then(function(shippingData) {
            
            let newTotalAmount = parseFloat(currentTotalAmount) + parseFloat(shippingData.shipping_cost);

            // Update the order with the new shipping cost
            return fetch('<?= site_url('/paypal/update-paypal-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID,
                    shippingCost: shippingData.shipping_cost,
                    shippingTitle: shippingData.shipping_title,
                    total_amount:newTotalAmount
                })
            });
        }).catch(function(err) {
            console.error('Shipping calculation or update failed', err);

        });
    },
        
    }).render('#paypal-button-container1');
</script>

   <script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            // First call to prepare-cart endpoint
            return fetch('<?= site_url('/paypal/prepare-cart') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }
                return response.json();
            })
            .then(function(preparedData) {
                if (preparedData.error) {
                    throw new Error(preparedData.error);
                }
                // Now call the create-order endpoint with the prepared data
                return fetch('<?= site_url('/paypal/create-order') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(preparedData)
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not OK');
                    }

                    return response.json();
                })
                .then(function(orderData) {
                    if (orderData.error) {
                        throw new Error(orderData.error);
                    }
                    currentTotalAmount = orderData.purchase_units[0].amount.value;

                    return orderData.id;
                });
            })
            .catch(function(error) {
                console.error('Error:', error);
                ShowNotificator('alert-danger', 'There was an error processing your request ') ;
            });
        },
        onApprove: function(data, actions) {
            return fetch('<?= site_url('/paypal/capture-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }
                return response.json();
            })
            .then(function(orderData) {
                if (orderData.error) {
                    throw new Error(orderData.error);
                }
                return fetch('<?= site_url('/paypal/save-order') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData) // send the order data received from PayPal
                });
230            })
            .then(function(saveOrderResponse) {
                if (!saveOrderResponse.ok) {
                    throw new Error('Network response was not OK');
                }
                return saveOrderResponse.json();
            })
            .then(function(saveOrderData) {

                // Call the server endpoint for post-payment processing
                return fetch('<?= site_url('/paypal/postPayment') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type:"shopping_cart",
                        orderID: saveOrderData.orderID // Sending the saved order ID
                    })
                });
            })
            .then(function(postPaymentResponse) {
                if (!postPaymentResponse.ok) {
                    throw new Error('Network response was not OK during post payment processing');
                }
                return postPaymentResponse.json();
            })
            .then(function(postPaymentData) {
                console.log('Post-payment processing completed', postPaymentData);
                window.location.href = '<?= base_url('/paypal/success') ?>';

            })
            .catch(function(error) {
                console.error('Error:', error);
                ShowNotificator('alert-danger', 'There was an error processing your request ') ;
            });
        },
        onShippingChange: function(data, actions) {
        return fetch('<?= site_url('/paypal/calculate-shipping') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                shippingAddress: data.shipping_address
            })
        }).then(function(res) {
            return res.json();
        }).then(function(shippingData) {
            
            let newTotalAmount = parseFloat(currentTotalAmount) + parseFloat(shippingData.shipping_cost);

            // Update the order with the new shipping cost
            return fetch('<?= site_url('/paypal/update-paypal-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID,
                    shippingCost: shippingData.shipping_cost,
                    shippingTitle: shippingData.shipping_title,
                    total_amount:newTotalAmount
                })
            });
        }).catch(function(err) {
            console.error('Shipping calculation or update failed', err);

        });
    },
    }).render('#paypal-button-container2');
    // Function to add custom text to the PayPal button


// Attempt to add custom text when the script loads
</script>
<script>
function imageZoom(imgID, resultID) {
  var img, lens, result, cx, cy;
  img = document.getElementById(imgID);
  result = document.getElementById(resultID);
  lens = document.getElementById("img-zoom-lens");
  cx = result.offsetWidth / lens.offsetWidth;
  cy = result.offsetHeight / lens.offsetHeight;
  var imgSrc = img.getAttribute('data-src');
  result.style.backgroundImage = "url('" + imgSrc + "')";
  result.style.backgroundSize = (img.width * cx) + "px " + (img.height * cy) + "px";
  

  img.addEventListener("mouseover", function() {
    lens.style.visibility = "visible";
    result.style.visibility = "visible";
  });
  lens.addEventListener("mouseover", function() {
    lens.style.visibility = "visible";
    result.style.visibility = "visible";
  });

  img.addEventListener("mouseout", function() {
    lens.style.visibility = "hidden";
    result.style.visibility = "hidden";
  });
  lens.addEventListener("mouseout", function() {
    lens.style.visibility = "hidden";
    result.style.visibility = "hidden";
  });

  lens.addEventListener("mousemove", moveLens);
  img.addEventListener("mousemove", moveLens);
  lens.addEventListener("touchmove", moveLens);
  img.addEventListener("touchmove", moveLens);
  function moveLens(e) {
    var pos, x, y;
    e.preventDefault();
    pos = getCursorPos(e);
    x = pos.x - (lens.offsetWidth / 2);
    y = pos.y - (lens.offsetHeight / 2);
    if (x > img.width - lens.offsetWidth) {x = img.width - lens.offsetWidth;}
    if (x < 0) {x = 0;}
    if (y > img.height - lens.offsetHeight) {y = img.height - lens.offsetHeight;}
    if (y < 0) {y = 0;}

    lens.style.left = x + "px";
    lens.style.top = y + "px";
    result.style.backgroundPosition = "-" + (x * cx) + "px -" + (y * cy) + "px";
  }
  function getCursorPos(e) {
    var a, x = 0, y = 0;
    e = e || window.event;
    /* Get the x and y positions of the image: */
    a = img.getBoundingClientRect();
    /* Calculate the cursor's x and y coordinates, relative to the image: */
    x = e.pageX - a.left;
    y = e.pageY - a.top;
    /* Consider any page scrolling: */
    x = x - window.pageXOffset;
    y = y - window.pageYOffset;
    return {x : x, y : y};
}
}

document.addEventListener("DOMContentLoaded", function() {
    if (window.innerWidth > 767) { // Only initialize zoom on non-mobile devices
        var img = document.getElementById("myimage");
        if (img.complete) {
            imageZoom("myimage", "myresult");
        } else {
            img.onload = function() {
                imageZoom("myimage", "myresult");
            };
        }
    }
});

</script>








