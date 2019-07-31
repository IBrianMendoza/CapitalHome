<?php
    $trans = array();
    $trans['FROM'] = esc_html__('Min', 'selio' );
    $trans['TO'] = esc_html__('Max', 'selio' );
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
<div class="form_field <?php echo esc_attr($class_add);?> <?php if(selio_plugin_call::sw_count($values_available)  < 2): ?>sf_input<?php endif;?>">
    <div class="form-group field_search_<?php echo esc_attr($f_id); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">
        <?php if(count($values_available) > 1): ?> 
            <div class="drop-menu">
                <div class="select">
                    <?php reset($values_available); if(key($values_available)==''):?>
                        <span><?php echo esc_html(current($values_available));?></span>
                    <?php else:?>
                        <span><?php echo esc_html__('Any type','selio');?></span>
                    <?php endif;?>
                    <i class="fa fa-angle-down"></i>
                </div>
                <input type="hidden" id="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" name="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" value="<?php echo esc_attr(search_value($f_id.$direction)); ?>" />
                <ul class="dropeddown">
                    <?php reset($values_available); if(key($values_available)==''):?>
                        <li><?php echo esc_html(current($values_available));?></li>
                    <?php else:?>
                        <li><?php echo esc_html__('Any type','selio');?></li>
                    <?php endif;?>
                    <?php if(selio_plugin_call::sw_count($values_available)>0) foreach ($values_available as $key => $value):?>
                        <?php $value = trim($value); if(empty($key)|| (empty($value) && empty($value)!=0))continue;?>
                       <li data-value="<?php echo esc_attr($value);?>"><?php echo esc_html($value);?></li>
                    <?php endforeach;?>
                </ul>
            </div>
        <?php else: ?>
        <input id="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" name="search_<?php echo esc_attr($f_id).esc_attr($direction); ?>" type="text" class="form-control" placeholder="<?php echo esc_attr($placeholder); ?><?php echo esc_attr($suf_pre); ?>" value="<?php echo esc_attr(search_value($f_id.$direction)); ?>" />
        <?php endif; ?>
    </div>
</div>