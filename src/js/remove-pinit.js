jQuery(window).bind('load', function () {
    jQuery('img').attr('data-pin-no-hover', true);
    jQuery('.entry-content > a img').removeAttr("data-pin-no-hover");
    jQuery('.entry-content > p img').removeAttr("data-pin-no-hover");
});
