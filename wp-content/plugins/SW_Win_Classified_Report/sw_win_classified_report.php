<?php

/*
Plugin Name: Winter Classified Report
Plugin URI: http://codecanyon.net/user/sanljiljan
Description: Addon to report listing on classified portal
Author: Sandi Winter
Author URI: http://codecanyon.net/user/sanljiljan
Version: 1.0
Text Domain: sw_win
Domain Path: /locale/
*/

if(!function_exists('sw_win_pluginsLoaded')) {
    return false;
}

if(version_compare(phpversion(), '5.5.0', '<'))
{
    return false;  
}

define( 'SW_WIN_REPORT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

function sw_win_report_added($listing_id) 
{
    $report_added = false;
    
    if(get_current_user_id($listing_id) != 0)
    {
        $CI =& get_instance();
        $CI->load->model('report_m');
        $report_added = $CI->report_m->check_if_exists(get_current_user_id(), 
                                                           $listing_id);
        if($report_added>0)$report_added = true;
    }
    
    return $report_added;
}

add_action( 'plugins_loaded', 'sw_pluginsLoaded_report' );

function sw_pluginsLoaded_report() {
	// Setup locale
	do_action( 'sw_win_plugins_loaded' );
	load_plugin_textdomain('sw_win', false, basename( dirname( __FILE__ ) ) . '/locale' );
}

// on unpaid invoices show message and link to invoice

function report_admin_notice__error() {
    global $wpdb;

    $table_name = $wpdb->prefix.'sw_report';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name && sw_classified_installed()) {
         //table not in database.
    	$class = 'notice notice-error';
        $message = __( 'Please update Classified plugin to use this "Report listing" addon', 'sw_win');
        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }
}

add_action( 'admin_notices', 'report_admin_notice__error' );


?>