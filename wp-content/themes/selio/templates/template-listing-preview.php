<?php
/**
 * template-listing-preview.php
 *
 * Template Name: Listing preview page
 */

$selio_header_layout_hidetop = TRUE;
get_header(); ?>
<?php
        $content = '';
        sw_win_load_ci_function('Frontend', 'listingpreview', array(&$content));
        $CI = &get_instance();
        $images = array();
        if(isset($CI->data['images']))
            $images = $CI->data['images'];            
		if(isset($CI->data['listing']))
		{
			$listing = $CI->data['listing']; 
			
			$avarage_stars = floor(($CI->review_m->avg_rating_listing($listing->idlisting) * 2) / 2);
			if($avarage_stars == 0)$avarage_stars = 5.0;
		
			$css_stars = number_format($avarage_stars,1,"-","");
			$css_stars = str_replace('-0', '', $css_stars);

			$avarage_stars = intval($avarage_stars);

			$favorite_added=false;
			if(get_current_user_id() != 0)
			{
				$CI->load->model('favorite_m');
				$favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
																	$listing->idlisting);
				if($favorite_added>0)$favorite_added = true;
			}
		}
?>
        <section class="form_sec">
            <h3 class="vis-hid">Invisible</h3>
            <div class="container">
                <?php echo do_shortcode('[swprimarysearch]'); ?>
            </div>
        </section><!--form_sec end-->
        <section class="property-single-pg">
            <div class="container">
                <?php if(!empty($listing)): ?>
                <div class="property-hd-sec">
                    <div class="card">
                        <div class="card-body">
                            <a href="#">
                                <h3><?php echo esc_html(_field($listing, 10));?></h3>
                                <p><i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address'));?></p>
                            </a>
                            <ul>
                                <li><?php echo esc_html(_field($listing, 19));?> <?php echo esc_html(_field_name(19));?></li>
                                <li><?php echo esc_html(_field($listing, 20));?> <?php echo esc_html(_field_name(20));?></li>
                                <li><?php echo esc_html(_field_name(5));?> <?php echo esc_html(_field($listing, 5));?></li>
                            </ul>
                        </div><!--card-body end-->
                        <div class="rate-info">
                            <h5><?php echo esc_html(_field($listing, 36));?></h5>
                            <span class="purpose-<?php echo esc_attr(url_title(_field($listing, 4), '-', TRUE)); ?>"><?php echo esc_html(_field($listing, 4));?></span>
                        </div><!--rate-info end-->
                    </div><!--card end-->
                </div><!---property-hd-sec end-->
                <?php endif;?>
                <div class="property-single-page-content">
                    <div class="row">
                        <div class="<?php if (is_active_sidebar('sidebar-listing-1')) : ?>col-lg-8 pl-0 pr-0 <?php else: ?>col-lg-12 pl-0 pr-0<?php endif;?>">
                        <?php
                            if ( have_posts() ) : 
                                /* Start the Loop */
                            while ( have_posts() ) : the_post();
                            get_template_part( 'template-parts/page/content', 'listing'  );
                            endwhile;
                            ?>
                            <?php the_posts_pagination(); ?>
                            <?php 
                            else :
                                get_template_part( 'template-parts/post/content', 'none' );
                            endif;
                            ?>
                        </div>
                        <?php if (is_active_sidebar('sidebar-listing-1')) : ?>
                        <div class="col-lg-4 pr-0">
                            <div class="sidebar layout2">
                                <?php dynamic_sidebar('sidebar-listing-1'); ?>
                            </div><!--sidebar end-->
                        </div>
                        <?php endif; ?>
                    </div>
                </div><!--property-single-page-content end-->
            </div>
        </section><!--property-single-pg end-->
        <br style="clear:both;" />
        <?php get_sidebar('bottom-selio-listing'); ?>
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