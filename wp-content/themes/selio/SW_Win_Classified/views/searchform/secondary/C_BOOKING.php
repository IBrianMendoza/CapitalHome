<?php if(function_exists('sw_pluginsLoaded_calendar')): ?>

<?php

    $f_id = 'booking';
    $placeholder = esc_html__('Booking', 'selio');

    $class_add = $field->class;

?>

<div class="form_field select-item-date <?php echo esc_attr($class_add); ?>" style="<?php echo esc_attr(selio_ch($field->style)); ?>">
    <div class="form-group">
        <div id="date_<?php echo esc_attr($f_id); ?>_from" class="input-group date search-date-group">
            <input value="<?php echo esc_attr(search_value($f_id.'_from')); ?>" name="search_<?php echo esc_attr($f_id); ?>_from" id="search_<?php echo esc_attr($f_id); ?>_from" type="text" class="form-control"  placeholder="<?php echo esc_attr__('Date from', 'selio'); ?>">
            <span class="input-group-addon">
                <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div><!-- /.form-group -->
</div>

<div class="form_field select-item-date  <?php echo esc_attr($class_add); ?>">
    <div class="form-group">
        <div id="date_<?php echo esc_attr($f_id); ?>_to" class="input-group date search-date-group">
            <input value="<?php echo esc_attr(search_value($f_id.'_to')); ?>" name="search_<?php echo esc_attr($f_id); ?>_to" id="search_<?php echo esc_attr($f_id); ?>_to" type="text" class="form-control"  placeholder="<?php echo esc_attr__('Date to', 'selio'); ?>">
            <span class="input-group-addon">
                 <span class="fa fa-calendar"></span>
            </span>
        </div>
    </div><!-- /.form-group -->
</div>

<script>

jQuery(document).ready(function() {
    if (jQuery('#date_<?php echo esc_attr($f_id); ?>_from').length) {
        jQuery('#date_<?php echo esc_attr($f_id); ?>_from').datetimepicker({
            format: '<?php echo esc_html(config_db_item('date_format_js')); ?>',
            useCurrent: false,
            minDate: '<?php echo esc_attr(date('Y-m-d H:i:s')); ?>',
            //hour : '12',
            stepping: 30,
            debug: false
        });
    }

    if (jQuery('#date_<?php echo esc_attr($f_id); ?>_to').length) {
        jQuery('#date_<?php echo esc_attr($f_id); ?>_to').datetimepicker({
            format: '<?php echo esc_html(config_db_item('date_format_js')); ?>',
            useCurrent: false,
            //hour : '12',
            stepping: 30,
            debug: false
        });

        jQuery('#date_<?php echo esc_attr($f_id); ?>_from').on("dp.change", function (e) 
        {
            jQuery('#date_<?php echo esc_attr($f_id); ?>_to').data("DateTimePicker").minDate(e.date);
            jQuery('#date_<?php echo esc_attr($f_id); ?>_to').datetimepicker('show');
            jQuery(this).datetimepicker('hide');
        });
        jQuery('#date_<?php echo esc_attr($f_id); ?>_to').on("dp.change", function (e) 
        {
            jQuery('#date_<?php echo esc_attr($f_id); ?>_from').data("DateTimePicker").maxDate(e.date);
            jQuery(this).datetimepicker('hide');
        });

    }
});

</script>

<?php
wp_enqueue_script( 'datetime-picker-moment', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/js/moment-with-locales.js', false, false, false );
wp_enqueue_script('bootstrap-datetime-picker', SELIO_JS . '/bootstrap-datetimepicker.min.js', false, false, true);
wp_enqueue_style( 'datetime-picker-css', plugins_url( SW_WIN_SLUG.'/assets' ) . '/js/datetime-picker/css/bootstrap-datetimepicker.css' );
?>

<?PHP else: ?>
<?php echo esc_html__('BOOKING PLUGIN MISSING', 'selio'); ?>
<?php endif; ?>