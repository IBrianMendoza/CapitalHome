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

$zoom_index = 10;
$sw_zoom_index = selio_plugin_call::sw_settings('zoom_index');
if(!empty($sw_zoom_index))
    $zoom = $sw_zoom_index;

// Special version for side template
if(strpos(get_page_template(), 'template-listing-preview') > 1):
?>

<form action="#" class="sw_search_primary sw_search_form row banner-search">
    <?php _search_form_primary(1, $subfolder); ?>

    <?php if(!$selio_button_search_defined): ?>
    <div class="form_field srch-btn">
          <button  type="submit" class="btn sw-search-start btn-outline-primary sw-search-start-slim">
                <i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i>
                <i class="la la-search"></i>
          </button>
    </div>
    <?php endif; ?>
</form>

<?php elseif(strpos(get_page_template(), 'template-results-half') > 1):
?>

<form action="#" class="sw_search_primary sw_search_form row banner-search">
    <?php _search_form_primary(1, $subfolder); ?>

    <?php if(!$selio_button_search_defined): ?>
    <div class="feat-srch">
        <div class="more-feat">
            <h3> <i class="la la-cog"></i><?php echo esc_html__('Show More Features', 'selio'); ?></h3>
        </div><!--more-feat end-->
        <div class="form_field">
            <button type="submit" class="sw-search-start btn btn-outline-primary ">
                <span><?php echo esc_html__('Search', 'selio'); ?></span>
                <i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i>
            </button>
        </div>
    </div><!--more-feat end-->
    <div class="features_list">
        <?php echo do_shortcode('[swsecondarysearch]'); ?>
    </div><!--features end-->
    <?php endif; ?>
</form>


<?php elseif($subfolder == 'filters/'): ?>

<form id="filter-models" action="#" class="sw_search_primary">
    <div class="filter-models-inner">

    <?php _search_form_primary(1, $subfolder); ?>

    </div>
</form>

<?php else: ?>

<section class="search-form color-primary header-search-form widget-with-control">
    <h2 class="hidden"><?php echo esc_html__('Search', 'selio'); ?></h2>  
    <div class="">
        <form action="#" class="form-horisontal form-primary sw_search_primary">
            <?php if(selio_plugin_call::sw_user_in_role('administrator')):?>
            <div class="section-widget-control">
                <a class="sw-c-btn sw-c-edit" href="<?php echo esc_url(admin_url('admin.php?page=listing_searchform'));?>" title="<?php echo esc_attr_e('Edit search form', 'selio'); ?>" target="_blank"><i class="fa fa-pencil"></i></a>
            </div>
            <?php endif;?>
            
            <?php _search_form_primary(1, $subfolder); ?>
            
            <?php if(!$selio_button_search_defined): ?>
            <div class=" col-md-6  search-btn-box">
                <div class="form-group" id="search-btn">
                    <button type="submit" class="btn btn-search focus-color sw-search-start"><i class="fa fa-search icon-white fa-ajax-hide"></i><i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i></button>
                </div>
            </div>
            <?php else: ?>
            </div>
            <?php endif; ?>
        </form>
    </div>
    <div style="clear: both;"></div>
</section>


<?php endif; ?>

<?php
$custom_js ="";
$custom_js .="jQuery(document).ready(function($) {
    
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

    $('.sw-search-start').click(function(e){
        search_result(0, false, false, true);
        return false;
    });

    if(typeof $.fn.typeahead  === 'function')
    $('#search_where').typeahead({
        minLength: 2,
        source: function(query, process) {
            var data = { q: query, limit: 8 };
            
            $.extend( data, {
               'page': 'frontendajax_locationautocomplete',
               'action': 'ci_action',
               'template': '".esc_html(basename(get_page_template()))."'
            });
            
            $.post('".esc_url(admin_url( 'admin-ajax.php' ))."', data, function(data) {
                //console.log(data); // data contains array
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

        if($('#basic-search-field').length > 0 && $('#basic-search-field').val() != '')
        {
            data['search_where'] = $('#basic-search-field').val();
        }
        
        // scroll_enabled is used only on pagination, and then we don't need to refresh map results
        if(load_map &&  $('#sw_map_results'))
        {
            data['map_num_listings'] = $('#sw_map_results').attr('data-num_listings');
        }
        $(\".fa-ajax-indicator\").css('display','inline-block');
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
        var gen_url = selio_generateUrl('".esc_html($page_link)."', data)+'#header-search';";
        
        if(selio_plugin_call::sw_settings('results_page') && sw_is_page(selio_plugin_call::sw_settings('results_page')) || 
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
            \"action\": 'ci_action',
            \"template\": '".esc_html(basename(get_page_template()))."'
        });
        
        
        $.post('".esc_url(admin_url( 'admin-ajax.php' ))."', data,
        function(data){

            $(selectorResults).parent().parent().parent().parent().html(data.html);
            selio_reloadElements();
            
            $(\".fa-ajax-indicator\").css('display','none');
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
                                className: 'open_steet_map_marker',
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
                var limits_center = [];
                if(markers.length){
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
        });";
        
        else:

        $custom_js .="window.location = gen_url;";
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


});

function selio_generateUrl(url, params) {
    var i = 0, key;
    for (key in params) {
        if (i === 0 && url.indexOf('?')===-1) {
            url += '?';
        } else {
            url += '&';
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
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
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
