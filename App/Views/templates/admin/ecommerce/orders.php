<link href="<?= base_url('assets/css/bootstrap-toggle.min.css') ?>" rel="stylesheet">
<div>
    <h1><img src="<?= base_url('assets/imgs/orders.png') ?>" class="header-img" style="margin-top:-2px;"> Orders
        <?= isset($_GET['settings']) ? ' / Settings' : '' ?></h1>
    <?php if (!isset($_GET['settings'])) { ?>
        <a href="?settings" class="pull-right orders-settings"><i class="fa fa-cog" aria-hidden="true"></i>
            <span>Settings</span></a>
    <?php } else { ?>
        <a href="<?= base_url('admin/orders') ?>" class="pull-right orders-settings"><i class="fa fa-angle-left"
                aria-hidden="true"></i> <span>Back</span></a>
    <?php } ?>
</div>
<hr>

<?php
if (!isset($_GET['settings'])) {
    if (!empty($orders)) {
        ?>
        <div style="margin-bottom:10px;">
            <select class="selectpicker changeOrder">
                <option <?= isset($_GET['order_by']) && $_GET['order_by'] == 'id' ? 'selected' : '' ?> value="id">Order by new
                </option>
                <option <?= (isset($_GET['order_by']) && $_GET['order_by'] == 'processed') || !isset($_GET['order_by']) ? 'selected' : '' ?> value="processed">Order by not processed</option>
            </select>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Preview</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($orders as $tr) {
                        if ($tr['processed'] == 0) {
                            $class = 'bg-danger';
                            $type = 'No processed';
                        }
                        if ($tr['processed'] == 1) {
                            $class = 'bg-success';
                            $type = 'Processed';
                        }
                        if ($tr['processed'] == 2) {
                            $class = 'bg-warning';
                            $type = 'Rejected';
                        }
                        ?>
                        <tr>
                            <td class="relative" id="order_id-id-<?= $tr['order_id'] ?>">
                                # <?= $tr['order_id'] ?>
                                <?php if ($tr['viewed'] == 0) { ?>
                                    <div id="new-order-alert-<?= $tr['id'] ?>">
                                        <img src="<?= base_url('assets/imgs/new-blinking.gif') ?>" style="width:100px;" alt="blinking">
                                    </div>
                                <?php } ?>
                                <div class="confirm-result">
                                    <?php if ($tr['confirmed'] == '1') { ?>
                                        <span class="label label-success">Confirmed by email</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">Not Confirmed</span>
                                    <?php } ?>
                                </div>
                            </td>
                            <td>
                                <?= date('d.M.Y / H:i:s', $tr['date']); ?>
                            </td>
                            <td>
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <?= $tr['first_name'] . ' ' . $tr['last_name'] ?>
                            </td>
                            <td><i class="fa fa-phone" aria-hidden="true"></i>
                                <?= $tr['phone'] ?>
                            </td>
                            <td class="<?= $class ?> text-center" data-action-id="<?= $tr['id'] ?>">
                                <div class="status" style="padding:5px; font-size:16px;">
                                    -- <b>
                                        <?= $type ?>
                                    </b> --
                                </div>
                                <div style="margin-bottom:4px;"><a href="javascript:void(0);"
                                        onclick="changeOrdersOrderStatus(<?= $tr['id'] ?>, 1, '<?= htmlentities($tr['products']) ?>', '<?= $tr['email'] ?>')"
                                        class="btn btn-success btn-xs">Processed</a></div>
                                <div style="margin-bottom:4px;"><a href="javascript:void(0);"
                                        onclick="changeOrdersOrderStatus(<?= $tr['id'] ?>, 0)" class="btn btn-danger btn-xs">No
                                        processed</a></div>
                                <div style="margin-bottom:4px;"><a href="javascript:void(0);"
                                        onclick="changeOrdersOrderStatus(<?= $tr['id'] ?>, 2)"
                                        class="btn btn-warning btn-xs">Rejected</a></div>
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0);" class="btn btn-default more-info" data-toggle="modal"
                                    data-target="#modalPreviewMoreInfo" style="margin-top:10%;"
                                    data-more-info="<?= $tr['order_id'] ?>">
                                    More Info
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </a>
                            </td>
                            <td class="hidden" id="order-id-<?= $tr['order_id'] ?>">
                                <div class="container">
                                    <div> <b>Dataen für weiterverarbeitung</b> </div>

                                    <div>
                                        <?= $tr['email'] ?>
                                    </div>
                                    <div>
                                        <?= 'SHND' . $tr['order_id'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['first_name'] . ' ' . $tr['last_name'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['company'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['street'] ?>
                                    </div>
                                    <div>
                                        <?= 'Houes:'.$tr['housenr'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['post_code'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['city'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['country'] ?>
                                    </div>
                                </div>

                                <br></br>

                                <div class="container">
                                    <div> <b>Adresse</b> </div>
                                    <div>
                                        <?= 'SHND' . $tr['order_id'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['email'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['first_name'] . ' ' . $tr['last_name'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['company'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['street'] . ' ' . $tr['housenr'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['post_code'] . ' ' . $tr['city'] ?>
                                    </div>
                                    <div>
                                        <?= $tr['country'] ?>
                                    </div>
                                </div>

                                <div class="container">
                                <div style="margin-bottom: 4px;">
                                    <form id="sendMailForm" method="post">
                                        <div class="input-group" style="margin-bottom:10px">
                                            <input placeholder="Shipping Number" type="text" class="form-control" name="shipping_number">
                                        </div>
                                        <input type="hidden" name="action" value="sendBestellbestaetigung">
                                        <input type="hidden" id="id" name="id" value="<?= $tr['id'] ?>">
                                        <input type="hidden" id="full_name" name="full_name" value="<?= $tr['first_name'] . ' ' . $tr['last_name'] ?>">
                                        <input type="hidden" id="country" name="country" value="<?= $tr['country'] ?>">
                                        <input type="hidden" id="email" name="email" value="<?= $tr['email'] ?>">
                                        <input type="hidden" id="housenr" name="addr_1" value="<?= $tr['street'] . ' ' . $tr['housenr'] ?>">
                                        <input type="hidden" id="post_code" name="addr_2" value="<?= $tr['post_code'] . ' ' . $tr['city'] ?>">
                                        <input type="hidden" id="company" name="company" value="<?= $tr['company'] ?>">
                                        <input type="hidden" id="shipping_type" name="shipping_type" value="<?= $tr['shipping_type'] ?>">
                                        <input type="hidden" id="payment_type" name="payment_type" value="<?= $tr['payment_type'] ?>">
                                        <input type="hidden" id="order_id" name="order_id" value="<?= $tr['order_id'] ?>">
                                        <input type="hidden" id="discount" name="discount" value="<?= $tr['discount'] ?>">
                                        <input type="hidden" id="arr_products" name="products" value="<?= htmlentities($tr['products'])?>">
                                        <input type="hidden" id="order_date" name="order_date" value="<?= $tr['date']?>">
                                        <input type="hidden" id="shipping_price" name="shipping_price" value="<?=  $tr['shipping_price']?>">

                                       
                                        <button type="submit" class="btn btn-warning btn-xs">Sende Bestätigungsmail</button>
                                    </form>
                                </div>
                            </div>


                                <div class="table-responsive">
                                    <table class="table more-info-purchase">
                                        <tbody>
                                            <tr>
                                                <td><b>Name</b></td>
                                                <td>
                                                    <?= $tr['first_name'] . ' ' . $tr['last_name'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Firma</b></td>
                                                <td>
                                                    <?= $tr['company'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Email</b></td>
                                                <td><a href="mailto:<?= $tr['email'] ?>"><?= $tr['email'] ?></a></td>
                                            </tr>
                                            <tr>
                                                <td><b>City</b></td>
                                                <td>
                                                    <?= $tr['city'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Street</b></td>
                                                <td>
                                                    <?= $tr['street'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>housenr</b></td>
                                                <td>
                                                    <?= $tr['housenr'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>country</b></td>
                                                <td>
                                                    <?= $tr['country'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Postcode</b></td>
                                                <td>
                                                    <?= $tr['post_code'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Notes</b></td>
                                                <td>
                                                    <?= $tr['notes'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Come from site</b></td>
                                                <td>
                                                    <?php if ($tr['referrer'] != 'Direct') { ?>
                                                        <a target="_blank" href="<?= $tr['referrer'] ?>" class="orders-referral">
                                                            <?= $tr['referrer'] ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        Direct traffic or referrer is not visible
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Payment Type</b></td>
                                                <td>
                                                    <?= $tr['payment_type'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Discount</b></td>
                                                <td>
                                                    <?= $tr['discount_type'] == 'float' ? '-' . $tr['discount_amount'] : '-' . $tr['discount_amount'] . '%' ?>
                                                </td>
                                            </tr>
                                            <?php if ($tr['payment_type'] == 'PayPal') { ?>
                                                <tr>
                                                    <td><b>PayPal Status</b></td>
                                                    <td>
                                                        <?= $tr['paypal_status'] ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><b>Shipping Type</b></td>
                                                <td>
                                                    <?= $tr['shipping_type'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Shipping Price</b></td>
                                                <td>
                                                    <?= $tr['shipping_price'] ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><b>Products</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?php
                                                    $total_amount_order = 0;

                                                    $arr_products = unserialize($tr['products']);
                                                    foreach ($arr_products as $product) {
                                                        $total_amount = 0;
                                                        $total_amount += round(str_replace(' ', '', str_replace(',', '.', $product['product_info']['price'])), 2);
                                                        $total_amount *= $product['product_quantity'];

                                                        $total_amount_order += $total_amount;
                                                        ?>
                                                        <div style="word-break: break-all;">
                                                            <div>
                                                                <img src="<?= base_url('attachments/shop_images/' . $product['product_info']['image']) ?>"
                                                                    alt="Product" style="width:100px; margin-right:10px;"
                                                                    class="img-responsive">
                                                            </div>
                                                            <a data-toggle="tooltip" data-placement="top" title="Click to preview"
                                                                target="_blank" href="<?= base_url($product['product_info']['url']) ?>">
                                                                <?= base_url($product['product_info']['url']) ?>
                                                                <div
                                                                    style=" background-color: #f1f1f1; border-radius: 2px; padding: 2px 5px;">
                                                                    <b>Quantity:</b>
                                                                    <?= $product['product_quantity'] ?> /
                                                                    <b>Price:
                                                                        <?= $product['product_info']['price'] . ' ' . config('config')->currency ?>
                                                                    </b>
                                                                </div>
                                                            </a>
                                                            <div class="">
                                                                <b>Vendor:</b>
                                                                <a href="">
                                                                    <?= $product['product_info']['vendor_name'] ?>
                                                                </a>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div style="padding-top:10px; font-size:16px;">Total amount of products:
                                                            <?= $total_amount . ' ' . config('config')->currency ?>
                                                        </div>
                                                        <hr>
                                                    <?php }
                                                    $total_amount_order_netto = round($total_amount_order / 1.19, 2);
                                                    $total_order_mwst19 = $total_amount_order - $total_amount_order_netto;
                                                    ?>


                                                    <div style="padding-top:10px; font-size:16px;">Total amount of order netto:
                                                        <?= $total_amount_order_netto . ' ' . config('config')->currency ?>
                                                    </div>
                                                    <div style="padding-top:10px; font-size:16px;">Total amount of order mwst 19%:
                                                        <?= $total_order_mwst19 . ' ' . config('config')->currency ?>
                                                    </div>
                                                    <div style="padding-top:10px; font-size:16px;">Total amount of order brutto:
                                                        <?= $total_amount_order . ' ' . config('config')->currency ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?= $links_pagination ?>
    <?php } else { ?>
        <div class="alert alert-info">No orders to the moment!</div>
    <?php }
    ?>
    <hr>
    <?php
}
if (isset($_GET['settings'])) {
    ?>
    <h3>Cash On Delivery</h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">Change visibility of this purchase option</div>
                <div class="panel-body">
                    <?php if ($this->session->flashdata('cashondelivery_visibility')) { ?>
                        <div class="alert alert-info">
                            <?= $this->session->flashdata('cashondelivery_visibility') ?>
                        </div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="cashondelivery_visibility" value="<?= $cashondelivery_visibility ?>">
                        <input <?= $cashondelivery_visibility == 1 ? 'checked' : '' ?> data-toggle="toggle"
                            data-for-field="cashondelivery_visibility" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h3>Paypal Account Settings</h3>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Paypal sandbox mode (use for paypal account tests)</div>
                <div class="panel-body">
                    <?php if ($this->session->flashdata('paypal_sandbox')) { ?>
                        <div class="alert alert-info">
                            <?= $this->session->flashdata('paypal_sandbox') ?>
                        </div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="paypal_sandbox" value="<?= $paypal_sandbox ?>">
                        <input <?= $paypal_sandbox == 1 ? 'checked' : '' ?> data-toggle="toggle"
                            data-for-field="paypal_sandbox" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Paypal business email</div>
                <div class="panel-body">
                    <?php if ($this->session->flashdata('paypal_email')) { ?>
                        <div class="alert alert-info">
                            <?= $this->session->flashdata('paypal_email') ?>
                        </div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" placeholder="Leave empty for no paypal available method"
                                name="paypal_email" value="<?= $paypal_email ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h3>Bank Account Settings</h3>
    <div class="row">
        <div class="col-sm-6">
            <?php if ($this->session->flashdata('bank_account')) { ?>
                <div class="alert alert-info">
                    <?= $this->session->flashdata('bank_account') ?>
                </div>
            <?php } ?>
            <form method="POST" action="">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td colspan="2"><b>Pay to - Recipient name/ltd</b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" name="name"
                                        value="<?= $bank_account != null ? $bank_account['name'] : '' ?>"
                                        class="form-control" placeholder="Example: BoxingTeam Ltd."></td>
                            </tr>
                            <tr>
                                <td><b>IBAN</b></td>
                                <td><b>BIC</b></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control"
                                        value="<?= $bank_account != null ? $bank_account['iban'] : '' ?>" name="iban"
                                        placeholder="Example: BG11FIBB329291923912301230"></td>
                                <td><input type="text" class="form-control"
                                        value="<?= $bank_account != null ? $bank_account['bic'] : '' ?>" name="bic"
                                        placeholder="Example: FIBBGSF"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Bank</b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text"
                                        value="<?= $bank_account != null ? $bank_account['bank'] : '' ?>" name="bank"
                                        class="form-control" placeholder="Example: First Investment Bank"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="submit" class="form-control" value="Save Bank Account Settings">
            </form>
        </div>
    </div>
<?php } ?>
<!-- Modal for more info buttons in orders -->
<div class="modal fade" id="modalPreviewMoreInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Preview <b id="client-name"></b></h4>
            </div>
            <div class="modal-body" id="preview-info-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/bootstrap-toggle.min.js') ?>"></script>
<script>
    function submitMailForm() {
        // Trigger the form submission
        document.getElementById('sendMailForm').submit();
    }
</script>