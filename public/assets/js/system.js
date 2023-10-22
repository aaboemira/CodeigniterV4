/* 
 * There are functions who needs to load in every template.
 * Shopping cart managing is here and etc.
 */

// Shopping Cart Manager
$('a.add-to-cart').click(function () {
    var reload = false;
    var article_id = $(this).data('id');
    var goto_site = $(this).data('goto');
    if ($(this).hasClass('refresh-me')) {
        reload = true;
    } else if (goto_site != null) {
        reload = goto_site;
    }
    manageShoppingCart('add', article_id, reload);
});

$(document).ready(function() {
    // Add a click event listener to the close button
    $(".closeModal").click(function(e) {
        e.preventDefault();
        location.reload();
    });
});
//DatePicker
if (typeof datepicker !== 'undefined') {
    $('.input-group.date').datepicker({
        format: "dd/mm/yy"
    });
}

//Filters Technique
$('.go-category').click(function () {
    var category = $(this).data('categorie-id');
    $('[name="category"]').val(category);
    submitForm();
});
$('.in-stock').click(function () {
    var in_stock = $(this).data('in-stock');
    $('[name="in_stock"]').val(in_stock);
    submitForm()
});
$(".order").change(function () {
    var order_type = $(this).val();
    var order_to = $(this).data('order-to');
    $('[name="' + order_to + '"]').val(order_type);
    submitForm();
});
$('.brand').click(function () {
    var brand_id = $(this).data('brand-id');
    $('[name="brand_id"]').val(brand_id);
    submitForm()
});
$("#search_in_title").keyup(function () {
    $('[name="search_in_title"]').val($(this).val());
});
$('#clear-form').click(function () {
    $('#search_in_title, [name="search_in_title"]').val('');
    $('#bigger-search .form-control').each(function () {
        $(this).val('');
    });
    submitForm();
});
$('.clear-filter').click(function () { //clear filter in right col
    var type_clear = $(this).data('type-clear');
    $('[name="' + type_clear + '"]').val('');
    submitForm();
});
/*
 * Submit search form in home page
 */
function submitForm() {
    document.getElementById("bigger-search").submit();
}
/*
 * Discount code checker
 */
var is_discounted = false;

function checkDiscountCode() {
    var enteredCode = $('[name="discountCode"]').val();
    $.ajax({
        type: "POST",
        url: variable.discountCodeChecker,
        data: { enteredCode: enteredCode }
    }).done(function (data) {
        if (data == 0) {
            ShowNotificator('alert-danger', lang.discountCodeInvalid);
        } else {
            // Check if the data is not an error (you can define your own criteria here)
            if (data !== 'error') {
                // Refresh the page
                window.location.reload();
            } else {
                // Handle any specific error message or behavior here
                console.log('Data returned an error');
            }
        }
    });
}

function updateTotalWithShipping(shipping,currency) {
    var shippingPrice = shipping;
    var currentTotal = parseFloat($('#final_amount').text().replace(/[^\d.-]/g, ''));
    var newTotal = currentTotal + shippingPrice;
    $('#final_amount').text(newTotal.toFixed(2));
    // Update the currency along with the numerical value
    $('#final_amount').append(' ' + currency);
}
function removeProduct(id, reload,bool) {
    if(bool){
        manageShoppingCart('removeProduct', id, reload);
    }
    else{
        manageShoppingCart('remove', id, reload);
    }
}
function manageShoppingCart(action, article_id, reload) {
    var action_error_msg = lang.error_to_cart;
    if (action == 'add') {
        $('.add-to-cart a[data-id="' + article_id + '"] span').hide();
        $('.add-to-cart a[data-id="' + article_id + '"] img').show();
        var action_success_msg = lang.added_to_cart;
    }
    if (action == 'remove'||action == 'removeProduct') {
        var action_success_msg = lang.remove_from_cart;
    }
    $.ajax({
        type: "POST",
        url: variable.manageShoppingCartUrl,
        data: {article_id: article_id, action: action}
    }).done(function (data) {


        $(".dropdown-cart").empty();
        $(".dropdown-cart").append(data);
        var sum_items = parseInt($('.sumOfItems').text());
        if (action == 'add') {
            $('.sumOfItems').text(sum_items + 1);
        }
        if (action == 'remove') {
            $('.sumOfItems').text(sum_items - 1);
        }
        if (reload == true) {
            location.reload(false);
            return;
        } else if (typeof reload == 'string') {
            location.href = reload;
            return;
        }
        //ShowNotificator('alert-info', action_success_msg);
    }).fail(function (err) {
        console.log(err)
        ShowNotificator('alert-danger', action_error_msg);
    }).always(function () {
        if (action == 'add') {
            $('.add-to-cart a[data-id="' + article_id + '"] span').show();
            $('.add-to-cart a[data-id="' + article_id + '"] img').hide();
        }
    });
}

function clearCart() {
    $.ajax({type: "POST", url: variable.clearShoppingCartUrl});
    $('ul.dropdown-cart').empty();
    $('ul.dropdown-cart').append('<li class="text-center">' + lang.no_products + '</li>');
    $('.sumOfItems').text(0);
    ShowNotificator('alert-info', lang.cleared_cart);
}

//Email Subscribe
function checkEmailField() {
    if ($('[name="subscribeEmail"]').val() == '') {
        ShowNotificator('alert-danger', lang.enter_valid_email);
        return;
    }
    document.getElementById("subscribeForm").submit();
}

//Email Subscribe
function checkEmailField() {
    if ($('[name="subscribeEmail"]').val() == '') {
        ShowNotificator('alert-danger', lang.enter_valid_email);
        return;
    }
    document.getElementById("subscribeForm").submit();
}

// Top Notificator
function ShowNotificator(add_class, the_text) {
    $('div#notificator').text(the_text).addClass(add_class).slideDown('slow').delay(3000).slideUp('slow', function () {
        $(this).removeClass(add_class).empty();
    });
}
