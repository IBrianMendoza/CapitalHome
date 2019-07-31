<?php
if(!function_exists('recursion_calc_count')) {
    function recursion_calc_count ($result_count, $tree_listings, $parent_title, $id, &$child_count){
        if (isset($tree_listings[$id]) && sw_count($tree_listings[$id]) > 0){
            foreach ($tree_listings[$id] as $key => $_child) {
                $options = $tree_listings[$_child->parent_id][$_child->idtreefield];
                if(isset($result_count[$options->idtreefield]))
                    $child_count+= $result_count[$options->idtreefield];

                $_parent_title = '';
                if (isset($tree_listings[$_child->idtreefield]) && sw_count($tree_listings[$_child->idtreefield]) > 0){    
                    recursion_calc_count($result_count, $tree_listings, $_parent_title, $_child->idtreefield, $child_count);
                }
            }
        }
    }
}

$CI = & get_instance();
$treefield_id = 2;

$CI->load->model('treefield_m');

// init varibles
$treefields = array();
$tree_listings_default = array();
$tmpfile ='';
$error_svg_widget='';
$widget_fatal_error = false;
$lang_id = sw_current_language_id();
$svg_path = sw_win_upload_path().'/files/';

$tree_listings = $CI->treefield_m->get_table_tree($lang_id, $treefield_id, NULL, FALSE);


if(empty($tree_listings) || !isset($tree_listings[0]))
    $widget_fatal_error = true;

if(!$widget_fatal_error){
/*  
$query='';

$this->db->select('sw_listing.idlisting, sw_listing.is_activated,
                    sw_listing.location_id, COUNT(location_id) as count');

$this->db->group_by('location_id'); 
$this->db->where('is_activated', 1);
$query= $this->db->get('sw_listing');


$result_count = array();

if($query){
    $result = $query->result();
    foreach ($result as $key => $value) {
        if(!empty($value->location_id))
            $result_count[$value->location_id]= $value->count;
    }
}
*/
$_treefields = $tree_listings[0];

$root_value = '';
$ariesInfo = array();
$treefields = array();
foreach ($_treefields as $val) {
    
    $options = $tree_listings[0][$val->idtreefield];
    $treefield = array();
    $field_name = 'value' ;
    $treefield['id'] = $val->idtreefield;
    $treefield['title'] = $options->$field_name;
    $treefield['parent_id'] = $options->parent_id;
    
    if(empty($root_value))
        $root_value = $options->idtreefield;
    
    /*
    $treefield['count'] = 0;
    if(isset($result_count[$options->idtreefield]))
        $treefield['count'] = $result_count[$options->idtreefield];
    */
    
    $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
    $conditions['search_location'] = $options->idtreefield;
    prepare_frontend_search_query_GET('listing_m', $conditions);
    $treefield['count'] = $CI->listing_m->total_lang(array(), sw_current_language_id());
    
    $ariesInfo[$val->idtreefield]['name']=$treefield['title'];
    $ariesInfo[$val->idtreefield]['parent_id']=$treefield['parent_id'];
    $ariesInfo[$val->idtreefield]['count']=$treefield['count'];
     
    $childs = array();
    if (isset($tree_listings[$val->idtreefield]) && sw_count($tree_listings[$val->idtreefield]) > 0)
        foreach ($tree_listings[$val->idtreefield] as $key => $_child) {
            $child = array();
            $options = $tree_listings[$_child->parent_id][$_child->idtreefield];
            $child['id'] = $_child->idtreefield;
            $field_name = 'value';
            $child['title'] = $options->$field_name;
            $child['parent_id'] = $options->parent_id;
            
            $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
            $conditions['search_location'] = $options->idtreefield;
            prepare_frontend_search_query_GET('listing_m', $conditions);
            $child['count'] = $CI->listing_m->total_lang(array(), sw_current_language_id());

            /*
            $child['count']= 0;
            if(isset($result_count[$child['id']]))
                $child['count'] = $result_count[$child['id']];
                   
            if (isset($tree_listings[$_child->idtreefield]) && sw_count($tree_listings[$_child->idtreefield]) > 0){
                $parent_title = $treefield['title'].' - '.$child['title'];
                recursion_calc_count($result_count, $tree_listings, $parent_title, $_child->idtreefield, $child['count']);
            }       
            */  
            
            $childs[] = $child;
            $ariesInfo[$_child->idtreefield]['name']=$child['title'];
            $ariesInfo[$_child->idtreefield]['parent_id']=$child['parent_id'];
            $ariesInfo[$_child->idtreefield]['count']=$child['count'];
        }

    $treefield['childs'] = $childs;
    $treefields[] = $treefield;
}
}
/*
echo '<pre>';
print_r($treefields);
echo '</pre>';*/
?>

