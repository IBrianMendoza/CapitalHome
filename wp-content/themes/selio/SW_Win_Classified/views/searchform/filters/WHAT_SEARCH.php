<?php

    $field_name = esc_html__('What?', 'selio');

    $col=3;
    $f_id = 'what';
    $placeholder = esc_html__('What?', 'selio');

    $class_add = $field->class;

?>

<div class="select-item  <?php echo esc_attr($class_add); ?>">
     <p class="title"><?php echo esc_html($field_name); ?></p>
    <div class="form-group " style="<?php esc_attr(selio_ch($field->style)); ?>">
        <input id="search_<?php echo esc_attr($f_id); ?>" name="search_<?php echo esc_attr($f_id); ?>" type="text" class="form-control" placeholder="<?php echo esc_attr($placeholder) ?>" value="<?php echo esc_attr(search_value($f_id)); ?>" />
    </div>
</div>
