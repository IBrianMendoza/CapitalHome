<?php 

// Special version for side template
if(strpos(get_page_template(), 'template-results-half-map') > 1 || (isset($_POST['template']) && $_POST['template'] == 'template-results-half-map.php') ):

?>

<div id="results" class="properties-rows">
<span id="results_top"></span>
<?php
    global $selio_default_view;
    $grid_active = 'active';
    $list_active = '';
    
    $_MERG = array_merge($_GET, $_POST);
    
    if(isset($_MERG['search_view']) && $_MERG['search_view'] == 'list')
    {
        $grid_active = '';
        $list_active = 'active';
    } elseif(isset($selio_default_view) && $selio_default_view == 'list') {
        $grid_active = '';
        $list_active = 'active';
    }
    
    $order_dropdown_def = 'idlisting ASC';
    if(isset($_MERG['search_order']) && !empty($_MERG['search_order']))
    {
        $order_dropdown_def = $_MERG['search_order'];
    }
    
    $order_dropdown = array('idlisting ASC'    => esc_html__('Relevant', 'selio'),
                            'idlisting DESC'   => esc_html__('Oldest', 'selio'),
                            'counter_views DESC, idlisting DESC' => esc_html__('Most View', 'selio'),
                            'field_36_int ASC, idlisting DESC' => esc_html__('Higher price', 'selio'),
                            'field_36_int DESC, idlisting DESC'=> esc_html__('Lower price', 'selio'));

?>

<div class="list-head">
    <div class="sortby">
        <span><?php echo esc_html__('Sort by', 'selio');?>:</span>
        <div class="drop-menu">
            <div class="select">
                <span><?php echo esc_html($order_dropdown[$order_dropdown_def]);?></span>
                <i class="la la-caret-down"></i>
            </div>
            <input type="hidden" name="search_order" id="search_order">
            <ul class="dropeddown">
                 <?php if(selio_plugin_call::sw_count($order_dropdown)>0) foreach ($order_dropdown as $key => $value):?>
                    <li data-value="<?php echo esc_attr($key);?>"><?php echo esc_html($value);?></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div><!--sortby end-->
    <div class="view-change">
        <ul class="nav nav-tabs sw-order-view">
            <li class="nav-item">
                <a href="#" class="nav-link grid view-type <?php echo esc_attr($grid_active); ?>" data-ref="grid"><i class="la la-th-large"></i></a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link list view-type <?php echo esc_attr($list_active); ?>" data-ref="list"><i class="la la-th-list"></i></a>
            </li>
        </ul>
    </div><!--view-change end-->
</div><!--list-head end-->


<div class="listings">
    <?php if($listings_count == 0): ?>
    <div class="list_products">
        <div class="row">
            <div class="alert alert-info" role="alert"><?php echo esc_html__('Results not found', 'selio'); ?></div>
        </div>
    </div>
    <?php endif; ?>
    <?php if($grid_active):?>
    <div class="list_products">
        <div class="row">
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
        <div class="col-lg-6 col-md-6">
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
    <?php endif;?>
    <?php if($list_active):?>
        <div class="list-products">
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
            <div class="card">
                <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 4)); ?>" class="preview">
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
                            <span class="purpose-<?php echo esc_attr(url_title(_field($listing, 4), '-', TRUE)); ?>"><?php echo esc_html(esc_html(_field($listing, 4))); ?></span>
                        </div>
                    </div>
                </a>
                <div class="card_bod_full">
                    <div class="card-body">
                        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>">
                            <h3><?php echo esc_html(_field($listing, 10)); ?></h3>
                            <p> <i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?></p>
                        </a>
                        <div class="resul-items">
                            <?php
                                // show items from visual result item builder
                                _show_items($listing, 2);
                            ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="crd-links">
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
                            <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(date('Y-m-d H:i:s'),strtotime($listing->date_modified));?>" class="plf">
                                <i class="la la-calendar-check-o"></i> 
                                <?php 
                                    $date_modified = $listing->date_modified;
                                    $date_modified_str = strtotime($date_modified);
                                   echo esc_html(human_time_diff($date_modified_str));
                                    echo ' '.esc_html__('Ago', 'selio');
                                ?>
                            </a>
                        </div><!--crd-links end-->
                        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>" class="btn btn-default"><?php echo esc_html__('View Details','selio');?></a>
                    </div>
                </div><!--card_bod_full end-->
                <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>" class="ext-link"></a>
            </div>
        <?php endforeach; ?>
        </div>

    <?php endif;?>
</div><!--tab-content end-->
<nav class="text-center pagin-parent"><?php
    $CI =& get_instance();
    /* Pagination configuration */ 
    $config = array();
    $config['full_tag_open'] = '<ul class="pagination">';
    $config['full_tag_close'] = '</ul>';
    
    $config['base_url'] = '';
    $config['total_rows'] = $listings_count;
    $config['per_page'] = selio_plugin_call::sw_settings('per_page');
    $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
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

