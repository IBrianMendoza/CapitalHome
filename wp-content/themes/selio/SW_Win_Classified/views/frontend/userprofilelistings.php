<div id="results-profile" class="properties-rows">

<span id="results_top"></span>

<div class="widget widget-profilelisting">
    <div class="widget-header text-uppercase">
        <h2><?php echo esc_html__('Results', 'selio'); ?>: <?php echo esc_html($listings_count); ?></h2>
    </div>
    <div class="properties">
<?php

    $grid_active = 'active';
    $list_active = '';
    
    $_MERG = array_merge($_GET, $_POST);
    
    if(isset($_MERG['search_view']) && $_MERG['search_view'] == 'list')
    {
        $grid_active = '';
        $list_active = 'active';
    }
    
    $order_dropdown = array('idlisting ASC'    => esc_html__('By publish date ASC', 'selio'),
                            'idlisting DESC'   => esc_html__('By publish date DESC', 'selio'),
                            'counter_views DESC, idlisting DESC' => esc_html__('Most View', 'selio'),
                            'idlisting DESC,field_36_int ASC' => esc_html__('By price ASC', 'selio'),
                            'idlisting DESC,field_36_int DESC'=> esc_html__('By price DESC', 'selio'));

    
?>

<div class="listings-filter">
    <div class="form-group">
        <?php esc_viewe(form_dropdown('search_order', $order_dropdown, search_value('order', NULL, 'idlisting DESC'), 'id="search_order" class="form-control selectpicker select-small" '))?>
    </div>
    <div class="grid-type pull-right sw-order-view">
        <a href="#" class="list view-type <?php echo esc_attr($list_active); ?>" data-ref="list"><i class="fa fa-th-list"></i></a>
        <a href="#" class="grid view-type <?php echo esc_attr($grid_active); ?>" data-ref="grid"><i class="fa fa-th-large"></i></a>
    </div>
</div>

<div class="clear"></div>

<?php if($listings_count == 0): ?>
<div class="row sw-listing-results">
    <div class="col-xs-12">
    <div class="alert alert-info" role="alert"><?php echo esc_html__('Results not found', 'selio'); ?></div>
    </div>
</div>
<?php endif; ?>

<?php if(!empty($list_active)): // is list view ?>

<div class="row sw-listing-results model-row-wrap models-list">
<?php foreach($listings as $key=>$listing): ?>
    <?php 
    $CI = &get_instance();
    $CI->load->model('favorite_m');
    $CI->load->model('review_m');
    $favorite_added=false;
    if(get_current_user_id() != 0)
    {
        $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                           $listing->idlisting);
        if($favorite_added>0)$favorite_added = true;
    }

    $avarage_stars = floor(($CI->review_m->avg_rating_listing($listing->idlisting) * 2) / 2);
    if($avarage_stars == 0)$avarage_stars = 5.0;

    $avarage_stars = intval($avarage_stars);
    ?>
    
    <div class="col-sm-12 col-lg-12 models-list-item">
        <article class="model-row">
                <div class="row">
                        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-2 offset-xl-0">
                                <a href="<?php echo esc_url(listing_url($listing)); ?>" class="img-wr">
                                        <img src="<?php echo esc_url(_show_img($listing->image_filename, '575x700', true)); ?>" alt="<?php echo esc_attr(_field($listing, 10)); ?>">
                                </a>
                        </div>
                        <div class="col-xl-6">
                                <div class="row-model-info">
                                        <h3 class="title">
                                                <a href="<?php echo esc_url(listing_url($listing)); ?>"><?php echo esc_html(_field($listing, 10)); ?></a>
                                        </h3>
                                        <div class="model-attr">
                                            <p class="attr-item">
                                                    <span class="attr-name"><?php echo esc_html(_field_name(22)); ?>:</span>
                                                    <span class="attr-value"><?php echo esc_html(_field($listing, 22)); ?></span>
                                            </p>
                                            <p class="attr-item">
                                                    <span class="attr-name"><?php echo esc_html(_field_name(23)); ?>:</span>
                                                    <span class="attr-value"><?php echo esc_html(_field($listing, 23)); ?></span>
                                            </p>
                                            <p class="attr-item">
                                                    <span class="attr-name"><?php echo esc_html(_field_name(29)); ?>:</span>
                                                    <span class="attr-value"><?php echo esc_html(_field($listing, 29)); ?></span>
                                            </p>
                                            <p class="attr-item">
                                                    <span class="attr-name"><?php echo esc_html(_field_name(32)); ?>:</span>
                                                    <span class="attr-value"><?php echo esc_html(_field($listing, 32)); ?></span>
                                            </p>
                                            <p class="attr-item">
                                                    <span class="attr-name"><?php echo esc_html(_field_name(30)); ?>:</span>
                                                    <span class="attr-value"><?php echo esc_html(_field($listing, 30)); ?></span>
                                            </p>
                                            <p class="attr-item">
                                                    <span class="attr-name"><?php echo esc_html(_field_name(31)); ?>:</span>
                                                    <span class="attr-value"><?php echo esc_html(_field($listing, 31)); ?></span>
                                            </p>
                                        </div>
                                        <p class="rating">
                                            <?php for($i = 1; $i<=5;$i++):?>
                                                <?php if($i<=$avarage_stars):?>
                                                <i class="fa fa-star active" aria-hidden="true"></i>
                                                <?php else:?>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <?php endif;?>
                                            <?php endfor;?>
                                        </p>
                                </div>
                        </div>
                        <div class="col-xl-2">
                                <p class="soc-icons-wr">
                                        <a href="#" class="soc-icon"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                        <a href="#" class="soc-icon"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                </p>
                        </div>
                        <div class="col-xl-2">
                                <p class="soc-icons-wr big">

                                        <?php if(function_exists('sw_show_favorites')): ?>
                                        <span class="is_favorite soc-icon">
                                            <a href="#" class="add-favorites-action <?php echo (esc_attr($favorite_added))?'hidden':''; ?>" <?php if (!is_user_logged_in()): ?> data-loginpopup="true" <?php endif;?> data-id="<?php echo esc_attr($listing->idlisting);?>" data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>"><i class="icon ion-android-favorite"></i></a>
                                            <a href="#" class="remove-favorites-action <?php echo (!esc_attr($favorite_added))?'hidden':''; ?>" data-id="<?php echo esc_attr($listing->idlisting);?>" data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>"><i class="icon ion-android-favorite"></i></a>
                                            <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
                                        </span>
                                        <?php endif; ?>
                                        <a href="#" class="soc-icon"><i class="icon ion-ios-email-outline"></i></a>
                                </p>
                        </div>
                </div>
        </article>
