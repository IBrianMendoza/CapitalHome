<?php
namespace ElementorSelio\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Editor;
use Elementor\Plugin;
use Top_Nav_Menu_Walker;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class Map_Svg extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		
		
		array(
			'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		

	);

	private $items_num = 1;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'map-svg';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Map Svg', 'selio-blocks' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-map';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'winter-themes' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {


		if(!isset($this->defaults[0]))return;

		$this->start_controls_section(
			'section_elements',
			[
				'label' => __( 'Block elements', 'selio-blocks' ),
			]
		);
                
                $this->add_control(
			'auto_search',
			[
				'label' => __( 'Auto Search', 'selio-blocks' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'True', 'selio-blocks' ),
				'label_off' => __( 'False', 'selio-blocks' ),
				'return_value' => 'true',
				'default' => '',
			]
		);


		for($i=0;$i<$this->items_num;$i++)
		{

			$item_elements = $this->defaults[0];

			if(isset($this->defaults[$i]))
			{
				$item_elements = $this->defaults[$i];
			}

			foreach($item_elements as $key=>$val)
			{

				$gen_item = 'item_'.($i+1).'_'.$key;

				$gen_label = ucwords(str_replace('_', ' ', $key));

				if(substr_count($key, 'link') > 0)
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::TEXT,
							'default' => $val,
						]
					);
				}
				elseif(substr_count($key, 'enable') > 0)
					{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::SWITCHER,
							'options' => [
								'yes' => __( 'Yes', 'selio-blocks' ),
								'no' => __( 'No', 'selio-blocks' ),
							],
							'default' => $val,
						]
					);
				}
				elseif(substr_count($key, 'image') > 0)
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::MEDIA,
							'default' => [
								'url' => Utils::get_placeholder_image_src(),
							]
						]
					);
				}
				elseif(substr_count($key, 'description') > 0)
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::TEXT,
							'default' => $val,
						]
					);
				}
				else
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::TEXT,
							'default' => $val,
						]
					);
				}
			}
		}

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
        	protected function render() {
                    
                if(!function_exists('sw_win_load_ci_frontend'))
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.$this->get_title().' '.__('Avaible only with Visual Listings - Agency Directory and Management Plugin ','selio-blocks')
				.'</div>';
				
                        return;
                }
                    
		$settings = $this->get_settings();

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'slogan', 'basic' );

		$this->add_render_attribute( 'title', [
			'class' => 'title elementor-inline-editing'
		] );

		$this->add_render_attribute( 'slogan', [
			'class' => 'geomap-title'
		] );
sw_win_load_ci_frontend();
$CI = &get_instance();
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
    
    $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
    $conditions['search_location'] = $options->idtreefield;
    prepare_frontend_search_query_GET('listing_m', $conditions);
    $treefield['count'] = $CI->listing_m->total_lang(array(), sw_current_language_id());
    
    
    $ariesInfo[$val->idtreefield]['name']=$treefield['title'];
    $ariesInfo[$val->idtreefield]['parent_id']=$treefield['parent_id'];
    $ariesInfo[$val->idtreefield]['count']=$treefield['count'];
     
    $childs = array();
    if (isset($tree_listings[$val->idtreefield]) && count($tree_listings[$val->idtreefield]) > 0)
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
var svg_default_area_color = '#fff' /* default color*/
var svg_selected_area_color = '#6a7be7' /* selected color*/
var svg_hover_area_color = '#6a7be7' /* hover color*/
var svg_selected_country_color = '#fff';
var svg_stroke_color = '#000';
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
            if(dataPath_origin !='')
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
            selio_hideShow_tags(selector);
        }
        firstload = false;
}