<?php else: ?>
<?php /* default template page */ ?>
    <div id="results" class="properties-rows">
    <span id="results_top"></span>
    <?php

        global $selio_default_view;
        $grid_active = 'active';
        $list_active = '';
        $_MERG = array_merge($_GET, $_POST);

        if(isset($_MERG['search_view']) && $_MERG['search_view'] == 'list')
        {
            $grid_active = '';
            $list_active = 'active';
        } elseif(isset($selio_default_view) && $selio_default_view ='list') {
            $grid_active = '';
            $list_active = 'active';
        }
    
        $order_dropdown_def = 'idlisting ASC';
        if(isset($_MERG['search_order']) && !empty($_MERG['search_order']))
        {
            $order_dropdown_def = $_MERG['search_order'];
        }

        $order_dropdown = array('idlisting ASC'    => esc_html__('Relevant', 'selio'),
                                'idlisting DESC'   => esc_html__('Oldest', 'selio'),
                                'counter_views DESC, idlisting DESC' => esc_html__('Most View', 'selio'),
                                'field_36_int ASC, idlisting DESC' => esc_html__('Higher price', 'selio'),
                                'field_36_int DESC, idlisting DESC'=> esc_html__('Lower price', 'selio'));

    ?>

    <div class="list-head">
        <div class="sortby">
            <span><?php echo esc_html__('Sort by', 'selio');?>:</span>
            <div class="drop-menu">
                <div class="select">
                    <span><?php echo esc_html($order_dropdown[$order_dropdown_def]);?></span>
                    <i class="la la-caret-down"></i>
                </div>
                <input type="hidden" name="search_order" id="search_order">
                <ul class="dropeddown">
                     <?php if(selio_plugin_call::sw_count($order_dropdown)>0) foreach ($order_dropdown as $key => $value):?>
                        <li><?php echo esc_html($value);?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div><!--sortby end-->
        <div class="view-change">
            <ul class="nav nav-tabs sw-order-view">
                <li class="nav-item">
                    <a href="#" class="nav-link grid view-type <?php echo esc_attr($grid_active); ?>" data-ref="grid"><i class="la la-th-large"></i></a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link list view-type <?php echo esc_attr($list_active); ?>" data-ref="list"><i class="la la-th-list"></i></a>
                </li>
            </ul>
        </div><!--view-change end-->
    </div><!--list-head end-->


    <div class="listings">
                    <?php if($listings_count == 0): ?>
                    <div class="list_products">
                        <div class="row">
                            <div class="col-12">
                            <div class="alert alert-info" role="alert"><?php echo esc_html__('Results not found', 'selio'); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($grid_active):?>
                    <div class="list_products">
                        <div class="row">
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
                        <div class="col-lg-6 col-md-6">
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
                    <?php endif;?>
                    <?php if($list_active):?>
                        <div class="list-products">
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
                            <div class="card">
                                <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>" class="preview">
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
                                            <span class="purpose-<?php echo esc_attr(url_title(_field($listing, 4), '-', TRUE)); ?>"><?php echo esc_html(esc_html(_field($listing, 4))); ?></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="card_bod_full">
                                    <div class="card-body">
                                        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>">
                                            <h3><?php echo esc_html(_field($listing, 10)); ?></h3>
                                            <p> <i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?></p>
                                        </a>
                                        <div class="resul-items">
                                            <?php
                                                // show items from visual result item builder
                                                _show_items($listing, 2);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="crd-links">
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
                                            <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(date('Y-m-d H:i:s'),strtotime($listing->date_modified));?>" class="plf">
                                                <i class="la la-calendar-check-o"></i> 
                                                <?php 
                                                    $date_modified = $listing->date_modified;
                                                    $date_modified_str = strtotime($date_modified);
                                                    echo esc_html(human_time_diff($date_modified_str));
                                                    echo ' '.esc_html__('Ago', 'selio');
                                                ?>
                                            </a>
                                        </div><!--crd-links end-->
                                        <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>" class="btn btn-default"><?php echo esc_html__('View Details','selio');?></a>
                                    </div>
                                </div><!--card_bod_full end-->
                                <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>" class="ext-link"></a>
                            </div>
                        <?php endforeach; ?>
                        </div>

                    <?php endif;?>

    </div><!--tab-content end-->
    <nav class="text-center pagin-parent"><?php
        $CI =& get_instance();
        /* Pagination configuration */ 
        $config = array();
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['base_url'] = '';
        $config['total_rows'] = $listings_count;
        $config['per_page'] = selio_plugin_call::sw_settings('per_page');
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
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

<?php endif; ?>


