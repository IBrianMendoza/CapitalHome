<?php

    $field_name = esc_html__('Category', 'selio');

    $col=3;
    $f_id = 'category';
    $placeholder = esc_html__('Search keyword', 'selio');

    $class_add = $field->class;
        
?>
<?php if(selio_plugin_call::sw_settings('show_categories')): ?>
<div class="select-item  winter_dropdown_tree_style form-group group_location_id search_field <?php echo esc_attr($class_add); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">
    <p class="title"><?php echo esc_html($field_name); ?></p>
 <?php esc_viewe(form_treefield('search_category', 'treefield_m', search_value($f_id), 'value', sw_current_language_id(), 'field_search_', true, esc_html__('All Categories', 'selio')));?>
</div><!-- /.form-group -->
<?php endif; ?>