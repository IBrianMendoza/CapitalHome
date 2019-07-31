<?php

$zoom_index = 10;
selio_plugin_call::sw_settings('zoom_index');
if(selio_plugin_call::sw_settings('zoom_index'))
    $zoom_index = selio_plugin_call::sw_settings('zoom_index');

?>

<div id="sw_map_results"  class="map" data-zoom_index="<?php echo esc_attr($zoom_index); ?>" data-num_listings="<?php if(isset($num_listings))echo esc_attr($num_listings); ?>" ></div>

<?php
    
    $CI =& get_instance();            
    
    $lat = $lng = 0;

    if($lat == 0)
    {
        $lat = config_item('lat');
        $lng = config_item('lng');
    }

?>

<?php
$custom_js ="";

if(selio_plugin_call::sw_settings('open_street_map_enabled')):

$custom_js .="
    var geocoder;
    var map;
    var markers = [];
    var clustererOptions;
    var infowindow;
    var markerCluster; 
    
    var clusters ='';
    var jpopup_customOptions =
    {
    'maxWidth': 'initial',
    'width': 'initial',
    'className' : 'popupCustom'
    }
    jQuery(document).ready(function($) {
        if(clusters=='')
            clusters = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
        map = L.map('sw_map_results', {
            center: [". esc_html($lat).",".esc_html($lng)."],
            zoom: ".$zoom_index.",
            scrollWheelZoom: false,
            dragging: !L.Browser.mobile,
            tap: !L.Browser.mobile
        });     
        L.tileLayer('', {
            attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
        }).addTo(map);
        $.fn.sw_isInViewport  = function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height()-40;
            return elementBottom > viewportTop && elementTop < viewportBottom;
        }; 
        var map_element = $('#sw_map_results')
        var doAnimations = function() {
            if (map_element.sw_isInViewport()) {
                var sw_style_open_street_ini = '//cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png';
                if(typeof sw_map_style_open_street != 'undefined') {
                    sw_style_open_street_ini = sw_map_style_open_street;
                }
                var positron = L.tileLayer(sw_style_open_street_ini).addTo(map);
            } 
        };
        $(window).on('scroll', doAnimations);
        $(window).trigger('scroll');
  ";  

        foreach($listings as $key=>$listing): 
        if(!is_numeric($listing->lat))continue;
        $custom_js .="
        var image = null;
        ";

        $pin_icon = "'".SELIO_IMAGES.'/markers/empty.png'."'";

        // check for version with field_id = 14
        if(file_exists(get_template_directory().'/assets/images/markers/'._field($listing, 14).'.png'))
        {
            $pin_icon = "'".SELIO_IMAGES.'/markers/'._field($listing, 14).'.png'."'";
        }

        // check for version with category related marker
        $category = get_listing_category($listing);

        if(isset($category->marker_icon_id))
        {
            $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
            if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
            {
                $pin_icon = "'".$img[0]."'";
            }
        }

        $font_icon = "";
        // check for version with category related marker
        if(isset($category->font_icon_code) && !empty($category->font_icon_code))
        {
            $font_icon = $category->font_icon_code;
        }
        
        if($font_icon){
            $custom_js .="var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"".esc_html($font_icon)."\"></i></div><div class=\"back face\"> <i class=\"".esc_html($font_icon)."\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
        } elseif($pin_icon){
            $custom_js .="var image = ".esc_view($pin_icon)."; var innerMarker = '<div class=\"marker-container marker-container-image\"><div class=\"marker-card\"><div class=\"front face\"><img src='+image+'></img></div></div><div class=\"marker-arrow\"></div></div></div>'";
        }else{
            $custom_js .="var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"la la-home\"></i></div><div class=\"back face\"> <i class=\"la la-home\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
        }

        $custom_js .="
        var marker = L.marker(
            [".esc_html($listing->lat).", ".esc_html($listing->lng)."],
            {icon: L.divIcon({
                    html: innerMarker,
                    className: 'open_steet_map_marker',
                    iconSize: [40, 60],
                    popupAnchor: [-1, -35],
                    iconAnchor: [21, 60],
                })
            }
        );
        marker.bindPopup('"._js(_infowindow_content($listing))."', jpopup_customOptions);
        clusters.addLayer(marker);
        markers.push(marker);
    ";
endforeach;
    
$custom_js .=" map.addLayer(clusters);
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

$custom_js .=" })";

else:

$custom_js .="
var geocoder;
var map;
var markers = [];
var clustererOptions;
var infowindow;
var markerCluster;

jQuery(document).ready(function($) {
    initMap();
});

    function selio_autoCenter(){
        if(markers.length == 0)return;
        var limits = new google.maps.LatLngBounds();
        for (var i in markers) {
            limits.extend(markers[i].position);
        };
        
        google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
            if(markers.length == 1)
            {
                map.setZoom(12);
            }
            else
            {
                map.setZoom(map.getZoom());
            }
        });
        map.fitBounds(limits);
    }

    function selio_deleteMarkers() {
        //Loop through all the markers and remove
        for (var i in markers) {
            markers[i].setMap(null);
        }
        markers = [];
    };