<div class="header-geomap section-color-secondary">
     <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="map-geo-widget">
                    <?php if(!$widget_fatal_error):?>
                    <div class="row">
                    <div class="col-sm-4">
                    <div class="geo-menu">
                        <div class="geo-menu-breadcrumb"></div>
                        <ul class="treefield-tags">
                            <?php foreach ($treefields as $key => $item) : ?>
                                <li class=''><a href="#<?php echo str_replace(' ', '-', $item['title']); ?>" data-id-lvl_0="<?php _che($item['id']); ?>" data-id='<?php _che($item['id']); ?>'><?php _che($item['title']); ?></a>
                                    <ul class='' id="<?php echo str_replace(' ', '-', $item['title']); ?>">
                                        <li><a href="#back" id='geo-menu-back' data-path=''> <i class="fa fa-arrow-left"></i> <?php echo __('back','sw_win'); ?> </a></li>
                                        <?php if (sw_count($item['childs']) > 0): ?> 
                                            <?php foreach ($item['childs'] as $child): ?>
                                                <li><a href="#" title="<?php _che($child['title']); ?>" data-region='<?php _che($child['title']); ?>' data-id-lvl_0="<?php _che($item['id']); ?>"  data-id-lvl_1="<?php _che($child['id']); ?>" data-id='<?php _che($child['id']); ?>'><?php _che($child['title']); ?> <span class="item-count">(<?php _che($child['count']); ?>)</span></a>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                        </div>
                    <div class="col-sm-8">
                    <div id="map-geo">
                        <?php if (isset($_GET['geo_map_preview']) && !empty($_GET['geo_map_preview']) && isset($svg) && FALSE): ?>
                            <object  data="<?php echo $tmpfile; ?>" type="image/svg+xml" id="svgmap" width="500" height="360">
                            </object>                                 
                        <?php else: ?>
                            <?php if (file_exists($svg_path.'current_map.svg')): ?>
                                <object data="<?php echo sw_win_upload_dir().'/files/current_map.svg'; ?>" type="image/svg+xml" id="svgmap" width="500" height="360"></object>                                 
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                        </div>
                    </div>
                    <?php else:?>
                        <p class="alert alert-success" style="margin: 15px 0;">
                        <?php echo __('Map didn`t create, please contact with administrator', 'sw_win');?>
                        </p>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(!$widget_fatal_error):?>

<script>
    
    
    var treefields = [];
    <?php if(isset($ariesInfo) && !empty($ariesInfo)) foreach ($ariesInfo as $key => $value):?>
        treefields["<?php echo $key;?>"] = [];
        treefields["<?php echo $key;?>"]['id'] = "<?php echo $key;?>";
        treefields["<?php echo $key;?>"]['name'] = "<?php echo $value['name'];?>";
        treefields["<?php echo $key;?>"]['count'] = "<?php echo $value['count'];?>";
        treefields["<?php echo $key;?>"]['parent_id'] = "<?php echo $value['parent_id'];?>";
        
    <?php endforeach;?>
/*
 * Set item in geo menu
 * @param dataPath (string) value-path for treefield field ("Croatia - Zagreb")
 * 
 */

/*
 * 
 * Styles for svg
 * 
 */
