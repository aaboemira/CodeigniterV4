</div><!-- content end -->
</div><!-- wrapper end -->

<!-- Site footer -->
<footer>
    <div class="site-footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-9 col-md-2">
                    <h6><?=lang_safe('footer_hauptmenu')?></h6>
                    <ul class="footer-links">
                        <li><a href="<?=LANG_URL?>"><?=lang_safe('nav_startseite')?> </a></li>
                        <li><a href="<?=LANG_URL . '/' . "shop"?>"><?=lang_safe('shop')?></a></li>
                        <?php
if (!empty($nonDynPages)) {
    foreach ($nonDynPages as $addonPage) {?>
                        <li><a href="<?=LANG_URL . '/' . $addonPage?>"><?=mb_ucfirst(lang_safe($addonPage))?> </a></li>
                        <?php
}
}
?>
                        <li><a href="<?=LANG_URL . '/contacts'?>"><?=lang_safe('nav_kontakt')?></a>
                        <li>
                    </ul>
                </div>

                <div class="col-xs-9 col-md-3">
                    <h6><?=lang_safe('footer_help-text')?></h6>
                    <ul class="footer-links">
                        <li><a
                                href="<?=LANG_URL . '/' . "/page/howto-order"?>"><?=lang_safe('footer_submenu_howto_order')?></a>
                        </li>
                        <li><a href="<?=LANG_URL . '/' . "/page/payment"?>"><?=lang_safe('payments-text')?></a></li>
                        <li><a href="<?=LANG_URL . '/' . "/page/shipment"?>"><?=lang_safe('footer_submenu_shipment')?></a>
                        </li>
                    </ul>
                </div>

                <div class="col-xs-9 col-md-3">
                    <h6><?=lang_safe('footer_menu_save_buy')?></h6>
                    <ul class="footer-links">
                        <li><a
                                href="<?=LANG_URL . '/' . "/page/howto-order"?>"><?=lang_safe('footer_submenu_fast_shipment')?></a>
                        </li>
                        <li><a href="<?=LANG_URL . '/' . "/page/payment"?>"><?=lang_safe('footer_submenu_return')?></a></li>
                        <li><a href="<?=LANG_URL . '/' . "/page/shipment"?>"><?=lang_safe('footer_submenu_save_pay')?></a>
                        </li>
                        <li><a href="<?=LANG_URL . '/' . "/page/shipment"?>"><?=lang_safe('footer_submenu_save_data')?></a>
                        </li>
                    </ul>
                </div>

                <div class="col-xs-9 col-md-3">
                    <h6><?=lang_safe('rechtliche_hinweise-text')?></h6>
                    <ul class="footer-links">
                        <li><a href="<?=LANG_URL . '/' . "page/Impressum"?>"><?=lang_safe('Impressum')?></a></li>
                        <li><a
                                href="<?=LANG_URL . '/' . "/page/revocation"?>"><?=lang_safe('footer_submenu_revocation')?></a>
                        </li>
                        <li><a href="<?=LANG_URL . '/' . "page/AGB"?>"><?=lang_safe('AGB')?></a></li>
                        <li><a href="<?=LANG_URL . '/' . "page/Datenschutz"?>"><?=lang_safe('Datenschutzerklärung')?></a>
                        </li>
                        <li><a
                                href="<?=LANG_URL . '/' . "page/Cookie_Bestimmungen"?>"><?=lang_safe('Cookie-Richtlinie')?></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div style="margin-top:20px"></div>

            <div class="col-xs-9 col-md-6 ">
                <h6><?=lang_safe('payments-text')?></h6>
                <ul class="footer-links">
                    <img class="payment_img img-thumbnail " alt="Paypal" src="<?=base_url('png/PayPal35.png')?>" />
                    <img class="payment_img img-thumbnail " alt="Visa" src="<?=base_url('png/VISA35.png')?>" />
                    <img class="payment_img img-thumbnail " alt="Mastercard"
                        src="<?=base_url('png/MasterCard35.png')?>" />
                    <img class="payment_img img-thumbnail " alt="American Express"
                        src="<?=base_url('png/AMEX35.png')?>" />
                    <img class="payment_img img-thumbnail " alt="Vorkasse per Banküberweisung"
                        src="<?=base_url('png/bank35.png')?>" />
                    <p class="footer_payment_types">Paypal, <?=lang_safe('Kreditkarte-text')?> (Visa, Mastercard, American
                        Express), <?=lang_safe('vork_bank-text')?></p>
                </ul>
            </div>

            <div class="col-xs-9 col-md-6 ">
                <h6><?=lang_safe('footer_ship_head')?></h6>
                <ul class="footer-links">
                    <img class="shipping_img " alt="dhl" src="<?=base_url('png/DHL_logo_rgb50.png')?>" />
                    <img class="shipping_img " alt="Visa" src="<?=base_url('png/DHL_Express_logo_rgb50.png')?>" />
                    <p class="footer_ship_cost"> <?=lang_safe('footer_ship_with')?> </p>
                    <p class="footer_ship_cost1"> -&nbsp <?=lang_safe('footer_free_ship_germany')?> </p>
                    <p class="footer_ship_cost1"> -&nbsp <?=lang_safe('footer_ship_EU')?> </p>
                </ul>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <hr>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <p class="copyright-text">Copyright &copy; 2023 All Rights Reserved by Node Devices GmbH.</p>

                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">

                    <ul class="social-icons">
                        <span class="app_icon-text"> Mobile app </span>
                        <li><a class="facebook" href="http://onelink.to/q7zjvn"><i class="fa fa-android"></i></a></li>
                        <li><a class="twitter" href="http://onelink.to/q7zjvn"><i class="fa fa-apple"></i></a></li>
                        <li><a class="dribbble" href="http://onelink.to/q7zjvn"><i class="fa fa-windows"></i></a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


