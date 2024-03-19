</div><!-- content end -->
</div><!-- wrapper end -->
<style>/* Custom styles for reducing space between images */
.no-padding {
    padding: 0 !important;
}

.apps-container .img-responsive.custom-size {
    width: 90%; /* Make the image fill the column */
    height: auto; /* Keep the aspect ratio */
    margin: 0 2% auto; /* Center the image if smaller than the column width */
}
.apps-container .box{
    padding:30px;
    border: 1.5px solid #808080fa;
    margin-top: 30px;
}


@media screen and (min-width: 768px) and (max-width: 992px) {
    .apps-container .box {
        margin: 60px 2% 40px;
    }
    .apps-container {
        margin-left: 0;
        padding-left: 10%;
    }
}

@media (min-width: 992px) {
    .apps-container .box{
        margin: 60px 19% 40px;
    }
}
.apps-container #title {
  position: absolute;
  top: -14px;
  left:20%;
  margin-left: 1em;
  display: inline;
  background-color: black;
  color:#808080fa;
  padding: 2px 20px;
}

@media (max-width: 600px) {
   .apps-container .img-responsive.custom-size{
        width: 96%;
        margin: 0 2% 0 0;
    }
    .apps-container .box{
        padding:15px 20px;
        margin-top: 30px;
    }
    .apps-container .row .container-fluid{
        padding: 0px;
    }
    .apps-container #title {

        left:7%;
    }
    .apps-container .col-md-4.no-padding:last-child {
        display: none; /* This will hide the last .col-md-4.no-padding element within .apps-container, which should be your Windows Store link */
    }
}

</style>
<!-- Site footer -->
<footer>
    <div class="site-footer" id="footer">
        <div class="container" style="padding-left: 10%;  width:100%;" >
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
                                href="<?=LANG_URL . '/' . "page/Bestellablauf"?>"><?=lang_safe('footer_submenu_howto_order')?></a>
                        </li>
                        <li><a href="<?=LANG_URL . '/' . "page/Bezahlung"?>"><?=lang_safe('payments-text')?></a></li>
                        <li><a href="<?=LANG_URL . '/' . "page/Versand"?>"><?=lang_safe('footer_submenu_shipment')?></a>
                        </li>
                    </ul>
                </div>

                <div class="col-xs-9 col-md-3">
                    <h6><?=lang_Safe('footer_menu_save_buy')?></h6>
                    <ul class="footer-links">
                        <li><?=lang_Safe('footer_submenu_fast_shipment')?></li>
                        <li><?=lang_Safe('footer_submenu_return')?></li>
                        <li><?=lang_Safe('footer_submenu_save_pay')?></li>
                        <li><?=lang_Safe('footer_submenu_save_data')?></li>
                    </ul>
                </div>

                <div class="col-xs-9 col-md-3">
                    <h6><?=lang_safe('rechtliche_hinweise-text')?></h6>
                    <ul class="footer-links">
                        <li><a href="<?=LANG_URL . '/' . "page/Impressum"?>"><?=lang_safe('Impressum')?></a></li>
                        <li><a
                                href="<?=LANG_URL . '/' . "page/Widerrufsrecht"?>"><?=lang_safe('footer_submenu_revocation')?></a>
                        </li>
                        <li><a href="<?=LANG_URL . '/' . "page/AGB"?>"><?=lang_safe('AGB')?></a></li>
                        <li><a href="<?=LANG_URL . '/' . "page/Datenschutz"?>"><?=lang_safe('Datenschutzerklärung')?></a>
                        </li>
                        <li><a
                                href="<?=LANG_URL . '/' . "page/Cookie-Bestimmungen"?>"><?=lang_safe('Cookie-Richtlinie')?></a>
                        </li>
                    </ul>
                </div>
            

            <div class="col-md-12" style="margin-top:20px"></div>

	            <div class="col-xs-9 col-md-6 ">
	                <h6><?=lang_safe('payments-text')?></h6>
	             
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
	               
	            </div>
	
	            <div class="col-xs-9 col-md-6 ">
	                <h6><?=lang_safe('footer_ship_head')?></h6>
	                
	                    <img class="shipping_img " alt="dhl" src="<?=base_url('png/DHL_logo_rgb50.png')?>" />
	                    <img class="shipping_img " alt="Visa" src="<?=base_url('png/DHL_Express_logo_rgb50.png')?>" />
	                    <p class="footer_ship_cost"> <?=lang_safe('footer_ship_with')?> </p>
	                    <p class="footer_ship_cost1"> -&nbsp <?=lang_safe('footer_free_ship_germany')?><br> -&nbsp <?=lang_safe('footer_ship_EU')?></p>
	                    
	                  
	                
	            </div>
	        </div>
        </div>
        <div class="container apps-container">
            <div class="row">            
                <div class="col-md-8 col-md-offset-2  col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1 box" >
                    <div id="title"><?=lang_safe('footer_app_download')?> </div>
                    <div class="container-fluid">
                        <div class="row no-gutters" style="margin:0;">
                            <div class="col-xs-6 col-sm-4 col-md-4  no-padding">
                                <a class="google" href="https://www.onelink.to/q7zjvn">
                                    <img class="img-responsive custom-size" alt="Google Play" src="<?=base_url('/png/google-play' . (MY_LANGUAGE_ABBR == 'de' ? '_de' : '_en') . '.png')?>">
                                </a>
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-4 no-padding">
                                <a class="apple" href="https://www.onelink.to/q7zjvn" style="padding-top:1px;">
                                    <img class="img-responsive custom-size" alt="Apple Store" src="<?=base_url('/png/apple-store' . (MY_LANGUAGE_ABBR == 'de' ? '_de' : '_en') . '.png')?>">
                                </a>
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-4 no-padding">
                                <a class="windows" href="https://www.onelink.to/q7zjvn" style="padding-top:1px;">
                                    <img class="img-responsive custom-size" alt="Windows Store" src="<?=base_url('/png/windows-store' . (MY_LANGUAGE_ABBR == 'de' ? '_de' : '_en') . '.png')?>">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container copyrights-container">
            <div class="row">
                <hr>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <p class="copyright-text" style="text-align: center;">Copyright &copy; 2023 All Rights Reserved by Node Devices GmbH.</p>

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


