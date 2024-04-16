(function ($) {
    'use strict';

    let arrowIcon = '<svg width="5" height="8" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.19824 1.14453L4.03516 3.98242L1.19824 6.81934" stroke="#E5E5E5"/></svg>';
    $('.recently-viewed-products .viewed-products').slick({
        // 4 products per slide
        slidesToShow: 3,
        prevArrow: '<button type="button" class="slick-prev">'+ arrowIcon +'</button>',
        nextArrow: '<button type="button" class="slick-next">'+ arrowIcon +'</button>',
    });

})(jQuery);