<?php if(file_exists(APPPATH.'controllers/admin/booking.php')):?>
    <div class="form-group col-sm-6">
        <label><?php echo esc_html__('Fromdate', 'selio'); ?></label>
        <input id="booking_date_from" type="text"  class="form-control" placeholder="<?php echo esc_attr__('Fromdate', 'selio'); ?>" value="<?php echo esc_attr(search_value('date_from')); ?>" />
    </div><!-- /.form-group -->
    
    <div class="form-group col-sm-6">
        <label><?php echo esc_html__('Todate', 'selio'); ?></label>
        <input id="booking_date_to" type="text"  class="form-control" placeholder="<?php echo esc_attr__('Todate', 'selio'); ?>" value="<?php echo esc_attr(search_value('date_to')); ?>" />
    </div><!-- /.form-group -->
<?php endif; ?>