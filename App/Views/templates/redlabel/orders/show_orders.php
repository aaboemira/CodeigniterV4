<?php
$subtotal = 0;
foreach ($order['products'] as $product) {
    $subtotal += $product['product_quantity'] * $product['product_info']['price'];
}
// Calculate the total
$total = $subtotal - $order['discount'] + $order['shipping_price'];
?>
<style>
    .details{
        font-size:16px;
    }
    .table-responsive .table th,
    .table-responsive .table td {
        border-top: none; /* Remove default top border */
        border-right: none; /* Remove default right border */
        border-left: none; /* Remove default left border */
        border-bottom: 1px solid #ccc; /* Add a bottom border */

        text-align: center;
    }

    .table-responsive .table th {
        background-color: #f0f0f0; /* Grey background for th */
        padding: 15px 8px 10px 8px !important;
    }
    .table-responsive .table td {
        padding: 25px 8px !important;

    }
    .table-responsive .table tfoot tr td{
        text-align:right;
        padding:4px !important;
        padding-right:0 !important;
        padding-left:0 !important;
    }
    .order-top-info{
        margin-bottom:1.5em;
    }
     .order-top-info h2 {
         font-size: 1.8em; /* Adjust the font size as needed */
         font-weight: normal; /* Normal font weight */
         margin-bottom: 0.5em; /* Space below the heading */
         color:black;

     }
    .order-info-box {
        border: 1px solid #ccc;
        padding: 15px;
        margin-bottom: 1em;
    }
    .order-info-box .info-line {
        display: flex;
        align-items: center; /* Aligns items vertically in the center */
        margin-bottom: 10px; /* Adds space between the lines */
    }
    .order-info-box .info-title {
        font-size: 1em;
        color: #333;
        margin: 0 10px 0 0; /* Adds space to the right of the title */
        white-space: nowrap; /* Prevents the title from wrapping */
    }
    .order-info-box .info-content {
        font-size: 1em;
        color: #666;
        flex: 1; /* Allows the content to fill the remaining space */
        margin: 0;
    }
    .order-info-box textarea {
        flex: 1; /* Allows the textarea to fill the remaining space */
        margin-top: 10px;
        height: auto; /* Adjust height as needed */
    }
    .arrow-down-text {
        border: 1px solid #ccc;
        padding: 5px 10px;
        display: inline-block; /* or 'block' based on your layout */
        margin-right: 0;
        float: right; /* Align to the right */
        font-size: 1em;
        color: #333;
    }
    .arrow-down-text .fa {
        margin-left: 5px;
    }