<!-- <script>
gtag('event', 'conversion', {
    'send_to': 'AW-428847483/z16ECLWjkZEDEPvivswB'
});
</script> -->
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
// $('#contactOptionsList a').click(function(e) {
// if (!$('#acceptPolicyCheckbox').prop('checked')) {
// e.preventDefault();
// ShowNotificator('alert-danger', '<?= lang_safe('contact_check')?>');
// }
// });
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

<script>
    $(document).ready(function() {

        // Select all elements with the class "fade-in-panel"
        $(".fade-in-panel").each(function(index) {
            var panel = $(this);
            
            // Add the "active" class to each panel with a delay
            setTimeout(function() {
                panel.addClass("active");
            }, index * 1); // Adjust the delay (in milliseconds) between panels
        });
    });
</script>
<script>

    function toggleImageColor(imgSelector, imageName, disableOnMobile = false) {
        // Check if the function should be disabled on mobile based on screen width
        if (disableOnMobile && window.innerWidth <= 768) {
            return; // Do nothing on mobile
        }

        // Get the current source of the image
        var currentSrc = $(imgSelector).attr("src");
        // Determine the new source based on the current one
        var newSrc = currentSrc.includes(imageName + "_white.png") ?
            "<?= base_url('png/') ?>" + imageName + "_black.png" :
            "<?= base_url('png/') ?>" + imageName + "_white.png";

        // Update the image source
        $(imgSelector).attr("src", newSrc);
    }
    // Bind the function to the hover event of the shopping cart icon
    $(".shopping-cart-dropdown-wrapper").hover(
        function() {
            // Mouse enter event
            toggleImageColor('.shopping_cart_img', 'cart',true);
        },
        function() {
            // Mouse leave event
            toggleImageColor('.shopping_cart_img', 'cart',true);
        }
    );

    $(document).ready(function() {
    // Function to handle hover effect
    function handleHover(selector, imageName) {
        if ($('.left-sidebar').length == 0) {
            toggleImageColor(selector, imageName);
        }
    }

    // Check screen size and apply hover effect
    function applyHoverBasedOnScreenSize() {
        var screenWidth = $(window).width();

        // Assuming 768px is the breakpoint for mobile devices
        if (screenWidth < 768) {
            // Mobile: Apply hover effect to the image
            $("#myAccountLink img").hover(
                function() { handleHover('#myAccountLink img', 'myaccount'); },
                function() { handleHover('#myAccountLink img', 'myaccount'); }
            );
        } else {
            // Desktop: Apply hover effect to the link
            $("#myAccountLink").hover(
                function() {
            // Mouse enter event
                toggleImageColor('#myAccountLink img', 'myaccount')
                },
                function() {
                    // Mouse leave event
                    toggleImageColor('#myAccountLink img', 'myaccount')
                }
            );
        }
    }

    // Initial application
    applyHoverBasedOnScreenSize();

    // Reapply on window resize
    $(window).resize(function() {
        applyHoverBasedOnScreenSize();
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to toggle password visibility
        function togglePasswordVisibility(targetInput) {
            const passwordInput = document.getElementById(targetInput);
            const eyeIcon = document.querySelector(`[data-target="${targetInput}"] i.fa`);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }

        // Add click event for all toggle password buttons
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                const targetInputId = this.getAttribute("data-target");
                const targetEyeIcon = document.getElementById("eye-icon-" + targetInputId);
                togglePasswordVisibility(targetInputId, targetEyeIcon);
            });
        });

        // ... (rest of your code)
    });

