<!-- Event snippet for Kauf conversion page -->
<script>
gtag('event', 'conversion', {
    'send_to': 'AW-428847483/7LNVCPTp2cADEPvivswB',
    'value': 1.0,
    'currency': 'EUR',
    'transaction_id': ''
});
</script>
<div class="container">
    <?= purchase_steps(1, 2, 3) ?>
    <div class="alert alert-success">
        <?= lang_safe('bank_success_msg') ?>
    </div>
</div>
<div class="container">
    <?php
    if (isset($_SESSION['order_id'])) {
        ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td colspan="2"><b class="bg-info"><?= lang_safe('bank_recipient_name') ?></b></td>
                </tr>
                <tr>
                    <td colspan="2"><?= $bank_account != null ? $bank_account['name'] : '' ?></td>
                </tr>
                <tr>
                    <td><b class="bg-info"><?= lang_safe('bank_iban') ?></b></td>
                    <td><b class="bg-info"><?= lang_safe('bank_bic') ?></b></td>
                </tr>
                <tr>
                    <td><?= $bank_account != null ? $bank_account['iban'] : '' ?></td>
                    <td><?= $bank_account != null ? $bank_account['bic'] : '' ?></td>
                </tr>
                <tr>
                    <td colspan="2"><b class="bg-info"><?= lang_safe('bank_name') ?></b></td>
                </tr>
                <tr>
                    <td colspan="2"><?= $bank_account != null ? $bank_account['bank'] : '' ?></td>
                </tr>
                <tr>
                    <td colspan="2"><b class="bg-info"><?= lang_safe('bank_reason') ?></b></td>
                </tr>
                <tr>
                    <td colspan="2"><?= lang_safe('the_reason') ?> - <?= $_SESSION['order_id'] ?></td>
                </tr>
                <tr>
                    <td colspan="2"><?= lang_safe('final_amount_for_pay') ?> <?= $_SESSION['final_amount'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>