<?php if(strpos(get_page_template(), 'template-listing-preview') > 1): ?>

<div class="bootstrap-wrapper" id="listing-preview">

<?php if(isset($edit_url)): ?>
<a href="<?php echo esc_url($edit_url); ?>" class="btn2 btn-editlisting pull-right"><i class="fa fa-edit"></i></a>
<div class="clear"></div>
<?php endif; ?>


<div class="property-pg-left">


<?php if(count($images) >= 1): ?>

<div class="property-imgs">
    <div class="property-main-img">
<?php $i=0;foreach($images as $image): ?>
        <!-- Slide <?php echo esc_html($i++); ?> -->

        <div class="property-img">
            <img src="<?php echo esc_url(_show_img($image->filename, '770x483', true, null)); ?>">
        </div><!--property-img end-->

<?php endforeach; ?>

    </div><!--property-main-img end-->
    <div class="property-thumb-imgs">
        <div class="row thumb-carous">

 <?php $i=0;foreach($images as $image): ?>
        <!-- Slide <?php echo esc_html($i++); ?> -->

        <div class="col-lg-4 col-md-4 col-sm-4 col-4 thumb-img">
            <div class="property-img">
                <img src="<?php echo esc_url(_show_img($image->filename, '770x483', true, null)); ?>">
            </div><!--property-img end-->
        </div>

<?php endforeach; ?>
        </div>
    </div><!--property-thumb-imgs end-->
</div><!--property-imgs end-->

<?php endif; ?>

<?php if(_field($listing, 13) != ''): ?>
    <div class="descp-text">
        <h3><?php echo esc_html(_field_name(13)); ?></h3>
        <p><?php esc_viewe(_field($listing, 13)); ?></p>
        <p class='clearfix'>
            <?php
            if(!empty($listing->location_id)){
                $location = array();
                $CI =& get_instance();
                $lang_id = sw_current_language_id();
                $CI->load->model('treefield_m');
                $tree = $CI->treefield_m->get_lang($listing->location_id);
                $location[] = $tree->{'value_'.esc_attr($lang_id)};

                while(!empty($tree->parent_id)) {
                    $tree = $CI->treefield_m->get_lang($tree->parent_id);
                    $location[] = $tree->{'value_'.esc_attr($lang_id)};
                }
                $location = array_reverse($location);
                $location = implode(', ', $location);
                echo '<strong>'.esc_html__('Location','selio').':</strong> '.esc_html($location);
            }
            ?>
        </p>
    </div><!--descp-text end-->
<?php endif; ?>

<?php foreach($fields as $key=>$field): ?>

<?php if(isset($field['parent']) && $field['parent']['type'] == 'CATEGORY' && $field['parent']['is_preview_visible']): ?>
<?php $in_cat_counter = 0; 

// Skip category 21 because already defined in listing preview tempalte
//if($field['parent']['idfield'] == 21)continue;

$special_class = '';
if($field['parent']['idfield'] == 1)
    $special_class = 'widget-overview visible-sm visible-xs';

?>

    <div class="details-info <?php if(in_array($field['parent']['idfield'], array('1', 43)) === FALSE):?> details-info-transparent <?php endif;?> <?php echo esc_attr($special_class); ?> <?php echo esc_attr('field_'.$field['parent']['idfield']); ?>">
        <h3 class=" <?php echo esc_attr($field['parent']['type']); ?>"><?php echo esc_html($field['parent']['field_name']); ?></h3>
<ul>
<?php foreach($field['children'] as $key_children=>$field_children): ?>

<?php $field_val = _field($listing, $field_children['idfield']); ?>

<?php if($field_children['type'] == 'CHECKBOX' || ($field_val != '-' && !empty($field_val))): ?>
<?php $in_cat_counter++; ?>
<?php if($field_children['type'] == 'DROPDOWN' || $field_children['type'] == 'DROPDOWN_MULTIPLE'): ?>
<li>
<h4><?php echo esc_html($field_children['field_name']); ?>:</h4>
<span><?php echo esc_html(esc_html($field_val)); ?></span>
</li>
<?php elseif($field_children['type'] == 'TEXTAREA'): ?>
<li class="full_widget"><?php echo esc_attr($field_val); ?></li>
<?php elseif($field_children['type'] == 'INPUTBOX' || $field_children['type'] == 'INTEGER'): ?>

<?php

