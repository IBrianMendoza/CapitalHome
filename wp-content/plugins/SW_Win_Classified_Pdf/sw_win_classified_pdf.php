<?php

/*
Plugin Name: Winter Classified PDF Export
Plugin URI: http://codecanyon.net/user/sanljiljan
Description: Addon to export listing in PDF on classified portal
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

define( 'SW_WIN_PDF_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

function sw_win_pdf_export($listing_id) 
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

add_action( 'plugins_loaded', 'sw_pluginsLoaded_pdf' );

function sw_pluginsLoaded_pdf() {
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