/* menu geo */
jQuery(document).ready(function($) {

    // if search_option_$treefield_id input missing
    /*if(!$('.search_option_<?php echo $treefield_id;?>').length) {
        $('.sw_search_primary').append('<input type="text" class="hidden form-control search_option_<?php echo $treefield_id;?>" name="search_option_<?php echo $treefield_id;?>" value="" id="search_option_<?php echo $treefield_id;?>">')
    }*/
    
    $('.geo-menu a').on('click',function(e){
        e.preventDefault();
        var dataPath =  $(this).attr('data-id')  || '';
        
        if($(this).parent().hasClass('active')) {
          dataPath='';
          $(this).parent().removeClass('active');
          if(jQuery('.group_location_id .winter_dropdown_tree').length){
           var dropdown = jQuery('.group_location_id .winter_dropdown_tree');
            geo_trigger_treefield = true;
            dropdown.find('.list_items li[key=""]').trigger('click');
            geo_trigger_treefield = false;
        } else {
            jQuery('.search_option_<?php echo $treefield_id;?>').val(dataPath)
        }
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
    $('*', svgdom).css('stroke-width', '3px');
    $('*[data-idtreefield]', svgdom).not('.area').css({'fill':svg_default_area_color, 'transition':'fill .6s', '-webkit-transtion':'fill .6s' });
    /* end colors */
    
    $('.treefield-tags a:not(#geo-menu-back)').on('click',function(){
        
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
    
    $('.geo-menu #geo-menu-back').on('click',function(e){
        e.preventDefault();
        $('*[data-idtreefield]', svgdom).myRemoveClass('highlight');
        $('*[data-idtreefield]', svgdom).not('.area').css('fill', svg_default_area_color);
    })
    
    /* start selected area */
    $('*[data-idtreefield]', svgdom).on('click',function(){
        
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
        
        <?php if($settings['auto_search'] == 'true'):?>
        if(!firstload && !trigger) {
           $('.sw-search-start,#search_header_button').trigger('click'); 
        }
        <?php endif;?>
       trigger = false;
    })
    /* end selected area */  
    
    $('*[data-idtreefield]', svgdom).on({
        'mouseover' : function (e) {
                        if(!$(this).myHasClass('highlight') && !$(this).myHasClass('area'))
                            $(this).css('fill', svg_hover_area_color);
                    },
        'mouseout' :function (e) {
                        if(!$(this).myHasClass('highlight') && !$(this).myHasClass('area'))
                        $(this).css('fill', svg_default_area_color);

                        if($('.geo-menu .treefield-tags >li.active >a').attr('data-id-lvl_0'))
                            $('*[data-id-lvl_0="'+$('.geo-menu .treefield-tags >li.active >a').attr('data-id-lvl_0').trim()+'"]:not(.highlight)', svgdom).css('fill', svg_selected_country_color);
                    }
    });
    /* end hover area */   
    
    // init map first load with data
    if(first_load_map) {
        var init_dataPath = '<?php echo search_value($treefield_id); ?>' || '<?php echo $root_value;?>' || '';
        selio_hideShow_tags(init_dataPath);     
        
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
    
    $('*[data-idtreefield]', svgdom).on({
            'mouseover' : function (e) {
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

                            <?php if(count($treefields)>1):?>
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
                        },
            'mouseout' :function () {
                             $('#map-geo-tooltip').fadeOut(100).remove();
                    },
    });
    /* end hint */
}
})
 
</script>

<script>
    
// change dropdown tree if exist
jQuery(document).ready(function($) {
    
    $('.group_location_id input[name="search_location"]').change(function(e, trigger){
        if(geo_trigger_treefield && false) {
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


function selio_hideShow_tags(parent_seletor) {
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
            jQuery('<li> <a href="#" class="more-tags c-base" data-close="true"><?php echo __('more', 'selio-blocks');?></a></li>').appendTo(jQuery('.geo-menu a[data-id="'+parent_seletor+'"]').closest('li').find('ul')).find('.more-tags').on('click',function(){
               if(jQuery(this).attr('data-close') == 'true') {
                    jQuery(this).closest('ul').find('li').css('display', 'inline-block');
                    jQuery(this).attr('data-close', 'false').html('<?php echo __('short', 'selio-blocks');?>')
                } else if(jQuery(this).attr('data-close') == 'false') {
                    selio_hideShow_tags(parent_seletor);
                }
            })
        } else {
          jQuery('a[data-id="'+parent_seletor+'"]').closest('li').find('ul li .more-tags').attr('data-close', 'true').html('<?php echo __('more', 'selio-blocks');?>')
        }
    } else {
    }
}
</script>
            <?php endif;?>         
<h2 class="vis-hid"><?php echo esc_html__('Svg elementor','selio-blocks');?></h2>
            <?php
                //if(true):
                if(Plugin::$instance->editor->is_edit_mode()):
                ?>
                <section class="map-sec">
                    <h3 class="vis-hid">Invisible</h3>
                    <div class="container">
                            <div class="map-details">
                                    <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                    <div class="map-city-links">
                                                            <ul>
                                                                    <li><a href="#" title="">Nevada</a></li>
                                                                    <li><a href="#" title="">Oklahoma</a></li>
                                                                    <li><a href="#" title="">New Hampshire</a></li>
                                                                    <li><a href="#" title="">Oregon</a></li>
                                                                    <li><a href="#" title="">New Jersey</a></li>
                                                                    <li><a href="#" title="">Pennsylvania</a></li>
                                                                    <li><a href="#" title="">New Mexico</a></li>
                                                                    <li><a href="#" title="">Rhode Island</a></li>
                                                                    <li><a href="#" title="">New York</a></li>
                                                                    <li><a href="#" title="">South Carolina</a></li>
                                                                    <li><a href="#" title="">North Carolina</a></li>
                                                                    <li><a href="#" title="">South Dakota</a></li>
                                                                    <li><a href="#" title="">North Dakota</a></li>
                                                                    <li><a href="#" title="">Tennessee</a></li>
                                                                    <li><a href="#" title="">Ohio</a></li>
                                                                    <li><a href="#" title="">Texas</a></li>
                                                            </ul>
                                                    </div><!--map-city-links end-->
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                    <div id="map-svg"></div>
                                            </div>
                                    </div>
                            </div><!--map-details end-->
                    </div>
                </section>
		<?php else: ?>
         <section class="map-sec">
                    <h3 class="vis-hid">Invisible</h3>
                    <div class="container">
                            <div class="map-details">
                                    <div class="map-geo-widget">
                                                <?php if(!$widget_fatal_error):?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                            <div class="geo-menu">
                                                                <ul class="treefield-tags">
                                                                    <?php foreach ($treefields as $key => $item) : ?>
                                                                        <li class=''><a href="#<?php echo str_replace(' ', '-', $item['title']); ?>" data-id-lvl_0="<?php _che($item['id']); ?>" data-id='<?php _che($item['id']); ?>'><?php _che($item['title']); ?></a>
                                                                            <ul class='' id="<?php echo str_replace(' ', '-', $item['title']); ?>">
                                                                                <li><a href="#" id='geo-menu-back' data-path=''> <i class="fa fa-arrow-left"></i> <?php echo __('back','selio-blocks'); ?> </a></li>
                                                                                <?php if (count($item['childs']) > 0): ?> 
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
                                                        <div class="col-md-6">
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
                                                <?php echo __('Map didn`t create, please contact with administrator', 'selio-blocks');?>
                                                </p>
                                            <?php endif;?>
                                        </div>
                            </div><!--map-details end-->
                    </div>
                </section>
		<?php endif; ?>
                <?php
	}
        
    /**
     * Helper function calculation counts in tree
     *
     * @access private
     */    
        
    private function _recursion_calc_count ($result_count, $tree_listings, $parent_title, $id, &$child_count){
        if (isset($tree_listings[$id]) && count($tree_listings[$id]) > 0){
            foreach ($tree_listings[$id] as $key => $_child) {
                $options = $tree_listings[$_child->parent_id][$_child->idtreefield];
                if(isset($result_count[$options->idtreefield]))
                    $child_count+= $result_count[$options->idtreefield];

                $_parent_title = '';
                if (isset($tree_listings[$_child->idtreefield]) && count($tree_listings[$_child->idtreefield]) > 0){    
                    $this->_recursion_calc_count($result_count, $tree_listings, $_parent_title, $_child->idtreefield, $child_count);
                }
            }
        }
    }

        
        
        
	protected function render__old() {
        $settings = $this->get_settings();
        
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
        }

		for($i=0;$i<$this->items_num;$i++)
		{

			$item_elements = $this->defaults[0];

			if(isset($this->defaults[$i]))
			{
				$item_elements = $this->defaults[$i];
			}

			foreach($item_elements as $key=>$val)
			{

				$gen_item = 'item_'.($i+1).'_'.$key;

                if(substr_count($gen_item, 'title') > 0 ||
                   substr_count($gen_item, 'address') > 0 ||
                   substr_count($gen_item, 'number') > 0 )
				{

					$this->add_inline_editing_attributes( $gen_item, 'basic' );
					$this->add_render_attribute( $gen_item, [
						'class' => ''
					] );
				}
				elseif(substr_count($gen_item, 'description') > 0)
				{
					$this->add_inline_editing_attributes( $gen_item, 'basic' );
					$this->add_render_attribute( $gen_item, [
						'class' => ''
					] );
                }
				else
				{

				}

			}
		}

		?>

<section class="map-sec">
<h3 class="vis-hid">Invisible</h3>
<div class="container">
	<div class="map-details">
		<div class="row">
			<div class="col-lg-6 col-md-6">
				<div class="map-city-links">
					<ul>
						<li><a href="#" title="">Nevada</a></li>
						<li><a href="#" title="">Oklahoma</a></li>
						<li><a href="#" title="">New Hampshire</a></li>
						<li><a href="#" title="">Oregon</a></li>
						<li><a href="#" title="">New Jersey</a></li>
						<li><a href="#" title="">Pennsylvania</a></li>
						<li><a href="#" title="">New Mexico</a></li>
						<li><a href="#" title="">Rhode Island</a></li>
						<li><a href="#" title="">New York</a></li>
						<li><a href="#" title="">South Carolina</a></li>
						<li><a href="#" title="">North Carolina</a></li>
						<li><a href="#" title="">South Dakota</a></li>
						<li><a href="#" title="">North Dakota</a></li>
						<li><a href="#" title="">Tennessee</a></li>
						<li><a href="#" title="">Ohio</a></li>
						<li><a href="#" title="">Texas</a></li>
					</ul>
				</div><!--map-city-links end-->
			</div>
			<div class="col-lg-6 col-md-6">
				<div id="map-svg"></div>
			</div>
		</div>
	</div><!--map-details end-->
</div>
</section>

	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
	<script>	
	
	</script>

	<style>
		#map-container.fullwidth-home-map
		{
			height:auto;
		}
	</style>
	
	<?php endif; ?>
	<?php
	
		self::$multiple_instance = true;
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
	}
}
