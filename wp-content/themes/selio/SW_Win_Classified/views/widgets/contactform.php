<?php 
if(stripos($widget_name, 'sidebar')===FALSE): ?>

<div class="widget-box widget-section box-container widget-contactform">
    <div class="widget-header text-uppercase">
        <?php echo esc_html__('Enquiry form', 'selio'); ?>
    </div>
    
    
    <form id="sw_contactform_<?php echo esc_attr($widget_id)?>" method="post" action="#sw_contactform_<?php echo esc_attr($widget_id)?>" class="form-additional">
        <?php _form_messages( esc_html__('Message sent successfuly', 'selio'), NULL, esc_html($widget_id)); ?>       
        <div class="row">
            <div class="col-sm-6">
                <div class="form-field">
                    <input class="" id="fullname_<?php echo esc_attr($widget_id)?>" name="fullname" type="text" value="<?php echo esc_attr(_fv('form_widget', 'fullname')); ?>" placeholder="<?php echo esc_attr_e('Full name', 'selio'); ?>" />
                </div>
                <div class="form-field">
                    <input class="" id="email_<?php echo esc_attr($widget_id)?>" name="email" type="text" value="<?php echo esc_attr(_fv('form_widget', 'email')); ?>" placeholder="<?php echo esc_attr_e('Your email', 'selio'); ?>" />
                </div>
                <div class="form-field">
                    <input class="" id="phone_<?php echo esc_attr($widget_id)?>" name="phone" type="text" value="<?php echo esc_attr(_fv('form_widget', 'phone')); ?>" placeholder="<?php echo esc_attr_e('Phone number', 'selio'); ?>" />
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-field">
                    <textarea id="message_<?php echo esc_attr($widget_id)?>" name="message" placeholder="<?php echo esc_attr_e('Message', 'selio'); ?>" rows="4" class=""><?php echo esc_attr(_fv('form_widget', 'message')); ?></textarea>
                </div>
            </div>
        </div>
        
        <input class="hidden" id="widget_id" name="widget_id" type="text" value="<?php echo esc_attr($widget_id)?>" />
        
        <div class="row">
            <div class="col-sm-6">
                <div class="control-group form-field control-group-captcha">
                    <?php esc_view(_recaptcha(strpos($widget_name, 'sidebar')!==FALSE)); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-field clearfix">
                    <button class="btn btn-primary color-primary pull-right" type="submit"><?php echo esc_html__('Send', 'selio'); ?></button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php else: ?>

