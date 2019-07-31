<?php

$sub_style = '';
if(strpos($atts['id'], 'sidebar') !== FALSE)
    $sub_style = 'secondary/';
else
{
    echo '<div class="alert alert-danger">';
    echo '"'.esc_attr($atts['widget_name']).'" can\'t be used in this widget placeholder';
    echo '</div>';
    return;
}

?>

<div class="widget-property-search">
    <form action="#" class="row sw_search_primary sw_search_form banner-search clearfix">
        <?php _search_form_secondary(1, $sub_style); ?>
        <div class="form_field">
            <button class="btn btn-outline-primary sw-search-start" type='submit'>
                <span><?php echo esc_html__('Search', 'selio'); ?><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></span>
            </button>
        </div>
    </form>
</div>