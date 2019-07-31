"use strict"; 
var markers = new Array();
var marker_clusterer ='';
var defaulColor =''; // default color
var enableHTMLmarkers = true;
var HTMLmarker_offset_x = -5;

var sw_map_style = [ {
                featureType:"poi", elementType:"labels.text.fill", stylers:[ {
                    color: "#747474"
                }
                , {
                    lightness: "23"
                }
                ]
            }
            , {
                featureType:"poi.attraction", elementType:"geometry.fill", stylers:[ {
                    color: "#f38eb0"
                }
                ]
            }
            , {
                featureType:"poi.government", elementType:"geometry.fill", stylers:[ {
                    color: "#ced7db"
                }
                ]
            }
            , {
                featureType:"poi.medical", elementType:"geometry.fill", stylers:[ {
                    color: "#ffa5a8"
                }
                ]
            }
            , {
                featureType:"poi.park", elementType:"geometry.fill", stylers:[ {
                    color: "#c7e5c8"
                }
                ]
            }
            , {
                featureType:"poi.place_of_worship", elementType:"geometry.fill", stylers:[ {
                    color: "#d6cbc7"
                }
                ]
            }
            , {
                featureType:"poi.school", elementType:"geometry.fill", stylers:[ {
                    color: "#c4c9e8"
                }
                ]
            }
            , {
                featureType:"poi.sports_complex", elementType:"geometry.fill", stylers:[ {
                    color: "#b1eaf1"
                }
                ]
            }
            , {
                featureType:"road", elementType:"geometry", stylers:[ {
                    lightness: "100"
                }
                ]
            }
            , {
                featureType:"road", elementType:"labels", stylers:[ {
                    visibility: "off"
                }
                , {
                    lightness: "100"
                }
                ]
            }
            , {
                featureType:"road.highway", elementType:"geometry.fill", stylers:[ {
                    color: "#ffd4a5"
                }
                ]
            }
            , {
                featureType:"road.arterial", elementType:"geometry.fill", stylers:[ {
                    color: "#ffe9d2"
                }
                ]
            }
            , {
                featureType:"road.local", elementType:"all", stylers:[ {
                    visibility: "simplified"
                }
                ]
            }
            , {
                featureType:"road.local", elementType:"geometry.fill", stylers:[ {
                    weight: "3.00"
                }
                ]
            }
            , {
                featureType:"road.local", elementType:"geometry.stroke", stylers:[ {
                    weight: "0.30"
                }
                ]
            }
            , {
                featureType:"road.local", elementType:"labels.text", stylers:[ {
                    visibility: "on"
                }
                ]
            }
            , {
                featureType:"road.local", elementType:"labels.text.fill", stylers:[ {
                    color: "#747474"
                }
                , {
                    lightness: "36"
                }
                ]
            }
            , {
                featureType:"road.local", elementType:"labels.text.stroke", stylers:[ {
                    color: "#e9e5dc"
                }
                , {
                    lightness: "30"
                }
                ]
            }
            , {
                featureType:"transit.line", elementType:"geometry", stylers:[ {
                    visibility: "on"
                }
                , {
                    lightness: "100"
                }
                ]
            }
            , {
                featureType:"water", elementType:"all", stylers:[ {
                    color: "#d2e7f7"
                }
                ]
            }
            ]

