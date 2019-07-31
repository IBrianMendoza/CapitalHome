<?php
/*
  # =================================================
  # The theme's functions.
  # =================================================
 */

/**
 *       ----------------------------------------------------------------------------------------
 * 1.0 - Define constants.
 * ----------------------------------------------------------------------------------------
 */
define('SELIO_THEMEROOT', get_stylesheet_directory_uri());
define('SELIO_IMAGES', SELIO_THEMEROOT . '/assets/images');

define('SELIO_JS', SELIO_THEMEROOT . '/assets/js');
define('SELIO_CSS', SELIO_THEMEROOT . '/assets/css');
define('SELIO_LIBS', SELIO_THEMEROOT . '/assets/libraries');
define('SELIO_FRAMEWORK', get_template_directory() . '/inc');

define('SELIO_MAIN_PLUGIN_FOLDER', 'SW_Win_Classified');
define('SELIO_MAIN_PLUGIN_REQUIRED', SELIO_MAIN_PLUGIN_FOLDER . '/index.php');


$selio_server_prtc = wp_get_server_protocol();
$selio_protocol = stripos($selio_server_prtc, 'https') === true ? 'https://' : 'http://';
define('SELIO_PROTOCOL', $selio_protocol);
/**
 * ----------------------------------------------------------------------------------------
 * 2.0 - Load the framework.
 * ----------------------------------------------------------------------------------------
 */
require_once( SELIO_FRAMEWORK . '/init.php' );

/**
 * ----------------------------------------------------------------------------------------
 * 3.0 - Set up the content width value based on the theme's design.
 * ----------------------------------------------------------------------------------------
 */

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function selio__content_width() {
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('selio__content_width', 800);
}

add_action('after_setup_theme', 'selio__content_width', 0);

/**
 * ----------------------------------------------------------------------------------------
 * 4.0 - Set up theme default and register various supported features.
 * ----------------------------------------------------------------------------------------
 */
function selio_setup() {
    /**
     * Make the theme available for translation.
     */
    $lang_dir = get_template_directory() . '/locale';
    load_theme_textdomain('selio', $lang_dir);


    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');
    //Add custom background
    add_theme_support('custom-background');
    //Add custom header
    add_theme_support('custom-header');
    
    //Add woocommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    // Set the default content width.
    if (!isset($content_width)) {
        $content_width = 600;
    }

    add_image_size('selio-555x442', 555, 442, true);
    add_image_size('selio-570x570', 570, 570, true);
    add_image_size('selio-770x483', 770, 483, true);
    add_image_size('selio-300x250', 270, 150, true);

    // This theme uses wp_nav_menu() in header
    register_nav_menus(array(
        'top' => esc_html__('Top Menu', 'selio'),
    ));

    /**
     * Add default theme mods
     */
    $mods = get_theme_mods();

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Add theme support for Custom Logo.
    add_theme_support('custom-logo', array(
        'width' => 250,
        'height' => 250,
        'flex-width' => true,
    ));

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');
}

