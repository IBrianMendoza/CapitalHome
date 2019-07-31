<?php

/*
# =================================================
# Admin special form elements functions.
# =================================================
*/

function local_dropdown($field_id, $field_name, $options=array(''=>'Not selected'), $selected_index=NULL, $extra='')
{
    echo '<select id="'.esc_attr($field_id).'" name="'.esc_attr($field_name).'" '.esc_attr($extra).'>';
    
    foreach($options as $key=>$val)
    {
        $selected='';
        if($selected_index == $key)
            $selected = ' selected';
        
        echo '<option value="'.esc_attr($key).'"'.esc_attr($selected).'>'.esc_attr($val).'</option>';
    }

    echo '</select>';
}


function selio_upload_media_element($elem_id, $field_id, $field_name, $your_img_id)
{
    static $media_element_counter = 0;
    
    $media_element_counter++;
    
    $elem_id.='_'.$media_element_counter;
    
    wp_enqueue_media();

    ?>
    <div id="<?php echo esc_attr($elem_id); ?>meta-box-id" class="postbox postbox-upload">
    <?php
    // Get WordPress' media upload URL
    $upload_link = '#';
    
    // Get the image src
    $your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );

    // For convenience, see if the array is valid
    $you_have_img = is_array( $your_img_src );
    
    $field_name_attr = substr(strrchr($field_name, "["), 1);
    $field_name_attr = str_replace(']', '', $field_name_attr);
    ?>
    
    <!-- Your image container, which can be manipulated with js -->
    <div class="custom-img-container">
        <?php if ( $you_have_img ) : ?>
            <img src="<?php echo esc_url($your_img_src[0]); ?>" alt="..." style="max-width:100%;" />
        <?php endif; ?>
    </div>
    
    <!-- Your add & remove image links -->
    <p class="hide-if-no-js">
        <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
           href="<?php echo esc_url($upload_link) ?>">
            <?php echo esc_html__('Set custom image','selio') ?>
        </a>
        <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
          href="#">
            <?php echo esc_html__('Remove this image','selio') ?>
        </a>
    </p>
    
    <!-- A hidden input to set and post the chosen image id -->
    <input class="logo_image_id" data-fieldname="<?php echo esc_attr($field_name_attr);?>" type="hidden" id="<?php echo esc_attr($field_id); ?>" name="<?php echo esc_attr($field_name); ?>" value="<?php echo esc_attr($your_img_id); ?>" />
    </div>
    
    <?php
    $custom_js ='';
    $custom_js .=" jQuery(function($) {
                        /* fix widget live */
                        if($('#".esc_html($elem_id)."meta-box-id.postbox').length>0) {
                            var elem = $('#".esc_html($elem_id)."meta-box-id.postbox');//.eq(-2);

                            var widget = elem.closest('.widget');
                            var id = widget.attr('id');
                            var name = elem.find('input').attr('data-fieldname')
                            var prefix =  id+'_'+name;
                            
                            elem.attr('id', prefix+'_".esc_html($elem_id)."')
                            elem.wpMediaElement();
                        }else {
                            //$('#".esc_html($elem_id)."meta-box-id.postbox').wpMediaElement();
                        }

                    });";
    
    selio_helpers_add_js($custom_js)
    ?>

    <?php
}

/**
 * Customizer issue hotfix.
 * 
 */

function selio_helpers_add_js($js) {
    $custom_js = $js;
    // @codingStandardsIgnoreStart
    echo "<script>".esc_view($custom_js)."</script>";
    // @codingStandardsIgnoreEnd
}

?>