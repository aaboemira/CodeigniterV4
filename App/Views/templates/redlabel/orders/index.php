<style>
    .table-responsive .table th,
    .table-responsive .table td {
        border-top: none; /* Remove default top border */
        border-right: none; /* Remove default right border */
        border-left: none; /* Remove default left border */
        border-bottom: 1px solid #ccc; /* Add a bottom border */
        padding: 8px;
        text-align: center;
    }

    .table-responsive .table th {
        background-color: #f0f0f0; /* Grey background for th */
    }
    .icon-border {
        position: relative;
        top:-5px;
        border: 1px solid #ccc; /* Adjust color and thickness of border as needed */
        border-radius: 4px; /* Optional: rounds the corners of the border */
        padding: 5px; /* Adjust padding to increase the clickable area around the icon */
        display: inline-block; /* Ensures the padding and border are applied properly */
        margin-right: 5px; /* Optional: adds some space to the right of the icon */
        /* Additional optional styles */
        text-align: center;
        transition: background-color 0.3s ease; /* Smooth transition for hover effect */
    }

    .icon-border:hover {
        background-color: #f0f0f0; /* Slight background color on hover for visual feedback */
        text-decoration: none; /* Removes the underline text decoration from the anchor tag on hover */
    }
</style>
<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
            <li><a href="<?= LANG_URL ?>/orders"><?= lang_safe('my_orders') ?></a></li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9">
            <h1 style="color:black;font-size: 2.5em;"><?= lang_safe('my_orders') ?></h1>
            <p style="color: black; font-size: 1.5em;"><?= lang_safe('my_orders_description') ?></p>
            <hr>
            <?= lang_safe('user_order_history') ?>
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                    <tr>
                        <th><?= lang_safe('record_number') ?></th>
                        <th><?= lang_safe('usr_order_date') ?></th>
                        <th><?= lang_safe('usr_order_id') ?></th>
                        <th><?= lang_safe('status') ?></th>
                        <th><?= lang_safe('delivery') ?></th>
                        <th><?= lang_safe('total') ?></th>
                        <th><?= lang_safe('action') ?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (!empty($orders)) {
                        $i = 0;
                        foreach ($orders as $order) {
                            $i++;
                            ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= date('d.m.Y', $order['date']) ?></td>
                                <td><?= lang_safe('shnd').$order['order_id'] ?></td>
                                <td><?= lang_safe('order_status_' . $order['order_status']) ?></td>
                                <td>
                                    <?php if (!empty($order['shipping_number'])): ?>
                                        <a href="<?= $shipping_link . $order['shipping_number'] ?>"><?= lang_safe('track_shipment') ?></a>
                                    <?php else: ?>
                                        <?= lang_safe('not_available') ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $order['total_amount'] . CURRENCY ?></td>
                                <td >
                                    <a class="icon-border" href="<?= base_url('/orders/show/' . $order['order_id']) ?>"><i class="fa fa-file-text-o"> Show Details</i></i>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5"><?= lang('usr_no_orders') ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <nav aria-label="Page navigation">
                    <ul class="pagination" style="position:relative !important;z-index:2;">
                        <?= $paginationLinks ?>
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