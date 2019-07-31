<?php
/**
 * Plugin Name: Elementor Blocks for SELIO
 * Description: Elementor elements for theme SELIO
 * Plugin URI:  https://listing-themes.com/
 * Version:     1.1.0
 * Author:      Sandi Winter
 * Author URI:  https://listing-themes.com/
 * Text Domain: selio-blocks
 * Domain Path: /locale/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$theme = wp_get_theme();
if(isset($theme->template) && $theme->template != 'selio')
	return;

define( 'ELEMENTOR_SELIO__FILE__', __FILE__ );

/**
 * Elementor Blocks for SELIO
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function elementor_selio_load() {
	// Load localization file
	load_plugin_textdomain( 'selio-blocks' , false, basename( dirname( __FILE__ ) ) . '/locale' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'elementor_local_fail_load' );
		return;
	}

	// Check required version
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'elementor_local_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/plugin.php' );
}
add_action( 'plugins_loaded', 'elementor_selio_load' );

function elementor_local_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Blocks for LOCAL is not working because you are using an old version of Elementor.', 'selio-blocks' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'selio-blocks' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}


// Our custom post type function
function selio_create_posttype() {
    
    register_post_type( 'sw-offers',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Offers' ),
                'singular_name' => __( 'Offer' )
            ),
            'public' => true,
            'has_archive' => true,
			'show_ui'     => true,
			'menu_position' => 29,
			'rewrite' => array('slug' => 'offer'),
			//'taxonomies' => array( 'post_tag', 'category '),
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			//'register_meta_box_cb' => 'additional_input_field',
        )
	);

	$posts = get_posts(array('post_type'=> 'sw-offers'));

	if(isset($posts[0]))
	{
		$post_id = $posts[0]->ID;

		add_post_meta($post_id, 'badge_title', 'SALE', true);
		add_post_meta($post_id, 'price_before', '$50', true);
		add_post_meta($post_id, 'price_now', '$25', true);
	}

}

// Hooking up our function to theme setup
//add_action( 'init', 'selio_create_posttype' );

function add_elementor_widget_categories( $elements_manager ) {

	$elements_manager->add_category(
		'winter-themes',
		[
			'title' => __( 'Winter Themes', 'selio-blocks' ),
			'icon' => 'fa fa-plug',
		]
	);
	// $elements_manager->add_category(
	// 	'second-category',
	// 	[
	// 		'title' => __( 'Second Category', 'plugin-name' ),
	// 		'icon' => 'fa fa-plug',
	// 	]
	// );

}

add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );

function selio_html_footer_code() {
    ?>
    <?php if (function_exists('sw_settings')): ?>
        <div class="popup" id="sign-popup">
            <h3><?php echo esc_html__('Sign In to your Account', 'selio'); ?></h3>
            <div class="popup-form">
                <form id="popup_form_login">
                    <?php if (function_exists('config_item') && config_item('app_type') == 'demo'): ?>
                        <div class="alert alert-success m0" role="alert">
                            <b><?php echo esc_html__('Demo login details for Admin', 'selio'); ?>:</b><br />
                            <?php echo esc_html__('Username', 'selio'); ?>: <?php echo esc_html('admin'); ?><br />
                            <?php echo esc_html__('Password', 'selio'); ?>:  <?php echo esc_html('admin'); ?><br /><br />
                            <b> <?php echo esc_html__('Demo login details for Agent', 'selio'); ?>:</b><br />
                            <?php echo esc_html__('Username', 'selio'); ?>:  <?php echo esc_html('agent'); ?><br />
                            <?php echo esc_html__('Password', 'selio'); ?>:  <?php echo esc_html('agent'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="alerts-box"></div>
                    <div class="form-field">
                        <input type="text" name="username" placeholder="<?php echo esc_attr__('Your Name', 'selio'); ?>" class="login" required="">
                    </div>
                    <div class="form-field">
                        <input type="password" name="password" placeholder="<?php echo esc_attr__('Password', 'selio'); ?>" class="password" required="">
                    </div>
                    <div class="form-cp">
                        <div class="form-field">
                            <div class="input-field">
                                <input type="checkbox" name="ccc" id="cc1">
                                <label for="cc1">
                                    <span></span>
                                    <small><?php echo esc_html__('Remember me', 'selio'); ?></small>
                                </label>
                            </div>
                        </div>
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" title="<?php echo esc_attr__('Forgot Password?', 'selio'); ?>"><?php echo esc_html__('Forgot Password?', 'selio'); ?></a>
                    </div><!--form-cp end-->
                    <button type="submit" class="btn2"><?php echo esc_html__('Log In', 'selio'); ?> <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator hidden"></i></button>
                    <input class="hidden" id="widget_id_login" name="widget_id" type="text" value="login" />
                </form>
                <a href="<?php echo esc_url( selio_login_page() ); ?>#sw_register" class="link-bottom"><?php echo esc_html__('Create new account', 'selio');?></a>
                <?php if (sw_settings('facebook_login_enabled') == '1' && sw_settings('facebook_app_id') != ''): ?>
                    <?php
                    // @codingStandardsIgnoreStart
                    $facebook_login_url = '';
                    $facebook_app_id = sw_settings('facebook_app_id');
                    $facebook_app_secret = sw_settings('facebook_app_secret');
                    if (!empty($facebook_app_id) && !empty($facebook_app_secret))
                        if (sw_settings('facebook_login_enabled') == '1' && function_exists('sw_win_load_ci_function_facebooklogin')) {
                            sw_win_load_ci_frontend();
                            $CI = &get_instance();
                            $CI->load->library('MY_Composer');

                            $callback = get_site_url() . '/?custom_login=facebook';

                            $fb = sw_win_load_ci_function_facebooklogin($callback);

                            // Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
                            $helper = $fb->getRedirectLoginHelper();

                            $permissions = ['email']; // optional

                            $facebook_login_url = $helper->getLoginUrl($callback, $permissions);
                        }
                    // @codingStandardsIgnoreEnd
                    ?>
                    <a href="<?php echo esc_url($facebook_login_url); ?>" class="fb-btn"><i class="fa fa-facebook" aria-hidden="true"></i><?php echo esc_html__('Sign in with facebook', 'selio'); ?></a>
                <?php endif; ?>
            </div>
        </div><!--popup end-->
    <?php endif; ?>
    <?php
}

function selio_local_app_type() {
    global $config;
    
    if(isset($config['app_type']))
        return $config['app_type'];
        
    return 'cms';
}


if (!function_exists('selio_login_page')) {

    function selio_login_page() {
        if (function_exists('sw_settings') && sw_settings('register_page')) {
            return get_permalink(sw_settings('register_page'));
        }

        return wp_login_url(get_permalink());
    }

}

function selio_access_buttons($atts = array()){ 
    ?>
    <li class="nav-item signin-btn">
        <span class="nav-link">
                <i class="la la-sign-in"></i>
                <span>
                    <a href="<?php echo esc_url( selio_login_page() ); ?>" class=" <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>">
                        <b class="signin-op"><?php echo esc_html__('Sign in','selio');?></b> 
                    </a>
                    <?php echo esc_html__('or','selio');?> 
                    <a href="<?php echo esc_url( selio_login_page() ); ?>#sw_register" class="">
                        <b class="reg-op"><?php echo esc_html__('Register','selio');?></b>
                    </a>
                </span>
        </span>
    </li>
    <?php
}

add_shortcode( 'selio_access_buttons', 'selio_access_buttons' );

// Function to change sender name
function selio_sender_name($original_email_from) {
    return get_bloginfo('name');
}

// Hooking up our functions to WordPress filters
add_filter('wp_mail_from_name', 'selio_sender_name');