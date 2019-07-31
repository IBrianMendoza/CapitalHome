<div class="agents-results-container">

<?php $this->load->view('frontend/agentsresults'); ?>

</div>


<?php
$custom_js ="";
$custom_js .="jQuery(document).ready(function($) {
    
    $('#search-start-agents').click(function(){
        search_result(0, false, false, true);
        return false;
    });
    
    selio_reloadElements();

    function selio_reloadElements()
    {        
        $('#results-agents .pagination a').click(function () { 
            
            var href = $(this).attr('href');
            
            var offset = selio_getParameterByName('offset', href);
            
            search_result(offset, true, false, false);

            return false;
        });
        
    }

    function search_result(results_offset, scroll_enabled, save_only, load_map)
    {
        var selectorResults = '#results_top';
        
        var search_term = $('#search_term').val(); 
        
        //Define default data values for search
        var data = {
            offset: results_offset,
            search_term: search_term
        };
        ";
        $page_link = '';

        // get results page ID
        $agents_page_id = selio_plugin_call::sw_settings('agents_page');
        if(!empty($agents_page_id))
        {
            // get results page link
            $page_link = get_page_link($agents_page_id);
        }

        $custom_js .="
        var gen_url = selio_generateUrl(\"".esc_html($page_link)."\", data)+\"#results-agents\";";
        
       if(is_page(selio_plugin_call::sw_settings('agents_page'))):
        $custom_js .="
        $.extend( data, {
            \"page\": 'frontendajax_agents',
            \"action\": 'ci_action'
        });
        
        $(\"#ajax-indicator\").show();
        $.post('".esc_url(admin_url( 'admin-ajax.php' ))."', data,
        function(data){

            $(selectorResults).parent().parent().html(data.html);
            selio_reloadElements();
            
            $(\"#ajax-indicator\").hide();
            if( scroll_enabled != false && !$(selectorResults).isInViewport(selectorResults) )
                $(document).scrollTop( $(selectorResults).offset().top );
            
            if ('history' in window && 'pushState' in history)
                history.pushState(null, null, gen_url);
            
        }, \"json\");";
        
        else:
        $custom_js .="
        window.location = gen_url;";
        endif;
    $custom_js .="
    }
    
    $.fn.isInViewport = function() {
        
        console.log($(this));
        
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
    
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
    
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };


});

function selio_generateUrl(url, params) {
    var i = 0, key;
    for (key in params) {
        if (i === 0 && url.indexOf(\"?\")===-1) {
            url += \"?\";
        } else {
            url += \"&\";
        }
        url += key;
        url += '=';
        url += params[key];
        i++;
    }
    return url;
}

function selio_getParameterByName(name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, \"\\$&\");
    var regex = new RegExp(\"[?&]\" + name + \"(=([^&#]*)|&|#|$)\"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, \" \"));
}

function sw_win_isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
";
        
selio_add_into_inline_js( 'selio-custom', $custom_js, true);
?>
