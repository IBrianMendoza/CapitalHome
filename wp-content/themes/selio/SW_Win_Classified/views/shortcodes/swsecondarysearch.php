<?php 

global $selio_button_search_defined;
$selio_button_search_defined=false;

$CI =& get_instance();
$atts = $CI->data['atts'];

$subfolder = '';
if(isset($atts['subfolder']) && !empty($atts['subfolder']))
{
    $subfolder = $atts['subfolder'].'/';
}

?>

<?php
// Special version for side template
if(strpos(get_page_template(), 'template-results-half') > 1):
?>
<?php _search_form_secondary(1); ?>
<?php else:?>
    <form class="sw_search_secondary">

    <?php _search_form_secondary(1); ?>


    <div class="form-group  col-sm-12" style="">
        <div class="button-wrapper-1">
            <button id="search-start-secondary" type="submit" class="sw-search-start btn btn-primary btn-inversed btn-block">&nbsp;&nbsp;<?php echo esc_html__('Search', 'selio'); ?>&nbsp;&nbsp</button>

        </div><!-- /.select-wrapper -->
    </div><!-- /.form-group -->

    </form>
<?php endif;?>
