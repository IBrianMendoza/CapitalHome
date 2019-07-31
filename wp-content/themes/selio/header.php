<!doctype html>
<html <?php language_attributes(); ?> >

<head>
    <meta charset="<?php esc_attr(bloginfo( 'charset' )); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="title" content="<?php echo esc_attr(wp_get_document_title()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
global $selio_header_layout_fullwidth;
global $selio_header_layout_hidetop;
global $selio_header_layout_shadow;
$selio_langs = sw_get_languages();
?>
<!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience and security.</p>
<![endif]-->
<?php if(isset($selio_header_layout_fullwidth)): ?>
<div class="wrapper half_map">
    <header class="fix">
<?php else: ?>
<div class="wrapper">
    <header>
<?php endif; ?>
        <?php if(isset($selio_header_layout_hidetop)): ?>
        <?php else: ?>
            <?php if (get_theme_mod('selio_phone_setting') != "" || get_theme_mod('selio_address_setting') != "" ||
                      (function_exists('sw_count') && selio_plugin_call::sw_count($selio_langs) > 1) || shortcode_exists('sw_win_selio_share_list')):?>
            <div class="top-header">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-6 col-md-7 col-sm-12">
                            <div class="header-address">
                                <?php if(function_exists('config_item') && config_item('app_type') == 'demo'): ?>
                                    <?php if (get_theme_mod('selio_phone_setting') != ""):?>
                                    <a href="tel://<?php echo esc_attr(urlencode(get_theme_mod('selio_phone_setting')));?>">
                                        <i class="la la-phone-square"></i>
                                        <span><?php echo esc_html(get_theme_mod('selio_phone_setting'));?></span>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (get_theme_mod('selio_address_setting') != ""):?>
                                     <a href="#">
                                        <i class="la la-map-marker"></i>
                                        <span><?php echo esc_html(get_theme_mod('selio_address_setting'));?></span>
                                    </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class='h-text'><?php echo esc_html(get_bloginfo('description'));?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (shortcode_exists('sw_win_selio_share_list')): ?>
                                <?php if (function_exists('sw_count') && count($selio_langs) > 1):?>
                                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-6 col-6">
                                <?php else:?>
                                <div class="col-xl-3 col-md-5 col-sm-12">
                                <?php endif;?>
                                <div class="header-social">
                                    <?php echo do_shortcode('[sw_win_selio_share_list]'); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php
                        if (function_exists('sw_count') && selio_plugin_call::sw_count($selio_langs) > 1):
                            ?>
                            <div class="col-xl-3 col-lg-3 col-md-2 col-sm-6 col-6">
                                <div class="language-selector">
                                    <div class="drop-menu">
                                        <div class="select">
                                            <span><img src="<?php echo esc_url(SELIO_IMAGES . '/flags/' . sw_current_language() . '.png'); ?>" alt="<?php esc_viewe(sw_get_language_name(sw_current_language())); ?>"><?php esc_viewe(sw_get_language_name(sw_current_language())); ?></span>
                                            <i class="la la-caret-down"></i>
                                        </div>
                                        <input type="hidden" name="gender">
                                        <ul class="dropeddown" style="display: none;">
                                        <?php foreach ($selio_langs as $lang): if ($lang['lang_code'] == sw_current_language()) continue; ?>
                                            <li> 
                                                <a class="dropdown-item" href="<?php echo esc_url(sw_get_language_url($lang['lang_code'])); ?>">
                                                    <img src="<?php echo esc_url(SELIO_IMAGES . '/flags/' . $lang['lang_code'] . '.png'); ?>" alt="<?php echo esc_attr($lang['title']); ?>" /> <?php echo esc_html($lang['title']); ?>
                                                </a>
                                            </li> 
                                        <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div><!--language-selector end-->
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php if(isset($selio_header_layout_fullwidth)): ?>
<div class="header affix-header shd">
    <?php else: ?>
<div class="header affix-header <?php if(isset($selio_header_layout_shadow)): ?> shd <?php endif;?>">
    <?php endif; ?>
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php if (get_theme_mod('selio_logo_upload')): ?>
                            <img src="<?php echo esc_url(get_theme_mod('selio_logo_upload')); ?>" alt="<?php esc_attr(bloginfo( 'title' )); ?>" />
                        <?php else: ?>
                            <img src="<?php echo esc_url(SELIO_THEMEROOT); ?>/assets/images/logo.png" alt="<?php esc_attr(bloginfo( 'title' )); ?>">
                        <?php endif; ?>
                    </a>
                    <button class="menu-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent">
                        <span class="icon-spar"></span>
                        <span class="icon-spar"></span>
                        <span class="icon-spar"></span>
                    </button>
                    <div class="navbar-collapse" id="navbarSupportedContent">
                    <?php if(has_nav_menu('top')): ?>
                    <?php wp_nav_menu(array(
                                'theme_location' => 'top',
                                'menu_id'        => 'top-menu',
                                'container'      => '',
                                'menu_class'     => 'navbar-nav mr-auto',
                                'walker' => new Top_Nav_Menu_Walker
                                ) 
                            ); 
                        else:
                            echo '<div class="mr-auto"></div>';
                    ?>
                    <?php endif; ?>
                        <div class="d-inline my-2 my-lg-0">
                            <ul class="navbar-nav">
                                <?php if (get_theme_mod('selio_login_enabled') && get_theme_mod('selio_login_enabled') == 1): ?>
                                    <?php if (!is_user_logged_in() && shortcode_exists('selio_access_buttons')): ?>
                                        <?php echo do_shortcode('[selio_access_buttons]'); ?>
                                    <?php else: ?>
                                    <li class="nav-item signin-btn">
                                            <a href="<?php echo esc_url(wp_logout_url()); ?>" class="nav-link ">
                                                    <i class="la la-sign-in"></i>
                                                    <span><b class="signin-op"><?php echo esc_html__('Sign out','selio');?></b></span>
                                            </a>
                                    </li>
                                    <?php endif; ?>
                                <?php endif;?>
                                <?php if (get_theme_mod('selio_listing_subm_enabled') && get_theme_mod('selio_listing_subm_enabled') == 1): ?>
                                <?php if( selio_plugin_call::sw_settings('quick_submission')):?>
                                <li class="nav-item submit-btn">
                                    <a href="<?php echo esc_url(get_permalink(selio_plugin_call::sw_settings('quick_submission'))); ?>" class="my-2 my-sm-0 nav-link sbmt-btn">
                                            <i class="icon-plus"></i>
                                            <span><?php echo esc_html__('Submit Listing','selio');?></span>
                                    </a>
                                </li>
                                <?php endif;?>
                                <?php endif;?>
                            </ul>
                        </div>
                        <a href="#"  class="close-menu"><i class="la la-close"></i></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
</header><!--header end-->