</script>
<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        function adjustButtonClasses() {
            // Find all buttons within .dropdown-menu
            const dropdownButtons = document.querySelectorAll('.dropdown-menu .btn');

            // Check the viewport width
            if (window.innerWidth <= 768) { // Assumes 768px is your breakpoint for mobile devices
                dropdownButtons.forEach(button => {
                    if (button.classList.contains('btn-new')) {
                        button.classList.remove('btn-new');
                        button.classList.add('btn-new-inverse');
                    }
                });
            } else {
                dropdownButtons.forEach(button => {
                    if (button.classList.contains('btn-new-inverse')) {
                        button.classList.remove('btn-new-inverse');
                        button.classList.add('btn-new');
                    }
                });
            }
        }

        // Adjust the button classes on load
        adjustButtonClasses();

        // Adjust the button classes when the window is resized
        window.addEventListener('resize', adjustButtonClasses);
    });


</script>
<script>
$(document).ready(function() {
    // Check if the current page has a sidebar
    if ($('.left-sidebar').length > 0) {
        // Only execute the following code if a sidebar is present
        function removeHoverEvents() {
            if (isMobile()) {
                // Unbind hover events
                $('.login-dropdown-li > a').off('mouseenter mouseover');
            }
        }
        // Function to check if it's a mobile device
        function isMobile() {
            return $(window).width() < 768;
        }

        // Function to forcibly open the dropdown
        function forceOpenDropdown() {
            if (isMobile()) {
                // Add the 'open' class with a slight delay
                setTimeout(function() {
                    $('.login-dropdown-li').addClass('open');
                    removeHoverEvents();
                }, 0); // Timeout set to 0 to push execution to the end of the call stack
            } else {
                // Remove the 'open' class when not in mobile view
                $('.login-dropdown-li').removeClass('open');
            }
        }

        // Apply the 'open' class each time the navbar is toggled
        $('.navbar-toggle').on('click', function() {
            forceOpenDropdown();
        });

        // Handle window resize
        $(window).resize(function() {
            forceOpenDropdown();
        });

        // Prevent the default toggle behavior on mobile
        $('.login-dropdown-li > a').on('click', function(e) {
            if (isMobile()) {
                e.preventDefault(); // Prevent the default action
                e.stopPropagation();
            }
        });


        $('.dropdown').on('click', function(event) {
            if (isMobile()) {
            // Check if the clicked element is within the dropdown menu
            if ($(event.target).closest('.dropdown-menu').length) {
                // Prevent closing the dropdown menu
                event.stopPropagation();
            }
        }
        });
    }
});

    </script>
<script>
    function removeTrailingSpaces(inputElement) {
        inputElement.value = inputElement.value.replace(/\s+/g, '');
    }
</script>

</body>
</html>