<?php
    $col=6;

    $direction = $field->direction;
    if($direction == 'NONE'){
        $col=12;
        $direction = '';
    }
    
    $f_id = $field->id;
    $class_add = $field->class;
        
    $field_name = $field_data->field_name;

    $values_available = explode(',', $field_data->values);
    $values_available = array_combine($values_available, $values_available);
    //$values_available[''] = '('.__('Any', 'selio').')';
    unset($values_available['']);
    
?>
<div class="sort-column field_search_<?php echo esc_attr($f_id); ?> <?php echo esc_attr($class_add); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">

    <?php //esc_viewe(form_dropdown('search_'.$field_data->idfield, $values_available, search_value($f_id.$direction), 'class=""'))?>

    <p class="title"><?php echo esc_html($field_name); ?></p>
    <div class="sort-properties">

        <?php foreach($values_available as $key=>$val): ?>


        <p class="sort-property-item-wr">
            <label class="sort-property-item">
                <input type="checkbox" name="<?php echo esc_attr('search_'.$field_data->idfield); ?>" value="<?php echo esc_attr($key); ?>">
                <span><?php echo esc_html($val); ?></span>
            </label>
        </p>

    <?php endforeach; ?>
    </div>
</div>