// iframe support
if(strpos($field_val, 'iframe') !== FALSE) {
    /* filter */
    $field_val = str_replace('""', '"', $field_val);
    $field_val= str_replace( '&quot;','', $field_val );
    
    /* if set not correct iframe code */
    $field_val= str_replace( '[','<', $field_val );
    $field_val= str_replace( ']','></iframe>', $field_val );
    
    esc_viewe('<li class="embed">'. $field_val.'</li>');
}

elseif(strpos($field_val, 'vimeo.com') !== FALSE)
{
    esc_viewe('<li class="embed">'. wp_oembed_get($field_val, array( "width"=>"560", "height"=>"315" )).'</li>');
}
elseif(strpos($field_val, 'watch?v=') !== FALSE)
{
    $embed_code = substr($field_val, strpos($field_val, 'watch?v=')+8);
    esc_viewe('<li class="embed">'. wp_oembed_get(SELIO_PROTOCOL.'www.youtube.com/watch?v='.$embed_code, array( )).'</li>');
}
// version for youtube link
elseif(strpos($field_val, 'youtu.be/') !== FALSE)
{
    $embed_code = substr($field_val, strpos($field_val, 'youtu.be/')+9);
    esc_viewe( '<li class="embed">'. wp_oembed_get(SELIO_PROTOCOL.'www.youtube.com/watch?v='.$embed_code, array( )).'</li>');
}
// basic text
else
{

    echo '<li><h4>' . esc_html($field_children['field_name']) . ':</h4> <span>' . esc_html($field_val) . '</span></p>';
}
?>

<?php elseif($field_children['type'] == 'CHECKBOX'): ?>

<?php $field_val?$field_val='check':$field_val='remove'; ?>
<li class="input-field option">
    <input type="checkbox" name="c<?php echo esc_attr($field_children['idfield']); ?>" <?php if($field_val =='check'):?> checked="checked" <?php endif;?>  disabled="disabled" id="c<?php echo esc_attr($field_children['idfield']); ?>">
    <label for="c<?php echo esc_attr($field_children['idfield']); ?>">
        <span></span>
        <small><?php echo esc_html($field_children['field_name']); ?></small>
    </label>
</li>

<?php else: ?>

<?php selio_dump($field_children); ?>

<?php endif; ?>
<?php endif; ?>


<?php endforeach; ?>

<?php 
    // Hide category if there is no items to show
    if($in_cat_counter == 0)
    {
        $custom_js = 'jQuery(".field_'.esc_html($field['parent']['idfield']).'").css("display","none")';
        wp_add_inline_script( 'selio-custom', $custom_js );
    }
?>

</ul>

<br style="clear: both;" />
</div> <!--details-info end-->

<?php endif; ?>
<?php endforeach; ?>

<?php