var svg_default_area_color = '#092c61' /* default color*/
var svg_selected_area_color = '#27a6dc' /* selected color*/
var svg_hover_area_color = '#27a6dc' /* hover color*/
var svg_selected_country_color = '#092c61';
var svg_stroke_color = '#fff';
var firstload = true;

var geo_trigger_treefield = false;

function set_path (dataPath, apply_in_search, tree_field) {
        if (typeof apply_in_search === 'undefined') apply_in_search = true;
        if (typeof tree_field === 'undefined') tree_field = true;
        var dataPath_origin = dataPath;
        // refresh
        
        var s_values_splited = new Array();
        s_values_splited.push(dataPath);
        var recurse_path = function(treefields,dataPath) {
            if(typeof treefields[dataPath] !== 'undefined' && treefields[dataPath]['parent_id'] !='0') {
                var id = treefields[treefields[dataPath]['parent_id']]['id'];
                s_values_splited.push(id);
                if(typeof treefields[treefields[dataPath]['parent_id']] !== 'undefined' && treefields[treefields[dataPath]['parent_id']]['parent_id'] !='0') {
                    recurse_path(treefields,id);
                }
            }
        }
        recurse_path(treefields,dataPath)
        
        var _last_element = jQuery.trim(s_values_splited[s_values_splited.length-1]);
        
        jQuery('.geo-menu li').removeClass('active');
        jQuery('.geo-menu ul > li li').css('display', 'none');
        jQuery('.geo-menu ul > li').css('display', 'inline-block');
        jQuery('.geo-menu ul a').css('display', 'block')
    
        var _dataPath = '';
        jQuery.each(s_values_splited, function(key, val){
            if(key>1) return false;
            /*console.log('key: '+key, 'val: '+val)*/
            
            val = jQuery.trim(val);
            if(!jQuery('.geo-menu a[data-id="'+val+'"]').length) return false;
            
            var _this =  jQuery('.geo-menu a[data-id="'+val+'"]');
            var parent = _this.closest('li');
            
            if( parent.hasClass('active')) {
                parent.removeClass('active')
                return false;
            }
            
            parent.addClass('active')
            if(parent.find('li').length){
                jQuery(' > li', parent.parent()).not(parent).css('display', 'none');
                _this.css('display', 'none')
                jQuery(' li', parent).css('display', 'inline-block');
                jQuery('.geo-menu ul'+_this.attr('href')).show();
            }
        })
        
        if(apply_in_search){
            if(_dataPath !='')
                jQuery('.search_option_<?php _che($treefield_id);?>').val(dataPath_origin);
            else 
                jQuery('.search_option_<?php _che($treefield_id);?>').val('');
        }
        
        if(apply_in_search && tree_field && jQuery('.group_location_id .winter_dropdown_tree').length){
            var dropdown = jQuery('.group_location_id .winter_dropdown_tree');
            /*dropdown.find('input').val(dataPath);*/
            geo_trigger_treefield = true;
            dropdown.find('.list_items li[key="'+dataPath+'"]').trigger('click');
            geo_trigger_treefield = false;
        }
        

        /* short-more tags*/
        dataPath_origin = jQuery.trim(s_values_splited)
        
        if(firstload && dataPath_origin[dataPath_origin.length-1] == '-') {
            dataPath_origin = dataPath_origin.slice(0, -1);
            dataPath_origin = jQuery.trim(dataPath_origin)
        }
        
        var selector = jQuery.trim(s_values_splited[(s_values_splited.length-1)]);
        
        if(jQuery('a[data-id="'+selector+'"]').closest('li').find('ul li .more-tags').length && jQuery('a[data-id="'+selector+'"]').closest('li').find('ul li .more-tags').attr('data-close') == 'false'){
        } else {
            hideShow_tags(selector);
        }
        firstload = false;
}

