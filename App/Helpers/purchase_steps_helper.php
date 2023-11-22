<?php


/*
 * Three steps
 * 1 - your order
 * 2 - checkout type
 * 3 - success order
 */

function purchase_steps($step0=null,$step1 = null, $step2 = null, $step3 = null, $step4 = null)
{

    if ($step0 == 1) {
        $icon0 = 'ok.png';
        $class0 = 'step-bg-ok';
    } else {
        $icon0 = 'no.png';
        $class0 = 'step-bg-not-ok';
    }
    if ($step1 == 1) {
        $icon1 = 'ok.png';
        $class1 = 'step-bg-ok';
    } else {
        $icon1 = 'no.png';
        $class1 = 'step-bg-not-ok';
    }

    if ($step2 == 1) {
        $icon2 = 'ok.png';
        $class2 = 'step-bg-ok';
    } else {
        $icon2 = 'no.png';
        $class2 = 'step-bg-not-ok';
    }
    if ($step3 == 1) {
        $icon3 = 'ok.png';
        $class3 = 'step-bg-ok';
    } else {
        $icon3 = 'no.png';
        $class3 = 'step-bg-not-ok';
    } 
    if ($step4 == 1) {
        $icon4 = 'ok.png';
        $class4 = 'step-bg-ok';
    } else {
        $icon4 = 'no.png';
        $class4 = 'step-bg-not-ok';
    } 
    ?>
    <div class="row steps">
        <div class="col-sm-2 step <?= $class0 ?>" onclick="location.href='<?= LANG_URL . '/checkout1'?>';">
            <span class="step"> <?= lang_safe('checkout0')?>
        </div>
        <div class="col-sm-2 step <?= $class1 ?>" onclick="location.href='<?= LANG_URL . '/checkout1'?>';">
            <span class="step"> <?= lang_safe('checkout1')?>
        </div>
        <div class="col-sm-3 step <?= $class2 ?>" onclick="location.href='<?= LANG_URL . '/checkout2'?>';">
            <span class="step"> <?= lang_safe('checkout2')?>
        </div>
        <div class="col-sm-3 step <?= $class3 ?>" onclick="location.href='<?= LANG_URL . '/checkout3'?>';">
            <span class="step"> <?= lang_safe('checkout3')?>
        </div>
        <div class="col-sm-2 step <?= $class4 ?>" onclick="location.href='#';">
            <span class="step"> <?= lang_safe('checkout4')?>
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
