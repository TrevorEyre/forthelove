jQuery(document).ready(function ($) {

    //Initialize widget
    $('.entry-title').html('Her Clothes');
    jQuery.ajax({
        url: gbo_ajax.ajaxurl,
        type: 'POST',
        dataType: 'html',
        data: {
            action: 'shop_button_shortcode',
            'shortcode_id': '300313'
        },
        dataType: 'html',
        success: function (response) {
            $('.entry-content').html(response);
        },
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });

    //Change widget on button click
    jQuery('.gbo-shop-button').on('click', function () {
        $('.entry-title').html($(this).attr('value'));
        jQuery.ajax({
            url: gbo_ajax.ajaxurl,
            type: 'POST',
            dataType: 'html',
            data: {
                action: 'shop_button_shortcode',
                'shortcode_id': this.id
            },
            dataType: 'html',
            success: function (response) {
                $('.entry-content').html(response);
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    });
});