if(!selio_plugin_call::sw_settings('hide_map_listingpage')){
    $w_p_title = _field($listing, 10);
    $gmap_lat = $listing->lat;
    $gmap_long = $listing->lng;
    $property_address = $listing->address;
    $metric = 'km';
    
    $zoom = '15';
    $sw_zoom_index_listing = selio_plugin_call::sw_settings('zoom_index_listing');
    if(!empty($sw_zoom_index_listing))
        $zoom = $sw_zoom_index_listing;

    $pin_icon = plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/empty.png';

    // check for version with field_id = 14
    if(file_exists(SW_WIN_PLUGIN_PATH.'assets/img/markers/'._field($listing, 14).'.png'))
    {
        $pin_icon = plugins_url( SW_WIN_SLUG.'/assets').'/img/markers/'._field($listing, 14).'.png';
    }
    
    $category = get_listing_category($listing);
    // check for version with category related marker
    if(isset($category->marker_icon_id))
    {
        $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
        if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
        {
            $pin_icon = $img[0];
        }
    }

    if(function_exists('sw_template_pin_icon'))
    {
        $pin_icon = sw_template_pin_icon($listing);
    }
    
    $font_icon = "";
    // check for version with category related marker
    if(isset($category->font_icon_code) && !empty($category->font_icon_code))
    {
        $font_icon = $category->font_icon_code;
    }
    
    if(!empty($gmap_lat))
    {
        echo '<div class="map-dv widget-property-map">
                    <h3>'.esc_html__('Location', 'selio').'</h3> ';
        
        if($font_icon){
            $custom_pin ="<div class='marker-container'><div class='marker-card'><div class='front face'><i class='".esc_attr($font_icon)."'></i></div><div class='back face'> <i class='".esc_attr($font_icon)."'></i></div><div class='marker-arrow'></div></div></div>";
        } elseif($pin_icon){
            $custom_pin ="<div class='marker-container'><div class='marker-card'><div class='front face'><i class='".esc_attr($pin_icon)."'></i></div><div class='back face'> <i class='".esc_attr($pin_icon)."'></i></div><div class='marker-arrow'></div></div></div>";
        }else{
            $custom_pin ="<div class='marker-container'><div class='marker-card'><div class='front face'><i class='".esc_attr($pin_icon)."'></i></div><div class='back face'> <i class='".esc_attr($pin_icon)."'></i></div><div class='marker-arrow'></div></div></div>";
        }
        
        
        if(selio_plugin_call::sw_settings('use_walker'))
        {
            echo do_shortcode('[walker metric="'.esc_attr($metric).'" marker_url="'.esc_attr($pin_icon).'" zoom="'.esc_attr($zoom).'" latitude="'.esc_attr($gmap_lat).'" longitude="'.esc_attr($gmap_long).'" default_index="0"]'.$w_p_title.'<br />'.$property_address.'[/walker]');
        }
        else
        {
            echo do_shortcode('[swmap custom_popup_x="1" htmlmarker_offset_x="17" htmlmarker_offset_y="-5" inner_html="'.$custom_pin.'" marker_url="' .esc_attr($pin_icon). '" metric="'.esc_attr($metric).'" marker_url="'.esc_attr($pin_icon).'" zoom="'.esc_attr($zoom).'" latitude="'.esc_attr($gmap_lat).'" longitude="'.esc_attr($gmap_long).'"]'.str_replace("'", "\'", _infowindow_content($listing, array('show_details'=>false))).'[/swmap]');
        }

        echo '</div>';
    }
}
?>

<?php if(function_exists('sw_show_reviews')): ?>
    <?php $this->load->view('frontend/reviews'); ?>
<?php endif; ?>

<?php  

sw_win_load_ci_frontend();
$CI = &get_instance();
$CI->load->model('review_m');
$CI->load->model('listing_m');
$CI->load->model('favorite_m');

$conditions = array('search_smart'=>'', 'search_is_activated'=>1);

$conditions['search_order'] = 'idlisting DESC';

if(!empty($listing->location_id)){
    $conditions['search_location'] = $listing->location_id;
}

if(!empty($listing->search_20)){
    $conditions['search_20'] = $listing->search_20;
}

$conditions['search_order'] = 'RAND()';

prepare_frontend_search_query_GET('listing_m', $conditions);
$limit = 4;
$similar_lisitngs = $CI->listing_m->get_pagination_lang($limit, 0, sw_current_language_id());


