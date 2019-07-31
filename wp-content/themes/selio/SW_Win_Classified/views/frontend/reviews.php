<?php
$dropzone = true;

if(selio_plugin_call::sw_settings('disable_reviews_gallery')) {
    $dropzone = false;
}

wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-widget', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, false);
wp_enqueue_script( 'jquery.fileupload' );
wp_enqueue_script( 'jquery.fileupload-fp' );
wp_enqueue_script( 'jquery.fileupload-ui' );
wp_enqueue_style( 'jquery.fileupload-ui');
?>

<div class="comments-dv widget-property-reviews widget-reviews clearfix" id="widget-reviews">
    <h3>
           <?php
           // @codingStandardsIgnoreStart
                printf(
                         esc_html(_nx( '%s Review', '%s Reviews', selio_plugin_call::sw_count($reviews_all), 'group of people', 'selio' )),
                         esc_html(selio_plugin_call::sw_count($reviews_all))
                );
            // @codingStandardsIgnoreEnd
            ?>
    </h3>
    <div class="comment-section">
    <?php if(selio_plugin_call::sw_count($reviews_all) > 0): ?>
        <ul>
            <?php foreach($reviews_all as $review_data): ?>
            <?php 
                $user = get_userdata( $review_data->user_id );
            ?>
            <?php
                $user_info = get_userdata($review_data->user_id);
                $user=$user_info->data;
                $review = $this->review_m->get_by(array('user_id'=>$review_data->user_id));
            ?>
            <?php
                $timestamp = strtotime($review_data->date_modified);
                $m_date = date("F", $timestamp);
                $d = date("d", $timestamp);
                $y = date("Y", $timestamp);
            ?>
            <li>
                <div class="cm-info-sec">
                    <div class="cm-img">
                        <a href="<?php echo esc_url(agent_url($user)); ?>" class="user-logo">
                            <img src="<?php echo esc_url(get_gravatar($review_data->user_email, 150)); ?>" alt="<?php echo esc_attr($user->display_name); ?>" />
                        </a>
                    </div><!--author-img end-->
                    <div class="cm-info">
                        <h3><?php echo esc_html($user->display_name); ?></h3>
                        <h4><?php echo esc_html($m_date);?> <?php echo esc_html($d);?>, <?php echo esc_html($y);?></h4>
                    </div>
                    <ul class="rating-lst">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $review_data->stars): ?>
                                <li><span class="la la-star"></span></li>
                            <?php else: ?>
                                <li><span class="la la-star innactive"></span></li>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </ul><!--rating-lst end-->
                </div><!--cm-info-sec end-->
                <p>
                    <?php echo esc_html($review_data->message); ?>
                </p>

                <ul class="files files-list reviews-files-list clearfix" data-toggle="modal-gallery" data-target="#modal-gallery">   
                    <?php
                    if(isset($review_data->review_repository_id) && !empty($review_data->review_repository_id)){
                        $rep_id = $review_data->review_repository_id;

                        //Fetch repository
                        $file_rep = $this->file_m->get_repository($review_data->review_repository_id);
                        if(selio_plugin_call::sw_count($file_rep)) foreach($file_rep as $file):?>
                            <?php sw_add_file_tags($file); ?>
                            <li class="template-download" href="<?php echo esc_url($file->download_url)?>" title="<?php echo esc_attr($file->filename);?>" download="<?php echo esc_attr($file->filename)?>">
                                <div class="preview">
                                    <img alt="<?php echo esc_attr($file->filename); ?>" data-src="<?php echo esc_attr($file->thumbnail_url); ?>" src="<?php echo esc_attr($file->thumbnail_url); ?>">
                                </div>
                            </li>
                        <?php
                        endforeach;
                    }
                    ?>
                </ul>
                <?php if(!$already_reviewed): ?>
                    <a href="#write_review" title="<?php echo esc_attr__('Reply','selio');?>" class="cm-reply  <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>"><?php echo esc_html__('Reply','selio');?></a>
                <?php endif;?>  
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="content-box">
        <p class="alert alert-success">
            <?php echo esc_html__('No reviews available', 'selio'); ?>
        </p>
        </div>
        <?php endif; ?>
    </div>
    <div class="review-hd" id="write_review">
        
        <div class="rev-hd">
            <h3><?php echo esc_html('Write a Review', 'selio');?></h3>
            <div class="form-group-rating rating-lst <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>">
                <input type="radio" name="stars" value=""  class="hidden" checked="checked" />
                <fieldset class="rating-action rating">
                    <input type="radio" id="star1" name="stars" value="5" required/>
                    <label class="full" for="star1" title="<?php echo esc_attr__('Awesome - 5 stars', 'selio');?>"></label>
                    <input type="radio" id="star2" name="stars" value="4" />
                    <label class="full" for="star2" title="<?php echo esc_attr__('Pretty good - 4 stars', 'selio');?>"></label>
                    <input type="radio" id="star3" name="stars" value="3" />
                    <label class="full" for="star3" title="<?php echo esc_attr__('Meh - 3 stars', 'selio');?>"></label>
                    <input type="radio" id="star4" name="stars" value="2" />
                    <label class="full" for="star4" title="<?php echo esc_attr__('Kinda bad - 2 stars', 'selio');?>"></label>
                    <input type="radio" id="star5" name="stars" value="1" />
                    <label class="full" for="star5" title="<?php echo esc_attr__('Very bad - 1 star', 'selio');?>"></label>
                </fieldset>
            </div>
        </div><!--rev-hd end-->
        <div class="post-comment-sec">
            <?php if(sw_is_logged_user() && $already_reviewed): ?>

            <p class="alert alert-info">
                <?php echo esc_html__('Thanks on review', 'selio'); ?>
            </p>

            <?php elseif(sw_is_logged_user()): ?>
                <?php
                 // @codingStandardsIgnoreStart
                if(isset($_POST['repository_id']) && !empty($_POST['repository_id']))
                {
                    $review_repository_id = esc_html($_POST['repository_id']);
                } else {
                    // Create new repository
                    $review_repository_id = $this->repository_m->save(array('model_name'=>'reviews_m'));
                }
                 // @codingStandardsIgnoreEnd
                ?>
            <form action="#form_review" method="post" >
                <input type="text" name="stars" value="" id="review_star_input"  class="hidden" />
                <?php _form_messages(esc_html__('Saved successfuly', 'selio'), NULL, 'review'); ?>
                <div class="row">
                    <div class="col-lg-12 pl-0 pr-0">
                        <div class="form-field"><textarea name="message" placeholder="<?php echo esc_attr__('Your Message', 'selio');?>"></textarea></div><!--form-field end-->
                    </div>
                    
                    <?php if($dropzone):?>
                    <div class="col-lg-12 pl-0 pr-0">
                        <input class="hidden" name="repository_id" type="text" value="<?php echo esc_attr($review_repository_id);?>" />
                        <div class="profile-uiploadimage">
                        <?php if(!isset($review_repository_id)):?>
                           <span class="label label-danger"><?php echo esc_html__('After saving, you can add files and images', 'selio');?></span>
                        <?php else:?>
                           <div id="page-files-<?php echo esc_attr($review_repository_id)?>" rel="repository_m">
                                <!-- The file upload form used as target for the file upload widget -->
                               <div class="fileupload fileupload-custom" id="fileupload_<?php echo esc_attr($review_repository_id); ?>">
                                    <div role="presentation" class="fieldset-content">
                                        <ul class="files files-list reviews-files-list clearfix" data-toggle="modal-gallery" data-target="#modal-gallery">   
                                            <?php 
                                            if(isset($review_repository_id)){
                                                $rep_id = $review_repository_id;

                                                //Fetch repository
                                                $file_rep = $this->file_m->get_repository($rep_id);
                                                if(selio_plugin_call::sw_count($file_rep)) foreach($file_rep as $file):?>
                                                    <?php sw_add_file_tags($file); ?>
                                                    <li class="template-download fade in">
                                                        <div class="preview">
                                                            <img alt="<?php echo esc_attr($file->filename); ?>" data-src="<?php echo esc_attr($file->thumbnail_url); ?>" src="<?php echo esc_attr($file->thumbnail_url); ?>">
                                                        </div>
                                                        <span class="delete">
                                                            <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo esc_url($file->delete_url); ?>"><i class="glyphicon glyphicon-remove"></i></button>
                                                            <input type="checkbox" value="1" name="delete">
                                                        </span>
                                                    </li>
                                                <?php
                                                endforeach;
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <!-- Redirect browsers with JavaScript disabled to the origin page -->
                                    <noscript><input type="hidden" name="redirect" value=""></noscript>
                                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                                    <div class="fileupload-buttonbar row hidden">
                                        <div class="col-md-12 ">
                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                            <span class="btn btn-success fileinput-button">
                                                <i class="icon-plus icon-white"></i>
                                                <span><?php echo esc_html__('Addfiles', 'selio')?></span>
                                                <input type="file" name="files[]" class="file-btn" multiple>
                                            </span>
                                            <button type="reset" class="btn btn-warning cancel">
                                                <i class="icon-ban-circle icon-white"></i>
                                                <span><?php echo esc_html__('Cancelupload', 'selio')?></span>
                                            </button>
                                            <button type="button" class="btn btn-danger delete">
                                                <i class="icon-trash icon-white"></i>
                                                <span><?php echo esc_html__('Delete', 'selio')?></span>
                                            </button>
                                            <input type="checkbox" class="toggle hidden" />
                                        </div>
                                        <!-- The global progress information -->
                                        <div class="col-md-12 fileupload-progress fade">
                                            <!-- The global progress bar -->
                                            <div id="progress-upload" class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                <div class="bar" style="width:0%;"></div>
                                            </div>
                                            <!-- The extended global progress information -->
                                            <div class="progress-extended">&nbsp;</div>
                                        </div>
                                    </div>
                                    <div class="fileupload-loading"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    <div class="col-lg-12 pl-0 pr-0">
                        <div id="dropzone-<?php echo esc_attr($review_repository_id)?>" class="dropzone fade well">
                            <div class="dropzone-content">
                                <svg class="dropzone_icon" xmlns="//www.w3.org/2000/svg" width="50" height="43" viewBox="0 0 50 43"><path d="M48.4 26.5c-.9 0-1.7.7-1.7 1.7v11.6h-43.3v-11.6c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v13.2c0 .9.7 1.7 1.7 1.7h46.7c.9 0 1.7-.7 1.7-1.7v-13.2c0-1-.7-1.7-1.7-1.7zm-24.5 6.1c.3.3.8.5 1.2.5.4 0 .9-.2 1.2-.5l10-11.6c.7-.7.7-1.7 0-2.4s-1.7-.7-2.4 0l-7.1 8.3v-25.3c0-.9-.7-1.7-1.7-1.7s-1.7.7-1.7 1.7v25.3l-7.1-8.3c-.7-.7-1.7-.7-2.4 0s-.7 1.7 0 2.4l10 11.6z"></path></svg>
                                <div class="dropzone-content-notice">
                                    <strong><?php echo esc_html__('Choose a images', 'selio');?></strong> <span class="box__dragndrop"><?php echo esc_html__('or drag it here', 'selio');?></span>.
                                </div>
                            </div>
                            <div class="loading_mask hidden"><i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i></div>
                        </div>
                    </div>
                    <?php endif;?>
                    
                    <div class="col-lg-12 pl-0 pr-0">
                        <button type="submit" class="btn-default">Post Review</button>
                    </div>
                </div>
            </form>

            <?php
            $custom_js ="";
            $custom_js .="
                // When the server is ready...
                jQuery( document ).ready(function($) {
                    // Define the url to send the image data to
                    var url_".esc_html($review_repository_id)." = '".esc_html(admin_url( 'admin-ajax.php' ))."';
            $('#dropzone-".esc_html($review_repository_id)."')
                    // Call the fileupload widget and set some parameters
                    $('#fileupload_".esc_html($review_repository_id)."').fileupload({
                        url: url_".esc_html($review_repository_id).",
                        autoUpload: true,
                        dropZone: $('#dropzone-".esc_html($review_repository_id)."'),
                        dataType: 'json',
                        formData: {
                            action: 'ci_action',
                            page: 'files_repository',
                            repository_id: '".esc_html($review_repository_id)."'
                        },
                    uploadTemplate: function (o) {
                        var rows = $();
                        //return rows;
                        $.each(o.files, function (index, file) {
                            var row = $('<div></div>');
                            rows = rows.add(row);
                        });
                        return rows;
                    },
                    downloadTemplate: function (o) {
                        var rows = $();
                        $.each(o.files, function (index, file) {
                            var added=false;
                            if (file.error) {
                                ShowStatus.show(file.error);
                            } else {
                                added=true;

                                var row = $('<li class=\"template-download fade\">' +
                                    '<div class=\"preview\"><span class=\"fade\"></span></div>' +
                                    ' <span class=\"delete\"><button class=\"btn btn-xs btn-danger\" data-type=\"'+file.delete_type+'\" data-url=\"'+file.delete_url+'\"><img src=\"".SELIO_IMAGES."/icon/cancel.png\"></button><input type=\"checkbox\" value=\"1\" name=\"delete\"></span>' +
                                    (file.error ? '<div class=\"error\"></div>' : '')+'</li>');

                                row.find('.name a').text(file.name);
                                if (file.thumbnail_url) {
                                    row.find('.preview').html('<img  alt=\"'+file.name+'\" data-src=\"'+file.thumbnail_url+'\" src=\"'+file.thumbnail_url+'\">');  
                                }
                                row.find('a').prop('href', file.url);
                                row.find('a').prop('title', file.name);
                                row.find('.delete button')
                                    .attr('data-type', file.delete_type)
                                    .attr('data-url', file.delete_url);
                            }
                            if(added)
                                rows = rows.add(row);
                        });

                        return rows;
                    },
                    start : function() {
                         $('#dropzone-".esc_html($review_repository_id)."').find('.loading_mask').removeClass('hidden');
                    },
                    stop : function() {
                         $('#dropzone-".esc_html($review_repository_id)."').find('.loading_mask').addClass('hidden');
                    },
                    progressall: function (e, data) {
                        // Update the progress bar while files are being uploaded
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress-upload .bar').css(
                            'width',
                            progress + '%'
                        );
                    },
                    destroyed: function (e, data) {
                    ";

                    if(config_item('app_type') != 'demo'):
                    $custom_js .="
                        if(data.success)
                        {}
                        else
                        {
                            ShowStatus.show('"._js(__('Unsuccessful, possible permission problems or file not exists', 'selio'))."');
                        }
                    ";
                    else:
                    $custom_js .="  
                        if(data.success)
                        { }
                        else
                        {
                            ShowStatus.show('"._js(__('Disabled in demo', 'selio'))."');
                        }";
                    endif;
                    $custom_js .="
                        return false;
                    },
                    ";
                    if(config_item('app_type') == 'demo'):

                    $custom_js .="
                    added: function (e, data) {

                        ShowStatus.show('"._js(__('Disabled in demo', 'selio'))."');
                        return false;
                    },";
                    endif;
                    $custom_js .="
                    });

                });
            ";

            if($dropzone):    

            $custom_js.="   

            /* start dropzone  documentation: https://github.com/blueimp/jQuery-File-Upload/wiki/Drop-zone-effects */  
            jQuery(document).ready(function ($) {
                if (typeof(window.FileReader) == 'undefined') {
                    $('#dropzone-".esc_html($review_repository_id)."').hide();
                } else {
                    $(document).on('dragover', function (e) {

                        var dropZone = $('#dropzone-".esc_html($review_repository_id)."'),
                            timeout = window.dropZoneTimeout;
                        if (!timeout) {
                            dropZone.addClass('in');
                        } else {
                            clearTimeout(timeout);
                        }
                        var found = false,
                            node = e.target;
                        do {
                            if (node === dropZone[0]) {
                                found = true;
                                break;
                            }
                            node = node.parentNode;
                        } while (node != null);
                        if (found) {
                            dropZone.addClass('hover');
                        } else {
                            dropZone.removeClass('hover');
                        }
                        window.dropZoneTimeout = setTimeout(function () {
                            window.dropZoneTimeout = null;
                            dropZone.removeClass('in hover');
                        }, 100);
                    });
                    $(document).on('dragover', function (e)
                        {
                        var dropZone = $('.dropzone'),
                            foundDropzone,
                            timeout = window.dropZoneTimeout;
                            if (!timeout)
                            {
                                dropZone.addClass('in');
                            }
                            else
                            {
                                clearTimeout(timeout);
                            }
                            var found = false,
                            node = e.target;
                            do{
                                if ($(node).hasClass('dropzone'))
                                {
                                    found = true;
                                    foundDropzone = $(node);
                                    break;
                                }
                                node = node.parentNode;
                            }while (node != null);
                            dropZone.removeClass('in hover');
                            if (found)
                            {
                                foundDropzone.addClass('hover');
                            }
                            window.dropZoneTimeout = setTimeout(function ()
                            {
                                window.dropZoneTimeout = null;
                                dropZone.removeClass('in hover');
                            }, 100);
                        });
                    $(document).on('drop dragover', function (e) {
                        e.preventDefault();
                    });

                    $('#dropzone-".esc_html($review_repository_id)."').on('click',function(){\$('#fileupload_".esc_html($review_repository_id)." .file-btn').trigger('click')})
                }
            });
            /* end dropzone */   
            ";
            endif;      
            selio_add_into_inline_js( 'selio-custom', $custom_js, true);
            ?>
            <?php else: ?>

                <p class="alert alert-success">
                    <?php echo esc_html__('Login to review', 'selio'); ?>, <a href="<?php echo esc_url(get_permalink(selio_plugin_call::sw_settings('register_page'))); ?>" class="<?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>"><?php echo esc_html__('Open login page', 'selio'); ?></a>
                </p>

            <?php endif; ?>
        </div><!--post-comment-sec end-->
    </div>
    
</div>
   