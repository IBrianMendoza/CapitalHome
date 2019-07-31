<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Selio
 * @since 1.0
 * @version 1.0
 */
get_header();
?>
<section class="pager-sec bfr">
    <div class="container">
        <div class="pager-sec-details">
            <h3>
                <?php if(is_single()):?>
                    <?php echo esc_html__("Blog", "selio");?>
                <?php elseif(is_home()):?>
                    <?php echo esc_html__("Homepage", "selio");?>
                <?php elseif(is_page()):?>
                    <?php echo esc_html__("Page", "selio");?>
                <?php elseif(is_category()):?>
                    <?php
                            the_archive_title( );
                    ?>
                <?php elseif(is_search()):?>
                    <?php echo esc_html__("Search", "selio");?>: <?php echo esc_html(get_search_query()); ?>
                <?php elseif(is_tag()):?>
                    <?php the_archive_title( ); ?>
                <?php elseif(is_day()):?>
                    <?php echo esc_html__("Day", "selio").': '.esc_html(get_the_time('d'));?>
                <?php elseif(is_month()):?>
                    <?php echo esc_html__("Month", "selio").': '.esc_html(get_the_time('F'));?>
                <?php elseif(is_year()):?>
                    <?php echo esc_html__("Year", "selio").': '.esc_html(get_the_time('Y'));?>
                <?php elseif(is_author()):?>
                    <?php echo esc_html__("Author", "selio");?>
                <?php elseif(is_404()):?>
                    <?php echo esc_html__("404", "selio");?>
                <?php elseif(is_archive()):?>
                    <?php echo esc_html__("Archive", "selio");?>
                <?php else:?>
                    <?php echo esc_html(get_bloginfo('name')) ?>
                <?php endif;?>
            </h3>
            <?php selio_the_selio_breadcrumb(); ?>
        </div><!--pager-sec-details end-->
    </div>
</section>
<section class="blog-standard section-padding">
    <div class="container">
        <div class="blog-single-details">
            <div class="row">
                <div class="<?php if (is_active_sidebar('sidebar-1')) : ?>col-lg-8 <?php else: ?> col-lg-12<?php endif;?>">
                    <div class="blog-posts">
                        <?php
                        if (have_posts()) :
                            /* Start the Loop */
                            while (have_posts()) : the_post();
                                get_template_part( 'template-parts/post/content', 'excerpt' );
                            endwhile;
                            ?>
                            <?php the_posts_pagination(); ?>
                            <?php
                        else :
                            get_template_part('template-parts/post/content', 'none');
                        endif;
                        ?>
                    </div><!--blog-posts end-->
                </div>
                <?php get_sidebar(); ?>
            </div>
        </div><!--blog-single-details end-->
    </div>
</section><!--blog-single-sec end-->
<?php get_sidebar('bottom-selio'); ?>
<?php if (is_active_sidebar('footer-1')) : ?>
    <section class="bottom section-padding">
        <div class="container placeholder-container">
        <div class="row">
            <?php dynamic_sidebar( 'footer-1' ); ?>
        </div>
        <?php if(function_exists('selio_get_footer_placeholder')):?> 
        <img src="<?php echo esc_url(selio_get_footer_placeholder());?>" alt="placeholder" class="footer-placeholder">
        <?php endif;?>
    </div>
    </section>
<?php endif; ?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="footer-content">
                    <div class="row justify-content-between">
                        <div class="col-xl-12">
                             <div class="copyright text-center">
                                <p>&copy; <?php
                                if (get_theme_mod('made_by') != "") {
                                    esc_viewe(get_theme_mod('made_by'));
                                } else {
                                    echo esc_html('Selio theme made in EU. All Rights Reserved.','selio');
                                }
                                ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php
get_footer();