if(count($similar_lisitngs) > 0):?>
<div class="similar-listings-posts clearfix">
    <h3><?php echo esc_html('Similar Listings','selio');?></h3>
    <div class="list-products">
        <?php  foreach($similar_lisitngs as $related_listing):?>
        <?php 
        $CI = &get_instance();
        $CI->load->model('favorite_m');
        $CI->load->model('review_m');
        $favorite_added=false;
        if(get_current_user_id() != 0)
        {
            $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                               $related_listing->idlisting);
            if($favorite_added>0)$favorite_added = true;
        }

        $avarage_stars = floor(($CI->review_m->avg_rating_listing($related_listing->idlisting) * 2) / 2);
        if($avarage_stars == 0)$avarage_stars = 5.0;

        $avarage_stars = intval($avarage_stars);
        ?>
        <div class="card">
            <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="preview">
                <div class="img-block">
                    <div class="overlay"></div>
                    <img src="<?php echo esc_url(_show_img($related_listing->image_filename, '851x678', true)); ?>" alt="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="img-fluid">
                    <div class="rate-info">
                        <h5>
                            <?php // @codingStandardsIgnoreStart ?>
                            <?php if(!empty(_field($related_listing, 37)) && _field($related_listing, 37) !='-'):?>
                            <?php echo esc_html(_field($related_listing, 37)); ?>
                            <?php else:?>
                            <?php echo esc_html(_field($related_listing, 36)); ?>
                            <?php endif;?>
                            <?php // @codingStandardsIgnoreEnd ?>
                        </h5>
                        <span class="purpose-<?php echo esc_attr(url_title(_field($related_listing, 4), '-', TRUE)); ?>"><?php echo esc_html(esc_html(_field($related_listing, 4))); ?></span>
                    </div>
                </div>
            </a>
            <div class="card_bod_full">
                <div class="card-body">
                    <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>">
                        <h3><?php echo esc_html(_field($related_listing, 10)); ?></h3>
                        <p> <i class="la la-map-marker"></i><?php echo esc_html(_field($related_listing, 'address')); ?></p>
                    </a>
                    <div class="resul-items">
                        <?php
                            // show items from visual result item builder
                            _show_items($related_listing, 2);
                        ?>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="crd-links">
                        <?php if(function_exists('sw_show_favorites')): ?>
                            <span class="favorites-actions pull-left">
                                <a href="#" data-id="<?php echo esc_attr($related_listing->idlisting);?>" class="add-favorites-action <?php echo (esc_attr($favorite_added))?'hidden':''; ?>" <?php if (!is_user_logged_in()): ?> data-loginpopup="true" <?php endif;?>  data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                                    <i class="la la-heart-o"></i>
                                </a>
                                <a href="#" data-id="<?php echo esc_attr($related_listing->idlisting);?>" class="remove-favorites-action <?php echo (!esc_attr($favorite_added))?'hidden':''; ?>" data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                                    <i class="la la-heart-o"></i>
                                </a>
                                <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(date('Y-m-d H:i:s'),strtotime($related_listing->date_modified));?>" class="plf">
                            <i class="la la-calendar-check-o"></i> 
                            <?php 
                                $date_modified = $related_listing->date_modified;
                                $date_modified_str = strtotime($date_modified);
                                echo esc_html(human_time_diff($date_modified_str));
                                echo ' '.esc_html__('Ago', 'selio');
                            ?>
                        </a>
                    </div><!--crd-links end-->
                    <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="btn btn-default"><?php echo esc_html__('View Details','selio');?></a>
                </div>
            </div><!--card_bod_full end-->
            <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="ext-link"></a>
        </div>
        <?php endforeach;?>
    </div><!-- list-products end-->
</div>
<?php endif;?>

<?php   if(count($related) > 0):?>
<div class="similar-listings-posts clearfix">
    <h3><?php echo esc_html('Related Listings','selio');?></h3>
    <div class="list-products">
        <?php  foreach($related as $related_listing):?>
        <?php 
        $CI = &get_instance();
        $CI->load->model('favorite_m');
        $CI->load->model('review_m');
        $favorite_added=false;
        if(get_current_user_id() != 0)
        {
            $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                               $related_listing->idlisting);
            if($favorite_added>0)$favorite_added = true;
        }

        $avarage_stars = floor(($CI->review_m->avg_rating_listing($related_listing->idlisting) * 2) / 2);
        if($avarage_stars == 0)$avarage_stars = 5.0;

        $avarage_stars = intval($avarage_stars);
        ?>
        <div class="card">
            <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="preview">
                <div class="img-block">
                    <div class="overlay"></div>
                    <img src="<?php echo esc_url(_show_img($related_listing->image_filename, '851x678', true)); ?>" alt="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="img-fluid">
                    <div class="rate-info">
                        <h5>
                            <?php // @codingStandardsIgnoreStart ?>
                            <?php if(!empty(_field($related_listing, 37)) && _field($related_listing, 37) !='-'):?>
                            <?php echo esc_html(_field($related_listing, 37)); ?>
                            <?php else:?>
                            <?php echo esc_html(_field($related_listing, 36)); ?>
                            <?php endif;?>
                            <?php // @codingStandardsIgnoreEnd ?>
                        </h5>
                        <span class="purpose-<?php echo esc_attr(url_title(_field($related_listing, 4), '-', TRUE)); ?>"><?php echo esc_html(esc_html(_field($related_listing, 4))); ?></span>
                    </div>
                </div>
            </a>
            <div class="card_bod_full">
                <div class="card-body">
                    <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>">
                        <h3><?php echo esc_html(_field($related_listing, 10)); ?></h3>
                        <p> <i class="la la-map-marker"></i><?php echo esc_html(_field($related_listing, 'address')); ?></p>
                    </a>
                    <div class="resul-items">
                        <?php
                            // show items from visual result item builder
                            _show_items($related_listing, 2);
                        ?>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="crd-links">
                        <?php if(function_exists('sw_show_favorites')): ?>
                            <span class="favorites-actions pull-left">
                                <a href="#" data-id="<?php echo esc_attr($related_listing->idlisting);?>" class="add-favorites-action <?php echo (esc_attr($favorite_added))?'hidden':''; ?>" <?php if (!is_user_logged_in()): ?> data-loginpopup="true" <?php endif;?>  data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                                    <i class="la la-heart-o"></i>
                                </a>
                                <a href="#" data-id="<?php echo esc_attr($related_listing->idlisting);?>" class="remove-favorites-action <?php echo (!esc_attr($favorite_added))?'hidden':''; ?>" data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_attr(sw_lang_query()); ?>">
                                    <i class="la la-heart-o"></i>
                                </a>
                                <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
                            </span>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(date('Y-m-d H:i:s'),strtotime($related_listing->date_modified));?>" class="plf">
                            <i class="la la-calendar-check-o"></i> 
                            <?php 
                                $date_modified = $related_listing->date_modified;
                                $date_modified_str = strtotime($date_modified);
                                echo esc_html(human_time_diff($date_modified_str));
                                echo ' '.esc_html__('Ago', 'selio');
                            ?>
                        </a>
                    </div><!--crd-links end-->
                    <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="btn btn-default"><?php echo esc_html__('View Details','selio');?></a>
                </div>
            </div><!--card_bod_full end-->
            <a href="<?php echo esc_url(listing_url($related_listing)); ?>" title="<?php echo esc_attr(_field($related_listing, 10)); ?>" class="ext-link"></a>
        </div>
        <?php endforeach;?>
    </div><!-- list-products end-->
