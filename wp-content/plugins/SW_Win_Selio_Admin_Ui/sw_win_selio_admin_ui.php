<?php

/*
Plugin Name: Winter Classified Selio Admin UI
Plugin URI: http://codecanyon.net/user/sanljiljan
Description: Addon to show share features on classified portal
Author: Sandi Winter
Author URI: http://codecanyon.net/user/sanljiljan
Version: 1.1
Text Domain: sw_win
Domain Path: /locale/
*/


if(version_compare(phpversion(), '5.5.0', '<'))
{
    return false;  
}

define( 'SW_WIN_SELIO_ADMINUI_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', 'sw_pluginsLoaded_selio_admin_ui' );

function sw_pluginsLoaded_selio_admin_ui() {
    
    /* first load */
    if(!get_site_option( 'selio_admin_ui' )){
        $selio_admin_ui = array();
        $selio_admin_ui['roles'] = array(
            'administrator'=> true,
            'user'=> true
        );
        update_site_option( 'selio_admin_ui' , $selio_admin_ui);
    }
    
    // Setup locale
    do_action( 'sw_win_plugins_loaded' );
    load_plugin_textdomain('sw_win', false, basename( dirname( __FILE__ ) ) . '/locale' );
}

include dirname(__FILE__).'/sw_win_options.php';

// Activation code here...
function selio_admin_ui_activate() {
    
    $selio_admin_ui = array();
    $selio_admin_ui['roles'] = array(
        'administrator'=> true,
        'user'=> true
    );
    update_site_option( 'selio_admin_ui' , $selio_admin_ui);
    
}
register_activation_hook( __FILE__, 'selio_admin_ui_activate' );


function selio_admin_stylesheet() {

    $options = get_option( 'selio_admin_ui' );

    $role_admin = false;
    if($options && isset($options['roles']) && isset($options['roles']['administrator']))
        $role_admin = true;

    $role_user = false;
    if($options && isset($options['roles']) && isset($options['roles']['user']))
        $role_user = true;

    if (isset( $_POST['selio_admin_ui_form'] ) ) { // WPCS: CSRF ok.
        $post = $_POST;
        $role_admin = false;
        if($post && isset($post['roles']) && isset($post['roles']['administrator']))
            $role_admin = true;

        $role_user = false;
        if($post && isset($post['roles']) && isset($post['roles']['user']))
            $role_user = true;
    }

    $user_type = selio_admin_ui_get_current_user_role();

    $enable_admin_ui = false;

    if($user_type == 'administrator' && $role_admin) {
        $enable_admin_ui = true;
    } 

    if($user_type != 'administrator' && $role_user) {
        $enable_admin_ui = true;
    }

    if($enable_admin_ui){
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&amp;subset=latin-ext');
        wp_enqueue_style('selio-admin-ui-dashboard', plugins_url(). '/SW_Win_Selio_Admin_Ui/css/frontend-dashboard.css' );
      
        if(version_compare(get_bloginfo('version'), '5.0', '>=')) { 
            wp_enqueue_style('selio-admin-ui-dashboard-5', plugins_url(). '/SW_Win_Selio_Admin_Ui/css/frontend-dashboard-5.css' );
        }
      
        if(is_rtl()){
            wp_enqueue_style('selio-admin-ui-dashboard-rtl', plugins_url(). '/SW_Win_Selio_Admin_Ui/css/frontend-dashboard-rtl.css' );
        }
    }
}
add_action('admin_enqueue_scripts', 'selio_admin_stylesheet' );

function selio_front_stylesheet() {
        wp_enqueue_style('selio-front-ui', plugins_url(). '/SW_Win_Selio_Admin_Ui/css/front-ui.css' );
}
add_action('wp_enqueue_scripts', 'selio_front_stylesheet' );

function selio_login_theme_style() {
    
    wp_enqueue_style( 'selio-admin-ui-login', plugins_url() . '/SW_Win_Selio_Admin_Ui/css/frontend-login.css' );
    
    /* logo */
    $logo = '';
    if (get_theme_mod('selio_logo_upload')){
        $logo = esc_url(selio_logo_in_header());
    } elseif (get_theme_mod('selio_logomini_upload')) {
        $logo = esc_url(selio_logomini_in_header());
    } 
    
    if(!empty($logo)){
        $css = "
                .login h1 a {
                    background-image: url({$logo});
                }
            ";
        wp_add_inline_style( 'selio-admin-ui-login', $css );
    } else {
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:200,300,400,600,700,800,900');
        $css = "
                .login h1 a:hover,
                .login h1 a {
                    font-size: 42.5px;
                    font-weight: 800;
                    color: #092c61;
                    text-transform: uppercase;
                    background: none;
                    border-radius: 0;
                    text-indent: initial;
                    overflow: visible;
                    width: auto;
                    text-align: center;
                    height: auto;
                    margin-bottom: 35px;
                    font-family: 'Montserrat', sans-serif;
                }
            ";
        wp_add_inline_style( 'selio-admin-ui-login', $css );
        function selio_login_logo_url_title() {
            $title = get_bloginfo('name');
            return $title;
        }
        add_filter( 'login_headertitle', 'selio_login_logo_url_title' );
    }
}
add_action( 'login_enqueue_scripts', 'selio_login_theme_style' );

function selio_admin_ui_get_current_user_role() {
  if( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $role = ( array ) $user->roles;
    return $role[0];
  } else {
    return false;
  }
 }
?>