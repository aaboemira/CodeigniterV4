</div>


  <!-- Site footer -->
  <footer class="site-footer">
      <div class="container">
        <div class="row">
          
          <div class="col-xs-6 col-md-3">
          <h6><?= lang_safe('footer_hauptmenu') ?></h6>
            <ul class="footer-links">
              <li><a href="<?= LANG_URL ?>"><?=lang_safe('nav_startseite')?> </a></li>
              <li><a href="<?= LANG_URL . '/' . "shop" ?>"><?=lang_safe('shop')?></a></li>
              <?php
                if (!empty($nonDynPages)) 
                {
                  foreach ($nonDynPages as $addonPage) 
                  {?>
                    <li><a href="<?= LANG_URL . '/' . $addonPage ?>"><?= mb_ucfirst(lang_safe($addonPage)) ?> </a></li>
              <?php
                  }
                }
              ?>
              <li><a href="<?= LANG_URL . '/contacts' ?>"><?= lang_safe('nav_kontakt') ?></a><li>
            </ul>
          </div>

          <div class="col-xs-6 col-md-3">
          <h6><?= lang_safe('categories') ?></h6>
            <ul class="footer-links">
              <li><a href="<?= LANG_URL . '/' . "shop" ?>"><?=lang_safe('shop')?></a></li>
              <li><a href="<?= LANG_URL . '/' . "shop" ?>"><?=lang_safe('shop')?></a></li>
              <li><a href="http://scanfcode.com/category/back-end-development/">PHP</a></li>
              <li><a href="http://scanfcode.com/category/java-programming-language/">Java</a></li>
              <li><a href="http://scanfcode.com/category/android/">Android</a></li>
              <li><a href="http://scanfcode.com/category/templates/">Templates</a></li>
            </ul>
          </div>

          <div class="col-xs-6 col-md-3">
            <h6><?= lang_safe('rechtliche_hinweise-text') ?></h6>
            <ul class="footer-links">
              <li><a href="<?= LANG_URL . '/' . "page/Impressum" ?>"><?=lang_safe('Impressum')?></a></li>
              <li><a href="<?= LANG_URL . '/' . "page/AGB" ?>"><?=lang_safe('AGB')?></a></li>
              <li><a href="<?= LANG_URL . '/' . "page/Datenschutz" ?>"><?=lang_safe('Datenschutzerklärung')?></a></li>
              <li><a href="<?= LANG_URL . '/' . "page/Cookie_Bestimmungen" ?>"><?=lang_safe('Cookie-Richtlinie')?></a></li>
            </ul>
          </div>

         
        </div>

        <div style="margin-top:20px"></div>
      <div class="row">
        <div class="col-xs-6 col-md-6">
            <h6><?= lang_safe('payments-text') ?></h6>
            <ul class="footer-links">
              <img class="payment_img img-thumbnail " alt="Paypal" src="<?= base_url('png/PayPal35.png') ?>" />
              <img class="payment_img img-thumbnail " alt="Visa" src="<?= base_url('png/VISA35.png') ?>" />
              <img class="payment_img img-thumbnail " alt="Mastercard" src="<?= base_url('png/MasterCard35.png') ?>" />
              <img class="payment_img img-thumbnail " alt="American Express" src="<?= base_url('png/AMEX35.png') ?>" />
              <img class="payment_img img-thumbnail " alt="Vorkasse per Banküberweisung" src="<?= base_url('png/bank35.png') ?>" />
              <p>Paypal, <?= lang_safe('Kreditkarte-text') ?> (Visa, Mastercard, American Express), <?= lang_safe('vork_bank-text') ?></p>
            </ul>
          </div>

          <div class="col-xs-6 col-md-6">
            <h6><?= lang_safe('footer_ship_head') ?></h6>
            <ul class="footer-links">
            <p> <?= lang_safe('footer_ship_with') ?> </p>
              <img class="shipping_img  " alt="dhl" src="<?= base_url('png/DHL_logo_rgb50.png') ?>" />
              <img class="shipping_img  " alt="Visa" src="<?= base_url('png/DHL_Express_logo_rgb50.png') ?>" />
              <p> <?= lang_safe('footer_free_ship_germany') ?> </p>
            </ul>
          </div>
        </div>

        <hr>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-sm-6 col-xs-12">
            <p class="copyright-text">Copyright &copy; 2023 All Rights Reserved by Node Devices GmbH.
            </p>
          </div>

          <!-- <div class="col-md-4 col-sm-6 col-xs-12">
            <ul class="social-icons">
              <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
              <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
              <li><a class="dribbble" href="#"><i class="fa fa-dribbble"></i></a></li>
              <li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>   
            </ul>
          </div> -->
        </div>
      </div>
