<?php
/**
 * Template part for displaying 404 error
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SW
 * @subpackage selio
 * @since 1.0
 * @version 1.0
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class('blog-single-post '); ?>>
    <div class="post_info widget_search">
        <h3> <?php esc_html_e('Oops! That page can&rsquo;t be found.', 'selio'); ?></h3>
        <div class="post-content clearfix">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'selio'); ?></p>
                <?php get_search_form(); ?>
        </div>
    </div>
</div><!--blog-single-post end-->   

<!-- #post-## -->