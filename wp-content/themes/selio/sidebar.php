<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Selio
 * @since Selio 1.0
 */
?>

<?php if ( is_active_sidebar( 'sidebar-1' )  ) : ?>
<div class="col-lg-4">
    <div class="sidebar">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </div>
</div>
<?php endif; ?>
