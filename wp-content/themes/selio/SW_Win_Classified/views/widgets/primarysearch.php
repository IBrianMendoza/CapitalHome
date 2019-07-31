<?php
    
$sub_style = '';
if(strpos($atts['id'], 'sidebar') !== FALSE)
    $sub_style = 'secondary/';

global $selio_button_search_defined;
$selio_button_search_defined=false;

$button_class_add=' col-md-1 ';
if(strpos(get_page_template(), 'template-results-side') > 1)
    $button_class_add = ' col-md-6 ';

if(strpos($atts['id'], 'sidebar') !== FALSE):           
?>
<div class="widget-property-search">
    <form action="#" class="row sw_search_primary sw_search_form banner-search clearfix">
        <?php _search_form_primary(1, $sub_style); ?>
        <div class="form_field">
            <button class="btn btn-outline-primary sw-search-start" type='submit'>
                <span><?php echo esc_html__('Search', 'selio'); ?><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></span>
            </button>
        </div>
    </form>
</div>
<?php else: ?>

<section id="header-search" class="search-form color-primary header-search-form widget-with-control">
    <h2 class="hidden"><?php echo esc_html__('Search', 'selio'); ?></h2>  
    <div class="container">
        <?php if(selio_plugin_call::sw_user_in_role('administrator')):?>
        <div class="section-widget-control">
            <a class="sw-c-btn sw-c-edit" href="<?php echo esc_url(admin_url('admin.php?page=listing_searchform'));?>" title="<?php echo esc_attr_e('Edit search form', 'selio'); ?>" target="_blank"><i class="fa fa-pencil"></i></a>
        </div>
        <?php endif;?>
        <form action="#" class="form-horisontal sw_search_primary">
            <div class="row">
                <?php _search_form_primary(1, $sub_style); ?>
                <?php if(!$selio_button_search_defined): ?>
                <div class="<?php echo esc_attr($button_class_add); ?> search-btn-box">
                    <div class="form-group" id="search-btn">
                        <button type="submit" class="btn btn-search focus-color sw-search-start"><i class="fa fa-search icon-white"></i></button>
                    </div>
                </div>
                <?php else: ?>
                </div>
                <?php endif; ?>
            </div>
        </form>
        <a class="search-additional-btn" id="search-additional">
            <i class="fa fa-plus-circle"></i><?php echo esc_html__('More Option', 'selio'); ?>
        </a>
    </div>
</section><!-- /.header-search-->
<?php endif; ?>

<?php

$custom_js ="";
$custom_js .="

