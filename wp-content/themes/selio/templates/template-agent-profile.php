<?php 
/**
 * template-agent-profile.php
 *
 * Template Name: Agent profile layout
 */

/* Load header.php. */
get_header();
?>
<section class="pager-sec bfr">
            <div class="container">
                <div class="pager-sec-details">
                    <h3><?php echo get_the_title(); ?></h3>
                    <?php selio_the_selio_breadcrumb(); ?>
                </div><!--pager-sec-details end-->
            </div>
        </section>
        <section class="page-main-content section-padding">
            <div class="container">
                <div class="agent-profile-sec">
                    <div class="row">
                        <div class="<?php if (is_active_sidebar('sidebar-profile-1')) : ?>col-xl-8 col-lg-9 col-md-12 pl-0 pr-0 <?php else: ?> col-lg-12<?php endif;?>">
                            <?php
                        if ( have_posts() ) :
                            /* Start the Loop */
                            while ( have_posts() ) : the_post();
                                /*
                                * Include the Post-Format-specific template for the content.
                                * If you want to override this in a child theme, then include a file
                                * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                */
                                get_template_part( 'template-parts/post/content', 'empty' );
                            endwhile;
                        else :
                            get_template_part( 'template-parts/post/content', 'empty' );
                        endif;
                        ?>
                        </div>
                        <?php if ( is_active_sidebar( 'sidebar-profile-1' ) ) : ?>
                        <div class="col-xl-4 col-lg-3 col-md-12 pr-0">
                                <?php dynamic_sidebar( 'sidebar-profile-1' ); ?>
                        </div>
                        <?php endif; ?>  
                    </div>
                </div><!--agent-profile-sec end-->
            </div>
        </section><!--page-main-content end-->
<br style="clear:both;" />
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