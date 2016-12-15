<?php
//* Template Name: Shop

//* Enqueue script for category buttons
add_action('wp_enqueue_scripts', 'gbo_page_shop_scripts');
function gbo_page_shop_scripts () {
    wp_enqueue_script('page_shop_buttons', get_bloginfo('stylesheet_directory') . '/js/page-shop.min.js', array('jquery'));
    wp_localize_script('page_shop_buttons', 'gbo_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

//* Remove standard post content output and sidebar
remove_action('genesis_sidebar', 'genesis_do_sidebar');

//* Hook custom sidebar
add_action('genesis_sidebar', 'gbo_page_shop_sidebar');
function gbo_page_shop_sidebar () { ?>
    <section class='widget widget_text'>
        <div class='widget-wrap'>
            <h4 class='widget-title widgettitle'>Category</h4>
            <div class='textwidget'>
                <input type='button' id='420285' class='gbo-shop-button' value='Her Clothes'>
                <input type='button' id='420303' class='gbo-shop-button' value='His Clothes'>
                <input type='button' id='420308' class='gbo-shop-button' value='Kids Clothes'>
            </div>
        </div>
    </section>
    <section class='widget widget_text'>
        <div class='widget-wrap'>
            <div class='textwidget'>
                <input type='button' id='420309' class='gbo-shop-button' value='Shoes'>
                <input type='button' id='420312' class='gbo-shop-button' value='Handbags'>
                <input type='button' id='420317' class='gbo-shop-button' value='Accessories/Jewelry'>
            </div>
        </div>
    </section>
    <section class='widget widget_text'>
        <div class='widget-wrap'>
            <div class='textwidget'>
                <input type='button' id='420320' class='gbo-shop-button' value='Makeup/Beauty'>
                <input type='button' id='420327' class='gbo-shop-button' value='Home'>
            </div>
        </div>
    </section>
<?php
}

genesis();
