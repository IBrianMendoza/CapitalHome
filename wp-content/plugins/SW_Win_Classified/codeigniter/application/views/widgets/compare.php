<?php

    $listing_id = '';
    $listing_title = '';

    $CI =& get_instance();

    if(isset($this->data['listing']))
    {
        $obj = json_decode($this->data['listing']->json_object);
        
        if(isset($obj->{'field_10'}))
            if(!empty($obj->{'field_10'}))
            {
                $listing_id = $this->data['listing']->idlisting;
                $listing_title = $obj->{'field_10'};
            }
    }
    
    if(sw_settings('compare_page') == '')
    {
        echo sw_notice(__('Please define compare page in Listings->Settings', 'sw_win'));
        return;
    }

?>

<div class="">
    <div class="widget-content">
        <form method="post" action="<?php echo get_permalink(sw_settings('compare_page')); ?>" id="listing_compare">
        
            <div class="form-group  col-sm-12" style="" id="listing_add_to_compare">
            <button type="button" class="btn btn-primary btn-inversed btn-block"><?php echo __('Add for comparison', 'sw_win'); ?></button>
            </div>
            
            <div class="form-group col-sm-12" style="display:none;" id="listing_remove_from_compare">
            <button type="button" class="btn btn-primary btn-inversed btn-block"><?php echo __('Remove from comparison', 'sw_win'); ?></button>
            </div>
            
            <br style="clear: both;" />
            <ul id="listing_compare_list">
            </ul>
            
            <div class="form-group  col-sm-12" style="display:none;" id="listing_compare_button">
            <button type="submit" class=" btn btn-primary btn-inversed btn-block"><?php echo __('Compare', 'sw_win'); ?></button>
            </div>
            
        </form>
    </div><!-- /.widget-content -->
</div><!-- /.widget -->  

<script>

jQuery(document).ready(function($) {

//    example to clear cookie
//    sw_setCookie('sw_compare_list', '');
//    return;

    var list_str = sw_getCookie('sw_compare_list');
    
    if(list_str != '')
    {   
        //console.log(list_str);
        
        l_array = list_str.split("|,|");
        
        $.each( l_array, function( i, l ){
            
            i_array = l.split("|:|");
            
            $('ul#listing_compare_list').append('<li id="compare_'+i_array[0]+'" rel="'+i_array[0]+'"><span class="label label-primary">'+i_array[1]+'</span></li>');
        });
        
        if($('#compare_<?php echo $listing_id;?>').length>0)
        {
           $('#listing_add_to_compare').hide();
           $('#listing_remove_from_compare').show();
        }
        
        if(l_array.length > 1)
        {
            $('#listing_compare_button').show();
        }
    }
    
	$('#listing_add_to_compare button').on('click', function(e) {
		e.preventDefault();
	    
        if($('#compare_<?php echo $listing_id;?>').length==0)
        {
           
           // Remove first on list if 4 already exists
           if($('ul#listing_compare_list li').length > 3)
           {
                $('ul#listing_compare_list li:first').remove();
           }

           $('ul#listing_compare_list').append('<li id="compare_<?php echo $listing_id;?>" rel="<?php echo $listing_id;?>"><span class="label label-primary"><?php echo '#'.$listing_id.', '.$listing_title; ?></span></li>');
           $('#listing_add_to_compare').hide();
           $('#listing_remove_from_compare').show();
           
           sw_save_compare_list();
        }
        
        return false;
    });
    
	$('#listing_remove_from_compare button').on('click', function(e) {
		e.preventDefault();
	   
       $('#compare_<?php echo $listing_id;?>').remove();
       
       $('#listing_remove_from_compare').hide();
       $('#listing_add_to_compare').show();
       
       sw_save_compare_list();
       
       return false;
    });	
    
    
    function sw_save_compare_list()
    {
        var list_str = '';
        
        $('ul#listing_compare_list li').each(function(){
            if($(this).attr('rel'))
                list_str+=$(this).attr('rel')+'|:|'+$(this).find('span').html()+'|,|';
        });
        
        if(list_str != '')
            list_str = list_str.substr(0,list_str.length-3);
        
        //console.log(list_str);
        
        sw_setCookie('sw_compare_list', list_str);
        
        $('#listing_compare_button').hide();
        if($('ul#listing_compare_list li').length > 1)
        {
            $('#listing_compare_button').show();
        }
    }

});


</script>







