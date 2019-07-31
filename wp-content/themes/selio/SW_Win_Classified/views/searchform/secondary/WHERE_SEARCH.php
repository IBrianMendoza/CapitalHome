<?php

    $field_name = esc_html__('Where?', 'selio');

    $col=3;
    $f_id = 'where';
    $placeholder = esc_html__('Enter Address, City or State', 'selio');

    $class_add = $field->class;

?>

<div class="form_field <?php echo esc_attr($class_add); ?>">
    <div class="form-group field_search_<?php echo esc_attr($f_id); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">
        <input id="search_where" name="search_where" value="<?php echo esc_attr(search_value('where')); ?>" type="text" class="form-control" placeholder="<?php echo esc_attr($placeholder); ?>" />
    </div>
</div>