</div>
<?php endforeach; ?>
    
</div>

<?php else: ?>
<div class="row sw-listing-results">
<?php foreach($listings as $key=>$listing): ?>
    <?php 
    $CI = &get_instance();
    $CI->load->model('favorite_m');
    $CI->load->model('review_m');
    $favorite_added=false;
    if(get_current_user_id() != 0)
    {
        $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                           $listing->idlisting);
        if($favorite_added>0)$favorite_added = true;
    }
    
    $avarage_stars = floor(($CI->review_m->avg_rating_listing($listing->idlisting) * 2) / 2);
    if($avarage_stars == 0)$avarage_stars = 5.0;

    $avarage_stars = intval($avarage_stars);
    ?>

<article class="col-sm-6 col-md-4">
    <a href="<?php echo esc_url(listing_url($listing)); ?>" class="item-wr">
        <div class="model-item" style="background-image: url('<?php echo esc_url(_show_img($listing->image_filename, '575x700', true)); ?>')">
            <div class="model-info">
            <p><?php echo esc_html(_field_name(22)); ?>: <span><?php echo esc_html(_field($listing, 22)); ?></span></p>
            <p><?php echo esc_html(_field_name(23)); ?>: <span><?php echo esc_html(_field($listing, 23)); ?></span></p>
            <p><?php echo esc_html(_field_name(29)); ?>: <span><?php echo esc_html(_field($listing, 29)); ?></span></p>
            <p><?php echo esc_html(_field_name(32)); ?>: <span><?php echo esc_html(_field($listing, 32)); ?></span></p>
            <p><?php echo esc_html(_field_name(30)); ?>: <span><?php echo esc_html(_field($listing, 30)); ?></span></p>
            <p><?php echo esc_html(_field_name(31)); ?>: <span><?php echo esc_html(_field($listing, 31)); ?></span></p>
            <p class="rating">
                <?php for($i=0; $i<$avarage_stars; $i++): ?>
                <i class="fa fa-star active" aria-hidden="true"></i>
                <?php endfor; ?>
            </p>
        </div>
        </div>
        <h3 class="title"><?php echo esc_html(_field($listing, 10)); ?></h3>
    </a>
</article>
<?php 

// solve issue with possible larger result items
if( ($key+1) % 3 == 0  )
{
    echo '<div class="clear hidden-sm"></div>';
}
if( ($key+1) % 2 == 0  )
{
    echo '<div class="clear visible-sm"></div>';
}
if( ($key+1) == count($listings)  )
{
    echo '<div class="clear"></div>';
}
?>
<?php endforeach; ?>
    
</div>

<?php endif; ?>

<nav class="text-center agent-pagin-parent"><?php
    
    $CI =& get_instance();
    
    /* Pagination configuration */ 
    $config = array();
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    
    $config['base_url'] = '';
    $config['total_rows'] = $listings_count;
    $config['per_page'] = selio_plugin_call::sw_settings('per_page');
    $config['cur_tag_open'] = '<li class="page-item"><a class="active" href="#">';
    $config['cur_tag_close'] = '</a></li>';
    $config['num_tag_open'] = '<li class="page-item">';
    $config['num_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li class="page-item">';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="page-item">';
    $config['next_tag_close'] = '</li>';
    $config['first_link'] = FALSE;
    $config['last_link'] = FALSE;
    $config['page_query_string'] = TRUE;
    $config['reuse_query_string'] = TRUE;
    $config['query_string_segment'] = 'offset';
    $config['suffix'] = "#results";
    $config['anchor_class'] = 'class="page-link" ';

    /* End Pagination */
    
    $CI->pagination->initialize($config);
    esc_viewe($CI->pagination->create_links());

?></nav>

    </div>
    <!-- /.properties -->
</div>

</div>