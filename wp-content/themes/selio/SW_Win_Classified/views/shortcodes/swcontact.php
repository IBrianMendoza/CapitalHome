<form id="sw_contactform_<?php echo esc_attr($widget_id)?>" method="post" action="#sw_contactform_<?php echo esc_attr($widget_id)?>" class="form">
    <p class="title"><?php echo esc_html__('Contact with Us', 'selio'); ?></p>
    <?php _form_messages( esc_html__('Message sent successfuly', 'selio'), NULL, esc_html($widget_id)); ?>
    <div class=" first-line">
        <div class="form-group">
            <input class="form-control" id="fullname_<?php echo esc_attr($widget_id)?>" name="fullname" type="text" value="<?php echo esc_attr(_fv('form_widget', 'fullname')); ?>" placeholder="<?php echo esc_attr_e('Full name', 'selio'); ?>" />
        </div><!-- /.form-group -->

        <div class="form-group">
            <input class="form-control" id="email_<?php echo esc_attr($widget_id)?>" name="email" type="text" value="<?php echo esc_attr(_fv('form_widget', 'email')); ?>" placeholder="<?php echo esc_attr_e('Your email', 'selio'); ?>" />
        </div><!-- /.form-group -->
    </div><!-- /.row -->
    
    <div class=" second-line">
        <div class="form-group">
            <input class="form-control" id="phone_<?php echo esc_attr($widget_id)?>" name="phone" type="text" value="<?php echo esc_attr(_fv('form_widget', 'phone')); ?>" placeholder="<?php echo esc_attr_e('Phone number', 'selio'); ?>" />
        </div><!-- /.form-group -->

        <div class="form-group">
            <input class="form-control" id="subject_<?php echo esc_attr($widget_id)?>" name="subject" type="text" value="<?php echo esc_attr(_fv('form_widget', 'subject')); ?>" placeholder="<?php echo esc_attr_e('Subject', 'selio'); ?>" />
        </div><!-- /.form-group -->
    </div><!-- /.row -->

    <div class="form-group">
        <textarea id="message_<?php echo esc_attr($widget_id)?>" name="message" rows="4" class="form-control" placeholder="<?php echo esc_attr_e('Message', 'selio'); ?>"><?php echo esc_attr(_fv('form_widget', 'message')); ?></textarea>
    </div><!-- /.form-group -->
    
    <input class="hidden" id="widget_id" name="widget_id" type="text" value="<?php echo esc_attr($widget_id)?>" />

    <?php esc_viewe(_recaptcha()); ?>

    <div class="form-group">
        <input type="submit" value="<?php echo esc_attr__('Send', 'selio'); ?>" class="btn-classic no-margin">
    </div><!-- /.form-group -->
</form>