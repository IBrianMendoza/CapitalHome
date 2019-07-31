<?php
/**
 * Template for displaying search forms in Selio
 *
 * @package WordPress
 * @subpackage Selio
 * @since 1.0
 * @version 1.0
 */

?>
<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>
<form role="search" method="get" class="search-wr" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" id="<?php echo esc_attr($unique_id); ?>" name="s" placeholder="<?php esc_attr_e('Search here ...', 'selio'); ?>" value="<?php echo get_search_query(); ?>">
    <button type="submit" value="" class=""><i class="la la-search"></i></button>
</form>