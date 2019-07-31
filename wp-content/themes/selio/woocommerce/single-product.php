<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header();
?>
<section class="pager-sec bfr">
    <div class="container">
        <div class="pager-sec-details">
            <h3>
                <?php if(is_product()):?>
                    <?php echo esc_html__("Product", "selio");?>
                <?php elseif(is_single()):?>
                    <?php echo esc_html__("Blog", "selio");?>
                <?php elseif(isset($pagename) && $pagename == "blog-standard"):?>
                    <?php
                        $selio_page = get_page_by_path( $pagename );
                        echo esc_html(get_the_title( $selio_page ));
                    ?>
                <?php elseif(is_home()):?>
                    <?php echo esc_html__("Homepage", "selio");?>
                <?php elseif(is_page()):?>
                    <?php echo esc_html__("Page", "selio");?>
                <?php elseif(is_category()):?>
                    <?php
                            the_archive_title( );
                    ?>
                <?php elseif(is_search()):?>
                    <?php echo esc_html__("Search", "selio");?>
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
                <?php elseif(is_shop()):?>
                    <?php echo esc_html__("Shop", "selio");?>
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
                                /**
                                 * woocommerce_before_main_content hook.
                                 *
                                 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                                 * @hooked woocommerce_breadcrumb - 20
                                 */
                                do_action( 'woocommerce_before_main_content' );
                        ?>

                                <?php while ( have_posts() ) : the_post(); ?>

                                        <?php wc_get_template_part( 'content', 'single-product' ); ?>

                                <?php endwhile; // end of the loop. ?>

                        <?php
                                /**
                                 * woocommerce_after_main_content hook.
                                 *
                                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                                 */
                                do_action( 'woocommerce_after_main_content' );
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