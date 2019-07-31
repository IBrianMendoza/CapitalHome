
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
      
      <div class="form-group <?php _has_error('listing_id'); ?>">
        <label for="input_listing_id" class="col-sm-2 control-label"><?php echo __('Listing','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="listing_id" value="<?php echo _fv('form_object', 'listing_id'); ?>" type="text" id="input_listing_id" class="form-control" readonly="" placeholder="<?php echo __('Listing','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('user_id'); ?>">
        <label for="input_user_id" class="col-sm-2 control-label"><?php echo __('User','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="user_id" value="<?php echo _fv('form_object', 'user_id'); ?>" type="text" id="input_user_id" class="form-control" readonly="" placeholder="<?php echo __('User','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_submit'); ?>">
        <label for="input_date_submit" class="col-sm-2 control-label"><?php echo __('Date submit','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_submit" value="<?php echo _fv('form_object', 'date_submit'); ?>" type="text" id="input_date_submit" class="form-control" readonly="" placeholder="<?php echo __('Date submit','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('stars'); ?>">
        <label for="input_starsr" class="col-sm-2 control-label"><?php echo __('Stars','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="stars" value="<?php echo _fv('form_object', 'stars'); ?>" type="text" id="input_stars" class="form-control" placeholder="<?php echo __('Stars','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('message'); ?>">
        <label for="input_message" class="col-sm-2 control-label"><?php echo __('Message','sw_win'); ?></label>
        <div class="col-sm-10">
            <textarea name="message" id="input_message" class="form-control" placeholder="<?php echo __('Message','sw_win'); ?>"><?php echo _fv('form_object', 'message'); ?></textarea>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_visible'); ?>">
        <label for="input_is_visible" class="col-sm-2 control-label"><?php echo __('Is visible','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="is_visible" value="1" type="checkbox" <?php echo _fv('form_object', 'is_visible', 'CHECKBOX'); ?>/>
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