</div>
<?php endif;?>
                
<?php if(!selio_plugin_call::sw_settings('hide_fbcomments_listingpage')): ?>
<div class="details-info details-info-transparent">
    <h3><?php echo esc_html__( 'Facebook comments', 'selio' ); ?></h3>
    <div class="fb-comments" data-href="<?php echo esc_url(selio_get_current_url()); ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
</div><!-- /. widget-facebook -->   
<?php endif; ?>


</div>

<?php
$custom_js ="";
$custom_js .="jQuery(document).ready(function($) {

    var estate_data_id = ".esc_html($listing->idlisting)."
    
    // [START] Add to favorites //  
    
    $(\"#selio_add_to_favorites\").click(function(){
        
        var data = { listing_id: estate_data_id };
        var loginpopup = $(this).attr('data-loginpopup');
        $.extend( data, {
            \"page\": 'frontendajax_addfavorite',
            \"action\": 'ci_action'
        });
        
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
        $.post(\"".esc_url(admin_url( 'admin-ajax.php' ))."\", data, 
               function(data){
            
            ShowStatus.show(data.message);
                            
            load_indicator.css('display', 'none');
            
            if(data.success)
            {
                $(\"#selio_add_to_favorites\").css('display', 'none');
                $(\"#selio_remove_from_favorites\").css('display', 'inline-block');
            } else {
                if(loginpopup == 'true' && $(window).width()>768) {
                    $('#sign-popup').toggleClass('active'); 
                    $('#register-popup').removeClass('active');
                    $('body').addClass('overlay-bgg');

                    $('html').on('click', function(){
                        $('#sign-popup').removeClass('active');
                        $('body').removeClass('overlay-bgg');
                    });
 
                    $('.login_popup_enabled, .popup').on('click', function(e) {
                        e.stopPropagation();
                    });
                }
            }
        });

        return false;
    });
    
    $(\"#selio_remove_from_favorites\").click(function(){
        
        var data = { listing_id: estate_data_id };
        
        $.extend( data, {
            \"page\": 'frontendajax_remfavorite',
            \"action\": 'ci_action'
        });
        
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
        $.post(\"".esc_url(admin_url( 'admin-ajax.php' ))."\", data, 
               function(data){
            
            ShowStatus.show(data.message);
                            
            load_indicator.css('display', 'none');
            
            if(data.success)
            {
                $(\"#selio_remove_from_favorites\").css('display', 'none');
                $(\"#selio_add_to_favorites\").css('display', 'inline-block');
            }
        });

        return false;
    });
    
    // [END] Add to favorites //  

});
";
        
wp_add_inline_script( 'selio-custom', $custom_js );

else:

    echo '<div class="alert alert-warning" role="alert">'.esc_html__( 'Wrong template file, please use listing preview', 'selio' ).'</div>';

endif;

?>

</div><!--property-pg-left end-->