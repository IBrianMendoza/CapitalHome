<?php

/*
Plugin Name: Winter Classified Selio Social Plugin
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

define( 'SW_WIN_SELIO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

function sw_win_selio_share_list($atts){ 
        $content ='';
        
        $content ='';
        
        if(!get_theme_mod('nexos_share_plugin_facebook_hide'))
        if(get_theme_mod('nexos_share_plugin_facebook'))
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_facebook')).'"><i class="fa fa-facebook"></i></a>';
        else  
            $content .='<a href="'.esc_url('https://www.facebook.com/share.php?u='.get_current_url()).'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-facebook"></i></a>';
        
        if(!get_theme_mod('nexos_share_plugin_twitter_hide'))
        if(get_theme_mod('nexos_share_plugin_twitter'))    
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_twitter')).'"><i class="fa fa-twitter"></i></a>';
        else
            $content .='<a href="'.esc_url('https://twitter.com/home?status='.get_current_url()).'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-twitter"></i></a>';
            
        if(!get_theme_mod('nexos_share_plugin_linkedin_hide'))
        if(get_theme_mod('nexos_share_plugin_linkedin'))    
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_linkedin')).'"><i class="fa fa-linkedin"></i></a>';
        else
            $content .='<a href="'.esc_url('https://www.linkedin.com/shareArticle?mini=true&url='.get_current_url().'&title=&summary=&source=').'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-linkedin"></i></a>';
            
        if(!get_theme_mod('nexos_share_plugin_instagram_hide'))    
        if(get_theme_mod('nexos_share_plugin_instagram'))
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_instagram')).'"><i class="fa fa-instagram"></i></a>';
        else
            $content .='<a href="'.esc_url('https://www.instagram.com').'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-instagram"></i></a>';
        
        
    return sw_win_clear_text($content);
}

add_shortcode( 'sw_win_selio_share_list', 'sw_win_selio_share_list' );

function sw_win_selio_share_listing($atts){ 
        $content ='';
        $content .='
        <p class="soc-icons">
            <a href="'.esc_url('https://www.facebook.com/share.php?u='.get_current_url()).'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-facebook-f"></i></a>
            <a href="'.esc_url('https://www.linkedin.com/shareArticle?mini=true&url='.get_current_url().'&title=&summary=&source=').'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-linkedin"></i></a>
            <a href="'.esc_url('https://twitter.com/home?status='.get_current_url()).'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-twitter"></i></a>
        </p> ';
    
    return sw_win_clear_text($content);
}

add_shortcode( 'sw_win_selio_share_listing', 'sw_win_selio_share_listing' );

function sw_win_selio_share_post($atts){ 
	$title = get_the_title();
	$permalink = get_permalink();
	$image = get_the_post_thumbnail_url();

	$facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink;
	$instagram = 'https://plus.instagram.com/share?url=' . $permalink;	
	$pinterest = 'https://pinterest.com/pin/create/button/?url=' . $image .'&media='. $image .'&description=' . urlencode($title);
	$twitter = 'https://twitter.com/home?status=' .urlencode($title);
		
	echo '
        <ul class="social-links">
	 <li><a href="' . esc_html($twitter) . '" data-csshare-type="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
	 <li><a href="' . esc_html($facebook) . '" data-csshare-type="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
	 <li><a href="' . esc_html($pinterest) . '" data-csshare-type="pinterest"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a></li>
	 <li><a href="' . esc_html($instagram) . '"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
	</ul>';
}

add_shortcode( 'sw_win_selio_share_post', 'sw_win_selio_share_post' );

function sw_win_selio_share_author($atts = array()){ 
    
    if (function_exists('sw_win_load_ci_frontend'))
        sw_win_load_ci_frontend();
    else
        return false;

    if(!isset($atts['user_id']) || empty($atts['user_id']))
        return false;
    
    $CI = &get_instance();
    $user = get_userdata($atts['user_id']);
    if(empty($user)) 
        return false;
    ?>
    <ul class="social-links">
        <?php
        $instagram = profile_data($user, 'instagram');
        if ($instagram != '-'):
            ?>
            <li><a href="<?php echo esc_url($instagram); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa ffa-instagram"></i></a></li>
        <?php else: ?>
            <li><a href="//instagram.com/share?url=<?php echo esc_url(agent_url($user)); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="instagram"><i class="fa fa-instagram"></i></a></li>
        <?php endif; ?>
        <?php
        $twitter = profile_data($user, 'twitter');
        if ($twitter != '-'):
            ?>
            <li><a href="<?php echo esc_url($twitter); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa fa-twitter"></i></a></li>
        <?php else: ?>
            <li><a href="//twitter.com/intent/tweet?text=<?php echo esc_attr(urlencode(profile_data($user, 'display_name'))); ?>:<?php echo esc_url(agent_url($user)); ?>"  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter"><i class="fa fa-twitter"></i></a></li>
        <?php endif; ?>
        <?php
        $facebook = profile_data($user, 'facebook');
        if ($facebook != '-'):
            ?>
            <li><a href="<?php echo esc_url($facebook); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa fa-facebook"></i></a></li>
        <?php else: ?>
            <li><a href="//www.facebook.com/share.php?u=<?php echo esc_url(agent_url($user)); ?>&amp;title=<?php echo esc_attr(urlencode(profile_data($user, 'display_name'))); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa fa-facebook"></i></a></li>
        <?php endif; ?>
        <?php
        $linkedin = profile_data($user, 'linkedin');
        if ($linkedin != '-'):
            ?>
            <li><a href="<?php echo esc_url($linkedin); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter"><i class="fa fa-linkedin-in"></i></a></li>
        <?php else: ?>
            <li><a href="//pinterest.com/pin/create/button/?url=<?php echo esc_url(agent_url($user)); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter"><i class="fa fa-pinterest-p"></i></a></li>
        <?php endif; ?>
    </ul>
    <?php
}

add_shortcode( 'sw_win_selio_share_author', 'sw_win_selio_share_author' );

function sw_win_selio_share_userprofile($atts = array()){ 
    
    if (function_exists('sw_win_load_ci_frontend'))
        sw_win_load_ci_frontend();
    else
        return false;

    if(!isset($atts['user_id']) || empty($atts['user_id']))
        return false;
    
    $CI = &get_instance();
    $user = get_userdata($atts['user_id']);
    if(empty($user)) 
        return false;
    ?>
    <ul class="socio-links">
        <?php
        global $wp;
        $instagram = profile_data($user, 'instagram');
        if ($instagram != '-'):
            ?>
            <li><a href="<?php echo esc_url($instagram); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa ffa-instagram"></i></a></li>
        <?php else: ?>
            <li><a href="//instagram.com/share?url=<?php echo esc_url(agent_url($user)); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="instagram"><i class="fa fa-instagram"></i></a></li>
        <?php endif; ?>
        <?php
        $twitter = profile_data($user, 'twitter');
        if ($twitter != '-'):
            ?>
            <li><a href="<?php echo esc_url($twitter); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa fa-twitter"></i></a></li>
        <?php else: ?>
            <li><a href="//twitter.com/intent/tweet?text=<?php echo esc_attr(urlencode(profile_data($user, 'display_name'))); ?>:<?php echo esc_url(agent_url($user)); ?>"  onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter"><i class="fa fa-twitter"></i></a></li>
        <?php endif; ?>
        <?php
        $facebook = profile_data($user, 'facebook');
        if ($facebook != '-'):
            ?>
            <li><a href="<?php echo esc_url($facebook); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa fa-facebook"></i></a></li>
        <?php else: ?>
            <li><a href="//www.facebook.com/share.php?u=<?php echo esc_url(agent_url($user)); ?>&amp;title=<?php echo esc_attr(urlencode(profile_data($user, 'display_name'))); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="facebook"><i class="fa fa-facebook"></i></a></li>
        <?php endif; ?>
        <?php
        $linkedin = profile_data($user, 'linkedin');
        if ($linkedin != '-'):
            ?>
            <li><a href="<?php echo esc_url($linkedin); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter"><i class="fa fa-linkedin-in"></i></a></li>
        <?php else: ?>
            <li><a href="//pinterest.com/pin/create/button/?url=<?php echo esc_url(agent_url($user)); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="twitter"><i class="fa fa-pinterest-p"></i></a></li>
        <?php endif; ?>
    </ul>
    <?php
}

add_shortcode( 'sw_win_selio_share_userprofile', 'sw_win_selio_share_userprofile' );


add_action( 'plugins_loaded', 'sw_pluginsLoaded_selio' );

function sw_pluginsLoaded_selio() {
	// Setup selioe
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

if(!function_exists('get_current_url'))
{
    function get_current_url()
    {
        global $wp;
        $current_url = home_url(add_query_arg(array(),$wp->request));
        
        return $current_url;
    }
		
}

if(!function_exists('sw_win_clear_text')){
	function sw_win_clear_text ($text) {
                $text = str_replace(array("\r\n", "\r", "\n", "\t",'  ','   ',), '', $text);
                return $text;
	}
}

?>