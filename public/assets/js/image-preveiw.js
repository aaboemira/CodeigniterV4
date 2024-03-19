var modal = document.getElementById('modalImagePreview');
var modalImg = document.getElementById("img01");
var allNum = $('.img-sl').length;
var attr_2400="data-src-model-2400";
var attr_650="data-src-model";
if (typeof selectedIndicator === 'undefined') {
    var selectedIndicator;
}
var next = null;
var prev = null;

$('.other-img-preview').click(function () {
    modal.style.display = "block";

    var modalImga = window.innerWidth <= 767 ? $(this).attr(attr_2400) : $(this).attr(attr_650); // Use data-src-2400 for mobile and data-src-650 for desktop
    console.log(modalImg);
    selectedIndicator = $(this).data('num');
    var cc = selectedIndicator + 1;
    $('.img-series').text(cc + ' / ' + allNum);
    modalImg.src = modalImga;
    if (window.innerWidth > 767) {
        modalImg.classList.add('zoom-in'); // Add zoom-in class by default
    }
});

$('#img01').click(function (e) {
    if (window.innerWidth > 767) {
        e.stopPropagation(); // Prevent event propagation

        var img = $(this);
        if (img.hasClass('zoom-mode')) {
            img.removeClass('zoom-mode');
            img.css('transform', 'scale(1)');
            var src650 = $('[data-num="' + selectedIndicator + '"]').attr(attr_650); // Get the data-src-650 attribute
            img.attr('src', src650); // Set the image src to data-src-650
        } else {
            img.addClass('zoom-mode');
            var src2400 = $('[data-num="' + selectedIndicator + '"]').attr(attr_2400); // Get the data-src-2400 attribute
            img.attr('src', src2400); // Set the image src to data-src-2400
            var rect = this.getBoundingClientRect();
            var offsetX = e.clientX - rect.left;
            var offsetY = e.clientY - rect.top;
            var originX = (offsetX / rect.width) * 100;
            var originY = (offsetY / rect.height) * 100;
            img.css('transform-origin', `${originX}% ${originY}%`);
            img.css('transform', 'scale(2)'); // Make sure to set the scale here as well
        }
    }
});


$('#img01').mousemove(function (e) {
    if (window.innerWidth > 767) {
        if ($(this).hasClass('zoom-mode')) {
            var rect = this.getBoundingClientRect();
            var offsetX = e.clientX - rect.left;
            var offsetY = e.clientY - rect.top;
            var originX = (offsetX / rect.width) * 100;
            var originY = (offsetY / rect.height) * 100;
            $(this).css('transform-origin', `${originX}% ${originY}%`);
        }
    }
});


$('.close').click(function () {
    modal.style.display = "none";
});
$('.inner-next').click(function () {
    next = selectedIndicator + 1;
    if (next >= allNum) {
        next = 0;
    }
    selectedIndicator = next;
    var cc = next + 1;
    $('.img-series').text(cc + ' / ' + allNum);
    var newSrc = window.innerWidth <= 767 ? $('[data-num="' + next + '"]').attr(attr_2400) : $('[data-num="' + next + '"]').attr(attr_650); // Use data-src-2400 for mobile and data-src-650 for desktop
    modalImg.src = newSrc;
});
$('.inner-prev').click(function () {
    prev = selectedIndicator - 1;
    if (prev < 0) {
        prev = allNum - 1;
    }
    selectedIndicator = prev;
    var cc = prev + 1;
    $('.img-series').text(cc + ' /' + allNum);
    var newSrc = window.innerWidth <= 767 ? $('[data-num="' + prev + '"]').attr(attr_2400) : $('[data-num="' + prev + '"]').attr(attr_650); // Use data-src-2400 for mobile and data-src-650 for desktop
    modalImg.src = newSrc;
});

$("#inner-slider").on('slide.bs.carousel', function (evt) {
    var thisSlideI = $(this).find('.active').index();
    var nextSlideI = $(evt.relatedTarget).index();
    $('[data-slide-to="' + thisSlideI + '"]').removeClass('active');
    $('[data-slide-to="' + nextSlideI + '"]').addClass('active');
});