function initMap() {
    clustererOptions = {
        imagePath: '".esc_url(plugins_url( SW_WIN_SLUG.'/assets').'/img/clusters/m')."'
    };
    
    var myLatlng = {lat: ". esc_html($lat).", lng: ".esc_html($lng)."};
    geocoder = new google.maps.Geocoder();
    map = new google.maps.Map(document.getElementById('sw_map_results'), {
      zoom: ".$zoom_index.",
      center: myLatlng,
    ";

    $custom_js .='
      styles: sw_map_style
    });
    ';
$custom_js .="
    infowindow = new google.maps.InfoWindow({
        content: '"._js( esc_html__('Loading...', 'selio'))."'
    });";
    
    foreach($listings as $key=>$listing): 
    if(!is_numeric($listing->lat))continue;
    $custom_js .="
    var image = null;
    ";
    
    $pin_icon = "'".esc_html(SELIO_IMAGES).'/markers/marker-transparent.png'."'";

    // check for version with field_id = 14
    if(file_exists(get_template_directory().'/assets/images/markers/'._field($listing, 14).'.png'))
    {
        $pin_icon = "'".SELIO_IMAGES.'/markers/'._field($listing, 14).'.png'."'";
    }

    // check for version with category related marker
    $category = get_listing_category($listing);

    if(isset($category->marker_icon_id))
    {
        $img = wp_get_attachment_image_src($category->marker_icon_id, 'thumbnail', true, '' );
        if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
        {
            $pin_icon = "'".$img[0]."'";
        }
    }
  
    $font_icon = "";
    // check for version with category related marker
    if(isset($category->font_icon_code) && !empty($category->font_icon_code))
    {
        $font_icon = $category->font_icon_code;
    }
        
    if($font_icon){
        $custom_js .="var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"".esc_html($font_icon)."\"></i></div><div class=\"back face\"> <i class=\"".esc_html($font_icon)."\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
    } elseif($pin_icon){
        $custom_js .="var image = ".esc_view($pin_icon)."; var innerMarker = '<div class=\"marker-container marker-container-image\"><div class=\"marker-card\"><div class=\"front face\"><img src='+image+'></img></div></div><div class=\"marker-arrow\"></div></div></div>'";
    }else{
        $custom_js .="var innerMarker = '<div class=\"marker-container\"><div class=\"marker-card\"><div class=\"front face\"><i class=\"la la-home\"></i></div><div class=\"back face\"> <i class=\"la la-home\"></i></div><div class=\"marker-arrow\"></div></div></div>'";
    }
    
    $custom_js .="
    image = ".esc_view($pin_icon).";
    var myLatlng = new google.maps.LatLng(".esc_html($listing->lat).", ".esc_html($listing->lng).");
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
                        content: '"._js(_infowindow_content($listing))."',
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
    var args = {
                'title':'"._js($listing->address)."'
            };
    var marker = new CustomMarker(myLatlng,map,innerMarker,callback,args);
    markers[".esc_html($listing->idlisting)."] = marker;
";

endforeach;

$custom_js .="
    markerCluster = new MarkerClusterer(map, markers, clustererOptions);
    selio_autoCenter();
}
";
   
endif;
selio_add_into_inline_js( 'selio-custom', $custom_js, true);

?>