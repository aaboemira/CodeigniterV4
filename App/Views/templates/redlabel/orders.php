
<div class="container-fluid user-page">
    <div class="row">
            <ol class="breadcrumb">
                <li><a href="<?= LANG_URL ?>">Home</a></li>
                <li><a href="<?= LANG_URL ?>/myaccount">Account</a></li>
                <li>Orders</li>
            </ol>
            <?= view('templates/redlabel/_parts/sidebar'); ?>

        <div class="col-md-9">

        <?= lang_safe('user_order_history') ?>
            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><?= lang_safe('usr_order_id') ?></th>
                            <th><?= lang_safe('usr_order_date') ?></th>
                            <th><?= lang_safe('usr_order_address') ?></th>
                            <th><?= lang_safe('usr_order_phone') ?></th>
                            <th><?= lang_safe('user_order_products') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($orders_history)) {
                            foreach ($orders_history as $order) {
                                ?>
                                <tr>
                                    <td><?= $order['order_id'] ?></td>
                                    <td><?= date('d.m.Y', $order['date']) ?></td>
                                    <td><?= $order['address'] ?></td>
                                    <td><?= $order['phone'] ?></td>
                                    <td>    
                                        <?php
                                        $arr_products = unserialize($order['products']);
                                        foreach ($arr_products as $product) {
                                            ?>
                                            <div style="word-break: break-all;">
                                                <div>
                                                    <img src="<?= base_url('attachments/shop_images/' . $product['product_info']['image']) ?>" alt="Product" style="width:100px; margin-right:10px;" class="img-responsive">
                                                </div>
                                                <a target="_blank" href="<?= base_url($product['product_info']['url']) ?>">
                                                    <?= base_url($product['product_info']['url']) ?> 
                                                </a> 
                                                <div style=" background-color: #f1f1f1; border-radius: 2px; padding: 2px 5px;"><b><?= lang('user_order_quantity') ?></b> <?= $product['product_quantity']; ?></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <hr>
                                        <?php }
                                        ?>
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
                <?= $links_pagination ?>
            </div>        
            </div>
            
        </div>
    </div>
</div>