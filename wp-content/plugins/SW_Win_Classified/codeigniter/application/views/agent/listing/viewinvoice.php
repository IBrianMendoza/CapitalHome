
<?php if(isset($_GET['id'])): ?>
<h1><?php echo __('View invoice','sw_win'); ?> </h1>
<?php else: ?>
<h1><?php echo __('View invoice','sw_win'); ?> </h1>
<?php endif; ?>

<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><?php echo __('Invoice data','sw_win'); ?></h3>
  </div>
  <div class="panel-body">
  
    <?php 
    
        $CI =& get_instance();
    
    ?>
    
    <?php if(isset($_GET['paid'])): ?>
    <div class="alert alert-success" role="alert"><?php echo __('Thank you very much on payment!','sw_win').' '.__('Admin will check bank account and activate related services','sw_win'); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_GET['listingsaved'])): ?>
    <div class="alert alert-success" role="alert"><?php echo __('Thank you on submission, please pay for package.','sw_win'); ?></div>
    <?php endif; ?>
    
  
    <form action="" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
      <div class="form-group <?php _has_error('invoicenum'); ?> IS-INPUTBOX">
        <label for="input_invoicenum" class="col-sm-2 control-label"><?php echo __('Num','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'invoicenum'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_created'); ?> IS-INPUTBOX">
        <label for="input_date_created" class="col-sm-2 control-label"><?php echo __('Date created','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'date_created'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('date_paid'); ?> IS-INPUTBOX">
        <label for="input_date_paid" class="col-sm-2 control-label"><?php echo __('Date paid','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'date_paid', 'TEXT', '-', '-'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('user_id'); ?> IS-INPUTBOX">
        <label for="input_user_id" class="col-sm-2 control-label"><?php echo __('User','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _ch($users[_fv('form_object', 'user_id')]); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('listing_id'); ?> IS-INPUTBOX">
        <label for="input_listing_id" class="col-sm-2 control-label"><?php echo __('Listing','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _ch($listings[_fv('form_object', 'listing_id')]); ?></p>
        </div>
      </div>

      <div class="form-group <?php _has_error('subscription_id'); ?> IS-INPUTBOX">
        <label for="input_subscription_id" class="col-sm-2 control-label"><?php echo __('Subscription','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _ch($subscriptions[_fv('form_object', 'subscription_id')]); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('is_activated'); ?>">
        <label for="input_is_activated" class="col-sm-2 control-label"><?php echo __('Activated','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'is_activated', 'TEXT', '', '')==''?
                                                '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>':
                                                '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('vat_percentage'); ?> IS-INPUTBOX">
        <label for="input_vat_percentage" class="col-sm-2 control-label"><?php echo __('VAT percentage','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'vat_percentage'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('price'); ?> IS-INPUTBOX">
        <label for="input_price" class="col-sm-2 control-label"><?php echo __('Price','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'price'); ?></p>
        </div>
      </div>
    
      <div class="form-group <?php _has_error('currency_code'); ?> IS-INPUTBOX">
        <label for="input_currency_code" class="col-sm-2 control-label"><?php echo __('Currency code','sw_win'); ?></label>
        <div class="col-sm-10">
          <p class="input-content"><?php echo _fv('form_object', 'currency_code'); ?></p>
        </div>
      </div>

      <div class="form-group <?php _has_error('paid_via'); ?> IS-INPUTBOX">
        <label for="input_paid_via" class="col-sm-2 control-label"><?php echo __('Paid via','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _ch($this->invoice_m->paid_via[_fv('form_object', 'paid_via')]); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('company_details'); ?>">
        <label for="input_company_details" class="col-sm-2 control-label"><?php echo __('Company details','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _fv('form_object', 'company_details', 'TEXT', '-', '-'); ?></p>
        </div>
      </div>
      
      <div class="form-group <?php _has_error('note'); ?>">
        <label for="input_note" class="col-sm-2 control-label"><?php echo __('Note','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo _fv('form_object', 'note', 'TEXT', '-', '-'); ?></p>
        </div>
      </div>
      
      <?php $json_data = json_decode(_fv('form_object', 'data_json')); ?> 
      
      <?php if(isset($json_data->item->package_name)): ?>
      
      <div class="form-group ">
        <label for="input_note" class="col-sm-2 control-label"><?php echo __('Package','sw_win'); ?></label>
        <div class="col-sm-10">
            <p class="input-content"><?php echo $json_data->item->package_name; ?></p>
        </div>
      </div>
      
      <?php endif; ?>
      </div>
      </div>
        
      <hr />
      
      <?php if(_fv('form_object', 'is_activated', 'TEXT', '', '')=='' && _fv('form_object', 'is_disabled', 'TEXT', '', '')==''): ?>
      <div class="form-group ">
        <label for="input_note" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <!-- Single button -->
            <div class="btn-group">
              <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo __('Pay now','sw_win'); ?> <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">
                <?php if(sw_settings('woocommerce_only_pay') == 1): ?>

                <?php else: ?>

                  <li><a href="<?php echo admin_url("admin.php?page=ownlisting_invoices&function=bankpayment&id=".$_GET['id']);?>"><?php echo __('Bank Payment','sw_win'); ?></a></li>

                  <?php if(sw_settings('paypal_email') != ''): ?>
                  <li><a href="<?php echo admin_url("admin.php?page=ownlisting_invoices&function=dopayment&provider=paypal&id=".$_GET['id']);?>" target="_blank"><?php echo __('PayPal','sw_win'); ?></a></li>
                  <?php endif; ?>

                <?php endif; ?>

                  <?php if ( class_exists( 'WooCommerce' ) ): ?>
                  <li><a href="<?php echo admin_url("admin.php?page=ownlisting_invoices&function=woopayment&provider=woo&id=".$_GET['id']);?>" target="_blank"><?php echo __('Webshop Cart','sw_win'); ?></a></li>
                  <?php endif; ?>

                
              </ul>
            </div>
        </div>
      </div>
      <?php elseif(_fv('form_object', 'is_disabled', 'TEXT', '', '')=='1'): ?>
        <div class="alert alert-warning" role="alert"><?php echo __('Invoice is disabled', 'sw_win'); ?></div>
      <?php else: ?>
        <div class="alert alert-success" role="alert"><?php echo __('Invoice is activated', 'sw_win'); ?></div>
      <?php endif; ?>

    </form>

  </div>
</div>


</div>

<?php

wp_enqueue_script( 'jquery' );
wp_enqueue_script('jquery-ui-core', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-widget', false, array('jquery'), false, false);
wp_enqueue_script('jquery-ui-sortable', false, array('jquery'), false, false);

wp_enqueue_script( 'datetime-picker-moment' );
wp_enqueue_script( 'datetime-picker-bootstrap' );

wp_enqueue_style( 'datetime-picker-css');

?>

<script>


jQuery(document).ready(function($) {
    $('.datetimepicker_1').datetimepicker({format:'YYYY-MM-DD HH:mm:ss', useCurrent:false});
});

</script>


<style>

p.input-content
{
    padding:5px 5px 0px 5px;
    margin:0px;
}


</style>

