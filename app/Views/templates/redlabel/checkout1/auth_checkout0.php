<style>
   .user-info {
        display: flex;
        align-items: center;
        /* Align items vertically in the center */
   }
   .icon-container {
        flex-shrink: 0;
        width: 80px;
        /* Set a fixed width for the icon container */
        height: 80px;
        /* Set a fixed height for the icon container */
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 16px;
        /* Add some space between the icon and the text */
        border-radius: 10px;
        /* Make the container circular */
        overflow: hidden;
        /* Ensure the image does not bleed outside the border-radius */
        border: 1px solid #ddd;
        /* A subtle border */
   }
   .icon-container .icon {
        font-size: 70px;
        margin-right: 8px;
        /* Space between icon and name */
        color: #337ab7;
        /* Set the icon color */
   }
   .icon-container img {
        width: 50%;
        /* Ensure the image maintains its aspect ratio */
        height: auto;
    /* Set the height to fill the container */
   }
   .col-md-6
   .details-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
    /* Center the details and logout link vertically */
   }
   .account-details,
   .logout {
        display: flex;
        align-items: center;
        /* Align text vertically in the center */
        margin-bottom: 4px;
    /* Reduced space between account details and logout */
   }
   .logout {
        margin-bottom: 0;
    /* Remove bottom margin from the last item */
   }
   .name,
   .email {
        margin-bottom: 5px;
        font-size: 1.7rem;
        /* Space between name and email */
        color: #000;
    /* Or any color you prefer */
   }
   .logout {
        text-decoration: none;
        font-weight: bold;
    /* Additional styling can be applied as needed */
   }
   .btn-container {
        display: flex;
        flex-direction: column;
        /* Stack the children vertically */
        align-items: end;
        /* Align the children horizontally to the center */
        margin-bottom: 20px;
    /* Space below the button container */
   }
   .btn-container a.btn-new,
   .btn-container form button.btn-new {
        width: 250px;
        /* Fixed width for both buttons */
        text-align: center;
        margin-bottom: 10px;
    /* Space below each button */
   }
   .popup-alert {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
   }
   .popup-content {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
    }
   .popup-content .icon {
        font-size: 40px;
        color: #337ab7;
        margin-bottom: 10px;
   }
/* ... existing CSS ... */

/* Ensure the buttons container uses flexbox */
.checkout-buttons .btn-container {
    display: flex;
    flex-direction: column; /* Stack buttons vertically */
    align-items: flex-start; /* Align buttons to the left */
}

/* Align the left button to the left and right buttons to the right */
.checkout-buttons .col-sm-6 {
    display: flex;
    align-items: center; /* Vertically align the content */
}

/* Align the right-side buttons to the end (right) */
.checkout-buttons .col-sm-6.text-right {
    justify-content: flex-end; /* Push content to the right */
}

/* Style for smaller screens */
@media (max-width: 767px) {
    .checkout-buttons .col-sm-6 {
        width: 100%; /* Full width on small screens */
        justify-content: center; /* Center buttons on small screens */
        margin-bottom: 10px; /* Add space between stacked buttons */
        order: 1; /* Set order to stack right buttons above */

    }
    .btn-container, form {
        width: 100%;
        margin-bottom: 0px;
    }
    .checkout-buttons .col-sm-6.text-right {
        order: 0; /* Set order to show these buttons first */
        margin-bottom: 0;
    }
    .btn-container a.btn-new, 
    .btn-container form button.btn-new {
        width: 100%; /* Full width for buttons on mobile */
        box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }
    .user-info{
        order: 2; /* Ensures user info is below the buttons */
    }

}

   .btn-container a.btn-new,
   .btn-container form button.btn-new {
    /* width: 300px; */
    /* Fixed width for both buttons */
    text-align: center;
    margin-bottom: 10px;
    /* Space below each button */
   }
   

   .user-info {
    /* ... existing styles ... */
    margin-top: 20px; /* Fixed margin above user info */
    margin-bottom: 30px; /* Fixed margin below user info */
    }

    /* Ensure consistent padding for both .user-info and .checkout-buttons */
    .container .row > div {
        padding-left: 30px; /* Align left padding with the button below */
        padding-right: 15px; /* Align right padding for consistency */
    }


    /* Since .col-sm-6 has a padding that affects the alignment, you may need to adjust it */
    .col-md-6 {
        /* ... existing styles ... */
        padding-left: 15px; /* Align with user info left space */
        padding-right: 15px; /* Consistent padding on the right */
    }
    .registration-page h3{
        margin-bottom: 0px ;
    }
    .alone {
    padding-left: 0px !important;
    }
