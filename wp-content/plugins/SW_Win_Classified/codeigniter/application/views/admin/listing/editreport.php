
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Read report','sw_win'); ?> </h1>
<?php else: ?>
<?php exit('Add report is not supported'); ?>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Report data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
      
      <div class="form-group <?php _has_error('listing_id'); ?>">
        <label for="input_listing_id" class="col-sm-2 control-label"><?php echo __('Listing','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="listing_id" value="<?php echo _fv('form_object', 'listing_id'); ?>" type="text" id="input_listing_id" class="form-control" readonly="" placeholder="<?php echo __('Listing','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_submit'); ?>">
        <label for="input_date_submit" class="col-sm-2 control-label"><?php echo __('Date sent','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_submit" value="<?php echo _fv('form_object', 'date_submit'); ?>" type="text" id="input_date_submit" class="form-control" readonly="" placeholder="<?php echo __('Date sent','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('name'); ?>">
        <label for="input_name" class="col-sm-2 control-label"><?php echo __('Full name','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="name" value="<?php echo _fv('form_object', 'name'); ?>" type="text" id="input_name" class="form-control" readonly="" placeholder="<?php echo __('name','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('phone'); ?>">
        <label for="input_phone" class="col-sm-2 control-label"><?php echo __('Phone','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="phone" value="<?php echo _fv('form_object', 'phone'); ?>" type="text" id="input_phone" class="form-control" readonly="" placeholder="<?php echo __('phone','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('email'); ?>">
        <label for="input_email" class="col-sm-2 control-label"><?php echo __('Email sender','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="email" value="<?php echo _fv('form_object', 'email'); ?>" type="text" id="input_email" class="form-control" readonly="" placeholder="<?php echo __('Email sender','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('message'); ?>">
        <label for="input_message" class="col-sm-2 control-label"><?php echo __('Message','sw_win'); ?></label>
        <div class="col-sm-10">
            <textarea name="message" id="input_message" class="form-control" readonly="" placeholder="<?php echo __('Message','sw_win'); ?>"><?php echo _fv('form_object', 'message'); ?></textarea>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('allow_contact'); ?>">
        <label for="input_allow_contact" class="col-sm-2 control-label"><?php echo __('Allow contact','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="allow_contact" value="1" readonly="" disabled="" type="checkbox" <?php echo _fv('form_object', 'allow_contact', 'CHECKBOX'); ?>/>
        </div>
      </div>

      <hr />
      <div class="form-group hidden">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <?php if(isset($form_object)):?>
          <?php
          $CI = &get_instance();
          $CI->load->model('listing_m');
          $form_object = $this->listing_m->get_lang(_fv('form_object', 'listing_id'), sw_default_language_id());
          ?>
        <?php if(_fv('form_object', 'email')):?>
            <a href="<?php echo esc_url('mailto:'._fv('form_object', 'email'));?>" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-envelope"></i> <?php echo __('Write email', 'sw_win');?></a>
        <?php endif;?>
          <a href="<?php echo esc_url(listing_url($form_object));?>" target="_blank" class="btn btn-default add_button"><?php echo __('Preview listing', 'sw_win');?></a>
          <?php
            $url = admin_url("admin.php?page=listing_manage&function=remlisting&id="._fv('form_object', 'listing_id'));
            echo anchor($url, '<i class="glyphicon glyphicon-remove"></i> '.__('Delete Listing', 'sw_win'), array('class'=>'btn btn-danger delete_button', 'onclick'=>"return confirm('".__('Are you sure?', 'sw_win')."')"));
          ?>
          <?php
          ?>
      <?php endif;?>
      </div>
    </div>
  </div>
</div>

</div>
    
    
<script>

/* 
    For custom field type elements, hide/show feature
    
    Example usage:
    css class: NOT-TREE, IS-TREE
    <div class="form-group NOT-TREE">
    <div class="form-group IS-TREE">
*/

jQuery(document).ready(function($) {
    reset_field_visibility();
    
    var field_type = $("select[name=type]").val();
    $(".NOT-"+field_type).hide();
    $(".IS-"+field_type).show();
        
    $("select[name=type]").change(function(){
        reset_field_visibility();
        
        var field_type = $(this).val();
        $(".NOT-"+field_type).hide();
        $(".IS-"+field_type).show();
    });
    
    function reset_field_visibility()
    {
        $("select[name=type] option" ).each(function( index ) {
            var field_type = $( this ).attr('value');
            
            $(".NOT-"+field_type).show();
            $(".IS-"+field_type).hide();
        });
    }

});

</script>

