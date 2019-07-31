<?php

/*
Plugin Name: Winter Classified Compare
Plugin URI: http://codecanyon.net/user/sanljiljan
Description: Addon to compare listing on classified portal
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

define( 'SW_WIN_COMPARE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// [Translation support]

add_action( 'plugins_loaded', 'sw_pluginsLoaded_compare' );

function sw_pluginsLoaded_compare() {
	// Setup locale
	do_action( 'sw_win_plugins_loaded' );
	load_plugin_textdomain('sw_win', false, basename( dirname( __FILE__ ) ) . '/locale' );
}

// [/Translation support]

// [Check if main plugin is installed properly]

function compare_admin_notice__error() {
    global $wpdb;

    if(!function_exists('sw_win_classified_version'))
    {
    	$class = 'notice notice-error';
        $message = __( 'Please install Classified plugin to use this "Compare listing" addon', 'sw_win');
        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }
    else if(sw_win_classified_version() < 1.4 && sw_classified_installed())
    {
    	$class = 'notice notice-error';
        $message = __( 'Please update Classified plugin to use this "Compare listing" addon', 'sw_win');
        printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
    }
}

add_action( 'admin_notices', 'compare_admin_notice__error' );

// [/Check if main plugin is installed properly]

// Load all widget files
if (is_dir(dirname(__FILE__)."/widgets/")){
  if ($dh = opendir(dirname(__FILE__)."/widgets/")){
    while (($file = readdir($dh)) !== false){
        if(strrpos($file, ".php") !== FALSE)
            include_once(dirname(__FILE__)."/widgets/".$file);
    }
    closedir($dh);
  }
}

?>