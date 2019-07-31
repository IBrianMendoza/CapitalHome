<?php 
/**
 * template-results-half-map.php
 *
 * Template Name: Results page with half side map template
 */

$selio_header_layout_fullwidth = TRUE;
$selio_header_layout_hidetop = TRUE;
get_header(); 
// [autoselect category if in uri]

    $page_title = get_the_title();

    $CI =& get_instance();
    $CI->load->model('treefield_m');  
    $treefield_value = $CI->treefield_m->get_all_list(array('value'=>$page_title, 'field_id'=>1), 1);

    if(selio_plugin_call::sw_count($treefield_value) > 0)
        $_GET['search_category']=key($treefield_value);

// [/autoselect category if in uri]
?>
<section class="half-map-sec">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-12">
                <div id="map-container" class="fullwidth-home-map">
                    <?php echo do_shortcode('[swmaplistings]'); ?>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="widget-property-search">
                <?php echo do_shortcode('[swprimarysearch]'); ?>
                </div><!--widget widget-property-searche end-->
                <div class="listing-directs">
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
                </div><!--listing-directs end-->
            </div>
        </div>
    </div>
</section><!--half-map-sec end-->
<?php get_footer();
