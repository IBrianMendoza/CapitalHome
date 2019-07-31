<?php

/*
Plugin Name: Winter Classified Facebook login
Plugin URI: http://codecanyon.net/user/sanljiljan
Description: Addon for facebook login on classified portal
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

define( 'SW_WIN_FACEBOOKLOGIN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

function sw_win_load_ci_function_facebooklogin($callback) 
{
    $facebook_app_id = sw_settings('facebook_app_id');
    $facebook_app_secret = sw_settings('facebook_app_secret');

    if(empty($facebook_app_id) || empty($facebook_app_secret))return NULL;

    $fb = new \Facebook\Facebook([
        'app_id' => sw_settings('facebook_app_id'),
        'app_secret' => sw_settings('facebook_app_secret'),
        'default_graph_version' => 'v3.2',
        'redirect_uri' => $callback
        //'default_access_token' => '{access-token}', // optional
      ]);

    return $fb;
}

add_action( 'plugins_loaded', 'sw_pluginsLoaded_facebooklogin' );

function sw_pluginsLoaded_facebooklogin() {
	// Setup locale
	do_action( 'sw_win_plugins_loaded' );
	load_plugin_textdomain('sw_win', false, basename( dirname( __FILE__ ) ) . '/locale' );
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

?>