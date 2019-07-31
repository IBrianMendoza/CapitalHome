<?php
    $col=6;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=1;
        $direction = '';
    }
    else
    {
        $placeholder = $direction;
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = _ch(${'options_prefix_'.$f_id}, '')._ch(${'options_suffix_'.$f_id}, '');
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
    
    $class_add = $field->class;
        
    $field_name = $field_data->field_name;
    
    
?>
<div class="<?php echo esc_attr($class_add); ?> checkbox-md">
<div class="checkbox-box-main clearfix">
    <label class="checkbox-styles checkbox-inline" for="search_<?php echo esc_attr($f_id); ?>">
        <input rel="<?php echo esc_attr($field_name); ?>" name="search_<?php echo esc_attr($f_id); ?>" id="search_<?php echo esc_attr($f_id); ?>" type="checkbox" value="1" <?php echo esc_html(search_value($f_id, 'checked')); ?>/> 
        <span class="checkmark"></span>
            <?php echo esc_html($field_name); ?>
    </label>
</div>
</div>