/* menu geo */
jQuery(document).ready(function($) {

    // if search_option_$treefield_id input missing
    if(!$('#TREE-GENERATOR_ID_<?php echo $treefield_id;?>').length) {
        $('.search-form').append('<input type="text" class="hidden form-control search_option_<?php echo $treefield_id;?> skip-input" name="search_option_<?php echo $treefield_id;?>" value="" id="search_option_<?php echo $treefield_id;?>">')
    }
    
    $('.geo-menu a').click(function(e){
        e.preventDefault();
        var dataPath =  $(this).attr('data-id')  || '';
        
        if($(this).parent().hasClass('active')) {
          dataPath='';
          $(this).parent().removeClass('active');
           var dropdown = jQuery('.group_location_id .winter_dropdown_tree');
            geo_trigger_treefield = true;
            dropdown.find('.list_items li[key=""]').trigger('click');
            geo_trigger_treefield = false;
          return;
        }
        set_path (dataPath)
    })
    
})

/* additional methds for svg map */
jQuery.fn.myAddClass = function (classTitle) {
   return this.each(function() {
     var oldClass = jQuery(this).attr("class") || '';
     oldClass = oldClass ? oldClass : '';
     jQuery(this).attr("class", (oldClass+" "+classTitle).trim());
   });
 }
jQuery.fn.myRemoveClass = function (classTitle) {
   return this.each(function() {
       var oldClass = jQuery(this).attr("class") || '';
       var newClass = oldClass.replace(classTitle, '');
       jQuery(this).attr("class", newClass.trim());
   });
 }
jQuery.fn.myHasClass = function (classTitle) {
    var current_class = jQuery(this).attr("class") || '';
    if(current_class.indexOf(classTitle)=='-1') {
        return false;
    } else {
        return true;
    }
 }

 // map
