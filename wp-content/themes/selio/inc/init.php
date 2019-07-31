<?php 
/**
 * init.php
 *
 * Load the widget files.
 */
 
// @codingStandardsIgnoreStart
$dir_widgets = dirname(__FILE__)."/widgets/";
$dir_shortcodes = dirname(__FILE__)."/shortcodes/";
$dir_vc = dirname(__FILE__)."/vc/";
$dir_menu_walkers = dirname(__FILE__)."/menu_walkers/";
$dir_helpers = dirname(__FILE__)."/helpers/";
$dir_el = dirname(__FILE__)."/elementor/";

// Load all helpers files
if (is_dir($dir_helpers)) {
    if ($dh = opendir($dir_helpers)) {
        while (($file = readdir($dh)) !== false) {
            if (strrpos($file, ".php") !== FALSE) {
                selio_load_file($dir_helpers . $file);
            }
        }
        closedir($dh);
    }
}

// Load all widget files
if (is_dir($dir_widgets)) {
    if ($dh = opendir($dir_widgets)) {
        while (($file = readdir($dh)) !== false) {
            if (strrpos($file, ".php") !== FALSE) {
                if(is_child_theme() && file_exists(get_stylesheet_directory() . '/inc/widgets/'.$file)) {
                    selio_load_file(get_stylesheet_directory() . '/inc/widgets/'.$file);
                } else {
                    selio_load_file($dir_widgets . $file);
                }
            }
        }
        closedir($dh);
    }
}

// Load all shortcode files
if (is_dir($dir_shortcodes)) {
    if ($dh = opendir($dir_shortcodes)) {
        while (($file = readdir($dh)) !== false) {
            if (strrpos($file, ".php") !== FALSE) {
                if(is_child_theme() && file_exists(get_stylesheet_directory() . '/inc/shortcodes/'.$file)) {
                    selio_load_file(get_stylesheet_directory() . '/inc/shortcodes/'.$file);
                } else {
                    selio_load_file($dir_shortcodes . $file);
                }
            }
        }
        closedir($dh);
    }
}

// Load all vc (visual composer) files
if (is_dir($dir_vc)) {
    if ($dh = opendir($dir_vc)) {
        while (($file = readdir($dh)) !== false) {
            if (strrpos($file, ".php") !== FALSE) {
                selio_load_file($dir_vc . $file);
            }
        }
        closedir($dh);
    }
}

// Load all menu walker files
if (is_dir($dir_menu_walkers)) {
    if ($dh = opendir($dir_menu_walkers)) {
        while (($file = readdir($dh)) !== false) {
            if (strrpos($file, ".php") !== FALSE) {
                if(is_child_theme() && file_exists(get_stylesheet_directory() . '/inc/menu_walkers/'.$file)) {
                    selio_load_file(get_stylesheet_directory() . '/inc/menu_walkers/'.$file);
                } else {
                    selio_load_file($dir_menu_walkers . $file);
                }
            }
        }
        closedir($dh);
    }
}
// Load all menu walker files
if (is_dir($dir_menu_walkers)) {
    if ($dh = opendir($dir_menu_walkers)) {
        while (($file = readdir($dh)) !== false) {
            if (strrpos($file, ".php") !== FALSE) {
                if(is_child_theme() && file_exists(get_stylesheet_directory() . '/inc/menu_walkers/'.$file)) {
                    selio_load_file(get_stylesheet_directory() . '/inc/menu_walkers/'.$file);
                } else {
                    selio_load_file($dir_menu_walkers . $file);
                }
            }
        }
        closedir($dh);
    }
}

if (is_dir($dir_el)){
  if ($dh = opendir($dir_el)){
    while (($file = readdir($dh)) !== false){
        if (strrpos($file, ".php") !== FALSE) {
            if(is_child_theme() && file_exists(get_stylesheet_directory() . '/inc/menu_walkers/'.$file)) {
                selio_load_file(get_stylesheet_directory() . '/inc/menu_walkers/'.$file);
            } else {
                selio_load_file($dir_el . $file);
            }
        }
    }
    closedir($dh);
  }
}

selio_load_file(dirname(__FILE__) . "/tgm_pa/configuration.php");


// @codingStandardsIgnoreEnd