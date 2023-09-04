<?php


/*
 * Three steps
 * 1 - your order
 * 2 - checkout type
 * 3 - success order
 */

function purchase_steps($step1 = null, $step2 = null, $step3 = null, $step4 = null)
{
    if ($step1 == 1) {
        $icon1 = 'ok.png';
        $class1 = 'step-bg-ok';
    } else {
        $icon1 = 'no.png';
        $class1 = 'step-bg-not-ok';
    }
    if ($step2 == 2) {
        $icon2 = 'ok.png';
        $class2 = 'step-bg-ok';
    } else {
        $icon2 = 'no.png';
        $class2 = 'step-bg-not-ok';
    }
    if ($step3 == 3) {
        $icon3 = 'ok.png';
        $class3 = 'step-bg-ok';
    } else {
        $icon3 = 'no.png';
        $class3 = 'step-bg-not-ok';
    }
	if ($step4 == 4) {
        $icon4 = 'ok.png';
        $class4 = 'step-bg-ok';
    } else {
        $icon4 = 'no.png';
        $class4 = 'step-bg-not-ok';
    }

    ?>
    <div class="row steps">
        <div class="col-sm-3 step <?= $class1 ?>">
            <img src="<?= base_url('assets/imgs/' . $icon1) ?>" alt="Ok"> <?= lang_safe('checkout1') ?>
        </div>
        <div class="col-sm-3 step <?= $class2 ?>">
            <img src="<?= base_url('assets/imgs/' . $icon2) ?>" alt="Ok"> <?= lang_safe('checkout2') ?>
        </div>
        <div class="col-sm-3 step <?= $class3 ?>">
            <img src="<?= base_url('assets/imgs/' . $icon3) ?>" alt="Ok"> <?= lang_safe('checkout3') ?>
        </div>
		<div class="col-sm-3 step <?= $class4 ?>">
            <img src="<?= base_url('assets/imgs/' . $icon4) ?>" alt="Ok"> <?= lang_safe('checkout4') ?>
        </div>
    </div>
    <?php
}