jQuery(document).ready(function($){
    
    
    /* Start menu dropdown */
    var _w = $(window);
          
    $('#top-menu [data-toggle="dropdown"]').on('click', function(event) {
        $(this).parent().find('.show-m').removeClass('show-m');
    });
          
    $('#top-menu .dropdown-menu .dropdown-item').on('click', function(event) {
        if($(this).parent().find('ul').length){
            event.preventDefault();
            event.stopPropagation();
            $(this).parent().siblings().removeClass('show-m');
            $(this).parent().toggleClass('show-m');

            if((parseInt($(window).width()) - ($(this).offset().left+400)) < 0 ) {
                $(this).parent().addClass('toleft')
            }
        }
    });

    /* End menu dropdown */
    
    /* images gellary for listing preview images */
    $('.property-imgs .property-img img').bind("click", function()
    {
        var myLinks = new Array();
        var current = $(this).attr('href');
        var curIndex = 0;

        $('.property-imgs .property-img img').each(function (i) {
            if(!$(this).attr('data-fullsrc')) return true;
            var img_href = $(this).attr('data-fullsrc');
            myLinks[i] = img_href;
            if(current == img_href)
                curIndex = i;
        });

        var options = {index: curIndex}

        blueimp.Gallery(myLinks, options);

        return false;
    });
    /* end images gellary fro reviews images */
    
    /* images gellary fro reviews images */
    $('.reviews-files-list .template-download').bind("click", function()
    {
        var myLinks = new Array();
        var current = $(this).attr('href');
        var curIndex = 0;

        $(this).closest('.reviews-files-list').find('.template-download').each(function (i) {
            var img_href = $(this).attr('href');
            myLinks[i] = img_href;
            if(current == img_href)
                curIndex = i;
        });

        var options = {index: curIndex}

        blueimp.Gallery(myLinks, options);

        return false;
    });
    /* end images gellary fro reviews images */
    
    // [START] submit smile //  
    $(".rev_smiles .rev_smile .icon").on('click',function(e){
        e.preventDefault();
        var self = $(this);
        var self_parent = self.parent()
        var review_data_type = self_parent.attr('data-revtype')
        var review_data_id = self_parent.attr('data-review')
        var ajax_url = self_parent.attr('data-ajax')
        var loginpopup = self_parent.attr('data-loginpopup')
        
        var $load_indicator = $(this).closest('.rev_smiles').find('.reviev_ajax_loader')
        $load_indicator.removeClass('hidden');

        var data = { 
            review_id: review_data_id, 
            review_type: review_data_type, 
        };

        $.extend( data, {
            "page": 'frontendajax_submitsmile',
            "action": 'ci_action'
        });

        $.post(ajax_url, data, 
            function(data){

            ShowStatus.show(data.message);
            if(data.success)
            {
                var $count = $.trim(self_parent.find('.rev_smile-count').text());
                if($count) {
                    $count = parseInt($count)+1;
                } else {
                    $count = 1;
                }
                self_parent.find('.rev_smile-count').html($count);
            } else {
                if(loginpopup == 'true' && $(window).width()>768) {
                    $('#login-modal').modal('show')  
                }
            }
        }).success(function(){
            $load_indicator.addClass('hidden');
        });

        return false;
    });
    // [END] submit smile // 
    
    selio_add_to_favorite ();
    selio_remove_from_favorites ();
        
    if(!$('.bootstrap-wrapper').length) {
        $('body').append('<div class="bootstrap-wrapper"></div>');
    }
    
     $(".login_popup_enabled").on("click", function(e) {
        if($(window).width()>768 && $("#sign-popup").length) {
            e.preventDefault();
            $("#sign-popup").toggleClass("active");
            $("#register-popup").removeClass("active");
            $("body").addClass("overlay-bgg");
        }
    });
    
    $("html").on("click", function(){
        $("#sign-popup").removeClass("active");
        $("body").removeClass("overlay-bgg");
    });
    
    $(".login_popup_enabled, .popup").on("click", function(e) {
        e.stopPropagation();
    });
        
    if ($('.sw_scale_range').length){
        $('.sw_scale_range').each(function(){
            var th_scale = $(this);
            var th_scale_id = $(this).attr('id');
            var conf_min = '0';
            var conf_max = '100000';
            var conf_sufix= '';
            var conf_prefix= '';
            var conf_infinity = '';
            var conf_predifinedMin = '';
            var conf_predifinedMax =  '';

            if(th_scale.find('.config-range').length ) {
                var $config = th_scale.find('.config-range');
                conf_min = $config.attr('data-min') || 0;
                conf_max = $config.attr('data-max') || '';
                conf_sufix= $config.attr('data-sufix') || '';
                conf_prefix= $config.attr('data-prefix') || '';
                conf_infinity = $config.attr('data-infinity') || "false";
                conf_predifinedMin = $config.attr('data-predifinedMin') || '';
                conf_predifinedMax = $config.attr('data-predifinedMax') || '';
            }
            selio_scale_range('#'+th_scale_id,conf_min,conf_max,conf_prefix,conf_sufix,conf_infinity,conf_predifinedMin,conf_predifinedMax);
       
        })
    }
    
    $('.rating-lst input[name="stars"]').on('change', function(){
        $('#review_star_input').val($(this).val());
    })
    
    $('.widget_nav_menu,.widget_meta,.widget_recent_entries,.widget_categories').find('a:empty').parent().hide();
    
    if($('#slider').length) {
        $('#slider').cardSlider({
            slideTag: 'div', 
            slideClass: 'slide',
            followingClass: 'slider-content'
        })
        
        $('.ui-card-slider-box .ui-card-slider-nav .arrow.left').on('click',function(e){
            e.preventDefault()
            $('#slider').find('.slide.left-slide').click();
        }) 
        
        $('.ui-card-slider-box .ui-card-slider-nav .arrow.right').on('click',function(e){
            e.preventDefault()
            $('#slider').find('.slide.right-slide').click();
        }) 
    }
    
    selio_slider_ini();
    
    /* subscribe */
    $('#sw_footer_subscribe_form').submit(function(e){
        e.preventDefault();
        var $config = $(this).find('.config');
        var conf_link = $config.attr('data-url') || 0;
        var data = $('#sw_footer_subscribe_form').serializeArray();
        data.push({name: 'page', value: "frontendajax_subscribe"});
        data.push({name: 'action', value: "ci_action"});
        var load_indicator = $(this).find('.load-indicator');
        load_indicator.css('display', 'inline-block');
              $.post(conf_link, data, 
                  function(data){
                 load_indicator.css('display', 'none');
                  ShowStatus.show(data.message);

                  if(data.success)
                  {
                    $('#sw_footer_subscribe_form').find('input[name="subscriber_email"]').val('');
                  }
              });

              return false;
        });
    /* subscribe */
        
    if($('.meta-categories-more').length) {
        $('.meta-categories-more').on('click', function(e){
            e.preventDefault();
            $('.meta-categories-more').closest('li').css('display','none');
            $(this).closest('ul').find('.cat-link.less').css('display','inline-block');
        });
        $('.meta-categories-less').on('click', function(e){
            e.preventDefault();
            $('.meta-categories-more').closest('li').css('display','inline-block');
            $(this).closest('ul').find('.cat-link.less').css('display','none');
        });
    }
})


 function selio_add_to_favorite () {
    // [START] Add to favorites //  
    var $ = jQuery; 
    $(".add-favorites-action").on('click',function(e){
        e.preventDefault();
        var self = $(this);
        var self_parent = self.parent()
        var estate_data_id = $(this).attr('data-id')
        var fav_ajax_url = $(this).attr('data-ajax')
        var loginpopup = $(this).attr('data-loginpopup');
        self_parent.addClass('loading');
        
        var data = { listing_id: estate_data_id };
        
        $.extend( data, {
            "page": 'frontendajax_addfavorite',
            "action": 'ci_action'
        });
        
        $.post(fav_ajax_url, data, 
            function(data){

            ShowStatus.show(data.message);
            if(data.success)
            {
                self_parent.find("a.add-favorites-action").addClass('hidden');
                self_parent.find("a.remove-favorites-action").removeClass('hidden');
            } else {
                
                if(loginpopup == 'true' && $(window).width()>768) {
                    e.preventDefault();
                    $("#sign-popup").toggleClass("active");
                    $("#register-popup").removeClass("active");
                    $("body").addClass("overlay-bgg");

                    $("html").on("click", function(){
                        $("#sign-popup").removeClass("active");
                        $("body").removeClass("overlay-bgg");
                    });

                    $(".login_popup_enabled, .popup").on("click", function(e) {
                        e.stopPropagation();
                    });
                }
                
            }
        }).success(function(){
            self_parent.removeClass('loading');
        });

        return false;
    });
    // [END] Add to favorites //  
}     
        
 function selio_remove_from_favorites () {
    // [START] Add to favorites //  
    var $ = jQuery; 
    $(".remove-favorites-action").on('click',function(e){
        e.preventDefault();
        var self = $(this);
        var self_parent = self.parent()
        var estate_data_id = $(this).attr('data-id')
        var fav_ajax_url = $(this).attr('data-ajax')
        
        var data = { listing_id: estate_data_id };
        
        $.extend( data, {
            "page": 'frontendajax_remfavorite',
            "action": 'ci_action'
        });
        
        self_parent.addClass('loading');
        $.post(fav_ajax_url, data, 
            function(data){

            ShowStatus.show(data.message);
            
            if(data.success)
            {
                self.parent().find("a.add-favorites-action").removeClass('hidden');
                self.parent().find("a.remove-favorites-action").addClass('hidden');
            } else {
                
            }
        }).success(function(){
            self_parent.removeClass('loading');
        });

        return false;
    });
}
// [END] Add to favorites //  

 /*
 * Scale range
 * @param {type} object selector for scale-range box
 * @param {type} min min value
 * @param {type} max max value
 * @param {type} prefix
 * @param {type} sufix
 * @param {type} infinity, is infinity
 * @param {type} predifinedMin value
 * @param {type} predifinedMax value
 * @returns {Boolean}
 * 
 * 
 * Example html :
    <div class="scale-range" id="nonlinear-price">
        <label>Price</label>
        <div class="nonlinear"></div>
        <div class="scale-range-value">
            <span class="nonlinear-min"></span>
            <span class="nonlinear-max"></span>
        </div>
        <input id="from" type="text" class="value-min hidden" placeholder="" value="" />
        <input id="to" type="text" class="value-max hidden" placeholder="" value="" />
    </div>
* Example js :                                                                                                                                                                                                                           
     selio_scale_range('#nonlinear-price',0, 500000, '$', 'k', true, '','');
*/

