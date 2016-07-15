jQuery(document).ready(function ($) {

    //Initialize widget
    getShopWidget('Nordstrom Sale', '420338');

    //Change widget on button click
    $('.gbo-shop-button').on('click', function () {
        var entryTitle = $(this).attr('value');
        var id = $(this).attr('id');
        getShopWidget(entryTitle, id);
    });

    // Get shop widget via AJAX
    function getShopWidget (entryTitle, widgetId) {
        $('.entry-title').html(entryTitle);
        $.ajax({
            url: gbo_ajax.ajaxurl,
            type: 'POST',
            dataType: 'html',
            data: {
                action: 'shop_button_shortcode',
                'shortcode_id': widgetId
            },
            dataType: 'html',
            success: function (response) {
                $('.entry-content').html(response);
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    }
});
