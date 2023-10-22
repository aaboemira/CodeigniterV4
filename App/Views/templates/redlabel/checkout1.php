<div class="container" id="checkout-page">
    <?= purchase_steps(1, 2) ?>
    <div class="row">
        <div class="title alone " >
            <span><?= lang_safe('checkout_contact') ?></span>
        </div>

        
            <form method="POST" id="goOrder">
                <?php
                    if (session('submit_error')) {
                        ?>
                <hr>
				<div class="row">
					<div class="alert alert-danger">
						<h4><span class="glyphicon glyphicon-alert"></span> <?= lang_safe('finded_errors') ?></h4>
						<?php
								foreach (session('submit_error') as $error) {
									echo $error . '<br>';
								}
								?>
					</div>
					</div>
                <hr>
                <?php
                    }
                    ?>
               
			   <div class="row">
                    <div class="col-sm-9 ">
                        <div class="form-group col-sm-6">
                            <label for="emailAddressInput"><?= lang_safe('email_address') ?>
                                <sup><?= lang_safe('required') ?></sup></label>
                            <input id="emailAddressInput" class="form-control" name="email" value="<?= @$_SESSION['email'] ?>"
                                type="text" placeholder="<?= lang_safe('email_address') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="phoneInput"><?= lang_safe('phone') ?> </label>
                            <input id="phoneInput" class="form-control" name="phone" value="<?= @$_SESSION['phone'] ?>"
                                type="text" placeholder="<?= lang_safe('phone') ?>">
                        </div>
                    </div>
			    </div>

            <div class="title alone">
            <br>
                <span><?= lang_safe('checkout_adress') ?></span>
            </div>
			
				<div class="row">
                    <div class="col-sm-9 ">
                        <div class="form-group col-sm-6">
                            <label for="firstNameInput"><?= lang_safe('first_name') ?><sup><?= lang_safe('required') ?></sup></label>
                            <input id="firstNameInput" class="form-control" name="first_name"
                                value="<?= @$_SESSION['first_name'] ?>" type="text" placeholder="<?= lang_safe('first_name') ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="lastNameInput"><?= lang_safe('last_name') ?> <sup><?= lang_safe('required') ?></sup></label>
                            <input id="lastNameInput" class="form-control" name="last_name"
                                value="<?= @$_SESSION['last_name'] ?>" type="text" placeholder="<?= lang_safe('last_name') ?>">
                        </div>
                    </div>	
                </div>
				<div class="row">
                    <div class="col-sm-9 ">
                        <div class="form-group col-sm-6">
                            <label for="companyInput"><?= lang_safe('company') ?></label>
                            <input id="companyInput" class="form-control" name="company" value="<?= @$_SESSION['company'] ?>"
                                type="text" placeholder="<?= lang_safe('company') ?>">
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="firmenzusatz_input"><?= lang_safe('firmenzusatz_input') ?></label>
                            <input id="firmenzusatz_input" class="form-control" name="firmenzusatz_input" value="<?= @$_SESSION['firmenzusatz'] ?>"
                                type="text" placeholder="<?= lang_safe('firmenzusatz_input') ?>">
                        </div>
                    </div>
				</div>
				
				<div class="row">
                    
                    <div class="col-sm-9 ">
                        <div class="form-group col-sm-6">
                            <label for="streetInput"><?= lang_safe('street') ?> <sup><?= lang_safe('required') ?></sup></label>
                            <input id="streetInput" class="form-control" name="street" value="<?= @$_SESSION['street'] ?>"
                                type="text" placeholder="<?= lang_safe('street') ?>">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="housenrInput"><?= lang_safe('housenr') ?> <sup><?= lang_safe('required') ?></sup></label>
                            <input id="housenrInput" class="form-control" name="housenr" value="<?= @$_SESSION['housenr'] ?>"
                                type="text" placeholder="<?= lang_safe('housenr') ?>">
                        </div>
                        
                        <div class="form-group col-sm-6">
                            <label for="adresszusatz_input"><?= lang_safe('adresszusatz_input') ?></sup></label>
                            <input id="adresszusatz_input" class="form-control" name="adresszusatz_input" value="<?= @$_SESSION['adresszusatz'] ?>"
                                type="text" placeholder="<?= lang_safe('adresszusatz_input') ?>">
                        </div>
                    </div>
                 </div>
				 
				<div class="row">
                    <div class="col-sm-9 ">
                        <div class="form-group col-sm-6">
                            <label for="country"><?= lang_safe('country') ?> <sup><?= lang_safe('required') ?></sup></label>
                            <?php
                                if(isset($_SESSION['country']))
                                    $selected_country = $_SESSION['country'];
                            ?>
                            <select size="1" id="country" name="country" class="form-control">
                                <option
                                    <?php if(isset($_SESSION['country']) ){ if($selected_country == 'Deutschland'){echo("selected");}} ?>data-name="DE">
                                    Deutschland </option>
                                    <option class="refresh-me add-to-cart"
                                    <?php if(isset($_SESSION['country'])){ if($selected_country == 'Belgien'){echo("selected");}}?>data-name="BE">
                                    Belgien </option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Bulgarien'){echo("selected");}}?>>
                                    Bulgarien</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Dänemark'){echo("selected");}}?>>
                                    Dänemark</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Estland'){echo("selected");}}?>>
                                    Estland</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Finnland'){echo("selected");}}?>>
                                    Finnland</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Griechenland'){echo("selected");}}?>>
                                    Griechenland</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Kroatien'){echo("selected");}}?>>
                                    Kroatien</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Lettland'){echo("selected");}}?>>
                                    Lettland</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Litauen'){echo("selected");}}?>>
                                    Litauen</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Luxemburg'){echo("selected");}}?>>
                                    Luxemburg</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Malta'){echo("selected");}}?>>
                                    Malta</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Monaco'){echo("selected");}}?>>
                                    Monaco</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Niederlande'){echo("selected");}}?>>
                                    Niederlande</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Österreich'){echo("selected");}}?>>
                                    Österreich</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Polen'){echo("selected");}}?>>
                                    Polen</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Portugal'){echo("selected");}}?>>
                                    Portugal</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Rumänien'){echo("selected");}}?>>
                                    Rumänien</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Schweden'){echo("selected");}}?>>
                                    Schweden</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Slowakei'){echo("selected");}}?>>
                                    Slowakei</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Slowenien'){echo("selected");}}?>>
                                    Slowenien</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Spanien'){echo("selected");}}?>>
                                    Spanien</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Tschechische Republik'){echo("selected");}}?>>
                                    Tschechische Republik</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Ungarn'){echo("selected");}}?>>
                                    Ungarn</option>
                                    <option <?php if(isset($_SESSION['country'])){ if($selected_country == 'Zypern'){echo("selected");}}?>>
                                    Zypern</option>
                            </select>
                        </div>
                    
                        <div class="form-group col-sm-6">
                            <label for="postInput"><?= lang_safe('post_code') ?> <sup><?= lang_safe('required') ?></sup></label>
                            <input id="postInput" class="form-control" name="post_code" value="<?= @$_SESSION['post_code'] ?>"
                                type="text" placeholder="<?= lang_safe('post_code') ?>">
                        </div>
                        
                        <div class="form-group col-sm-6">
                            <label for="cityInput"><?= lang_safe('city') ?> <sup><?= lang_safe('required') ?></sup></label>
                            <input id="cityInput" class="form-control" name="city" value="<?= @$_SESSION['city'] ?>"
                                type="text" placeholder="<?= lang_safe('city') ?>">
                        </div>
                    </div>
                </div>
				 
				 <div class="row">
                    <div class="col-sm-12 ">
                        <div class="form-group col-sm-9">
                            <br><br>
                                <label for="notesInput"><?= lang_safe('notes') ?></label>
                                <textarea id="notesInput" class="form-control" name="notes"
                                    rows="3"><?= @$_SESSION['notes'] ?></textarea>
                            </div>
                            
                            <div class="form-group col-sm-12">
                                <label>
                                <br><br><br>
                                    <?= lang_safe('dataprotection_contact_accept1') ?>
                                    <a href="<?= LANG_URL . '/' . "Datenschutz" ?>"><?=lang_safe('dataprotection_contact_accept2')?></a>

                                    <?= lang_safe('dataprotection_contact_accept3') ?>
                                    <sup><?= lang_safe('required') ?> </sup>

                                </label>

                                <input style="transform: scale(1.5); margin-left: 10px;" type="checkbox"
                                    name="post_dataprotection" id="post_dataprotection" required="required"
                                    value="post_dataprotection"
                                    <?php if(isset($_SESSION['post_dataprotection'])) echo 'checked="checked"'; ?> />
                            </div>
                            
                            <div class="form-group col-sm-12">
                                <label>
                                    <?= lang_safe('agb_accept1') ?>
                                    <a href="<?= LANG_URL . '/' . "AGB" ?>"><?=lang_safe('agb_accept2')?></a>.
                                </label>
                            </div>
                        </div>
                    </div>

                <!-- <div class="align-right max-675-flex-col">
                    <a class="custom-btn text-dark bg-light fw-light p-2 w-40 max-675-w-100" href="<?= LANG_URL . '/shopping-cart' ?>"><?= lang_safe('back_to_basket') ?> </a>
                    <a class="custom-btn text-light bg-black p-2 w-15 max-675-w-100 go-checkout go-order" onclick="document.getElementById('goOrder').submit();" href="javascript:void(0);"><?= lang_safe('to_checkout2') ?></a>
                </div> -->

                <div class="container checkout1-container">
                    <div class="row">
                        <div class="col-sm-12 checkout-buttons">
                            <br> 
                            <br> 
                            <a class="btn btn-primary go-checkout w3-right" onclick="document.getElementById('goOrder').submit();" href="javascript:void(0);">
                                <?= lang_safe('to_checkout2') ?>
                                <i class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></i>
                            </a>
                            <a href="<?= LANG_URL . '/shop'?>" class="btn btn-primary go-shop">
                                <span class="glyphicon glyphicon-circle-arrow-left"></span>
                                <?= lang_safe('back_to_shop') ?>
                            </a>
                         </div>
                    </div>
                </div>
                <div style="margin-top:60px"></div>
                <!-- <div>

                        <a href="<?= LANG_URL . '/shopping-cart'?>" class="btn btn-primary go-shop">
                            <span class="glyphicon glyphicon-circle-arrow-left"></span>
                            <?= lang_safe('back_to_basket') ?>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-primary go-order"
                            onclick="document.getElementById('goOrder').submit();" class="pull-left">
                            <?= lang_safe('to_checkout2') ?>
                            <span class="glyphicon glyphicon-circle-arrow-right"></span>
                        </a>
                    <div class="clearfix"></div>
                </div> -->
        </div>
        

    </div>
</div>
<?php
if (session('deleted')) {
    ?>
    <script>
        $(document).ready(function () {
            ShowNotificator('alert-info', '<?= session('deleted') ?>');
        });
    </script>
<?php } ?>