function selio_scale_range(object, min, max, prefix, sufix, infinity, predifinedMin, predifinedMax) {
  if (typeof object == 'undefined')
        return false;
    if (typeof min == 'undefined' || min=='')
        var min = 0;
    if (typeof max == 'undefined' || max=='')
        return false;
    if (typeof prefix == 'undefined' || prefix=='')
        var prefix = '';
    if (typeof sufix == 'undefined' || sufix=='')
        var sufix = '';
    if (typeof infinity === 'infinity' || infinity=='')
        var infinity = true;
    if(infinity == "false") infinity = false;
    
    var $ = jQuery;
    if (typeof predifinedMin == 'undefined' || predifinedMin ==''){
        var predifinedMin = min || 0;
        predifinedMin-=10;
    }
    if (typeof predifinedMax == 'undefined' || predifinedMax==''){
        var predifinedMax = max || 100000;
        predifinedMax+=10;
    }
    
    /* errors */
    
    if(!$(object + ' .value-min').length || !$(object + ' .value-max').length) {
        console.log('Scale range: missing input min or max');
        return false;
    }
    
    var r = 0;
    if(infinity) {
        r = 10;
    }
    
    if ($(object + ' .nonlinear').length) {
        var nonLinearSlider = document.querySelector(object + ' .nonlinear');
        noUiSlider.create(nonLinearSlider, {
            connect: true,
            behaviour: 'tap',
            start: [predifinedMin, predifinedMax],
            range: {
                'min': [parseInt(min)-r],
                'max': [parseInt(max)+r]
            }
        });

        var nodes = [
            document.querySelector(object + ' .nonlinear-min'), // 0
            document.querySelector(object + ' .nonlinear-max')  // 1
        ];
        
        var inputs = [
            document.querySelector(object + ' .value-min'), // 0
            document.querySelector(object + ' .value-max')  // 1
        ];

        // Display the slider value and how far the handle moved
        
        var noUiSlider_ini = 0;
        
        nonLinearSlider.noUiSlider.on('update', function (values, handle, unencoded, isTap, positions) {
            noUiSlider_ini++;
            
            if(parseInt(values[handle]) > max && infinity){
                nodes[handle].innerHTML = prefix + selio_number_format(parseInt(max)) + sufix+'+';
            }
            else if(parseInt(values[handle]) < min && infinity){
                nodes[handle].innerHTML = prefix +selio_number_format(parseInt(min)) + sufix+'-';
            }
            else
                nodes[handle].innerHTML = prefix + selio_number_format(parseInt(values[handle])) + sufix;
            
            if(parseInt(values[handle]) > max && infinity){
                inputs[handle].value = '';
            }
            else if(parseInt(values[handle]) < min && infinity){
                inputs[handle].value = '';
            }
            else if(noUiSlider_ini>2)
                inputs[handle].value = parseInt(values[handle]).toFixed();
                   
            if(noUiSlider_ini>2 && $(object + ' .value-max').val()=='') {
                $(object + ' .value-max').val(predifinedMax);
            }
            
            $(object + ' .value-min, '+object + ' .value-max').trigger('change')
        });
        
    }
}

