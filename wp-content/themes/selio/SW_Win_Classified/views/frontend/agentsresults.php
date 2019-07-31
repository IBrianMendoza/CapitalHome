<span id="results_top"></span>

<div class="">
    <div class="row">
        <div class="col-lg-3 order-lg-2 widgets">
                <form id="widget-search-form">
                    <input type="search" class="search" name="search" placeholder="Search...">
                    <i class="ion-ios-search"></i>
                </form>
        </div>
        
        <section class="col-lg-9 order-lg-1">
        <div id="results-agents" >

            <?php if ($agents_count == 0): ?>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="alert alert-info" role="alert"><?php echo esc_html__('Agents not found', 'selio'); ?></div>
                    </div>
                </div>

            <?php else: ?>
                <div class="agancies-wr">

                    <?php
                    sw_win_load_ci_frontend();
                    $CI =& get_instance();
                    foreach ($agents as $key => $user_details):
                        $user = $user_details->data;
                        $CI->load->model('listing_m');
                        // [Fetch user listings]

                        $listings_count = $CI->listing_m->total_lang(array(), sw_current_language_id(), FALSE, $user_details->ID);
                        $listings = $CI->listing_m->get_pagination_lang(3, NULL, sw_current_language_id(), FALSE, $user_details->ID);
                        
                        ?>
                <article class="agancy-item">
                    <div class="row">
                        <div class="col-md-3 col-lg-2">
                            <a href="<?php echo esc_url(agent_url($user)); ?>" class="agancy-logo">
                                <img src="<?php echo esc_url(sw_profile_image($user, 120)); ?>" alt="..." class="img-circle">
                                <p><?php echo esc_html__('goes here', 'selio'); ?></p>
                            </a>
                        </div>
                        <div class="col-md-9 col-lg-10">
                            <div class="agancy-item-inner">
                                <header class="agancy-header">
                                    <h2 class="agancy-title"><a href="<?php echo esc_url(agent_url($user)); ?>" class="agent-name"><?php echo esc_html($user->display_name); ?> </a></h2>
                                    <p class="models-counter"><?php echo esc_html__('Models', 'selio'); ?>: <span><?php echo esc_html($listings_count);?></span></p>
                                </header>
                                <div class="agancy-content">
                                    <p><?php echo esc_html(wp_trim_words(get_the_author_meta("user_description", $user_details->ID), 20, '...')); ?></p>
                                </div>
                                <div class="agancy-models-wr">
                                    <header class="block-header">
                                        <p><?php echo esc_html__('Agency Top Models', 'selio'); ?></p>
                                    </header>
                                    <div class="agancy-models">
                                    <div class="row row-xs">
                                        <?php foreach($listings as $key=>$listing): ?>
                                            <?php 
                                            $CI->load->model('review_m');

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
                                        <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
                </div>

                <nav class="text-center agentsresults-pagin-parent"><?php
                    $CI = & get_instance();

                    /* Pagination configuration */
                    $config = array();
                    $config['full_tag_open'] = '<ul class="pagination">';
                    $config['full_tag_close'] = '</ul>';

                    $config['base_url'] = '';
                    $config['total_rows'] = $agents_count;
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


            <?php endif; ?>
            </div>
        </section>
    </div>
</div>
