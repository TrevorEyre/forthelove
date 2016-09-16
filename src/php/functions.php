<?php
// Start the engine
include_once(get_template_directory() . '/lib/init.php');

// Child Theme Definitions (Do Not Remove)
define('CHILD_THEME_NAME', __('GBO', 'gbo'));
define('CHILD_THEME_URL', 'http://trevoreyre.com');
define('CHILD_THEME_VERSION', '1.0.1');

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', 'gbo_scripts_styles');
function gbo_scripts_styles () {
    wp_enqueue_script('gbo-main-js', get_bloginfo('stylesheet_directory' ) . '/js/main.min.js', array('jquery'), '1.0.0');
    wp_enqueue_script('gbo-fastclick', get_bloginfo('stylesheet_directory') . '/js/fastclick.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('gbo-remove-pinit', get_bloginfo('stylesheet_directory') . '/js/remove-pinit.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('dashicons');
    wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Merriweather:400,700,400italic|Source+Sans+Pro:400', array(), CHILD_THEME_VERSION);
}

// Ajax function for Reward Style widget on shop page [show_boutique_widget id="267828"]
add_action('wp_ajax_shop_button_shortcode', 'shop_button_shortcode_ajax');
add_action('wp_ajax_nopriv_shop_button_shortcode', 'shop_button_shortcode_ajax');
function shop_button_shortcode_ajax () {
    echo do_shortcode('[show_boutique_widget id="' . $_REQUEST['shortcode_id'] . '"]');
    die();
}

// Pinterest hovering pinit button script
add_action('wp_head', 'gbo_pinit_js');
function gbo_pinit_js () { ?>
    <script async data-pin-color="red" data-pin-height="28" data-pin-hover="true" defer src="//assets.pinterest.com/js/pinit.js"></script>
<?php }

// Add HTML5 markup structure
add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption',));

// Add viewport meta tag for mobile browsers
add_theme_support('genesis-responsive-viewport');

// Add support for custom header
add_theme_support('custom-header', array(
    'header-selector' => '.site-title a',
    'flex-height' => true,
    'height' => 136,
    'width' => 201,
    'flex-width' => true,
    'header-text' => false,
));

// Unregister secondary navigation menu
add_theme_support('genesis-menus', array('primary' => __('Primary Navigation Menu', 'genesis')));

// Remove the header right widget area
unregister_sidebar('header-right');

// Remove the site description
remove_action('genesis_site_description', 'genesis_seo_site_description');

// Remove extra site layouts
genesis_unregister_layout('content-sidebar-sidebar');
genesis_unregister_layout('sidebar-sidebar-content');
genesis_unregister_layout('sidebar-content-sidebar');

// Remove the secondary sidebar
unregister_sidebar('sidebar-alt');

// Reposition the primary navigation menu
remove_action('genesis_after_header', 'genesis_do_nav');
add_action('genesis_before_header', 'genesis_do_nav');

// Search bar label
add_filter('genesis_search_text', 'gbo_search_text');
function gbo_search_text ($text) {
    return esc_attr('Search GBO...');
}

// Position post info above post title
remove_action('genesis_entry_header', 'genesis_post_info', 12);
add_action('genesis_entry_header', 'genesis_post_info', 9);

// Customize the entry meta in the entry header (requires HTML5 theme support)
add_filter('genesis_post_info', 'gbo_post_info_filter');
function gbo_post_info_filter ($post_info) {
    return '[post_date]';
}

// Replaces [...] at end of excerpt with just ...
add_filter('excerpt_more', 'gbo_excerpt_more');
function gbo_excerpt_more ($more) {
    return '...';
}

// Modify the length of post excerpts
add_filter('excerpt_length', 'gbo_excerpt_length');
function gbo_excerpt_length ($length) {
    return 150;
}

// View Post button in excerpt
add_filter('the_excerpt', 'gbo_excerpt_view_post');
function gbo_excerpt_view_post ($output) {
    return $output . "<div class='gbo-entry-excerpt'><a class='button excerpt-button' href='" . get_permalink() . "'>View the Post</a></div>";
}

// Add button class to next posts and previous posts links
add_filter('next_posts_link_attributes', 'gbo_posts_link_attributes');
add_filter('previous_posts_link_attributes', 'gbo_posts_link_attributes');
function gbo_posts_link_attributes () {
    return "class='button'";
}

// Older posts label
add_filter ('genesis_next_link_text' , 'gbo_next_page_link');
function gbo_next_page_link ($text) {
    return 'Older Posts >';
}

// Newer posts label
add_filter ('genesis_prev_link_text' , 'gbo_previous_page_link');
function gbo_previous_page_link ($text) {
    return '< Newer Posts';
}

// Override Medium Thumbnail Size
add_image_size('medium', 300, 300, TRUE);

// Modify the size of the Gravatar in the entry comments
add_filter('genesis_comment_list_args', 'gbo_comments_gravatar');
function gbo_comments_gravatar ($args) {
    $args['avatar_size'] = 100;
    return $args;
}

// Remove comment form allowed tags
add_filter('comment_form_defaults', 'gbo_remove_comment_form_allowed_tags');
function gbo_remove_comment_form_allowed_tags ($defaults) {
    $defaults['comment_notes_after'] = '';
    return $defaults;
}

// Add hero image at top of page. This is only done on the front page of the blog
add_action('genesis_before_header', 'gbo_hero_image');
function gbo_hero_image () {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if (is_home() && $paged == 1) {
        remove_action('genesis_header', 'genesis_header_markup_open', 5);
        remove_action('genesis_header', 'genesis_do_header');
        remove_action('genesis_header', 'genesis_header_markup_close', 15);
        echo "
            <div class='gbo-hero-image' style='background-image:url(" . get_bloginfo('stylesheet_directory') . "/images/Hero-Image.jpg);'>
                <div class='gbo-hero-image_nav-gradient'></div>
                <div class='gbo-hero-image_cover'></div>
                <div class='gbo-hero-image_slogan'>
                    <img src='" . get_bloginfo('stylesheet_directory') . "/images/GBO-Logo-White.png'>
                    <h1>Answers to the pressing question, &#039;Do I look good, bad, or okay?&#039</h1>
                    <div id='hero-scroll' class='button-call'>Scroll Down</div>
                </div>
            </div>
        ";
    }
}

// Add social icons, search bar to end of primary nav
add_filter('wp_nav_menu_items', 'gbo_nav_menu_items', 10, 2);
function gbo_nav_menu_items ($menu, $args) {
    // Change 'primary' to 'secondary' to add extras to the secondary navigation menu
    if ('primary' !== $args->theme_location)
        return $menu;

    // Add logo to left side of nav
    $navLogo = "
        <li class='menu-item gbo-nav-logo'>
            <a href='" . home_url() . "'>
                <img src='" . get_bloginfo('stylesheet_directory') . "/images/GBO-Nav-Logo.png'>
            </a>
        </li>
    ";

    // Add social media icons to left side of nav
    $socialIcons = "
        <li class='menu-item gbo-social-icons'>
            <a class='gbo-icon-heart' href='https://www.bloglovin.com/blogs/gbo-fashion-3582697' target='_blank'></a>
        </li>
        <li class='menu-item gbo-social-icons'>
            <a class='gbo-icon-facebook' href='http://www.facebook.com/GboFashion' target='_blank'></a>
        </li>
        <li class='menu-item gbo-social-icons'>
            <a class='gbo-icon-instagram-filled' href='http://instagram.com/shannonwillardson' target='_blank'></a>
        </li>
        <li class='menu-item gbo-social-icons'>
            <a class='gbo-icon-pinterest' href='http://pinterest.com/shanwillardson' target='_blank'></a>
        </li>
        <li class='menu-item gbo-social-icons'>
            <a class='gbo-icon-rss' href='http://feeds.feedburner.com/GboFashion' target='_blank'></a>
        </li>
        <li class='menu-item gbo-social-icons gbo-social-icons--last'>
            <a class='gbo-icon-twitter' href='https://twitter.com/#!/shanwillardson' target='_blank'></a>
        </li>
    ";

    // Add search bar to right side of nav
    ob_start();
    get_search_form();
    $search = ob_get_clean();
    $search = "<li class='menu-item gbo-search gbo-margin-left'>" . $search . "</li>";

    return $navLogo . $socialIcons . $menu . $search;
}

// Add floating social icons to right hand side of screen
/*
add_action('genesis_after_header', 'gbo_right_social_icons');
function gbo_right_social_icons () {
    echo "
        <div class='floating-social-icons-right gbo-fixed'>
            <ul>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-heart' href='https://www.bloglovin.com/blogs/gbo-fashion-3582697' target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-facebook' href='http://www.facebook.com/GboFashion' target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-instagram-filled' href='http://instagram.com/shannonwillardson' target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-pinterest' href='http://pinterest.com/shanwillardson' target='_blank'></a>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-rss' href='http://feeds.feedburner.com/GboFashion' target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-twitter' href='https://twitter.com/#!/shanwillardson' target='_blank'></a>
                </li>
            </ul>
        </div>
   ";
}
*/
// Customize the entry meta in the entry footer (requires HTML5 theme support)
add_filter('genesis_post_meta', 'gbo_post_meta_filter');
function gbo_post_meta_filter ($post_meta) {
    $pinScript = "javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());";

    $entry_meta = "
        <div class='gbo-entry-meta clearfix'>
            <div class='gbo-entry-meta_left'>
                <ul class='post-meta-social'>
                <p>Share: </p>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-pinterest' href=$pinScript target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-facebook' href='https://www.facebook.com/sharer/sharer.php?u=" . get_permalink() . "' target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-twitter' href='https://twitter.com/home?status=" . get_permalink() . "' target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-gplus' href='https://plus.google.com/share?url=" . get_permalink() . "' target='_blank'></a>
                </li>
                <li class='gbo-social-icons'>
                    <a class='gbo-icon-mail-alt' href='mailto:?&subject=" . get_the_title() . "&body=Check%20out%20" . get_the_title() . "%20on%20GBO%20Fashion.%0A%0A" . get_permalink() . "' target='_blank'></a>
                </li>
                </ul>
            </div>
            <div class='gbo-entry-meta_left-dot'>&middot</div>
            <div class='gbo-entry-meta_middle'>"
                . '[post_comments zero="No Comments <p>Leave yours!</p>" one="1 Comment <p>Leave yours!</p>" more="% Comments <p>Leave yours!</p>"]' . "
            </div>
            <div class='gbo-entry-meta_right-dot'>&middot</div>
            <div class='gbo-entry-meta_right'>"
                . '[post_categories before="View all posts in: "]' . "
            </div>
        </div>
        <div class='gbo-post-spacer'>
            <span><img src='" . get_bloginfo('stylesheet_directory') . "/images/GBO-Nav-Logo.png'></span>
        </div>
    ";

    return $entry_meta;
}

// Change the footer text
add_filter('genesis_footer_creds_text', 'gbo_footer_creds_filter');
function gbo_footer_creds_filter ($creds) {
    $followOnInsta = "<a href='http://instagram.com/shannonwillardson' target='_blank'><h2 class='gbo-insta-footer'><span>@shannonwillardson on Instagram</span></h2></a>";
    $rewardErrorMessage = 'Disable your ad blocking software to view this content.';
    $rewardStyleScript = "<div class='ltkwidget-widget' data-rows='2' data-cols='8' data-show-frame='false' data-user-id='50912' data-padding='0'><script type='text/javascript'>!function(d,s,id){var e, p = /^http:/.test(d.location) ? 'http' : 'https';if(!d.getElementById(id)) {e = d.createElement(s);e.id = id;e.src = p + '://' + 'widgets.rewardstyle.com' + '/js/ltkwidget.js';d.body.appendChild(e);}if(typeof(window.__ltkwidget) === 'object') {if(document.readyState === 'complete') {__ltkwidget.init();}}}(document, 'script', 'ltkwidget-script');</script><div class='rs-adblock'><img src='//assets.rewardstyle.com/images/search/350.gif' onerror='this.parentNode.innerHTML=$rewardErrorMessage' /><noscript>JavaScript is currently disabled in this browser. Reactivate it to view this content.</noscript></div></div>";
    $creds = "<p>[footer_copyright] GBO Fashion &middot All Rights Reserved &middot Website Design by <a href='http://trevoreyre.com' target='_blank'>Trevor Eyre</a></p>";

    return $followOnInsta . $rewardStyleScript . $creds;
}

// Modify loop on home page to include Instagram widget after second post
add_action('genesis_before_loop', 'gbo_home_loop');
function gbo_home_loop () {
    if (is_home()) {
        remove_action('genesis_loop', 'genesis_do_loop');
        add_action('genesis_loop', 'gbo_custom_loop');

        function gbo_custom_loop () {
            $gbo_post_counter = 1;
            if (have_posts()) :
                do_action('genesis_before_while');
                while (have_posts()) : the_post();

                    do_action('genesis_before_entry');
                    printf('<article %s>', genesis_attr('entry'));
                    do_action('genesis_entry_header');
                    do_action('genesis_before_entry_content');
                    printf('<div %s>', genesis_attr('entry-content'));

                    do_action('genesis_entry_content'); // Remove standard excerpt
                    echo '</div>';
                    do_action('genesis_after_entry_content');
                    do_action('genesis_entry_footer');
                    echo '</article>';
                    do_action('genesis_after_entry');

                    if ($gbo_post_counter == 2) {
                        echo "
                            <article class='entry post'>
                                <header class='gbo-insta-widget_header'>
                                    <h2 class='entry-title'>
                                        <a href='http://instagram.com/shannonwillardson' target='_blank'>What&#39s New on Instagram</a>
                                    </h2>
                                </header>
                                <div class='entry-content gbo-insta-widget_body'>";
                                    echo do_shortcode('[instagram-feed]');
                        echo "
                                </div>
                                <div class='gbo-post-spacer'>
                                    <span><img src='" . get_bloginfo('stylesheet_directory') . "/images/GBO-Nav-Logo.png'></span>
                                </div>
                            </article>
                        ";
                    }
                    $gbo_post_counter++;

                endwhile; // end of one post
                do_action('genesis_after_endwhile');

            else : // if no posts exist
                do_action('genesis_loop_else');
            endif; // end loop
        }
    }
}
