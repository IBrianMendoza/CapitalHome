<?php
$lang_savesearch = __('Savesearch', 'sw_win');
if(isset($atts['lang_Savesearch']))
    $lang_savesearch = $atts['lang_Savesearch'];

?>
<div class="search-widget bootstrap-wrapper">
    <button id="search-save" type="button" class="btn btn-primary color-primary pull-right"><i class="fa fa-bookmark" style="margin-top: 4px; margin-right: 3px;"></i><?php echo $lang_savesearch;?></button>
</div>

<script>

jQuery(document).ready(function($) {
    
	$('button#search-save').on('click', function(e) {
		e.preventDefault();

        <?php if(!is_user_logged_in()): ?>
        
        // If user is not logged in

        ShowStatus.show('<?php echo_js(__('Please login to save search', 'sw_win')); ?>');
        
        <?php else: ?>

        // Try to save search
        
        sw_save_search();

        <?php endif; ?>
        
        return false;
    });
    
    function sw_save_search()
    {
        var data = {};
        
        // Add custom data values, automatically by fields inside search-form
        $('form.sw_search_primary input, form.sw_search_primary select, '+
          'form.sw_search_secondary input, form.sw_search_secondary select').each(function (i) {
            
            if($(this).attr('type') == 'checkbox')
            {
                if ($(this).attr('checked'))
                {
                    data[$(this).attr('name')] = $(this).val();
                }
            }
            else if($(this).val() != '' && $(this).val() != 0&& $(this).val() != null)
            {
                data[$(this).attr('name')] = $(this).val();
            }
            
        });

        // Check if search changes exists
        if(jQuery.isEmptyObject(data))
        {
            ShowStatus.show('<?php echo_js(__('Search criteria not selected', 'sw_win')); ?>');
        }
        else
        {
            // Save search via ajax request
            $.extend( data, {
                "page": 'frontendajax_savesearch',
                "action": 'ci_action'
            });
            
            $(".fa-ajax-indicator").show();
            $.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', data,
                function(data){
                    $(".fa-ajax-indicator").hide();
                    
                    ShowStatus.show(data.message);
                }
            , "json");
        }
        
    }

});

</script>


