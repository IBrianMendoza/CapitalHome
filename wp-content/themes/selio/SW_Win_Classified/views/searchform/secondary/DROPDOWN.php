<?php
    $col=2;
    $field_name = $field_data->field_name;
    $trans = array();
    $trans['FROM'] = esc_html__('Min', 'selio' );
    $trans['TO'] = esc_html__('Max', 'selio' );
    $trans['NONE'] = '';
    $trans[''] = '';
    
    $suf_pre = $field_data->prefix.$field_data->suffix;
    if(!empty($suf_pre))
        $suf_pre = ' ('.$suf_pre.')';
    
    $placeholder = $field_name;
    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=3;
        $direction = '';
    }
    else
    {
        $placeholder = $trans[$field->direction].' '.$placeholder;
        $direction=strtolower('_'.$direction);
    }
    
    $f_id = $field->id;
    $class_add = $field->class;
        
    $field_name = $field_data->field_name;

    $values_available = explode(',', $field_data->values);
    $values_available = array_combine($values_available, $values_available);
    $values_available[''] = $field_name.' ('.__('Any', 'selio').')';
    
    
?>
<div class="form_field <?php echo esc_attr($class_add);?>">
    <div class="form-group">
        <div class="drop-menu">
            <div class="select">
                <?php reset($values_available); if(key($values_available)==''):?>
                    <span><?php echo esc_html(current($values_available));?></span>
                <?php else:?>
                    <span><?php echo esc_html__('Any','selio');?> <?php echo esc_html($field_name);?></span>
                <?php endif;?>
                <i class="fa fa-angle-down"></i>
            </div>
            <input type="hidden" id="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" name="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" value="<?php echo esc_attr(search_value($f_id.$direction)); ?>" />
            <ul class="dropeddown">
                    <?php reset($values_available); if(key($values_available)==''):?>
                        <li><?php echo esc_html(current($values_available));?></li>
                    <?php else:?>
                        <li><?php echo esc_html__('Any','selio');?> <?php echo esc_html($field_name);?></li>
                    <?php endif;?>
                <?php if(selio_plugin_call::sw_count($values_available)>0) foreach ($values_available as $key => $value):?>
                    <?php $value = trim($value); if(empty($key)|| (empty($value) && empty($value)!=0))continue;?>
                    <li data-value="<?php echo esc_attr($value);?>"><?php echo esc_html($value);?></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>