function selio_number_format(number, format) {
    if(typeof format == 'undefined') var  format = true;
    
    if(format)
        return new Intl.NumberFormat('de-DE').format(number.toFixed());
    else
        return number.toFixed();
        
}

function selio_open_location() {
    var $ = jQuery;
    
    /* listing open popup */
    
    $('[data-listingid]').on('mouseover',function(e){
        if(typeof markers =='undefined') return;
        var listing_id = $(this).attr('data-listingid');
        
        if(!listing_id) return;
        if(typeof markers[listing_id] =='undefined') return;
        
        if(typeof google !== 'undefined'){ 
            var m = markers[listing_id].clickMarker();;
            e.preventDefault();
            return false;
            
        } else if(typeof L !== 'undefined'){
            if(typeof clusters =='undefined') return;
            var m = markers[listing_id].openPopup();;
            clusters.zoomToShowLayer(m, function() {
                m.openPopup();
            });
            e.preventDefault();
            return false;
        }
        
    })
    
    /* end listing open popup */
}

function selio_slider_ini() {
    
    var slider_posts = jQuery('.posts-slider')
    if(slider_posts.hasClass('slick-initialized')) {
            slider_posts.slick("unslick");
    }
    
    jQuery('.posts-slider').slick({
        dots: false,
        infinite: true,
        arrows: false,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true,
        fade: true
    });
      
    jQuery('.sect-post-slider .ps-list .ps-item').on('hover', function(){
        var imgIndex = jQuery(this).index();
        jQuery('.sect-post-slider .ps-list .ps-item').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.posts-slider').slick('slickGoTo', imgIndex);
    })
}