jQuery(window).load(function($) {
    $ = jQuery
    if($('#svgmap').length) { 
     
    var nameAreaRoot = false;
    var trigger = false;
    var first_load_map = true; 

    var svgobject = document.getElementById('svgmap');
    if ('contentDocument' in svgobject) {             
        var svgdom = jQuery(svgobject.contentDocument);  
    }
    /* colors */
    $('*', svgdom).css('stroke', svg_stroke_color);
    $('*[data-idtreefield]', svgdom).not('.area').css('fill', svg_default_area_color);
    /* end colors */
    
    $('.treefield-tags a:not(#geo-menu-back)').click(function(){
        
        // country hover
        if($('.geo-menu .treefield-tags >li.active >a').attr('data-id-lvl_0'))
            $('*[data-id-lvl_0="'+$('.geo-menu .treefield-tags >li.active >a').attr('data-id-lvl_0').trim()+'"]:not(.highlight)', svgdom).css('fill', svg_selected_country_color);
        
        if($(this).attr('data-id')) {
            if($('*[data-idtreefield="'+$(this).attr('data-id').trim()+'"]', svgdom).length) {
                 trigger = true
                $('*[data-idtreefield="'+$(this).attr('data-id').trim()+'"]', svgdom).trigger('click');
                
            } else {
                $('*[data-idtreefield]', svgdom).myRemoveClass('highlight');
            }
        } 
        else {
            $('*[data-idtreefield]', svgdom).myRemoveClass('highlight');
        }
    })
    
    $('.geo-menu #geo-menu-back').click(function(e){
        e.preventDefault();
        $('*[data-idtreefield]', svgdom).myRemoveClass('highlight');
        $('*[data-idtreefield]', svgdom).not('.area').css('fill', svg_default_area_color);
    })
    
    /* start selected area */
    $('*[data-idtreefield]', svgdom).click(function(){
        
        if($(this).myHasClass('highlight')) {
            $('*[data-idtreefield]', svgdom).myRemoveClass('highlight'); 
            $('*[data-idtreefield]', svgdom).not('.area').css('fill', svg_default_area_color);
           
           if(!trigger && $(this).attr('data-idtreefield')) {
                var dataPath = $(this).attr('data-idtreefield');
                set_path (dataPath);
           }
           return false;
        }
        
        $('*[data-idtreefield]', svgdom).myRemoveClass('highlight');
        $('*[data-idtreefield]', svgdom).not('.area').css('fill', svg_default_area_color);
        
        /* highlight country */ 
        $('*[data-name-lvl_0="'+$(this).attr('data-name-lvl_0').trim()+'"]', svgdom).css('fill', svg_selected_country_color);
        
        $(this).myAddClass('highlight');
        if(!$(this).myHasClass('area'))
            $(this).css('fill', svg_selected_area_color);
        if(!trigger && $(this).attr('data-name-lvl_1') && $(this).attr('data-idtreefield').trim()) {
            var dataPath = $(this).attr('data-idtreefield');
            set_path (dataPath);
        }
        
       trigger = false;
    })
    /* end selected area */  
    
    $('*[data-idtreefield]', svgdom).hover(function(){
        if(!$(this).myHasClass('highlight') && !$(this).myHasClass('area'))
            $(this).css('fill', svg_hover_area_color);
        }, function(){
        if(!$(this).myHasClass('highlight') && !$(this).myHasClass('area'))
            $(this).css('fill', svg_default_area_color);
        
            if($('.geo-menu .treefield-tags >li.active >a').attr('data-id-lvl_0'))
                $('*[data-id-lvl_0="'+$('.geo-menu .treefield-tags >li.active >a').attr('data-id-lvl_0').trim()+'"]:not(.highlight)', svgdom).css('fill', svg_selected_country_color);
        }
    )
    /* end hover area */   
    
    // init map first load with data
    if(first_load_map) {
        var init_dataPath = '<?php echo search_value($treefield_id); ?>' || '<?php echo $root_value;?>' || '';
        hideShow_tags(init_dataPath);     
        
        /* fix proporties svg file from amcharts */
        var attr = $('svg', svgdom).attr('xmlns:amcharts');
        if(typeof attr !== typeof undefined && attr !== false) {
            /*console.log($('svg', svgdom).find('g'));*/
            var _h = $('svg', svgdom).find('g').get(0).getBoundingClientRect().height || 500;
            var _w = $('svg', svgdom).find('g').get(0).getBoundingClientRect().width || 1000;
            $('svg', svgdom).attr('viewBox', '0 0 '+_w+' '+(_h+10)+'');
        }
        
        /* end fix proporties svg file */
       
        var dataPath = '<?php echo search_value($treefield_id); ?>' || '<?php echo $root_value;?>' || '';
        $('.group_location_id input[name="search_location"]').trigger('change');
        set_path (dataPath, false);
    }
    first_load_map = false;
    
    /* start hint */
    $('*[data-idtreefield]', svgdom).hover(function(e){
        var textHin = '';
        $(this).css('cursor', 'pointer');
        var id = $(this).attr('data-idtreefield').trim()
        if(typeof treefields[id] !='undefined') {
            textHint = treefields[id].name;
            
            if(treefields[id].count) 
                textHint+=' '+' ('+treefields[id].count+')';
        } else {
           return false; 
        }
        
        <?php if(sw_count($treefields)>1):?>
        if( treefields[id].parent_id !='0' && typeof treefields[treefields[id].parent_id] !='undefined')
            textHint = textHint.replace(treefields[id].name, treefields[id].name+', '+ treefields[treefields[id].parent_id].name);
        <?php endif;?>
        
        $('body').append('<div id="map-geo-tooltip">'+textHint+'</div>')
        
        var mapCoord = document.getElementById("svgmap").getBoundingClientRect();
        $(this).mouseover(function(){
            $('#map-geo-tooltip').css({opacity:0.8, display:"none"}).fadeIn(200);
        }).mousemove(function(kmouse){
            var max_right = mapCoord.right - 150;
            if(max_right<kmouse.pageX)
                $('#map-geo-tooltip').css({left: 'initial',right:mapCoord.right-kmouse.pageX+10, top:mapCoord.top+kmouse.pageY+10});
            else 
                $('#map-geo-tooltip').css({right: 'initial',left:mapCoord.left+kmouse.pageX+10, top:mapCoord.top+kmouse.pageY+10});
        });
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            setTimeout(function(){ $('#map-geo-tooltip').fadeOut(100).remove();},1000);
        }
    }, function() {
        $('#map-geo-tooltip').fadeOut(100).remove();
    })
    /* end hint */
}
})
 
