<?php

    $field_name = esc_html__('Location', 'selio');

    $col=3;
    $f_id = 'location';
    $placeholder = esc_html__('Search keyword', 'selio');

    $class_add = $field->class;

?>
<?php if(selio_plugin_call::sw_settings('show_locations')): ?>
<div class="form_field winter_dropdown_tree_style form-group group_location_id <?php echo esc_attr($class_add); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">
    <div class="form-group">
        <?php esc_viewe(form_treefield('search_location', 'treefield_m', search_value($f_id), 'value', sw_current_language_id(), 'field_search_', true, esc_html__('All Locations', 'selio'), 2));?>
    </div><!-- /.form-group -->
</div><!-- /.form-group -->
<?php endif; ?>