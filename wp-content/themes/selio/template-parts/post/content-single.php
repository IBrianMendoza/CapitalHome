<?php

$date_format = get_option('date_format');
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blog-single-post single'); ?>>
    <ul class="post-nfo">
        <li><i class="la la-calendar"></i><?php echo get_the_date($date_format) ?></li>
        <li><i class="la la-comment-o"></i><a href="#comment-form" title=" <?php echo esc_attr__('Comments', 'selio'); ?>"><?php comments_number(); ?></a></li>
        <?php
        if (has_tag()) {
            $selio_tags = wp_get_post_tags($post->ID);
            $selio_tags_counts = count($selio_tags);
            $count = 1;
            foreach ($selio_tags as $selio_tag) {
                if ($count > 2 && false) {
                    break;
                } else {
                    $selio_tag_link = get_tag_link($selio_tag->term_id);
                    $selio_tag_name = $selio_tag->name;
                    echo '<li><i class="la la-bookmark-o"></i>';
                    echo '<a href="' . esc_url($selio_tag_link) . '">' . esc_html($selio_tag_name) . '</a>';
                    echo '</li>';
                    $count++;
                }
            }
        }
        ?>
         <?php
            if (has_category()) {
                $categories = get_the_category();
                $categories_counts = count($categories);
                $count = 1;
                foreach ($categories as $category) {
                    if ($count == 3) {
                        echo '<li><i class="la la-plus"></i>';
                        echo '<a href="#" class="meta-categories-more">'.esc_html('more','selio').'</a>';
                        echo '</li>';
                    }
                    $class_help ='';
                    if ($count > 2) {
                        $class_help ='less';
                    } 
                    
                    $cat_link = get_category_link($category->term_id);
                    $cat_name = $category->name;
                    echo '<li class="cat-link '.esc_attr($class_help).'"><i class="fa fa-folder-o"></i>';
                    echo '<a href="' . esc_url($cat_link) . '">' . esc_html($cat_name) . '</a>';
                    echo '</li>';
                    
                    if ($count == $categories_counts) {
                        echo '<li class="cat-link less"><i class="la la-minus"></i>';
                        echo '<a href="#" class="meta-categories-less">'.esc_html('less','selio').'</a>';
                        echo '</li>';
                    }
                    $count++;
                }
            }
            ?>
    </ul>
    <h3>
        <?php if(get_the_title() !='' && false):?>
            <?php the_title(); ?>
        <?php else:?>
            <span class="ntitle"></span>
        <?php endif;?>
    </h3>
        <?php if (is_sticky()) { ?>
            <span class="sticky-post"><?php esc_html_e('Sticky', 'selio'); ?></span>
        <?php } ?>
        <?php if (has_post_thumbnail()): ?>
        <div class="blog-img">
        <?php the_post_thumbnail('selio-770x483'); ?>
        </div><!--blog-img end-->
        <?php endif; ?>
    <div class="blog-single-post-content">
        <?php
        if (get_theme_mod('selio_installed'))
            add_filter('the_content', 'selio_strip_shortcode_gallery');
        ?>
        <?php the_content(); ?>
    </div>
    <div class="post-share">
        <?php if (shortcode_exists('sw_win_selio_share_post')): ?>
            <?php echo do_shortcode('[sw_win_selio_share_post]'); ?>
        <?php endif; ?>
        <?php if (comments_open() || get_comments_number()) : ?>
            <a href="#comment-form" title="<?php echo esc_attr__('Write A Comment', 'selio'); ?>"><?php echo esc_html__('Write A Comment', 'selio'); ?> <i class="la la-arrow-right"></i></a>
    <?php endif; ?>
    </div><!--post-share end-->
    <?php
    if (get_theme_mod('selio_author_section_enabled') && get_theme_mod('selio_author_section_enabled') == 1):
        sw_win_load_ci_function('Frontend', 'userprofile', array(&$content));
        $CI = &get_instance();
        $user = array();

        $user = get_userdata(get_the_author_meta('ID'));
        ?>
        <div class="cm-info-sec">
            <div class="cm-img">
                <a href="<?php echo esc_url(agent_url($user)); ?>" class="user-logo">
                    <?php echo get_avatar(get_the_author_meta('ID'), 128); ?>
                </a>
            </div><!--author-img end-->
            <div class="cm-info">
                <h3><a href="<?php echo esc_url(agent_url($user)); ?>"><?php echo esc_html(get_the_author()); ?></a></h3>
                <p> <?php echo esc_html(the_author_meta('description')); ?> </p>
                <?php if (shortcode_exists('sw_win_selio_share_author')): ?>
                    <?php echo do_shortcode('[sw_win_selio_share_author user_id="'.esc_attr(get_the_author_meta('ID')).'"]'); ?>
                <?php endif; ?>
            </div>
        </div><!--cm-info-sec end-->
    <?php endif; ?>
    <?php
    $gallery = get_post_galleries(get_the_ID());
    if (get_theme_mod('selio_gallery_section_enabled') && get_theme_mod('selio_gallery_section_enabled') == 1):
        if (!empty($gallery)):
            ?>
            <div class="widget-gallery"> 
                <h3><?php echo esc_html__('Gallery', 'selio'); ?></h3>
                <div class="selio_sw_win_wrapper">
                        <?php foreach ($gallery as $key => $value):
                            ?>    
                        <div class="widget widget-section widget-box box-container widget-preloadigallery">
                            <?php
                            esc_viewe($value);
                            ?>
                        </div>
            <?php endforeach; ?>  
                </div>
            </div>
    <?php endif; ?>
    <?php endif; ?>
</div><!--blog-single-post end-->
<div class = "page-links-single"><?php
    wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('Pages:', 'selio'),
        'after' => '</div>',
        'link_before' => '<span class="page-number">',
        'link_after' => '</span>',
    ));
?></div>
    <?php if (!get_theme_mod('selio_installed') && is_singular('post')): ?>
    <div class="page-links pagepost-navigation">
        <?php
        selio_post_nav(array(
            'prev_text' => '<i class="ion-arrow-left-c"></i>',
            'next_text' => '<i class="ion-arrow-right-c"></i>',
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'selio') . ' </span>',
        ));
        ?>
    </div>
<?php
endif;
// If comments are open or we have at least one comment, load up the comment template.
if (comments_open() || get_comments_number()) :
    comments_template();
endif;
?>