</script>

<script>
    
// change dropdown tree if exist
jQuery(document).ready(function($) {
    
    $('.group_location_id input[name="search_location"]').change(function(e, trigger){
        if(geo_trigger_treefield) {
            $('.sw-search-start').trigger('click');
            return false;
        }
        var id_region = $(this).val();
        set_path (id_region, true, false);
        /* start selected area */
        
        if($('#svgmap').length && id_region){   
            var svgobject = document.getElementById('svgmap');
            if ('contentDocument' in svgobject) {             
                var svgdom = jQuery(svgobject.contentDocument);  
            }
            $('*[data-idtreefield]', svgdom).myRemoveClass('highlight');
            
            $('*[data-idtreefield]', svgdom).not('.area').css('fill', svg_default_area_color);
            
            $('*[data-idtreefield="'+id_region+'"]', svgdom).myAddClass('highlight');
            $('*[data-idtreefield="'+id_region+'"]', svgdom).not('.area').css('fill', svg_selected_area_color);
        } else if($('#svgmap').length) {
            var svgobject = document.getElementById('svgmap');
            if ('contentDocument' in svgobject) {             
                var svgdom = jQuery(svgobject.contentDocument);  
            }
            $('*[data-idtreefield]', svgdom).myRemoveClass('highlight');
            
            $('*[data-idtreefield]', svgdom).not('.area').css('fill', svg_default_area_color);
            
        }
        /* end selected area */   
        
                
        
    })
})

</script>

<script>
/* for first load */
jQuery(document).ready(function($) {
    var dataPath = '<?php echo search_value($treefield_id); ?>' || '<?php echo $root_value;?>' || '';
    set_path (dataPath, false);
})


function hideShow_tags(parent_seletor) {
    if (typeof parent_seletor === 'undefined') return false;
    if(jQuery('.geo-menu a[data-id="'+parent_seletor+'"]').closest('li').find('ul li').length>18) {
        var _Liselector = jQuery('.geo-menu a[data-id="'+parent_seletor+'"]').closest('li').find('ul li');
        var _count = 0;
        
        if(_Liselector.hasClass('active'))
            _count = 1;
        
        jQuery.each(_Liselector, function(key, value){
            if(!jQuery(this).hasClass('active') && !jQuery(this).find('a').hasClass('more-tags') && _count>18){
                jQuery(this).css('display', 'none');
            } else {
                jQuery(this).css('display', 'inline-block');
            }
            if(!jQuery(this).hasClass('active'))
                _count++;
        })
        
        if(!jQuery('.geo-menu a[data-id="'+parent_seletor+'"]').closest('li').find('ul li .more-tags').length) {
            jQuery('<li> <a href="#" class="more-tags c-base" data-close="true"><?php echo __('more', 'sw_win');?></a></li>').appendTo(jQuery('.geo-menu a[data-id="'+parent_seletor+'"]').closest('li').find('ul')).find('.more-tags').click(function(){
               if(jQuery(this).attr('data-close') == 'true') {
                    jQuery(this).closest('ul').find('li').css('display', 'inline-block');
                    jQuery(this).attr('data-close', 'false').html('<?php echo __('short', 'sw_win');?>')
                } else if(jQuery(this).attr('data-close') == 'false') {
                    hideShow_tags(parent_seletor);
                }
            })
        } else {
          jQuery('a[data-id="'+parent_seletor+'"]').closest('li').find('ul li .more-tags').attr('data-close', 'true').html('<?php echo __('more', 'sw_win');?>')
        }
    } else {
    }
}
</script>
<?php endif;?>