<div id="notificator" class="alert"></div>

<script src="<?=base_url('assets/js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('assets/js/bootstrap-confirmation.min.js')?>"></script>
<script src="<?=base_url('assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js')?>"></script>
<script src="<?=base_url('assets/js/placeholders.min.js')?>"></script>
<script src="<?=base_url('assets/js/bootstrap-datepicker.min.js')?>"></script>
<script>
var variable = {
    clearShoppingCartUrl: "<?=base_url('clearShoppingCart')?>",
    manageShoppingCartUrl: "<?=base_url('manageShoppingCart')?>",
    discountCodeChecker: "<?=base_url('discountCodeChecker')?>"
};
</script>
<script src="<?=base_url('assets/js/system.js')?>"></script>
<script src="<?=base_url('templatejs/mine')?>"></script>



<script>
var slideIndex = 0;

var slides = document.getElementsByClassName("mySlides");
var dots = document.getElementsByClassName("dot");
/*
showSlides();


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
*/


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

<script>
// Script to open and close sidebar

function w3_open() {

    document.getElementById("mySidebar").style.display = "block";

    // document.getElementById("myOverlay").style.display = "block";

}

function w3_close() {

    document.getElementById("mySidebar").style.display = "none";

    // document.getElementById("myOverlay").style.display = "none";

}
</script>
<script>
gtag('event', 'conversion', {
    'send_to': 'AW-428847483/z16ECLWjkZEDEPvivswB'
});
</script>
<script>
$(document).ready(function() {
    // Add a click event listener for the WhatsApp link
    $("#float_wa").click(function(e) {
        e.preventDefault(); // Prevent the default behavior of the link (opening a new page)
        // Show the modal
        $("#contactModal").modal("show");
    });

    // Add a click event listener for the phone link
    $("#float_phone").click(function(e) {
        e.preventDefault(); // Prevent the default behavior of the link (making a phone call)
        // Show the modal
        $("#contactModal").modal("show");
    });

    // Add a click event listener for the email link
    $("#float_mail").click(function(e) {
        e.preventDefault(); // Prevent the default behavior of the link (opening the default email client)
        // Show the modal
        $("#contactModal").modal("show");
    });
});
$('#contactOptionsList a').click(function(e) {
        if (!$('#acceptPolicyCheckbox').prop('checked')) {
            e.preventDefault();
            ShowNotificator('alert-danger', 'Bitte akzeptieren Sie die Richtlinie, um diese Kontaktmöglichkeit zu verwenden.');
        }
    });
</script>
<script>
function redirectToGoogleMaps() {
    // Get the value entered in the input field
    var plzOrStadt = document.getElementById("plz").value;

    // Construct the Google Maps URL with the entered value
    var googleMapsUrl = "https://www.google.de/maps/search/elektroinstallateur+torbauer+rolltore+" + encodeURIComponent(plzOrStadt);

    // Redirect to the Google Maps URL
    window.location.href = googleMapsUrl;
}

</script>



</body>

</html>