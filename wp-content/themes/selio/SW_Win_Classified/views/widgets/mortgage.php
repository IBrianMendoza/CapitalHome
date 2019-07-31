<?php
    $def_value = '';
    $def_int = '';
    $def_yea = '';

    // Fetch default values
    
    $CI =& get_instance();

    if(isset($this->data['listing']))
    {
        $obj = json_decode($this->data['listing']->json_object);
        
        if(isset($obj->{'field_36'}))
            if(is_numeric($obj->{'field_36'}))
            {
                $def_value = $obj->{'field_36'};
                $def_value = str_replace(',', '', $def_value);
                $def_value = number_format($def_value, 2, '.', '');
            }
            
    }
?>

<div class="">
    <div class="widget widget-calculator mortgage_widget clearfix">
        
         <form method="get" action="#" class="" id="mortgage_calculator">
            <ul>
                <li>
                    <i>$</i>
                    <input id="mortgage_balance" type="text" value="<?php echo esc_attr($def_value); ?>" placeholder="<?php echo esc_attr_e('House price', 'selio'); ?>*">
                </li>
                <li>
                    <i>$</i>
                    <input id="mortgage_interest" type="text" value="<?php echo esc_attr($def_int); ?>" placeholder="<?php echo esc_attr_e('Interest', 'selio'); ?>*">
                </li>
                <li>
                    <i>$</i>
                    <input id="mortgage_downpayment"  type="text" placeholder="<?php echo esc_attr_e('Down payment', 'selio'); ?>">
                </li>
                <li>
                    <i>Y</i>
                    <input id="mortgage_years" type="text" value="<?php echo esc_attr($def_yea); ?>" placeholder="<?php echo esc_attr_e('Years', 'selio'); ?>*">
                </li>
                <li class="form-group-result" style="display: none">
                    <label><?php echo esc_html__('Monthly Repayments', 'selio'); ?></label>
                    <p id="results_monthly" class="form-control-static center"><?php echo esc_html(selio_ch($options_prefix_36)); ?> 0 <?php echo esc_html(selio_ch($options_suffix_36)); ?></p>
                </li><!-- /.form-group -->
                <li class="form-group-result" style="display: none">
                    <label><?php echo esc_html__('Weekly Repayments', 'selio');?> </label>
                    <p id="results_weekly" class="form-control-static center"><?php echo esc_html(selio_ch($options_prefix_36)); ?> 0 <?php echo esc_html(selio_ch($options_suffix_36)); ?></p>
                </li><!-- /.form-group -->
            
                <li>
                    <button type="submit" class="btn2"><?php echo esc_html__('Calculate', 'selio'); ?></button>
                </li>
            </ul>
        </form>
    </div>
</div>


<?php

$custom_js ="";
$custom_js .="

jQuery(document).ready(function($) {
    
	$('#mortgage_calculator').on('submit', function(e) {
		e.preventDefault();
		var \$params = {
			balance: $('#mortgage_balance').val() - $('#mortgage_downpayment').val(),
			rate: $('#mortgage_interest').val(),
			term: $('#mortgage_years').val(),
			period: 12
		};
		
		$(this).calculateMortgage({
			params: \$params,
            results_weekly: $('#results_weekly'),
            results_monthly: $('#results_monthly')
		})
	
	});	
    
	$.fn.calculateMortgage = function(options) {
		var defaults = {
			currency_prefix: '"._ch($options_prefix_36)."',
            currency_suffix: '". _ch($options_suffix_36)."',
			params: {}
		};
		options = $.extend(defaults, options);
		
		var calculate = function(params) {
			params = $.extend({
				balance: 0,
				rate: 0,
				term: 0,
				period: 0,
                results_weekly: null,
                results_monthly: null
			}, params);
			
			var N = params.term * params.period;
			var I = (params.rate / 100) / params.period;
			var v = Math.pow((1 + I), N);
			var t = (I * v) / (v - 1);
			var result = params.balance * t;
			
			return result;
		};
		
		return this.each(function() {
			var \$element = $(this);
			var \$result_custom = calculate(options.params);
            var \$result_month = calculate($.extend(options.params, {period: 12}));
            var \$result_week = calculate($.extend(options.params, {period: 52}));
            
            \$element.find('div.alert').remove();
            
			var output_week = options.currency_prefix + ' ' + \$result_week.toFixed(2) + ' ' + options.currency_suffix;
            if(selio_mortgage_is_numeric(\$result_week.toFixed(2)))
            {
                options.results_weekly.html(output_week);
                options.results_weekly.parent().slideDown();
            }
            else
            {
                \$element.prepend('<div class=\"alert alert-danger\" role=\"alert\">".esc_html__('Please fill empty fields', 'selio')."</div>');
                 $('html,body').animate({scrollTop: \$element.offset().top-170}, 'slow');
            }
			     
		
			var output_month = options.currency_prefix + ' ' + \$result_month.toFixed(2) + ' ' + options.currency_suffix;
			if(selio_mortgage_is_numeric(\$result_month.toFixed(2))){
                            options.results_monthly.html(output_month);
                            options.results_monthly.parent().slideDown();
                        }
		});

	};

});


function selio_mortgage_is_numeric(mixed_var) {
  var whitespace =
    ' \\n\\r\\t\\f\\x0b\\xa0\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u200b\\u2028\\u2029\\u3000';
  return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
    1)) && mixed_var !== '' && !isNaN(mixed_var);
}
";
        
selio_add_into_inline_js( 'selio-custom', $custom_js, true);

?>