</style>
<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
            <li><a href="<?= LANG_URL ?>/orders"><?= lang_safe('my_orders') ?></a></li>
            <li><a href="<?= LANG_URL ?>/orders/show/<?= $order['order_id'] ?>"><?= lang_safe('shnd') ?><?= $order['order_id'] ?></a></li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9 details">
            <div class="order-top-info">
                <div class="arrow-down-text">
                    <a href="<?= site_url('generate-invoice/' . $order['order_id']) ?>" target="_blank"><span><?=lang_safe('download_invoice')?></span> <i class="fa fa-arrow-down"></i></a>
                </div>
                <h2><?= lang_safe('order_from') ?> <?= date('d.m.Y', $order['date']) ?></h2>
                <p><?= lang_safe('order_number') ?>: <?= lang_safe('shnd') ?><?= $order['order_id'] ?></p>

            </div>

            <div class="row" style="margin:0px !important;">
                <div class="col-md-3 col-xs-12" style="padding-left: 0 !important;margin-bottom: 1.2em; margin-right: 2em">
                    <div class="title alone">
                        <span><?= lang_safe('billing_address') ?></span>
                    </div>
                    <div class="flex-div">
                        <?= $order['billing_first_name'] .' '. $order['billing_last_name'] ?>
                    </div>
                    <div>
                        <?= $order['billing_street'] . ' ' . $order['billing_housenr'] ?>
                    </div>
                    <div>
                        <?= $order['billing_post_code'] . ' ' . $order['billing_city'] ?>
                    </div>
                    <div>
                        <?= $order['billing_country'] ?>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12" style="padding-left: 0 !important;margin-bottom: 1.2em;">
                    <div class="title alone">
                        <span><?= lang_safe('shipping_address') ?></span>
                    </div>
                    <div class="flex-div">
                        <?= $order['shipping_first_name'] .' '. $order['shipping_last_name'] ?>
                    </div>
                    <div>
                        <?= $order['shipping_street'] . ' ' . $order['shipping_housenr'] ?>
                    </div>
                    <div>
                        <?= $order['shipping_post_code'] . ' ' . $order['shipping_city'] ?>
                    </div>
                    <div>
                        <?= $order['shipping_country'] ?>
                    </div>
                </div>
            </div>
            <div class="row" style="margin:0px !important;">
                <!-- Order State and Payment Type Information -->
                <div class="col-md-6 col-xs-12" style="padding-left: 0 !important;">
                    <div class="order-info-box">
                        <div class="info-line">
                            <h3 class="info-title"><?= lang_safe('status') ?>:</h3>
                            <p class="info-content"><?= lang_safe('order_status_' . $order['order_status']) ?></p>
                        </div>

                        <div class="info-line">
                            <h3 class="info-title"><?= lang_safe('payment_type') ?>:</h3>
                            <p class="info-content"><?= $order['payment_type'] ?></p>
                        </div>

                        <div class="info-line">
                            <h3 class="info-title"><?= lang_safe('notes') ?>:</h3>
                            <p class="info-content"><?= !empty($order['notes']) ? $order['notes'] : lang_safe('none') ?></p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table orders-table">
                    <thead>
                    <tr>
                        <th>POS.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Menge</th>
                        <th>Preis</th>
                        <th>Summe</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order['products'] as $index => $product): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td style="padding-top:10px !important;"><img width="70" src="<?=base_url('/attachments/shop_images/'. $product['product_info']['image']) ?>" alt="<?= $product['product_info']['url'] ?>"></td>
                            <td><?= $product['product_info']['url'] ?></td>
                            <td><?= $product['product_quantity'] ?></td>
                            <td><?= $product['product_info']['price'].CURRENCY ?></td>

                            <td><?= number_format($product['product_info']['price'] * $product['product_quantity'], 2).CURRENCY ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <!-- Assume $order contains all the necessary details, including products, currency, discount, etc. -->
                    <tfoot>
                    <tr>
                        <td colspan="2" style="border:0;"></td>
                        <td colspan="3" style="text-align:right;border:0;"><?= lang_safe('sub_total')?> :</td>
                        <td style="text-align:right;border:0;"><?= number_format($subtotal, 2) .CURRENCY ?></td>
                    </tr>
                    <?php if ($order['discount'] != 0): ?>
                        <tr>
                            <td colspan="2" style="border:0;"></td>
                            <td colspan="3" style="text-align:right;border:0;">Discount :</td>
                            <td style="text-align:right;border:0;">-<?= number_format($order['discount'], 2) .CURRENCY ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($order['shipping_price'] != 0): ?>
                        <tr>
                            <td colspan="2" style="border:0;"></td>
                            <td colspan="3" style="text-align:right;border:0;"><?= $order['shipping_type'] ?> :</td>
                            <td style="text-align:right;border:0;"><?= number_format($order['shipping_price'], 2) .CURRENCY ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="2" style="border:0;"></td>
                        <td colspan="3" style="text-align:right;border:0;"><strong><?= lang_safe('total')?> :</strong></td>
                        <td style="text-align:right;border:0;"><strong><?= number_format($total, 2) .CURRENCY ?></strong></td>
                    </tr>
                    </tfoot>

                </table>

                <nav aria-label="Page navigation">
                    <ul class="pagination">
                    </ul>
                </nav>
            </div>
        </div>

    </div>

</div>
</div>
<script>
    $('.view-order-details').click(function() {
        var orderId = $(this).data('order-id');
        // Make an AJAX call to fetch order details
        $.ajax({
            url: 'your-server-endpoint', // Server endpoint to fetch order details
            method: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                // Populate the modal with order details
                $('#orderDetailsModal .modal-body').html(response);
                // Show the modal
                $('#orderDetailsModal').modal('show');
            }
        });
    });

</script>