</footer>

<!-- 

<div>
    <div class="w3-gray" id="footer">
        <div class="w3-container w3-padding-32 w3-grey">
            <div class="w3-row-padding">

                <div class="pull-left">
                    <span class="payments-text"> <?= lang_safe('payments-text') ?> </span>
                    <ul class="nav nav-pills payments">
                        <li><i class="fa fa-cc-paypal"></i></li>
                        <li><i class="fa fa-cc-visa"></i></li>
                        <li><i class="fa fa-cc-mastercard"></i></li>
                        <li><i class="fa fa-cc-amex"></i></li>

                    </ul>
                </div>

                <div class="pull-left">
                    <div class=" w3-container  ">
                        <p><a href="<?= LANG_URL . '/' . "Impressum" ?>"
                                class="w3-button w3-block w3-large"><?=lang_safe('Impressum')?></a>
                        </p>
                        <p><a href="<?= LANG_URL . '/' . "Datenschutz" ?>"
                                class="w3-button w3-block w3-large"><?=lang_safe('Datenschutzerklärung')?></a>
                        </p>
                        <p><a href="<?= LANG_URL . '/' . "AGB" ?>" class="w3-button w3-block w3-large"><?=lang_safe('AGB')?></a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> -->

</div>

</div>

<div id="notificator" class="alert"></div>

<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-confirmation.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js') ?>"></script>
<script src="<?= base_url('assets/js/placeholders.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script>
var variable = {
    clearShoppingCartUrl: "<?= base_url('clearShoppingCart') ?>",
    manageShoppingCartUrl: "<?= base_url('manageShoppingCart') ?>",
    discountCodeChecker: "<?= base_url('discountCodeChecker') ?>"
};
</script>
<script src="<?= base_url('assets/js/system.js') ?>"></script>
<script src="<?= base_url('templatejs/mine.js') ?>"></script>



<script>
var slideIndex = 0;

var slides = document.getElementsByClassName("mySlides");

var dots = document.getElementsByClassName("dot");






function showSlides() {

    var i;

    for (i = 0; i < slides.length; i++) {

        slides[i].style.display = "none";

    }

    slideIndex++;

    if (slideIndex > slides.length) {
        slideIndex = 1
    }

    for (i = 0; i < dots.length; i++) {

        dots[i].className = dots[i].className.replace(" active", "");

    }

    slides[slideIndex - 1].style.display = "block";

    dots[slideIndex - 1].className += " active";

    setTimeout(showSlides, 5000); // Change image every 2 seconds 	

}



function plusDivs(n) {

    showDivs(slideIndex += n);

}



function showDivs(n) {

    var i;

    if (n > slides.length) {
        slideIndex = 1
    }

    if (n < 1) {
        slideIndex = slides.length
    };

    for (i = 0; i < slides.length; i++) {

        slides[i].style.display = "none";

    }

    slides[slideIndex - 1].style.display = "block";



    for (i = 0; i < dots.length; i++) {

        dots[i].className = dots[i].className.replace(" active", "");

    }

    dots[slideIndex - 1].className += " active";

}
</script>

</body>

</html>



<script>
// Script to open and close sidebar

function w3_open() {

    document.getElementById("mySidebar").style.display = "block";

    document.getElementById("myOverlay").style.display = "block";

}

function w3_close() {

    document.getElementById("mySidebar").style.display = "none";

    document.getElementById("myOverlay").style.display = "none";

}
</script>