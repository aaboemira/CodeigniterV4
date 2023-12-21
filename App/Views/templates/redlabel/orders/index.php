<style>
    .table-order .table th,
    .table-order .table td {
        border-top: none; /* Remove default top border */
        border-right: none; /* Remove default right border */
        border-left: none; /* Remove default left border */
        border-bottom: 1px solid #ccc; /* Add a bottom border */
        text-align: center;
    }
    .table-order .table td{
        padding:15px 4px  !important;

    }
    .table-order .table th {
        background-color: #f0f0f0; /* Grey background for th */
        padding: 8px;

    }
    .icon-border {
        position: relative;
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

    /* Base styles for the table */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #dddddd;
    text-align: center;
    padding: 5px;
    vertical-align: middle !important;
}

th {
    background-color: #f2f2f2;
}

.table-order .table tbody tr:nth-child(odd) {
        background-color: #ffffff; /* White background for odd rows */
    }

    .table-order .table tbody tr:nth-child(even) {
        background-color: #f2f2f2 !important; /* Grey background for even rows */
    }
    .icon-border {
        padding: 8px;
    }
    .icon-border i {
        vertical-align: middle;
        font-size: 2.2rem;
    }
/* Styles for mobile devices */
@media only screen and (max-width: 767px) {
    table{
        width: 100% !important;
        display: block !important;
        overflow-x: auto; /* Allows table to be scrollable horizontally if needed */
    }
    th, td {
        min-width: auto !important;
        font-size: 12px; /* Smaller font size for mobile */
        padding: 12px 4px !important; /* Adjust padding for mobile */
        word-wrap: break-word; /* Breaks long words to fit */
    }

    th span, td span {
        font-size: 12px !important; /* Smaller font size for spans */
    }

    th {
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .orders-description{
        font-size: 1em !important;
    }
    .icon-border i {
        vertical-align: middle;
        font-size: 1.6rem;
    }
    /* Assign percentage widths to specific columns */
    th:nth-child(1), td:nth-child(1) { width: 14%; }
    th:nth-child(2), td:nth-child(2) { width: 14%; }
    th:nth-child(3), td:nth-child(3) { width: 14%; }
    th:nth-child(4), td:nth-child(4) { width: 14%; }
    th:nth-child(5), td:nth-child(5) { width: 14%; }
    th:nth-child(6), td:nth-child(6) { width: 15%; }
    th:nth-child(7), td:nth-child(7) { width: 15%; }


        /* Hide the first column */
        th:nth-child(1), td:nth-child(1) {
            display: none;
        }
        th:nth-child(3), td:nth-child(3) {
            display: none;
        }
        .icon-border img{
        width: 15px;
    }
}
</style>
<div class="container-fluid user-page">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="<?= LANG_URL ?>"><?= lang_safe('home') ?></a></li>
            <li><a href="<?= LANG_URL ?>/myaccount"><?= lang_safe('my_account') ?></a></li>
            <li><?= lang_safe('my_orders') ?></li>
        </ol>
        <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9">
            <div class="alone title" style="margin-bottom:20px;">
                <span>
                    <h2>
                    <?= lang_safe('my_orders') ?>
                    </h2>
                </span>
            </div>
            <p class="orders-description" style="color: black; font-size: 1.5em;"><?= lang_safe('my_orders_description') ?></p>
            <hr>
            <?= lang_safe('user_order_history') ?>
            <div class="table-order">
                <?php if (empty($orders)): ?>
                    <p><?= lang_safe('no_orders_found') ?></p>
                <?php else: ?>
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
                                <td>
                                    <a class="icon-border" href="<?= base_url('/orders/show/' . $order['order_id']) ?>">
                                    <i class="fa fa-file-text-o"></i>
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
                <?php endif; ?>
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