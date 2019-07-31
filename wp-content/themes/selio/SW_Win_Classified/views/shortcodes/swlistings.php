<?php
$CI = &get_instance();
$CI->load->model('review_m');
$CI->load->model('favorite_m');
?>
<?php if(!isset($widget_id_short) || strpos($widget_id_short, 'sidebar') === FALSE ): ?>
<div class="sw-listing-results widget widget-recentproperties">
<div class="row result-container row-flex">
<?php foreach($listings as $key=>$listing): ?>
<?php
    $avarage_stars = floor(($CI->review_m->avg_rating_listing($listing->idlisting) * 2) / 2);
    if($avarage_stars == 0)$avarage_stars = 5.0;

    $css_stars = number_format($avarage_stars,1,"-","");
    $css_stars = str_replace('-0', '', $css_stars);
?>
    
<?php 

$favorite_added=false;
if(get_current_user_id() != 0)
{
    $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                       $listing->idlisting);
    if($favorite_added>0)$favorite_added = true;
}

$grid_class = "col-md-6 col-sm-6";
if(strpos(get_page_template(), 'template-results-full-width') > 1 || (isset($_POST['template']) && $_POST['template'] == 'template-results-full-width.php') ) {
    $grid_class = "col-lg-3 col-md-4 col-sm-4";
}

?>
<div class="<?php echo esc_attr($grid_class);?>">
    <div class="card">
        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>">
            <div class="img-block">
                <div class="overlay"></div>
                <img src="<?php echo esc_url(_show_img($listing->image_filename, '851x678', true)); ?>" alt="<?php echo esc_attr(_field($listing, 10)); ?>" class="img-fluid">
                <div class="rate-info">
                    <h5>
                        <?php // @codingStandardsIgnoreStart ?>
                        <?php if(!empty(_field($listing, 37)) && _field($listing, 37) !='-'):?>
                        <?php echo esc_html(_field($listing, 37)); ?>
                        <?php else:?>
                        <?php echo esc_html(_field($listing, 36)); ?>
                        <?php endif;?>
                        <?php // @codingStandardsIgnoreEnd ?>
                    </h5>
                    <span class="purpose-<?php echo esc_attr(url_title(_field($listing, 4), '-', TRUE)); ?>"><?php echo esc_html(_field($listing, 4)); ?></span>
                </div>
            </div>
        </a>
        <div class="card-body">
            <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>">
                <h3><?php echo esc_html(_field($listing, 10)); ?></h3>
                <p>
                    <i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?>
                </p>
            </a>
            <div class="resul-items">
                <?php
                    // show items from visual result item builder
                    _show_items($listing, 2);
                ?>
            </div>
        </div>
        <div class="card-footer">
            <?php if(function_exists('sw_show_favorites')): ?>
                <span class="favorites-actions pull-left">
                    <a href="#" data-id="<?php echo esc_attr($listing->idlisting);?>" class="add-favorites-action <?php echo (esc_attr($favorite_added))?'hidden':''; ?>" <?php if (!is_user_logged_in()): ?> data-loginpopup="true" <?php endif;?>  data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                        <i class="la la-heart-o"></i>
                    </a>
                    <a href="#" data-id="<?php echo esc_attr($listing->idlisting);?>" class="remove-favorites-action <?php echo (!esc_attr($favorite_added))?'hidden':''; ?>" data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                        <i class="la la-heart-o"></i>
                    </a>
                    <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
                </span>
            <?php endif; ?>
            <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(date('Y-m-d H:i:s'),strtotime($listing->date_modified));?>" class="pull-right">
                <i class="la la-calendar-check-o"></i>
                <?php 
                    $date_modified = $listing->date_modified;
                    $date_modified_str = strtotime($date_modified);
                    echo esc_html(human_time_diff($date_modified_str));
                    echo ' '.esc_html__('Ago', 'selio');
                ?>
            </a>
        </div>
        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>" class="ext-link"></a>
    </div>
</div>    
<?php endforeach; ?>
    
</div>
</div>
<?php else: ?>

<div class="result-container-latest-side">
<?php foreach($listings as $key=>$listing): ?>
<?php
    $avarage_stars = floor(($CI->review_m->avg_rating_listing($listing->idlisting) * 2) / 2);
    if($avarage_stars == 0)$avarage_stars = 5.0;

    $css_stars = number_format($avarage_stars,1,"-","");
    $css_stars = str_replace('-0', '', $css_stars);
?>
    
<?php 
$CI = &get_instance();
$CI->load->model('favorite_m');
$favorite_added=false;
if(get_current_user_id() != 0)
{
    $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                       $listing->idlisting);
    if($favorite_added>0)$favorite_added = true;
}

?>

<div class="card">
    <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>">
        <div class="img-block">
            <div class="overlay"></div>
            <img src="<?php echo esc_url(_show_img($listing->image_filename, '851x678', true)); ?>" alt="<?php echo esc_attr(_field($listing, 10)); ?>" class="img-fluid">
            <div class="rate-info">
                <h5>
                    <?php // @codingStandardsIgnoreStart ?>
                    <?php if(!empty(_field($listing, 37)) && _field($listing, 37) !='-'):?>
                    <?php echo esc_html(_field($listing, 37)); ?>
                    <?php else:?>
                    <?php echo esc_html(_field($listing, 36)); ?>
                    <?php endif;?>
                    <?php // @codingStandardsIgnoreEnd ?>
                </h5>
                <span class="purpose-<?php echo esc_attr(url_title(_field($listing, 4), '-', TRUE)); ?>"><?php echo esc_html(_field($listing, 4)); ?></span>
            </div>
        </div>
    </a>
    <div class="card-body">
        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>">
            <h3><?php echo esc_html(_field($listing, 10)); ?></h3>
            <p>
                <i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?>
            </p>
        </a>
        <div class="resul-items d-none">
            <?php
                // show items from visual result item builder
                _show_items($listing, 2);
            ?>
        </div>
    </div>
    <div class="card-footer d-none">
        <?php if(function_exists('sw_show_favorites')): ?>
            <span class="favorites-actions pull-left">
                <a href="#" data-id="<?php echo esc_attr($listing->idlisting);?>" class="add-favorites-action <?php echo (esc_attr($favorite_added))?'hidden':''; ?>" <?php if (!is_user_logged_in()): ?> data-loginpopup="true" <?php endif;?>  data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                    <i class="la la-heart-o"></i>
                </a>
                <a href="#" data-id="<?php echo esc_attr($listing->idlisting);?>" class="remove-favorites-action <?php echo (!esc_attr($favorite_added))?'hidden':''; ?>" data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                    <i class="la la-heart-o"></i>
                </a>
                <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
            </span>
        <?php endif; ?>
        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(date('Y-m-d H:i:s'),strtotime($listing->date_modified));?>" class="pull-right">
            <i class="la la-calendar-check-o"></i>
            <?php 
                $date_modified = $listing->date_modified;
                $date_modified_str = strtotime($date_modified);
                echo esc_html(human_time_diff($date_modified_str));
                echo ' '.esc_html__('Ago', 'selio');
            ?>
        </a>
    </div>
    <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>" class="ext-link"></a>
</div>

<?php endforeach; ?>
</div> 

<?php endif; ?>