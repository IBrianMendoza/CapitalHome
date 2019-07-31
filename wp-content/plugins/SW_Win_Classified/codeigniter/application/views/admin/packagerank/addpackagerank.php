
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('Edit rank package','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('Add rank package','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Rank package data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php _form_messages(); ?>
  
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
      <div class="form-group <?php _has_error('package_name'); ?> IS-INPUTBOX">
        <label for="input_package_name" class="col-sm-2 control-label"><?php echo __('Package name','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="package_name" value="<?php echo _fv('form_object', 'package_name'); ?>" type="text" id="input_package_name" class="form-control" placeholder="<?php echo __('Package name','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('rank'); ?> IS-INPUTBOX">
        <label for="input_rank" class="col-sm-2 control-label"><?php echo __('Rank','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="rank" value="<?php echo _fv('form_object', 'rank'); ?>" type="text" id="input_rank" class="form-control" placeholder="<?php echo __('Rank','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('package_days'); ?> IS-INPUTBOX">
        <label for="input_package_days" class="col-sm-2 control-label"><?php echo __('Days','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="package_days" value="<?php echo _fv('form_object', 'package_days'); ?>" type="text" id="input_package_days" class="form-control" placeholder="<?php echo __('Days','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('package_price'); ?> IS-INPUTBOX">
        <label for="input_package_price" class="col-sm-2 control-label"><?php echo __('Price','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="package_price" value="<?php echo _fv('form_object', 'package_price'); ?>" type="text" id="input_package_price" class="form-control" placeholder="<?php echo __('Price','sw_win'); ?>"/>
        </div>
      </div>
    
      <div class="form-group <?php _has_error('currency_code'); ?> IS-INPUTBOX">
        <label for="input_currency_code" class="col-sm-2 control-label"><?php echo __('Currency code','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="currency_code" value="<?php echo sw_settings('default_currency'); ?>" type="text" id="input_currency_code" class="form-control" placeholder="<?php echo __('Currency code','sw_win'); ?>" readonly/>
        </div>
      </div>
      
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


<style>



</style>

