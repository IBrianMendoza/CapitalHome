<?php
/**
 * The template for the sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Selio
 * @since Selio 1.0
 */
?>
<?php if ( is_active_sidebar( 'bottom-selio' )  ) : ?>
<div class="bottom-sidebar">
    <?php dynamic_sidebar( 'bottom-selio' ); ?>
</div>
<?php endif; ?>



