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

</style>
<div class="container-fluid user-page">
    <div class="row">
            <ol class="breadcrumb">
                <li><a href="<?= LANG_URL ?>">Home</a></li>
                <li><a href="<?= LANG_URL ?>/myaccount">Account</a></li>
                <li>Orders</li>
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
                            $i=0;
                            foreach ($orders as $order) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= date('d.m.Y', $order['date']) ?></td>
                                    <td><?=lang_safe('shnd'). $order['order_id'] ?></td>
                                    <td><?= lang_safe('order_status_'.$order['order_status']) ?></td>
                                    <td>
                                        <?php if (!empty($order['shipping_number'])): ?>
                                            <a href="<?= $shipping_link . $order['shipping_number'] ?>"><?= lang_safe('track_shipment') ?></a>
                                        <?php else: ?>
                                            <?= lang_safe('not_available') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $order['total_amount'] .CURRENCY ?></td>
                                    <td>
                                        <!-- Button to trigger modal -->
                                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" style="position:relative;top:-6px;" data-target="#orderDetailsModal<?= $order['order_id'] ?>">
                                            <i class="fa fa-eye" aria-hidden="true"></i> <!-- Eye icon for Font Awesome 4.7.0 -->
                                        </button>
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
                    <ul class="pagination">
                        <?= $paginationLinks ?>
                    </ul>
                </nav>
            </div>        
            </div>
            
        </div>
    </div>
</div>