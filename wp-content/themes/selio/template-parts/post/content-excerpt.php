<?php
$class = '';
if (is_search() || $pagename == "blog-standard") {
    if (get_theme_mod('selio_installed'))
        $class = 'selio-cover';
} elseif (is_single()) {
    if (get_theme_mod('selio_installed'))
        $class = 'selio-cover-single';
}
$date_format = get_option('date_format');
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blog-single-post ' . esc_attr($class)); ?>>
    <?php if (has_post_thumbnail()): ?>
        <div class="blog-img-cover">
            <div class="blog-img">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                    <?php the_post_thumbnail('selio-770x483'); ?>
                </a>
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>" class="hover"></a>
            </div><!--blog-img end-->
        </div><!--blog-img end-->
    <?php endif; ?>
    <div class="post_info">
        <ul class="post-nfo">
            <li><i class="la la-calendar"></i><?php echo get_the_date($date_format) ?></li>
            <li><i class="la la-comment-o"></i><a href="<?php the_permalink(); ?>#comment-form" title="<?php echo esc_attr__('Comment', 'selio'); ?>"><?php comments_number(); ?></a></li>
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
            <?php if(get_the_title() !=''):?>
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a>
            <?php else:?>
                <span class="ntitle"></span>
            <?php endif;?>
        </h3>
        <?php if (is_sticky()) { ?>
            <span class="sticky-post"><?php esc_html_e('Sticky', 'selio'); ?></span>
        <?php } ?>
        <div class="post-content clearfix">
            <p></p>
            <?php
            the_excerpt();
            ?>
            <p></p>
        </div>
        <?php if(!is_singular()):?>
        <a href="<?php the_permalink(); ?>" title="<?php esc_attr_e('Read more', 'selio'); ?>" class="btn-default"><?php esc_html_e('Read more', 'selio'); ?></a>
        <?php endif;?>
    </div>
    <?php
    if (FALSE && has_tag()) {
        echo '<p class="post-tags">';
        the_tags();
        echo '</p>';
    }
    ?>
</div><!--blog-single-post end-->   

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