jQuery(document).ready(function($) {
    
    // On change value, change field style
    $('form.sw_search_primary input, form.sw_search_primary select, '+
          'form.sw_search_secondary input, form.sw_search_secondary select').each(function(i)
    {
        $(this).change(function(){selio_search_highlight($(this))});
        selio_search_highlight($(this));
    })

    function selio_search_highlight(elem)
    {

        if(elem.attr('type') == 'checkbox')
        {
        }
        else if(elem.is('select'))
        {   
            if(elem.val() == '' || elem.val() == 0 || elem.val() == null)
            {
                // remove selector class
                elem.closest('.select-item').removeClass('sel_class');
                elem.parent().removeClass('sel_class');
                elem.removeClass('sel_class');
            }
            else
            {                
                // add selector class
                elem.closest('.select-item').addClass('sel_class');
                elem.parent().addClass('sel_class');
                elem.addClass('sel_class');
            }
        }
        else if(elem.attr('type') == 'text')
        {
            if(elem.parent().find('.winter_dropdown_tree').length > 0) // For treefield
            {
                if(elem.val() != '' && elem.val() != 0 && elem.val() != null)
                {
                    // add selector class
                    elem.closest('.winter_dropdown_tree_style').find('.winter_dropdown_tree').addClass('sel_class');
                    elem.parent().find('.btn-group:first-child').addClass('sel_class');
                }
                else
                {
                    // remove selector class
                    elem.closest('.winter_dropdown_tree_style').find('.winter_dropdown_tree').removeClass('sel_class');
                    elem.parent().find('.btn-group:first-child').removeClass('sel_class');
                }
            }
            else  // For basic input
            {
                if(elem.val() != '' && elem.val() != 0&& elem.val() != null)
                {
                    // add selector class
                    elem.addClass('sel_class');
                }
                else
                {
                    // remove selector class
                    elem.removeClass('sel_class');
                }
            }
        }
        else
        {
        }

    }

    // [Change multiselect functionality]
    // avoid the need for ctrl-click in a multi-select box
    
    $('.side-search-form select[multiple] option').mousedown(function(e) {
        e.preventDefault();
        $(this).prop('selected', !$(this).prop('selected'));
        return false;
    });
    
    // [/Change multiselect functionality]
    
    $('.sw-search-start').click(function(){
        search_result(0, false, false, true);
        return false;
    });
    
    if(typeof $.fn.typeahead  === 'function')
    $('#search_where').typeahead({
        minLength: 2,
        source: function(query, process) {
            var data = { q: query, limit: 8 };
            
            $.extend( data, {
                \"page\": 'frontendajax_locationautocomplete',
                \"action\": 'ci_action'
            });
            
            $.post('".esc_url(admin_url( 'admin-ajax.php' ))."', data, function(data) {
                process(data);
            });
        }
    });
    
    
    selio_reloadElements();


    function selio_reloadElements()
    {
        $('#results .view-type').click(function () { 
          $(this).parent().parent().find('.view-type').removeClass(\"active\");
          $(this).addClass(\"active\");
          return false;
        });
        
        $('#results a.view-type:not(.active)').click(function(){
            search_result(0, false, false, false);
            return false;
        });
        
        $('#results #search_order').change(function(){
            search_result(0, false, false, true);
            return false;
        });
        
        $('#results .pagination a').click(function () { 
            
            var href = $(this).attr('href');
            
            var offset = selio_getParameterByName('offset', href);
            
            search_result(offset, true, false, false);

            return false;
        });
        
        //dropdown select order
        $('.properties-rows .drop-menu').selio_drop_down();
    }

    function search_result(results_offset, scroll_enabled, save_only, load_map)
    {
        var selectorResults = '#results_top';
        // Order ASC/DESC
        var results_order = $('#results #search_order').val();
        
        if (results_order === undefined || results_order === null) {
            results_order = 'idlisting DESC';
        }
        
        // View List/Grid
        var results_view = $('.view-type.active').attr('data-ref');  
                
        if (results_view === undefined || results_view === null) {
            results_view = 'grid';
        }
        
        //Define default data values for search
        var data = {
            search_order: results_order,
            search_view: results_view,
            offset: results_offset
        };
        
        // Add custom data values, automatically by fields inside search-form
        $('form.sw_search_primary input, form.sw_search_primary select, '+
          'form.sw_search_secondary input, form.sw_search_secondary select').each(function (i) {
            
            if($(this).attr('type') == 'checkbox')
            {
                if ($(this).attr('checked'))
                {
                    data[$(this).attr('name')] = $(this).val();
                }
            }
            else if($(this).val() != '' && $(this).val() != 0&& $(this).val() != null)
            {
                data[$(this).attr('name')] = $(this).val();
            }
            
        });
        
        // scroll_enabled is used only on pagination, and then we don't need to refresh map results
        if(load_map &&  $('#sw_map_results').length>0)
        {
            data['map_num_listings'] = $('#sw_map_results').attr('data-num_listings');
        }
        ";
        
       
        $page_link = '';

        // get results page ID
        $results_page_id = selio_plugin_call::sw_settings('results_page');
        if(!empty($results_page_id))
        {
            // get results page link
            $page_link = get_page_link($results_page_id);
        }

        if(selio_plugin_call::sw_settings('enable_multiple_results_page') == 1 && strpos(get_page_template(), 'results') !== FALSE)
        {
            $page_link = get_page_link(get_the_ID());
        }
        
        $custom_js .="
        var gen_url = selio_generateUrl(\"".esc_html($page_link)."\", data)+\"#header-search\";";
        
        if(sw_is_page(selio_plugin_call::sw_settings('results_page')) || 
                (selio_plugin_call::sw_settings('enable_multiple_results_page') == 1)):
            
        if(selio_plugin_call::sw_settings('enable_multiple_results_page') == 1):
        $custom_js .="
            if(!$(selectorResults).length){
                $(\".fa-ajax-indicator\").css('display','inline-block');
                $(\".fa-ajax-hide\").hide();
                window.location = gen_url;
            }
        ";
        endif;
        
        $custom_js .="
        $.extend( data, {
            \"page\": 'frontendajax_resultslisting',
            \"action\": 'ci_action'
        });
        
        $(\".fa-ajax-indicator\").css('display','inline-block');
        $(\".fa-ajax-hide\").hide();
        
        $.post('".esc_url(admin_url( 'admin-ajax.php' ))."', data,
        function(data){

            $(selectorResults).parent().parent().parent().parent().html(data.html);
            selio_reloadElements();
            
            $(\".fa-ajax-indicator\").css('display','none');
            $(\".fa-ajax-hide\").show();
            if( scroll_enabled != false && !$(selectorResults).isInViewport() )
                $(document).scrollTop( $(selectorResults).offset().top-$('.header-inner').height()-150 );
            
            if ('history' in window && 'pushState' in history)
                history.pushState(null, null, gen_url);

            // populate map
            if(data.hasOwnProperty(\"listings_map\") && typeof map !== 'undefined')
            {
            
                /* start map refreash */
                ";
            if(selio_plugin_call::sw_settings('open_street_map_enabled')):
        
                $custom_js .="
                    
                //Loop through all the markers and remove
                for (var i in markers) {
                    clusters.removeLayer(markers[i]);
                }
                markers = [];
 
                $.each( data.listings_map, function( key, obj ) {
                    
                    if(!sw_win_isNumeric(obj.lat))return;

                    var innerMarker ='<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"la la-home\"></i></div><div class=\"back face\"> <i class=\"la la-home\"></i></div><div class=\"marker-arrow\"></div></div></div>';
                    if(obj.font_icon!=''){
                        innerMarker ='<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"'+obj.font_icon+'\"></i></div><div class=\"back face\"> <i class=\"'+obj.font_icon+'\"></i></div><div class=\"marker-arrow\"></div></div></div>';
                    } else if(obj.pin_icon!=''){
                        innerMarker = '<div class=\"marker-container marker-container-image\"><div class=\"marker-card\"><div class=\"front face\"><img src=\"'+obj.pin_icon+'\"></img></div></div><div class=\"marker-arrow\"></div></div></div>';
                    }

                    var marker = L.marker(
                        [parseFloat(obj.lat), parseFloat(obj.lng)],
                        {icon: L.divIcon({
                                html: innerMarker,
                                className: 'open_steet_map_marker google_marker',
                                iconSize: [40, 60],
                                popupAnchor: [-1, -35],
                                iconAnchor: [21, 60],
                            })
                        }
                    );

                    marker.bindPopup(obj.infowindow, jpopup_customOptions);
                      
                    clusters.addLayer(marker);
                    markers.push(marker);

                });
    
                /* set center */
                if(markers.length){
                    var limits_center = [];
                    for (var i in markers) {
                        var latLngs = [ markers[i].getLatLng() ];
                        limits_center.push(latLngs)
                    };
                    var bounds = L.latLngBounds(limits_center);
                    map.fitBounds(bounds);
                }
                ";
                if(selio_plugin_call::sw_settings('auto_set_zoom_disabled')):
                $custom_js .="setTimeout(function(){
                        if($('#sw_map_results').attr('data-zoom_index'))
                           map.setZoom($('#sw_map_results').attr('data-zoom_index'));
                    }, 1000);";
                endif;
                $custom_js .="
                /* end set center */
                
                ";
                
            else:
                 
            $custom_js .="
                markerCluster.clearMarkers();
                selio_deleteMarkers();
                
                $.each( data.listings_map, function( key, obj ) {
                    
                    if(!sw_win_isNumeric(obj.lat))return;
                    
                    var myLatlng = new google.maps.LatLng(parseFloat(obj.lat), parseFloat(obj.lng));

                    var callback = {
                        'click': function(map, e){
                            var activemarker = e.activemarker;
                            jQuery.each(markers, function(){
                                this.activemarker = false;
                            })

                            sw_infoBox.close();
                            if(activemarker) {
                                e.activemarker = false;
                                return true;
                            }

                            var boxOptions = {
                                content: obj.infowindow,
                                disableAutoPan: false,
                                alignBottom: true,
                                maxWidth: 0,
                                pixelOffset: new google.maps.Size(-157, -15),
                                zIndex: null,
                                infoBoxClearance: new google.maps.Size(1, 1),
                                isHidden: false,
                                pane: 'floatPane',
                                enableEventPropagation: false,
                                closeBoxURL: '".SELIO_IMAGES.'/close.png'."'
                            };

                            sw_infoBox.setOptions( boxOptions);
                            sw_infoBox.open( map, e );

                            setTimeout(function(){
                                var $ = jQuery;
                                $(\".infoBox-close\").on('click', function(e){
                                    e.preventDefault(), sw_infoBox.close();
                                })
                            }, 500)
                            
                            e.activemarker = true;
                        }
                    };
                    var innerMarker ='<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"la la-home\"></i></div><div class=\"back face\"> <i class=\"la la-home\"></i></div><div class=\"marker-arrow\"></div></div></div>';
                    if(obj.font_icon!=''){
                        innerMarker ='<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"'+obj.font_icon+'\"></i></div><div class=\"back face\"> <i class=\"'+obj.font_icon+'\"></i></div><div class=\"marker-arrow\"></div></div></div>';
                    } else if(obj.pin_icon!=''){
                        innerMarker = '<div class=\"marker-container marker-container-image\"><div class=\"marker-card\"><div class=\"front face\"><img src=\"'+obj.pin_icon+'\"></img></div></div><div class=\"marker-arrow\"></div></div></div>';
                    }
                    
                    var args = {
                                'title': obj.address
                            };

                    var marker = new CustomMarker(myLatlng,map,innerMarker,callback,args);
                    
                    markers[obj.idlisting] = marker;

                });

                markerCluster = new MarkerClusterer(map, markers, clustererOptions);
                
                selio_autoCenter();
                "; 
                 
            endif;   
            
            
                $custom_js .="
                /* end map refreash */
            }
            
        }, \"json\").success(function(){
            if(typeof  sw_anim_scroll == 'function')
                sw_anim_scroll()
            selio_add_to_favorite ();
            selio_remove_from_favorites ();
            
        });
        
        showCounters(data);";
        else:
        $custom_js .="$(\".fa-ajax-indicator\").css('display','inline-block');
        $(\".fa-ajax-hide\").hide();
        window.location = gen_url;";
        endif;
    $custom_js .="
    }
    
    $.fn.isInViewport = function() {
        var elementTop = $(this).offset().top;
        var elementBottom = elementTop + $(this).outerHeight();
    
        var viewportTop = $(window).scrollTop();
        var viewportBottom = viewportTop + $(window).height();
    
        return elementBottom > viewportTop && elementTop < viewportBottom;
    };
    showCounters();

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


function showCounters(data_params)
{

    if(typeof data_params == 'undefined') {
        var data_params = {
            \"page\": 'frontendajax_getallcounters',
            \"action\": 'ci_action',
            \"form_id\": '1'
        }
    } else {
        jQuery.extend( data_params, {
            \"page\": 'frontendajax_getallcounters',
            \"action\": 'ci_action',
            \"form_id\": '1'
        });
    }     
    
    jQuery.post('".esc_url(admin_url( 'admin-ajax.php' ))."', data_params, function(data){
        jQuery(\"input[name^='search'][type='checkbox']\").each(function(){
            if(!jQuery(this).parent().find('span.count').length)
                jQuery(this).parent().append('<span class=\"count\"></span>')
        })
        
        // remove all
        jQuery(\"input[name^='search'][type='checkbox']\").parent().find('span.count').html('');

        jQuery.each(data.counters, function( index, obj ) {
          if(!jQuery(\"input[name='search_\"+index+\"'][type='checkbox']\").is(\":checked\"))
            jQuery(\"input[name='search_\"+index+\"'][type='checkbox']\").parent().find('span.count').html('&nbsp('+obj+')');
        });

    }, \"json\");
}

";
        
selio_add_into_inline_js( 'selio-custom', $custom_js, true);

?>

