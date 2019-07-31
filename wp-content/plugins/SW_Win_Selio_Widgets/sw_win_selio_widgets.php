<?php

/*
Plugin Name: Winter Classified Selio Custom Widgets
Plugin URI: http://codecanyon.net/user/sanljiljan
Description: Addon to show share features on classified portal
Author: Sandi Winter
Author URI: http://codecanyon.net/user/sanljiljan
Version: 1.0
Text Domain: sw_win
Domain Path: /locale/
*/

if(version_compare(phpversion(), '5.5.0', '<'))
{
    return false;  
}

define( 'SW_WIN_SELIO_WIDGETS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );



add_action( 'plugins_loaded', 'sw_pluginsLoaded_selio_widgets' );
function sw_pluginsLoaded_selio_widgets() {
	// Setup locale
	do_action( 'sw_win_plugins_loaded' );
	load_plugin_textdomain('selio', false, basename( dirname( __FILE__ ) ) . '/locale' );
}

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

if(!function_exists('get_current_url'))
{
    function get_current_url()
    {
        global $wp;
        $current_url = home_url(add_query_arg(array(),$wp->request));
        
        return $current_url;
    }
		
}

if(!function_exists('sw_count')) {
    function sw_count($mixed='') {
        $count = 0;
        
        if(!empty($mixed) && (is_array($mixed))) {
            $count = count($mixed);
        } else if(!empty($mixed) && function_exists('is_countable') && version_compare(PHP_VERSION, '7.3', '<') && is_countable($mixed)) {
            $count = count($mixed);
        }
        else if(!empty($mixed) && is_object($mixed)) {
            $count = 1;
        }
        return $count;
    }
}
?>