<div class="contact-agent widget-form" id="form">
    <form id="sw_contactform_<?php echo esc_attr($widget_id)?>" method="post" action="#sw_contactform_<?php echo esc_attr($widget_id)?>" class="box">
        <div class="box-container widget-body">

            <?php _form_messages( esc_html__('Message sent successfuly', 'selio'), NULL, esc_html($widget_id)); ?>

                <?php if(function_exists('sw_win_load_ci_function_calendar') && 
             sw_is_page(selio_plugin_call::sw_settings('listing_preview_page'))): ?>

    <?php
    
        wp_enqueue_script( 'datetime-picker-moment' );
        wp_enqueue_script( 'bootstrap-datetimepicker' );
        wp_enqueue_style( 'datetime-picker-css' );
    
        $CI = &get_instance();
        $CI->load->model('calendar_m');
        $listing = $CI->data['listing'];
        
        $readonly ='';
        if( !is_user_logged_in())
            $readonly ='readonly';
        
        $calendar = $CI->calendar_m->get_by(array('sw_calendar.listing_id'=>$listing->idlisting), true);
        if(selio_plugin_call::sw_count($calendar)):

    ?>
    
    <?php if( !is_user_logged_in()):?>
    <div class="alert alert-info">
        <?php echo esc_html__('For booking, please', 'selio'); ?> <a href="<?php echo esc_url(get_permalink(selio_plugin_call::sw_settings('register_page'))); ?>" class="<?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>"><?php echo esc_html__('login', 'selio'); ?></a>
    </div>
    <?php endif;?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-field <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>">
                <div id="datetimepicker-<?php echo esc_attr($widget_id)?>" class="input-group date">
                    <input value="<?php esc_viewe(_fv('form_widget', 'date_from')); ?>" id="date_from_<?php echo esc_attr($widget_id)?>" name="date_from" type="text" class="" <?php echo esc_html($readonly);?> placeholder="<?php echo esc_attr__('Date from', 'selio'); ?>">
                    <span class="input-group-addon">
                        <i class="la la-calendar"></i>
                    </span>
                </div>
            </div><!-- /.form-field -->
        </div>
        <div class="col-sm-6">
            <div class="form-field <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>">
                <div id="datetimepicker-<?php echo esc_attr($widget_id)?>-2" class="input-group date">
                <input value="<?php esc_viewe(_fv('form_widget', 'date_to')); ?>" id="date_to_<?php echo esc_attr($widget_id)?>" name="date_to" type="text" class="" <?php echo esc_html($readonly);?> placeholder="<?php echo esc_attr__('Date to', 'selio'); ?>">
                <span class="input-group-addon">
                    <i class="la la-calendar"></i>
                </span>
            </div>
            </div><!-- /.form-field -->
        </div>
        <div class="col-sm-12">
            <div class="form-field <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>">
                <?php esc_viewe(form_dropdown('guests_number', array(''=>'Select', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5+'=>'5+'), _fv('form_widget', 'guests_number', 'TEXT','1'), 'class=""'))?>
            </div><!-- /.form-field -->
        </div>
    </div><!-- /.row -->

<?php

$CI->load->model('rates_m');
$CI->load->model('reservation_m');


$listing = $CI->data['listing'];
$dates_enabled = $CI->reservation_m->get_enabled_dates($listing->idlisting);

?>
<?php
$custom_js ='';

if( !is_user_logged_in()){
    $custom_js .= "
    ";
}

$custom_js .= "
    jQuery(document).ready(function() {
        if (jQuery('#datetimepicker-".esc_html($widget_id)."').length) {
            var datetimepicker_first = jQuery('#datetimepicker-". esc_html($widget_id)."').datetimepicker({";
                
                if($calendar->calendar_type == 'DAY'):
                $custom_js .= "format: '".esc_view(substr(config_db_item('date_format_js'), 0, strpos(config_db_item('date_format_js'), ' ')))."',";
                else: 
                $custom_js .= "format: '".esc_html(config_db_item('date_format_js'))."',";
                endif;
                $custom_js .= "
                useCurrent: 'hour',
                stepping: 30,
            });
        }
        
        if (jQuery('.btn-book.btn-open-book').length) {
            jQuery('.btn-book.btn-open-book').on('click', function(e){
                e.preventDefault();
                datetimepicker_first.datetimepicker('show');
            })
        }

        if (jQuery('#datetimepicker-".esc_html($widget_id)."-2').length) {
            jQuery('#datetimepicker-".esc_html($widget_id)."-2').datetimepicker({";
                if($calendar->calendar_type == 'DAY'):
                $custom_js .= "format: '".esc_html(substr(config_db_item('date_format_js'), 0, strpos(config_db_item('date_format_js'), ' ')))."',";
                else: 
                $custom_js .= "format: '".esc_html(config_db_item('date_format_js'))."',";
                endif;
                $custom_js .= "
                useCurrent: 'hour',
                enabledDates: [".esc_view(join(',', $dates_enabled))."],
                stepping: 30,
            });

            jQuery('#datetimepicker-".esc_html($widget_id)."').on('dp.change', function (e) {
                jQuery('#datetimepicker-".esc_html($widget_id)."-2').data('DateTimePicker').minDate(e.date);
            });
            jQuery('#datetimepicker-".esc_html($widget_id)."-2').on('dp.change', function (e) {
                jQuery('#datetimepicker-".esc_html($widget_id)."').data('DateTimePicker').maxDate(e.date);
            });

        }
    });
";
selio_add_into_inline_js( 'selio-custom', $custom_js, true);
?>

    <?php endif;endif; ?>
            
            
            <div class="form-field">
                <input class="" id="fullname_<?php echo esc_attr($widget_id)?>" name="fullname" type="text" value="<?php echo esc_attr(_fv('form_widget', 'fullname')); ?>" placeholder="<?php echo esc_attr_e('Full name', 'selio'); ?>" />
            </div>
            <div class="form-field">
                <input class="" id="email_<?php echo esc_attr($widget_id)?>" name="email" type="text" value="<?php echo esc_attr(_fv('form_widget', 'email')); ?>" placeholder="<?php echo esc_attr_e('Your email', 'selio'); ?>" />
            </div>
            <div class="form-field">
                <input class="" id="phone_<?php echo esc_attr($widget_id)?>" name="phone" type="text" value="<?php echo esc_attr(_fv('form_widget', 'phone')); ?>" placeholder="<?php echo esc_attr_e('Phone number', 'selio'); ?>" />
            </div>
            <div class="form-field">
                <input class="" id="subject_<?php echo esc_attr($widget_id)?>" name="subject" type="text" value="<?php echo esc_attr(_fv('form_widget', 'subject')); ?>" placeholder="<?php echo esc_attr_e('Subject', 'selio'); ?>" />
            </div>
            <div class="form-field">
                <textarea id="message_<?php echo esc_attr($widget_id)?>" name="message" rows="4" class="" placeholder="<?php echo esc_attr_e('Message', 'selio'); ?>"><?php echo esc_attr(_fv('form_widget', 'message')); ?></textarea>
            </div>

            <input class="hidden" id="widget_id" name="widget_id" type="text" value="<?php echo esc_attr($widget_id)?>" />

            <?php esc_view(_recaptcha(strpos($widget_name, 'sidebar')!==FALSE)); ?>

            <div class="form-field">
                <button type="submit" class="btn2"><?php echo esc_attr_e('Send', 'selio'); ?></button>
            </div>
        </div>
    </form>
</div>
<?php endif; ?>