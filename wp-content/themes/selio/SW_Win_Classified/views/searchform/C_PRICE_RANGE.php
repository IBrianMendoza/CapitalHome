<?php

$CI = &get_instance();
$CI->load->model('listing_m');
$CI->load->model('field_m');
$max_price = 250000;
$results_obj = $CI->listing_m->get_by(array('lang_id'=>sw_current_language_id()), FALSE, 1, 'sw_listing_lang.field_36_int DESC');
if($results_obj and !empty($results_obj) && isset($results_obj[0])) {
    if(!empty($results_obj[0]->field_36_int) &&  $results_obj[0]->field_36_int > 25000) 
        $max_price = $results_obj[0]->field_36_int+1000;
}
$col=6;
$class_add = $field->class;

if(strpos(get_page_template(), 'template-results-half') > 1) {

    if(substr_count($class_add, 'col')>0){

        if(!empty($class_add) && preg_match("/col-(xl|md|sm|xs)-[1-12]/", $class_add, $match)){
            $class_add = str_replace($match[0], '', $class_add);
            $class_add .=' col-md-6';
        } else {
            $class_add =' col-md-6';
        }

    } else {
        $class_add .=' col-md-6';
    }

} 
/* skip if enabled only for side page, but page not side */
elseif(stripos($class_add, 'show_side') !== FALSE){ 
    return false;
}


$field_data = $CI->field_m->get_field_data(36);

if(empty($field_data)) {
    echo '<div class="'.esc_attr($class_add).'">';
    echo '<div class="clearfix"></div><div class="alert alert-danger">';
    echo esc_html__("PRICE RANGE can't load missng field #36", "selio");
    echo '</div>';
    echo '</div>';
    return;
}

$suf = $field_data->suffix;
$pre = $field_data->prefix;

?>
<div class="form_field <?php echo esc_attr($class_add); ?>" style="<?php esc_attr(selio_ch($field->style)); ?>">
    <div class="form-group">
        <div class="scale-range sw_scale_range" id="nonlinear-price">
            <div class="hidden config-range"
              data-min="0"
              data-max="<?php echo esc_attr($max_price);?>"
              data-sufix="<?php echo esc_attr($suf);?>"
              data-prefix="<?php echo esc_attr($pre);?>"
              data-infinity="false"
              data-predifinedMin="<?php echo esc_attr(search_value('36_from')); ?>"
              data-predifinedMax="<?php echo esc_attr(search_value('36_to')); ?>"
            >
            </div>
            <div class="scale-range-value">
                <span class="scale-range-label"><?php echo esc_html__('Price','selio');?></span>
                <span class="nonlinear-min"></span> -
                <span class="nonlinear-max"></span>
            </div>
            <div class="nonlinear"></div>
            <input id="search_36_from" name="search_36_from" type="text" class="value-min hidden" value="<?php echo esc_attr(search_value('36_from')); ?>" />
            <input id="search_36_to" name="search_36_to" type="text" class="value-max hidden" value="<?php echo esc_attr(search_value('36_to')); ?>" />
        </div>
    </div>
</div>