add_action('after_setup_theme', 'selio_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function selio_widgets_init() {
    register_sidebar(
            array(
                'name' => esc_html__('Blog Sidebar', 'selio'),
                'id' => 'sidebar-1',
                'description' => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'selio'),
                'before_widget' => '<div id="%1$s" class="widget %2$s side">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
    );

    register_sidebar(
            array(
                'name' => esc_html__('Blog footer', 'selio'),
                'id' => 'footer-1',
                'description' => esc_html__('Add widgets here to appear in blog footer.', 'selio'),
                'before_widget' => '<div id="%1$s" class="widget footer-regular %2$s col-xl-3 col-sm-6 col-md-3">',
                'after_widget' => '</div>',
                'before_title' => '<h3>',
                'after_title' => '</h3>',
            )
    );

    register_sidebar(
            array(
                'name' => esc_html__('Listing Preview Sidebar', 'selio'),
                'id' => 'sidebar-listing-1',
                'description' => esc_html__('Add widgets here to appear in your sidebar on listing preview page.', 'selio'),
                'before_widget' => '<div id="%1$s" class="widget %2$s side">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
    );

    register_sidebar(
            array(
                'name' => esc_html__('Right sidebar Agent profile', 'selio'),
                'id' => 'sidebar-profile-1',
                'description' => esc_html__('Appears on Agent profile page.', 'selio'),
                'before_widget' => '<div id="%1$s" class="widget %2$s side">',
                'after_widget' => '</div> <!-- end widget -->',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
    );

    register_sidebar(
            array(
                'name' => esc_html__('Sidebar for Results', 'selio'),
                'id' => 'sidebar-results-1',
                'description' => esc_html__('Appears on Results with sidebar.', 'selio'),
                'before_widget' => '<div id="%1$s" class="widget %2$s side">',
                'after_widget' => '</div> <!-- end widget -->',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
    );

    register_sidebar(
            array(
                'name' => esc_html__('Bottom sidebar', 'selio'),
                'id' => 'bottom-selio',
                'description' => esc_html__('Bottom Sidebar Full Wide', 'selio'),
                'before_widget' => '<div id="%1$s" class="%2$s col-xl-3 col-sm-6 col-md-3">',
                'after_widget' => '</div> <!-- end widget -->',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
    );

    register_sidebar(
            array(
                'name' => esc_html__('Bottom sidebar Listing', 'selio'),
                'id' => 'bottom-selio-listing',
                'description' => esc_html__('Bottom Sidebar for Listing page', 'selio'),
                'before_widget' => '<div id="%1$s" class="%2$s">',
                'after_widget' => '</div> <!-- end widget -->',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
    );
}

add_action('widgets_init', 'selio_widgets_init');

function selio_fonts_url() {
    $font_url = '';
    
    /*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
     */
    if ( 'off' !== _x( 'on', 'Google font: on or off', 'selio' ) ) {
        $font_url = add_query_arg( 'family',  'Lora%7COpen+Sans:300,400,600,700%7CPlayfair+Display:400,700%7CPoppins:300,400,500,600,700%7CRaleway:300,400,500,600,700,800%7CRoboto:300,400,500,700&display=swap&subset=cyrillic&display=swap', "//fonts.googleapis.com/css" );
    }
    return $font_url;
}

/**
 * Enqueue scripts and styles.
 */
function selio_scripts() {

    wp_enqueue_style('selio-animate', SELIO_CSS . '/animate.min.css');

    wp_enqueue_style('slick', SELIO_THEMEROOT . '/assets/js/lib/slick/slick.css');
    wp_enqueue_style('slick-theme', SELIO_THEMEROOT . '/assets/js/lib/slick/slick-theme.css');

    wp_enqueue_style('fontawesome-5', SELIO_LIBS.'/fontawesome-5.8/css/fontawesome-5.css');
    wp_enqueue_style('font-awesome', SELIO_THEMEROOT . '/assets/icons/font-awesome/css/font-awesome.min.css');
    wp_enqueue_style('simple-line-icons', SELIO_THEMEROOT . '/assets/icons/simple-line-icons/css/simple-line-icons.css');
    wp_enqueue_style('line-awesome', SELIO_THEMEROOT . '/assets/icons/simple-line-icons/css/line-awesome.min.css');
    wp_enqueue_style('bootstrap', SELIO_THEMEROOT . '/assets/css/bootstrap.min.css');

    wp_enqueue_style('selio-style', SELIO_THEMEROOT . '/assets/css/style.css');

    wp_enqueue_style('selio-responsive', SELIO_THEMEROOT . '/assets/css/responsive.css');
    wp_enqueue_style('selio-color', SELIO_THEMEROOT . '/assets/css/color.css');

    wp_enqueue_style( 'selio-fonts', selio_fonts_url(), array(), null );
    
    wp_enqueue_style('ionicons', SELIO_THEMEROOT . '/assets/css/ionicons.min.css', '', '4.1.2');
    wp_enqueue_style('blueimp-gallery', SELIO_THEMEROOT . '/assets/css/blueimp-gallery.min.css');

    wp_enqueue_script('nouislider', SELIO_LIBS . '/nouislider/nouislider.js', false, false, true);
    wp_enqueue_style('nouislider', SELIO_LIBS . '/nouislider/nouislider.css');

    wp_enqueue_script('card-slider', SELIO_LIBS . '/card-slider/dist/js/card-slider-min.js', false, false, true);
    wp_enqueue_style('card-slider-style', SELIO_LIBS . '/card-slider/dist/css/style.css');

    wp_enqueue_style('selio-wp-style', SELIO_THEMEROOT . '/assets/css/wp-style.css');

    wp_enqueue_style('selio-helper-form', SELIO_THEMEROOT . '/assets/css/helper-form.css');
    wp_enqueue_style('selio-custom', SELIO_THEMEROOT . '/assets/css/custom.css');
    wp_enqueue_style('selio-custom-media', SELIO_THEMEROOT . '/assets/css/custom-media.css');

    // dynamic loaded files based on page/template file
    if (selio_plugin_call::sw_settings('results_page') && sw_is_page(selio_plugin_call::sw_settings('quick_submission'))) {
        wp_enqueue_style('selio-quick-submission', SELIO_CSS . '/quick-submission.css');
    }

    if (is_rtl()) {
        wp_enqueue_style('selio-rtl', SELIO_THEMEROOT . '/assets/css/rtl.css', '', '1.0');
    }

    wp_enqueue_script('modernizr', SELIO_THEMEROOT . '/assets/js/modernizr-3.6.0.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('bootstrap', SELIO_THEMEROOT . '/assets/js/bootstrap.min.js', '', '4.1', false, false, true);
    wp_enqueue_script('bootstrap-affix', SELIO_THEMEROOT . '/assets/js/affix.js', '', '3.7', false, false, true);
    wp_enqueue_script('selio-scripts', SELIO_THEMEROOT . '/assets/js/scripts.js', array('jquery'), '1.0', true);

    wp_enqueue_script('blueimp-gallery', SELIO_THEMEROOT . '/assets/js/blueimp-gallery.js', array('jquery'), '', true);
    wp_enqueue_script('slick', SELIO_THEMEROOT . '/assets/js/lib/slick/slick.js', array('jquery'), '', true);
    wp_enqueue_script('html5lightbox', SELIO_THEMEROOT . '/assets/js/html5lightbox.js', array('jquery'), '', true);
    wp_enqueue_script('facebook', SELIO_JS . '/facebook.js', false, false, true);
    wp_enqueue_script('selio-drop-menu', SELIO_JS . '/selio-drop-menu.js', false, false, true);
    wp_enqueue_script('map-cluster-infobox', SELIO_JS . '/map-cluster/infobox.min.js', array('google-maps-api-w'), false, true);

    wp_enqueue_script('google-markerclusterer', SELIO_JS . '/markerclusterer.js', array('google-maps-api-w'), false, true);
    wp_enqueue_script('selio-maps', SELIO_JS . '/map-cluster/maps.js', array('google-maps-api-w'), false, true);
    wp_enqueue_script('selio-sw-custom-marker', SELIO_JS . '/sw-custom-marker.js', array('google-maps-api-w'), false, true);

    wp_enqueue_script('raphael', SELIO_JS . '/map-cluster/raphael.js', array('google-maps-api-w'), false, true);
    wp_enqueue_script('jquery-usmap', SELIO_JS . '/map-cluster/jquery.usmap.js', array('google-maps-api-w'), false, true);

    wp_enqueue_script('jquery-helpers', SELIO_JS . '/jquery.helpers.js', false, false, true);

    wp_register_script('bootstrap-datetimepicker', SELIO_JS . '/bootstrap-datetimepicker.min.js', false, false, true);
    wp_enqueue_script('selio-custom', SELIO_JS . '/custom.js', false, false, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'selio_scripts');

function selio_admin_scripts($hook) {
    wp_enqueue_script('jquery-wpmediaelement', SELIO_JS . '/jquery.wpmediaelement.js', false, false, false);

    add_editor_style(array(SELIO_CSS . '/editor-style.css', SELIO_CSS . '/basic-bootstrap.css'));
}

add_action('admin_enqueue_scripts', 'selio_admin_scripts');

/**
 * remove h2 tag from pagination
 */
function selio_sanitize_pagination($content) {
    $content = preg_replace('#<h2.*?>(.*?)<\/h2>#si', '', $content);

    return $content;
}

add_action('navigation_markup_template', 'selio_sanitize_pagination');

/**
 * set header_textcolor to desktom navigation links
 */
function selio_header_text_color_function() {
    if (get_header_textcolor()) {

        echo '<style type="text/css">
		.navigation a,
		.navigation > ul .sub-menu a{
			color: #' . esc_html(get_header_textcolor()) .
        '}
		</style>';
    }
}

add_action('wp_head', 'selio_header_text_color_function');

/**
 * breadcrumbs
 */
function selio_the_selio_breadcrumb($home_title = '', $page_title = FALSE, $skip_post = FALSE) {

    if($home_title =='') 
        $home_title = esc_html__('Home', 'selio');
    
    $munu_array = array();
    $items = array();

    $locations = get_nav_menu_locations();
    if($locations && isset($locations['top'])) {
        $theme_location = $locations['top'];
        $items = wp_get_nav_menu_items($theme_location);

        if(!empty($items))
            foreach ($items as $item) {
                $munu_array[$item->object_id] = $item;
        }
    }

    $breadcrump_string = array();
    $id = 0;
    if (empty($page_title)) {
        $id = get_queried_object_id ();
    } else {
        $page_item = get_page_by_title($page_title);
        if (!empty($page_item))
            $id = $page_item->ID;
    }
    
    if (!is_home()) {
        if (empty($id) || !isset($munu_array[$id])) {
            $li = '';
            if (function_exists('is_product_tag') && is_product_tag()) {
                $li = esc_html__('Product', 'selio');
            } 
            elseif (function_exists('is_product_category') && is_product_category()) {
                $li = esc_html__('Category', 'selio');
            } 
            elseif (function_exists('is_shop') && is_shop()) {
                $li = esc_html__('Shop', 'selio');
            } 
            elseif (is_tag()) {
                $li = esc_html__('Tag: ', 'selio') . esc_html(single_tag_title("", false));
            } elseif (is_category()) {
                $li = esc_html__('Catagory', 'selio');
            } elseif (is_archive()) {
                $li = esc_html__('Archive', 'selio');
            } elseif (is_search()) {
                $li = esc_html__('Search', 'selio');
            } elseif (is_home()) {
                $li = esc_html__('Blog', 'selio');
            }
            
            if($li !='')
                $breadcrump_string[] = $li;
        }
    
        if(is_single()){
            $li = get_the_title();
            $breadcrump_string[] = $li;
        }
    }

    if ($skip_post && isset($munu_array[$id]))
        $id = $munu_array[$id]->menu_item_parent;
    if(!is_single()){
        while (isset($munu_array[$id])) {
            $item = $munu_array[$id];

            $breadcrump_string[] = $item->title;
            $id = $item->menu_item_parent;
        };
    }
    
    $breadcrump_string = array_reverse($breadcrump_string);
    
    /* output */
    ?>
    <ul>
        <li class="item">
            <a href="<?php echo esc_url(home_url('/'));?>"><?php echo esc_html($home_title);?></a>
        </li>
        <?php foreach ($breadcrump_string as $value): ?>
            <li>
                <span><?php echo esc_html($value);?></span>
            </li>
        <?php endforeach;?>
    </ul>
    <?php 
}


/**
 * editor styles
 */
function selio_editor_styles() {
    add_editor_style('assets/css/editor-style.css');
}

add_action('current_screen', 'selio_editor_styles');

/**
 * add placeholder to protected posts form
 */
function selio_password_placeholder($output) {
    $placeholder = esc_html__('Password', 'selio');
    $search = 'type="password"';
    return str_replace($search, $search . " placeholder=\"$placeholder\"", $output);
}

add_filter('the_password_form', 'selio_password_placeholder');

/**
 * Custom Gallery Code For Flipboard/Pulse/Google Currents Feeds
 */
add_filter('post_gallery', 'selio_customFormatGallery', 10, 2);

function selio_customFormatGallery($output, $attr) {

    // Modifying for a different gallery output ONLY in my custom feed
    global $post;

    static $instance = 0;
    $instance++;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby'])
            unset($attr['orderby']);
    }

    extract(shortcode_atts(array(
        'order' => 'ASC',
        'orderby' => 'menu_order ID',
        'id' => $post->ID,
        'itemtag' => 'dl',
        'icontag' => 'dt',
        'captiontag' => 'dd',
        'columns' => 3,
        'size' => 'thumbnail',
        'include' => '',
        'exclude' => ''
                    ), $attr));

    $id = intval($id);
    if ('RAND' == $order)
        $orderby = 'none';

    if (!empty($include)) {
        $include = preg_replace('/[^0-9,]+/', '', $include);
        $_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

        $attachments = array();
        foreach ($_attachments as $key => $val) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif (!empty($exclude)) {
        $exclude = preg_replace('/[^0-9,]+/', '', $exclude);
        $attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
    } else {
        $attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
    }

    if (empty($attachments))
        return '';

    $class = 'gallery-columns-3';
    if (isset($attr['columns']) && $attr['columns'] == 1) {
        $class = 'gallery-columns-1';
    }
    if (isset($attr['columns']) && $attr['columns'] == 2) {
        $class = 'gallery-columns-2';
    }
    if (isset($attr['columns']) && $attr['columns'] == 3) {
        $class = 'gallery-columns-3';
    }
    if (isset($attr['columns']) && $attr['columns'] == 4) {
        $class = 'gallery-columns-4';
    }

    if (isset($attr['columns']) && $attr['columns'] == 5) {
        $class = 'gallery-columns-5';
    }

    if (isset($attr['columns']) && $attr['columns'] == 6) {
        $class = 'gallery-columns-6';
    }

    if (isset($attr['columns']) && $attr['columns'] == 7) {
        $class = 'gallery-columns-7';
    }

    if (isset($attr['columns']) && $attr['columns'] == 8) {
        $class = 'gallery-columns-8';
    }

    if (isset($attr['columns']) && $attr['columns'] == 9) {
        $class = 'gallery-columns-9';
    }
    $output = '<div class="gallery-container" id="custom-gallery">';

    $output .= '<div data-target="#modal-gallery" data-toggle="modal-gallery" class="row images-gallery">';

    foreach ($attachments as $att_id => $attachment) {
        $src = wp_get_attachment_image_src($att_id, 'selio-770x483');
        $src = $src[0];
        if (!empty($src))
            $output .= '
		<div class="gallery-columns ' . esc_attr($class) . '">
			<div class="preview-img card-gallery">
				<a data-description="' . esc_attr(wp_trim_words($attachment->post_excerpt, 10, '...')) . '" data-gallery="gallery" href="' . esc_url($src) . '" title="' . esc_attr($attachment->post_title) . '" download="' . esc_url($src) . '" class="preview show-icon">
					<img src="' . esc_url($src) . '" data-src="' . esc_url($src) . '" alt="' . esc_attr($attachment->post_title) . '" class="gallery-image" />
				</a>
			</div>
			<p class="text">' . esc_html(wptexturize($attachment->post_excerpt)) . '</p>
		</div>';
    }

    $output .= '</div></div>';

    return $output;
}

/**
 * Comments
 */
function selio_comment($comment, $args, $depth) {
    $date_format = get_option('date_format');
    ?>
    <li id="comment-<?php comment_ID() ?>">
        <div class="cm-info-sec">
            <div class="cm-img">
    <?php echo get_avatar($comment, 79); ?>
            </div><!--author-img end-->
            <div class="cm-info">
                <h3><?php echo esc_html($comment->comment_author); ?></h3>
                <h4><?php echo esc_html(get_comment_date($date_format)); ?></h4>
            </div>
        </div><!--cm-info-sec end-->
        <?php echo comment_text(get_comment_ID()); ?>
    <?php
    $link = get_comment_reply_link(array(
        'reply_text' => esc_attr__("Reply", 'selio'),
        'depth' => 1,
        'max_depth' => 2,
        'class' => 'cm-reply',
    ));

    echo wp_kses($link, 'post');
    ?>

        <?php
    }

    function esc_view($content) {
        return $content;  // WPCS: XSS ok, sanitization ok.
    }

    function esc_viewe($content) {
        // @codingStandardsIgnoreStart
        if (function_exists('sw_win_viewe'))
            sw_win_viewe($content); // WPCS: XSS ok, sanitization ok.
            
// @codingStandardsIgnoreEnd
    }

    /*
      Theme installation
     */

    /* Check for installed plugins */

    function selio_notinstalled_admin_notice__error() {
        global $wpdb;
        $user_id = get_current_user_id();
        if (!get_user_meta($user_id, 'selio_notinstalled_admin_notice__error_dismissed')) {

            if (is_dir(WP_PLUGIN_DIR . '/' . SELIO_MAIN_PLUGIN_FOLDER) || (isset($_GET['page']) && $_GET['page'] == 'theme-panel')) {
                return;
            }

            $class = 'notice notice-error';
            $message = '';

            $message .= esc_html__('Recommended plugins for full theme functionality not installed:', 'selio');
            $message .= ' <a href="' . esc_url(admin_url("themes.php?page=theme-panel&amp;not_installed=true")) . '">' . esc_html__('Click to install', 'selio') . '</a><br />';

            printf('<div class="%1$s" style="position:relative;"><p>%2$s</p><a href="?selio_notinstalled_admin_notice__error_dismissed"><button type="button" class="notice-dismiss"></button></a></div>', esc_html($class), esc_view($message));  // WPCS: XSS ok, sanitization ok.
        }
    }

    add_action('admin_notices', 'selio_notinstalled_admin_notice__error');

    function selio_notinstalled_admin_notice__error_dismissed() {
        $user_id = get_current_user_id();
        if (isset($_GET['selio_notinstalled_admin_notice__error_dismissed']))
            add_user_meta($user_id, 'selio_notinstalled_admin_notice__error_dismissed', 'true', true);
    }

    add_action('admin_init', 'selio_notinstalled_admin_notice__error_dismissed');

    function selio_theme_settings_page() {

        if (is_dir(WP_PLUGIN_DIR . '/' . SELIO_MAIN_PLUGIN_FOLDER)) {
            ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Theme plugins', 'selio'); ?></h1>
            <a href="<?php echo esc_url(admin_url("plugins.php")); ?>"><?php echo esc_html__('Plugins included in theme already extracted, check plugins page and activate if needed.', 'selio'); ?></a>
        </div>
        <?php
        return;
    }

    if (isset($_POST["included_plugins"]) && $_POST["included_plugins"] == 1) {
        // extract plugins and start installation
        $demo_plugins_dir = get_template_directory() . "/inc/tgm_pa/plugins/";

        WP_Filesystem();
        $destination = wp_upload_dir();
        $destination_path = WP_PLUGIN_DIR;

        $directory_iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($demo_plugins_dir));

        $is_wp_error = false;
        foreach ($directory_iterator as $filename => $path_object) {
            if (strpos($filename, '.zip') === FALSE) {
                continue;
            }


            $filename = str_replace('\\', '/', $filename);

            $unzipfile = unzip_file($filename, $destination_path);

            if (is_wp_error($unzipfile)) {
                $is_wp_error = true;
            }
        }

        if ($is_wp_error) {
            echo '<div class="wrap">';
            echo '<h1>' . esc_html__('Theme plugins', 'selio') . '</h1>';
            echo esc_html__('There was an error unzipping the file, please install plugins manually.', 'selio');
            echo '</div>';
        } else {
            selio_run_activate_plugin(SELIO_MAIN_PLUGIN_REQUIRED);

            echo '<div class="wrap">';
            echo '<h1>' . esc_html__('Theme plugins', 'selio') . '</h1>';
            echo '<a href="' . esc_url(admin_url("tools.php?page=install_index&amp;not_installed=true")) . '">' . esc_html__('Plugins extract successfuly, click here to start plugin installation.', 'selio') . '</a>';
            echo '</div>';
        }
    } else {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Theme plugins', 'selio'); ?></h1>

            <form method="post" action="<?php echo esc_url(get_permalink());?>" class="theme-install">
        <?php
        settings_fields("section");
        do_settings_sections("theme-options");
        ?>

        <?php
        $plugins = array();
        $plugins[] = array(
            'title' => esc_html__('Visual Listings - Agency Directory and Management', 'selio'),
            'description' => esc_html__('Listings Agency Directory and Management plugin with special visual customization features', 'selio')
        );

        $plugins[] = array(
            'title' => esc_html__('Selio Elementor','selio'),
            'description' => esc_html__('Selio extension for Elementor','selio')
        );

        $plugins[] = array(
            'title' => esc_html__('Elementor','selio'),
            'description' => esc_html__('Visual Builder used in theme','selio')
        );

        $plugins[] = array(
            'title' => esc_html__('Winter Listings WpAllImport','selio'),
            'description' => esc_html__('Winter Listings add-on for WP All Import!','selio')
        );

        $plugins[] = array(
            'title' => esc_html__('Winter Classified Compare','selio'),
            'description' => esc_html__('Addon to compare listing on classified portal','selio')
        );

        $plugins[] = array(
            'title' => esc_html__('Winter Classified Currency converter','selio'),
            'description' => esc_html__('Addon for quick submission on classified portal','selio')
        );

        $plugins[] = array(
            'title' => esc_html__('Winter Classified Dependent fields','selio'),
            'description' => esc_html__('Addon for quick submission on classified portal','selio')
        );

        $plugins[] = array(
            'title' => esc_html__('Winter Classified Favorites','selio'),
            'description' => esc_html__('Addon for quick submission on classified portal','selio')
        );
        $plugins[] = array(
            'title' => esc_html__('Winter Classified SVG Geo maps','selio'),
            'description' => esc_html__('Addon for show GEO map on classified portal','selio')
        );
        $plugins[] = array(
            'title' => esc_html__('Winter Classified PDF Export','selio'),
            'description' => esc_html__('Addon to export listing in PDF on classified portal','selio')
        );
        $plugins[] = array(
            'title' => esc_html__('Winter Classified Quick Submission','selio'),
            'description' => esc_html__('Addon for quick submission on classified portal','selio')
        );
        $plugins[] = array(
            'title' => esc_html__('Winter Classified Rank packages','selio'),
            'description' => esc_html__('Addon for quick submission on classified portal','selio')
        );
        $plugins[] = array(
            'title' => esc_html__('Winter Classified Report','selio'),
            'description' => esc_html__('Addon to report listing on classified portal','selio')
        );
        $plugins[] = array(
            'title' => esc_html__('Winter Classified Reviews','selio'),
            'description' => esc_html__('Addon for quick submission on classified portal','selio')
        );
        $plugins[] = array(
            'title' => esc_html__('Winter Classified Save Search','selio'),
            'description' => esc_html__('Addon to Save Search with alerts on classified portal','selio')
        );
        ?>
                <table class="wp-list-table widefat plugins">
                    <thead>
                        <tr>
                            <th scope="col" id="name" class="manage-column column-name column-primary"><?php echo esc_html__('Plugin', 'selio'); ?></th>
                            <th scope="col" id="description" class="manage-column column-description"><?php echo esc_html__('Description', 'selio'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="the-list">
                        <?php foreach ($plugins as $plugin): ?>
                            <tr class="active">
                                <td class="plugin-title column-primary">
                                    <strong><?php echo esc_html($plugin['title']); ?></strong>
                                </td>
                                <td class="column-description desc">
                                    <div class="plugin-description"><p><?php echo esc_html($plugin['description']); ?></p></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                submit_button(esc_html__('Start extraction', 'selio'));
                ?>
                <img src="<?php echo esc_url(SELIO_THEMEROOT.'/assets/img/loading.gif');?>" class="loading hidden" alt="<?php echo esc_html__('loading', 'selio');?>">
            </form>
        </div>
        <?php
    }
}

add_action( 'admin_enqueue_scripts', 'selio_load_admin_styles' );
function selio_load_admin_styles() {
    wp_enqueue_style( 'selio-admin-style', SELIO_CSS.'/admin-style.css', false, '1.0.0' );
}  

add_action( 'admin_enqueue_scripts', 'selio_load_admin_scripts' );
function selio_load_admin_scripts() {
    wp_enqueue_script( 'selio-admin-scripts', SELIO_JS.'/admin-scripts.js', false, '1.0.0' );
}  

function selio_add_theme_menu_item() {
    if (!is_dir(get_template_directory() . "/inc/tgm_pa/plugins/"))
        return;

    // check for plugin using plugin name
    add_theme_page(esc_html__('Theme plugins', 'selio'), esc_html__('Theme plugins', 'selio'), "manage_options", "theme-panel", "selio_theme_settings_page", null, 99);
}

add_action("admin_menu", "selio_add_theme_menu_item");

function selio_display_plugins_element() {
    ?>
    <input type="checkbox" name="included_plugins" value="1" <?php checked(1, 1, true); ?> />
    <?php
}

function selio_display_theme_panel_fields() {
    $demo_plugins_dir = get_template_directory() . "/inc/tgm_pa/plugins/";

    add_settings_section("section", esc_html__("All Plugins", "selio"), null, "theme-options");

    if (is_dir($demo_plugins_dir)) {
        if ($dh = opendir($demo_plugins_dir)) {
            while (($file = readdir($dh)) !== false) {
                add_settings_field("included_plugins", esc_html__('Do you want to extract included plugins?', 'selio'), "selio_display_plugins_element", "theme-options", "section");

                break;
            }
            closedir($dh);
        }
    }
}

add_action("admin_init", "selio_display_theme_panel_fields");

function selio_load_file($_template_file, $vars = array(), $require_once = true) {
    if (!empty($vars))
        foreach ($vars as $key => $value) {
            ${$key} = &$vars[$key];
        }

    if ($require_once) {
        require_once( $_template_file );
    } else {
        require( $_template_file );
    }

    return $vars;
}

if(!function_exists('selio_add_into_inline_js')){
    
    if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true'){
        require_once( SELIO_FRAMEWORK . '/helpers/min-js.php' );
    }
    
    function selio_add_into_inline_js($handle = 'selio-custom', $custom_js, $minify = false) {
        global $config;
        if(isset($config) && isset($config['load_optimization']) && $config['load_optimization'] == 'true' && $minify){
            $jSqueeze = new JSqueeze();
            $custom_js = $jSqueeze->squeeze($custom_js, true, false);
        }
        wp_add_inline_script( $handle, $custom_js );
    }
}

function selio_custom_js() {
    $custom_js = '';

    $custom_js .= "
    var default_marker_url = '" . SELIO_IMAGES . "/markers/default.png';
";

    $custom_js .= "
    jQuery('document').ready(function($){
    $('form#popup_form_login').submit(function(e){
        e.preventDefault();
        $('form#popup_form_login .ajax-indicator').removeClass('hidden');
        var form = $('form#popup_form_login');
        var load_indicator = form.find('.fa-custom-ajax-indicator');
        var alert_box = form.find('.alerts-box');
        var data = {
            username: form.find('[name=\"username\"]').val(),
            password: form.find('[name=\"password\"]').val()
        };
        $.extend( data, {
            'page': 'frontendajax_login',
            'action': 'ci_action'
        });
        load_indicator.removeClass('hidden');
        $.post('" . esc_url(admin_url('admin-ajax.php')) . "', data,
            function(data){
            if(data.message)
                ShowStatus.show(data.message);
            if(data.success)
            {
                // Display agent details
                alert_box.html('');
                if(data.redirect) {
                    location.href = data.redirect;
                } else {
                    location.reload();
                }
                load_indicator.attr('style', 'display: inline-block !important;');
            }
            else
            {
                alert_box.html(data.errors);
            }
        }).success(function(){
            load_indicator.addClass('hidden');
        });
        return false;
    });
})
";
    
    if(get_theme_mod('header_sticky_enable') == 1){
        $custom_js .= "
            jQuery('document').ready(function($){
               // Find all affix
               if(!$('.wrapper.half_map').length){
                $('.affix-header').each(function () {
                    var sticky = $(this);
                    var stickyWrapper = $('<div>').addClass('sticky-wrapper'); // insert hidden element to maintain actual top offset on page
                    sticky.before(stickyWrapper);
                })
                .affix({
                    offset: {
                        top: 550
                            } 
                    })
                .on('affix.bs.affix', function(){
                        $('.sticky-wrapper').height($('.affix-header').outerHeight(true));
                })
                .on('affixed-top.bs.affix', function(){ 
                    $('.sticky-wrapper').height('auto');
                });
               }
            });
        ";
    }
    
    selio_add_into_inline_js( 'selio-custom', $custom_js, true);
}

add_action('wp_enqueue_scripts', 'selio_custom_js');

function selio_custom_js_slick() {
    $custom_js = '';
    $custom_js .= "var selio_theme_root = '" . esc_html(SELIO_THEMEROOT) . "';";
    selio_add_into_inline_js( 'slick', $custom_js, true);
}
add_action('wp_enqueue_scripts', 'selio_custom_js_slick');

function selio_custom_css_main() {

    $custom_css = '';

    $custom_css = '
        .bootstrap-datetimepicker-widget .glyphicon-time:before {
            content: "'.esc_html("Hourly Booking","selio").'";
        }

        .bootstrap-datetimepicker-widget .glyphicon-calendar:before {
            content:  "'.esc_html("Switch to dates","selio").'";
        }
    ';
    
    if (selio_plugin_call::sw_settings('quicksubmission_gallery_on_top') == 1) {
        $custom_css = '
            .quick-submission {
                display: -webkit-flex;
                display: flex;
                -webkit-flex-direction: column-reverse;
                flex-direction: column-reverse;
            }
        ';
    }

    wp_add_inline_style('selio-custom', $custom_css);
}

add_action('wp_enqueue_scripts', 'selio_custom_css_main');


if (!function_exists('sw_get_languages')) {

    function sw_get_languages() {
        return false;
    }

}

add_filter('oembed_result', 'selio_oembed_dataparse');

function selio_oembed_dataparse($return) {
    // Remove the unwanted attributes:
    $return = str_replace(
            array(
                'allow="autoplay; encrypted-media"',
                'frameborder="0"',
                'webkitallowfullscreen',
                'mozallowfullscreen'
            ), '', $return
    );
    return $return;
}

/*
 * Set post views count using post meta
 */

if (!function_exists('selio_set_post_views')) {

    function selio_set_post_views($postID) {
        $countKey = 'post_views_count';
        $count = get_post_meta($postID, $countKey, true);
        if ($count == '') {
            $count = 0;
            delete_post_meta($postID, $countKey);
            add_post_meta($postID, $countKey, '0');
        } else {
            $count++;
            update_post_meta($postID, $countKey, $count);
        }
    }

}

if (!function_exists('sw_lang_query')) {

    function sw_lang_query() {
        $str = '';
        if (function_exists('sw_current_language')) {
            $str = 'lang=' . sw_current_language();
        }
        return $str;
    }

}

if (!function_exists('sw_featured_image')) {

    function sw_featured_image() {
        $url = '';

        $id = get_queried_object_id();
        if (!$id)
            return '';

        $galleries = get_post_galleries_images($id);
        // Check if the post/page has featured image
        if (has_post_thumbnail($id)) {

            // Change thumbnail size, but I guess full is what you'll need
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full');

            $url = $image[0];
        } elseif (!empty($galleries) && isset($galleries[0]) && isset($galleries[0][0])) {
            $url = $galleries[0][0];
        } else {
            //Set a default image if Featured Image isn't set
            $url = '';
        }
        return $url;
    }

}

if (!function_exists('sw_featured_excerpt')) {

    function sw_featured_excerpt() {
        $exc = '';
        $id = get_queried_object_id();
        if (!$id)
            return '';
        // Check if the post/page has featured image
        $post = get_post($id);
        if (!is_object($post))
            return $exc;

        $excerpt = $post->post_excerpt;
        $content = $post->post_content;
        $elementor_page = get_post_meta($id, '_elementor_edit_mode', true);
        if (!$elementor_page) {
            if (!empty($excerpt)) {
                $exc = wp_trim_words(strip_shortcodes(strip_tags(wpautop($excerpt))), 25, '...');
            } elseif (!empty($content)) {
                $exc = wp_trim_words(strip_shortcodes(strip_tags(wpautop($content))), 25, '...');
            }
        }
        return $exc;
    }

}

if (!function_exists('selio_ch')) {

    function selio_ch(&$var, $empty = '-', $limit = NULL) {
        if (empty($var))
            return $empty;

        if ($limit !== NULL && function_exists('sw_character_limiter')) {
            $var = sw_character_limiter($var, $limit);
        }

        return $var;
    }

}

function selio_categories_postcount_filter($variable) {
    $variable = str_replace('(', '<span class="post_count"> ', $variable);
    $variable = str_replace(')', ' </span>', $variable);
    return $variable;
}

add_filter('wp_list_categories', 'selio_categories_postcount_filter');
add_filter('get_archives_link', 'selio_categories_postcount_filter');

function selio_strip_shortcode_gallery($content) {
    preg_match_all('/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER);

    if (!empty($matches)) {
        foreach ($matches as $shortcode) {
            if ('gallery' === $shortcode[2]) {
                $pos = strpos($content, $shortcode[0]);
                if (false !== $pos) {
                    return substr_replace($content, '', $pos, strlen($shortcode[0]));
                }
            }
        }
    }

    return $content;
}

if (!function_exists('selio_show_page_name')) {

    function selio_show_page_name() {
        $title = get_the_title();

        $id = get_queried_object_id();
        if (!$id)
            return get_bloginfo('name');
        // Check if the post/page has featured image
        $post = get_post($id);
        if (!is_object($post))
            return $title;
        if ($post->post_title)
            $title = $post->post_title;

        return $title;
    }

}

if(function_exists('selio_html_footer_code')) {
    add_action('wp_footer', 'selio_html_footer_code');
}

if (!function_exists('selio_post_nav')) {

    function selio_post_nav() {
        global $post;
        $previous = ( is_attachment() ) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
        $next = get_adjacent_post(false, '', false);

        if (!$next && !$previous)
            return;
        $code = 'class="btn-default"';
        $next_post_link_url = get_permalink(get_adjacent_post(false, '', false));
        $prev_post_link_url = get_permalink(get_adjacent_post(false, '', true));
        ?>
        <div class="nav-links">
            <div class="nav-previous">
                <a class="btn-default" href="<?php echo esc_url($prev_post_link_url); ?>" rel="prev"><i class="ion-arrow-left-c"></i></a>
            </div>
            <div class="nav-next">
                <a class="btn-default" href="<?php echo esc_url($next_post_link_url); ?>" rel="prev"><i class="ion-arrow-right-c"></i></a>
            </div>
        </div>
        <?php
    }
}
