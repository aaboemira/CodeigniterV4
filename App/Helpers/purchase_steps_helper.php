<?php
/*
 * Three steps
 * 1 - your order
 * 2 - checkout type
 * 3 - success order
 */

function purchase_steps($step0=null, $step1=null, $step2=null, $step3=null, $step4=null)
{
    if ($step0 == 1) {
        $icon0 = 'ok.png';
        $class0 = 'checkout-panel-active';
    } else if ($step0 == 2) {
        $icon0 = 'ok.png';
        $class0 = 'checkout-panel-completed';
    } else {
        $icon0 = 'no.png';
        $class0 = 'checkout-panel-inactive';
    }
    
    if ($step1 == 1) {
        $icon1 = 'ok.png';
        $class1 = 'checkout-panel-active';
    } else if ($step1 == 2) {
        $icon1 = 'ok.png';
        $class1 = 'checkout-panel-completed';
    } else {
        $icon1 = 'no.png';
        $class1 = 'checkout-panel-inactive';
    }

    if ($step2 == 1) {
        $icon2 = 'ok.png';
        $class2 = 'checkout-panel-active';
    } else if ($step2 == 2) {
        $icon2 = 'ok.png';
        $class2 = 'checkout-panel-completed';
    } else {
        $icon2 = 'no.png';
        $class2 = 'checkout-panel-inactive';
    }
    
    if ($step3 == 1) {
        $icon3 = 'ok.png';
        $class3 = 'checkout-panel-active';
    } else if ($step3 == 2) {
        $icon3 = 'ok.png';
        $class3 = 'checkout-panel-completed';
    } else {
        $icon3 = 'no.png';
        $class3 = 'checkout-panel-inactive';
    } 
    ?>
    <div class="row checkout-panel-steps">
        <div class="col-sm-3 checkout-panel-step <?= $class0 ?>" onclick="location.href='<?= LANG_URL . '/checkout0'?>';">
            <span class="checkout-panel-step"> <?= lang_safe('checkout0')?>
        </div>
        <div class="col-sm-3 checkout-panel-step <?= $class1 ?>" onclick="location.href='<?= LANG_URL . '/checkout1'?>';">
            <span class="checkout-panel-step"> <?= lang_safe('checkout1')?>
        </div>
        <div class="col-sm-3 checkout-panel-step <?= $class2 ?>" onclick="location.href='<?= LANG_URL . '/checkout2'?>';">
            <span class="checkout-panel-step"> <?= lang_safe('checkout2')?>
        </div>
        <div class="col-sm-3 checkout-panel-step <?= $class3 ?>" onclick="location.href='<?= LANG_URL . '/checkout3'?>';">
            <span class="checkout-panel-step"> <?= lang_safe('checkout3')?>
        </div>
       
        <!-- <div class="col-sm-4 step <?= $class2 ?>">
            <img src="<?= base_url('assets/imgs/' . $icon2) ?>" alt="Ok"> <?= lang_safe('checkout2') ?>
        </div> 
        <div class="col-sm-4 step <?= $class3 ?>">
            <img src="<?= base_url('assets/imgs/' . $icon3) ?>" alt="Ok"> <?= lang_safe('checkout3') ?>
        </div> -->
    </div>
    <?php
}
