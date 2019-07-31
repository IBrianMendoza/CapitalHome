<?php 
/**
 * template-full-width
 *
 * Template Name: Full width page
 */

/* Load header.php. */
get_header(); ?>
        <section class="pager-sec bfr">
            <div class="container">
                <div class="pager-sec-details">
                    <h3>
                       <?php echo esc_html(selio_show_page_name()) ?>
                    </h3>
                    <?php selio_the_selio_breadcrumb(); ?>
                </div><!--pager-sec-details end-->
            </div>
        </section>
        <section class="blog-standard section-padding">
            <div class="container">
                <div class="blog-single-details">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="blog-posts">
                            <?php
                            if ( have_posts() ) : 
                            /* Start the Loop */
                            while ( have_posts() ) : the_post();
                                if(is_single())
                                {
                                    selio_set_post_views(get_the_ID()); // for most popular posts
                                    get_template_part( 'template-parts/post/content', 'single' );
                                }
                                else
                                {
                                    get_template_part( 'template-parts/post/content', get_post_format() );
                                }
                            endwhile;
                            ?>
                            <?php the_posts_pagination(); ?>
                            <?php 
                            else :
                                get_template_part( 'template-parts/post/content', 'none' );
                            endif;
                            ?>
                            </div><!--blog-posts end-->
                        </div>
                    </div>
                </div><!--blog-single-details end-->
            </div>
        </section><!--blog-single-sec end-->
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