<div class="container">
    <?= purchase_steps(1, 2, 3) ?>
    <div class="alert alert-success"><?= lang_safe('c_o_d_order_completed') ?></div>
    <div>
        <a href="<?= LANG_URL . '/shop'?>" class="btn btn-primary btn-new go-shop">
            <span class="glyphicon glyphicon-circle-arrow-left"></span>
            <?= lang_safe('back_to_shop') ?>
        </a>
    </div>
</div>