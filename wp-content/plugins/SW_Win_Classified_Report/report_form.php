<!-- form itself -->
<form id="popup_report_listing" class="form-horizontal mfp-hide white-popup-block ci sw_widget sw_wrap">
    <div id="popup-form-validation-report" style="display:none;">
        <div class="alert alert-danger" role="alert"></div>
    </div>
    <div class="form-group">
        <label class="control-label" for="inputName"><?php echo __('Full name', 'sw_win'); ?></label>
        <div class="controls">
            <input class="form-control" type="text" name="name" id="inputName" value="" placeholder="<?php echo __('Full name', 'sw_win'); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="inputPhone"><?php echo __('Phone', 'sw_win'); ?></label>
        <div class="controls">
            <input class="form-control" type="text" name="phone" id="inputPhone" value="" placeholder="<?php echo __('Phone', 'sw_win'); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="inputEmail"><?php echo __('Email', 'sw_win'); ?></label>
        <div class="controls">
            <input class="form-control" type="text" name="email" id="inputEmail" value="" placeholder="<?php echo __('Email', 'sw_win'); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="inputEmail"><?php echo __('Message', 'sw_win'); ?></label>
        <div class="controls">
            <textarea name="message" id="message"></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="controls">
            <label class="checkbox">
                <input name="allow_contact" value="1" type="checkbox"> <?php echo __('I allow admin to contact me', 'sw_win'); ?>
            </label>
        </div>
    </div>
    
    <div class="form-group">
        <div class="controls">
            <button id="unhide-report-mask" type="button" class="btn"> <?php echo __('Submit', 'sw_win'); ?> <i class="load-indicator fa fa-spinner fa-spin fa-fw"></i></button>
        </div>
    </div>
</form>


<link rel="stylesheet" href="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/js/magnific-popup/magnific-popup.css">
<script src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/js/magnific-popup/jquery.magnific-popup.js"></script>

<script>
     
    jQuery(document).ready(function($){
        
            $('body').append($('#popup_report_listing').detach());
        
        
            // Popup form Start //
                $('#report_listing.popup-with-form-report').magnificPopup({
                	type: 'inline',
                	preloader: false,
                	focus: '#name',
                                    
                	// When elemened is focused, some mobile browsers in some cases zoom in
                	// It looks not nice, so we disable it:
                	callbacks: {
                		beforeOpen: function() {
                			if($(window).width() < 700) {
                				this.st.focus = false;
                			} else {
                				this.st.focus = '#name';
                			}
                		}
                	}
                });
                
                
                $('#popup_report_listing #unhide-report-mask').click(function(){
                    
                    var data = $('#popup_report_listing').serializeArray();
                    data.push({name: 'listing_id', value: "<?php echo $listing->idlisting; ?>"});
                    data.push({name: 'user_id', value: "<?php echo get_current_user_id(); ?>"});
                    data.push({name: 'page', value: "frontendajax_addreport"});
                    data.push({name: 'action', value: "ci_action"});
                    
                    var load_indicator = $(this).find('.load-indicator');
                    load_indicator.css('display', 'inline-block');
                    
                    // send info to agent
                    $.post("<?php echo admin_url( 'admin-ajax.php' ); ?>", data,
                    function(data){
                        if(data.success)
                        {
                            // Display agent details
                            load_indicator.css('display', 'none');
                            // Close popup
                            $.magnificPopup.instance.close();
                            ShowStatus.show(data.message);
                            $('#popup_report_listing #popup-form-validation-report').hide();
                            $('.report').hide();
                        }
                        else
                        {
                            $('.alert.hidden').css('display', 'block');
                            $('.alert.hidden').css('visibility', 'visible');

                            $('#popup_report_listing #popup-form-validation-report').show();
                            $('#popup_report_listing #popup-form-validation-report>div').html(data.message);
                            
                            //console.log("Data Loaded: " + data);
                        }
                        load_indicator.css('display', 'none');
                    });

                    return false;
                });
                
            // Popup form End //     
    })

</script>