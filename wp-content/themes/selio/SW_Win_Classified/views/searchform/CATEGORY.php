<?php

    $field_name = esc_html__('Category', 'selio');

    // [autoselect category if in uri]

        $page_title = get_the_title();

        $CI =& get_instance();
        $CI->load->model('treefield_m');  
        $treefield_value = $CI->treefield_m->get_all_list(array('value'=>$page_title, 'field_id'=>1), 1);

        if(selio_plugin_call::sw_count($treefield_value) > 0)
            $_GET['search_category']=key($treefield_value);

    // [/autoselect category if in uri]
    
    
    $col=3;
    $f_id = 'category';
    $placeholder = esc_html__('Search keyword', 'selio');

    $class_add = $field->class;

?>
<?php if(selio_plugin_call::sw_settings('show_categories')): ?>
<div class="form_field winter_dropdown_tree_style group_location_id search_field <?php echo esc_attr($class_add); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">
    <div class="form-group">
        <?php esc_viewe(form_treefield('search_category', 'treefield_m', search_value($f_id), 'value', sw_current_language_id(), 'field_search_', true, esc_html__('All Categories', 'selio')));?>
    </div><!-- /.form-group -->
</div><!-- /.form-group -->
<?php endif; ?>