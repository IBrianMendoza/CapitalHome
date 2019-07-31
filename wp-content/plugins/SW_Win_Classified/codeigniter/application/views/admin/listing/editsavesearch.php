
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit review','sw_win'); ?> </h1>
<?php else: ?>
<?php exit('Add review is not supported'); ?>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Review data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
      
      <div class="form-group <?php _has_error('user_id'); ?>">
        <label for="input_user_id" class="col-sm-2 control-label"><?php echo __('User','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="user_id" value="<?php echo _fv('form_object', 'user_id'); ?>" type="text" id="input_user_id" class="form-control" readonly="" placeholder="<?php echo __('User','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('lang_id'); ?>">
        <label for="input_lang_id" class="col-sm-2 control-label"><?php echo __('Lang id','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="lang_id" value="<?php echo _fv('form_object', 'lang_id'); ?>" type="text" id="input_lang_id" class="form-control" readonly="" placeholder="<?php echo __('Lang id','sw_win'); ?>"/>
        </div>
      </div>  
          
      <div class="form-group <?php _has_error('date_submit'); ?>">
        <label for="input_date_submit" class="col-sm-2 control-label"><?php echo __('Date submit','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_submit" value="<?php echo _fv('form_object', 'date_submit'); ?>" type="text" id="input_date_submit" class="form-control" readonly="" placeholder="<?php echo __('Date submit','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_modified'); ?>">
        <label for="input_date_modified" class="col-sm-2 control-label"><?php echo __('Date modified','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_modified" value="<?php echo _fv('form_object', 'date_modified'); ?>" type="text" id="input_date_modified" class="form-control" readonly="" placeholder="<?php echo __('Date modified','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_last_informed'); ?>">
        <label for="input_date_last_informed" class="col-sm-2 control-label"><?php echo __('Date last informed','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_last_informed" value="<?php echo _fv('form_object', 'date_last_informed'); ?>" type="text" id="input_date_last_informed" class="form-control" readonly="" placeholder="<?php echo __('Date last informed','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('parameters'); ?>">
        <label for="input_parameters" class="col-sm-2 control-label"><?php echo __('Parameters','sw_win'); ?></label>
        <div class="col-sm-10">
            <textarea name="parameters" id="input_parameters" class="form-control" readonly="" placeholder="<?php echo __('Parameters','sw_win'); ?>"><?php echo _fv('form_object', 'parameters'); ?></textarea>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_activated'); ?>">
        <label for="input_is_activated" class="col-sm-2 control-label"><?php echo __('Is activated','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_activated" value="1" type="checkbox" <?php echo _fv('form_object', 'is_activated', 'CHECKBOX'); ?>/>
        </div>
      </div>

      <hr />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
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

