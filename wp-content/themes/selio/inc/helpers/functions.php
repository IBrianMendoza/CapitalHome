<?php

/*
# =================================================
# General helper functions.
# =================================================
*/

if(!function_exists('selio_dump'))
{
    
    function selio_dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
    
}

if(!function_exists('selio_get_current_url'))
{
    function selio_get_current_url()
    {
        global $wp;
        $current_url = home_url(add_query_arg(array(),$wp->request));
        
        return $current_url;
    }
}

function selio_get_the_content_with_formatting ($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}


/*

Custom template pin url functions.

Custom functionality for functions in wp-content\plugins\SW_Win_Classified\codeigniter\application\controllers\Frontendajax.php

*/

function sw_template_pin_icon($listing)
{
    $pin_icon = esc_html(SELIO_IMAGES).'/markers/empty.png';
    
    // check for version with field_id = 14
    if(file_exists(get_template_directory().'/assets/images/markers/'._field($listing, 14).'.png'))
    {
        $pin_icon = esc_html(SELIO_IMAGES).'/markers/'._field($listing, 14).'.png';
    }
    
    // check for version with category related marker
    $category = get_listing_category($listing);
    
    if(isset($category->marker_icon_id))
    {
        $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
        if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
        {
            $pin_icon = $img[0];
        }
    }
    
    return $pin_icon;
}


class selio_plugin_call {
    public function __call($function_name, $arguments) {
        if(function_exists($function_name)) {
            return call_user_func_array($function_name, $arguments);
        } else {
            return false;
        }
    }

    public static function __callStatic($function_name, $arguments) {
        if(function_exists($function_name)) {
            return call_user_func_array($function_name, $arguments);
        } else {
            return false;
        }
    }
}