</style>
<div class="container registration-page">
    <?= purchase_steps(1) ?>
    <div class="row">
        <div class="alone title">
            <span>
            <?= lang_safe('user_login') ?>
            </span>
        </div>
        <h3 style="color:black;">
            <?= lang_safe('already_logged', 'You are already logged in') ?>
        </h3>
    <!-- Registration Section -->
        <div class="col-md-6">
            <div class="user-info">
                <div class="icon-container">
                    <img src="<?= base_url('png/myaccount_black.png') ?>">
                </div>
                <div class="details-container">
                    <p class="name">
                    <?= session()->get('user_name') ?>
                    </p>
                    <p class="email">
                    <?= session()->get('email') ?>
                    </p>
                    <a class="logout" href="<?= base_url('/logout') ?>">Logout</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 ">

        <div id="paypal-button-container" style="position:relative;z-index:0;width:260px;margin-left:auto;"></div>

        </div>
    </div>
    <!-- ... existing HTML above ... -->
    <div class="row checkout-buttons">
        <!-- Button aligned to the left -->
        <div class="col-sm-6">
            <a href="<?= LANG_URL . '/shopping-cart' ?>" class="btn btn-primary btn-new go-shop">
                <span class="glyphicon glyphicon-circle-arrow-left"></span>
                <?= lang_safe('back_to_shop') ?>
            </a>
        </div>
        
        <!-- Buttons stacked on the right -->
        <div class="col-sm-6 text-right">
            <div class="btn-container">
                <a class="btn btn-primary btn-new" href="<?= base_url('/checkout1') ?>">
                    <?= lang_safe('continue_address', 'Continue to address') ?>
                </a>
                <form id="guestCheckoutForm" method="post" action="<?= base_url('/checkout1') ?>">
                    <input type="hidden" name="logout_guest" value="1">
                    <input type="hidden" name="guest_checkout" value="1">
                    <button type="submit" name="guest_checkout" class="btn btn-default btn-new">
                        <?= lang_safe('continue_guest', 'Shop as guest') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div id="popupAlert" class="popup-alert">
        <div class="popup-content">
            <i class="fa fa-exclamation-circle icon"></i> <!-- Your icon here -->
            <p>
                <?= lang_safe('confirm_logout') ?>
            </p>
            <button id="confirmButton" class="btn btn-primary">Confirm</button>
            <button id="cancelButton" class="btn btn-default">Cancel</button>
        </div>
    </div>
    <script>
        document.getElementById('guestCheckoutForm').onsubmit = function(event) {
        event.preventDefault(); // Prevent form submission
        document.getElementById('popupAlert').style.display = 'flex'; // Show the popup
        };
        
        document.getElementById('cancelButton').onclick = function() {
        document.getElementById('popupAlert').style.display = 'none'; // Hide the popup
        };
        
        document.getElementById('confirmButton').onclick = function() {
        document.getElementById('guestCheckoutForm').submit(); // Submit the form
        };
    </script>
    <script src="https://www.paypal.com/sdk/js?client-id=ASTEf-iIF0JeRpMAPTfUOSdumIWKWcHnMpdjDSFCxodtXstVStSyUvdzpBXwnKvVVKUbe2V-wlKMuDf1&currency=EUR&components=buttons,messages,applepay&enable-funding=paylater&disable-funding=card,giropay,sepa,sofort&buyer-country=DE" data-sdk-integration-source="integrationbuilder_sc"></script>
    <script>
        let currentTotalAmount=0;
    paypal.Buttons({
        createOrder: function(data, actions) {
            // First call to prepare-cart endpoint
            return fetch('<?= site_url('/paypal/prepare-cart') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }
                return response.json();
            })
            .then(function(preparedData) {
                if (preparedData.error) {
                    throw new Error(preparedData.error);
                }
                // Now call the create-order endpoint with the prepared data
                return fetch('<?= site_url('/paypal/create-order') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(preparedData)
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not OK');
                    }

                    return response.json();
                })
                .then(function(orderData) {
                    if (orderData.error) {
                        throw new Error(orderData.error);
                    }
                    currentTotalAmount = orderData.purchase_units[0].amount.value;

                    return orderData.id;
                });
            })
            .catch(function(error) {
                console.error('Error:', error);
                ShowNotificator('alert-danger', 'There was an error processing your request ') ;
            });
        },
        onApprove: function(data, actions) {
            return fetch('<?= site_url('/paypal/capture-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID
                })
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not OK');
                }
                return response.json();
            })
            .then(function(orderData) {
                if (orderData.error) {
                    throw new Error(orderData.error);
                }
                return fetch('<?= site_url('/paypal/save-order') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(orderData) // send the order data received from PayPal
                });
            })
            .then(function(saveOrderResponse) {
                if (!saveOrderResponse.ok) {
                    throw new Error('Network response was not OK');
                }
                return saveOrderResponse.json();
            })
            .then(function(saveOrderData) {

                // Call the server endpoint for post-payment processing
                return fetch('<?= site_url('/paypal/postPayment') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type:"shopping_cart",
                        orderID: saveOrderData.orderID // Sending the saved order ID
                    })
                });
            })
            .then(function(postPaymentResponse) {
                if (!postPaymentResponse.ok) {
                    throw new Error('Network response was not OK during post payment processing');
                }
                return postPaymentResponse.json();
            })
            .then(function(postPaymentData) {
                console.log('Post-payment processing completed', postPaymentData);
                window.location.href = '<?= base_url('/paypal/success') ?>';

            })
            .catch(function(error) {
                console.error('Error:', error);
                ShowNotificator('alert-danger', 'There was an error processing your request ') ;
            });
        },
        onShippingChange: function(data, actions) {
        return fetch('<?= site_url('/paypal/calculate-shipping') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                shippingAddress: data.shipping_address
            })
        }).then(function(res) {
            return res.json();
        }).then(function(shippingData) {
            
            let newTotalAmount = parseFloat(currentTotalAmount) + parseFloat(shippingData.shipping_cost);

            // Update the order with the new shipping cost
            return fetch('<?= site_url('/paypal/update-paypal-order') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID,
                    shippingCost: shippingData.shipping_cost,
                    shippingTitle: shippingData.shipping_title,
                    total_amount:newTotalAmount
                })
            });
        }).catch(function(err) {
            console.error('Shipping calculation or update failed', err);

        });
    },
    }).render('#paypal-button-container');
</script>