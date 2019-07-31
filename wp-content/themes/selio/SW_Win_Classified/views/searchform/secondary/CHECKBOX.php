<?php
    $col=6;
    $f_id = $field->id;
    $placeholder = _ch(${'options_name_'.$f_id});
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
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
<div class="input-field field_search_<?php echo esc_attr($f_id); ?> <?php echo esc_attr($class_add); ?>" style="<?php echo esc_attr(selio_ch($field->style)); ?>">
    <input type="checkbox" name="search_<?php echo esc_attr($f_id); ?>" id="search_<?php echo esc_attr($f_id); ?>"  rel="<?php echo esc_attr($field_name); ?>"  value="1" <?php echo esc_html(search_value($f_id, 'checked')); ?>>
    <label for="search_<?php echo esc_attr($f_id); ?>">
        <span></span>
        <small><?php echo esc_html($field_name); ?></small>
    </label>
</div>