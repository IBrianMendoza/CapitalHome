<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage selio
 * @since Selio 1.0
 */

global $selio_header_layout_hidetop;
global $selio_header_layout_shadow;
$selio_header_layout_hidetop = TRUE;
$selio_header_layout_shadow = TRUE;
?>

<?php
/* Load header.php. */

get_header(); ?>

<section class="error-sec">
<div class="eror-sec-data">
    <h1>404</h1>
    <p><?php echo esc_html__('The page you’re looking for can’t be found.', 'selio');?></p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr__('Back to Homepage', 'selio');?>" class="btn2"><?php echo esc_html__('Back to Homepage', 'selio');?></a>
</div><!--eror-sec-data end-->
</section><!--error-sec end-->

<?php get_footer();