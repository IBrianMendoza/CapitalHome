<?php 
/**
 * template-full-width-clear
 *
 * Template Name: Full width page clear page
 */

/* Load header.php. */

get_header(); ?>
        <main class="main-clear">
            <?php
            if ( have_posts() ) : 
            /* Start the Loop */
            while ( have_posts() ) : the_post();
                    get_template_part( 'template-parts/post/content', 'clear' );
            endwhile;
            ?>
            <?php the_posts_pagination(); ?>
            <?php 
            else :
                get_template_part( 'template-parts/post/content', 'none' );
            endif;
            ?>
        </main><!--blog-single-sec end-->
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