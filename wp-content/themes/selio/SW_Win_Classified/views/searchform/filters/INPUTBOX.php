<?php
    $trans = array();
    $trans['FROM'] = esc_html__('FROM', 'selio' );
    $trans['TO'] = esc_html__('TO', 'selio' );
    $trans['NONE'] = '';
    $trans[''] = '';
    
    $field_name = $field_data->field_name;

    $col=2;
    $f_id = $field->id;
    $placeholder = $field_name;
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=3;
        $direction = '';
    }
    else
    {
        $placeholder = $trans[$field->direction];
        $direction=strtolower('_'.$direction);
    }
    
    $suf_pre = $field_data->prefix.$field_data->suffix;
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
        
    $class_add = $field->class;

    $values_available = explode(',', $field_data->values);
    $values_available = array_combine($values_available, $values_available);
    if(isset($values_available['']))
    {
        $values_available[''] = $trans[$field->direction];
    }
    
    $placeholder = esc_html($placeholder);
    
    if($f_id == 78) {
        $placeholder = esc_html__('Enter Your', 'selio' ).' '.$field_name.'...';
    }

?>
<div class="select-item  <?php echo esc_attr($class_add); ?>">
     <p class="title"><?php echo esc_html($field_name); ?></p>
    <div class="form-group field_search_<?php echo esc_attr($f_id); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">
    <?php if(count($values_available) > 1): ?>
    <?php esc_viewe(form_dropdown('search_'.$f_id.$direction, $values_available, search_value($f_id.$direction), 'class="form-control selectpicker"'))?>
    <?php else: ?>
    <input id="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" name="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" type="text" class="form-control" placeholder="<?php echo esc_attr($placeholder); ?><?php echo esc_attr($suf_pre); ?>" value="<?php echo esc_attr(search_value($f_id.$direction)); ?>" />
    <?php endif; ?>
    </div>
</div>