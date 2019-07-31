<?php 

global $selio_button_search_defined;

if($selio_button_search_defined)return;

$selio_button_search_defined=true;

$class_add = ' col-md-3 ';
if(strpos(get_page_template(), 'template-results-side') > 1)
    $class_add = ' col-md-6 ';

 ?>

<div class="<?php echo esc_attr($class_add); ?> search-btn-box">
    <div class="form-group" id="search-btn">
        <button type="submit" class="btn btn-search sw-search-start"><?php echo esc_html__('Search Property', 'selio'); ?><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></button>
    </div>
</div>

<div style="clear:both"></div>
<div class="clearfix" id='form-addittional' style="display: none;">