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
class Header_Search extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'background_image' => '',
		),
		array(
			'enable_mask_color' => 'yes',
		),
		array(
			'slogan_title' => 'Discover best properties in one place',
		),
	);

	private $items_num = 3;

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
		return 'header-search';
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
		return __( 'Header Search', 'selio-blocks' );
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
		return 'fa fa-search';
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
        $settings = $this->get_settings();
        
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
                        return;
                }
		
                if(!function_exists('sw_win_load_ci_frontend'))
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.$this->get_title().' '.__('Avaible only with Visual Listings - Agency Directory and Management Plugin ','selio-blocks')
				.'</div>';
				
                        return;
                }
                
		if ( empty( $settings['item_1_background_image']['id'] ) ) {
			$settings['item_1_background_image']['url'] = SELIO_THEMEROOT.'/assets/images/banner/1.jpg';
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
                
                wp_enqueue_script('sw_win_b3_typeahead',plugins_url(SW_WIN_SLUG.'/assets/js/typeahead/bootstrap3-typeahead.js', SW_WIN_PLUGIN_PATH), array('jquery'));
		
                /*
			Fetch listings data
		*/
		
		sw_win_load_ci_frontend();
                $CI = &get_instance();
                // [Load custom view from template]
                if(is_child_theme() && file_exists(get_stylesheet_directory().'/SW_Win_Classified/views/'))
                {
                    $CI->load->add_package_path(get_stylesheet_directory().'/SW_Win_Classified/');
                } 
                else if(file_exists(get_template_directory().'/SW_Win_Classified/views/')) 
                {
                    $CI->load->add_package_path(get_template_directory().'/SW_Win_Classified/');
                }
                // [/Load custom view from template]

		/*
			Fetch listings data
		*/
                

		?>
<h2 class="vis-hid"><?php echo esc_html__('Headser elementor','selio-blocks');?></h2>
<section class="banner section-header-search <?php if($settings['item_2_enable_mask_color'] == 'no'): ?> no-mask <?php endif;?>" style="background-image:url('<?php echo $settings['item_1_background_image']['url']; ?>')">
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="banner-content">
				<h2 <?php echo $this->get_render_attribute_string( 'item_3_slogan_title' ); ?>><?php echo $settings['item_3_slogan_title']; ?></h2>
			</div>
                    <?php if(Plugin::$instance->editor->is_edit_mode()): ?>
                        <form action="#" class="row banner-search">
				<div class="form_field addres banner_search_show">
					<input type="text" class="form-control" placeholder="Enter Address, City or State">
				</div>
				<div class="form_field tpmax banner_search_show">
					<div class="form-group">
						<div class="drop-menu">
							<div class="select">
								<span>Any type</span>
								<i class="fa fa-angle-down"></i>
							</div>
							<input type="hidden" name="gender">
							<ul class="dropeddown">
								<li>For Sale</li>
								<li>For Rent</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="form_field tpmax banner_search_show">
					<div class="form-group">
						<div class="drop-menu">
							<div class="select">
								<span>Min Price</span>
								<i class="fa fa-angle-down"></i>
							</div>
							<input type="hidden" name="gender">
							<ul class="dropeddown">
								<li>300$</li>
								<li>400$</li>
								<li>500$</li>
								<li>200$</li>
								<li>600$</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="form_field tpmax banner_search_show">
					<div class="form-group">
						<div class="drop-menu">
							<div class="select">
								<span>Max Price</span>
								<i class="fa fa-angle-down"></i>
							</div>
							<input type="hidden" name="gender">
							<ul class="dropeddown">
								<li>2000</li>
								<li>3000</li>
								<li>4000</li>
								<li>5000</li>
								<li>6000</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="form_field srch-btn">
					<a href="#" class="btn btn-outline-primary ">
						<i class="la la-search"></i>
						<span>Search</span>
					</a>
				</div>
			</form>
                    <?php else:?>
                    
                        <form action="#" class="row banner-search sw_search_primary">
                                <?php _search_form_primary(1); ?>
				<div class="form_field srch-btn">
					<button id="search_header_button" type="submit" class="btn btn-outline-primary ">
						<i class="la la-search"></i>
						<span> <?php esc_html_e('Buscar', 'selio-blocks');?></span>
                                                <i class="fa fa-spinner fa-spin fa-ajax-indicator" style="display: none;"></i>
					</button>
				</div>
			</form>
                    
                    <?php endif;?>

		</div>
	</div>
</div>
</section>

	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	<script>	
	
	</script>
	<?php endif; ?>
        
        

	<script>	
		jQuery(document).ready(function($){
                    if(typeof $.fn.typeahead  === 'function'){
                        
                    $('#location,#search_where').typeahead({
                        minLength: 2,
                        source: function(query, process) {
                            var data = { q: query, limit: 8 };

                            $.extend( data, {
                               'page': 'frontendajax_locationautocomplete',
                               'action': 'ci_action',
                               'template': '<?php echo esc_html(basename(get_page_template()));?>'
                            });

                            $.post('<?php echo esc_url(admin_url( 'admin-ajax.php' ));?>', data, function(data) {
                                //console.log(data); // data contains array
                                process(data);
                            });
                        }
                    });
                    }

			jQuery('#search_header_button').on('click',function(){
                                jQuery(this).find(".fa-ajax-indicator").show();
                                jQuery(this).find(".fa-ajax-hide").hide();
				//Define default data values for search
				var data = { };
				
				// Add custom data values, automatically by fields inside search-form
				jQuery('form.sw_search_primary input, form.sw_search_primary select, '+
				'form.sw_search_secondary input, form.sw_search_secondary select').each(function (i) {
					
					if(jQuery(this).attr('type') == 'checkbox')
					{
						if (jQuery(this).attr('checked'))
						{
							data[jQuery(this).attr('name')] = jQuery(this).val();
						}
					}
					else if(jQuery(this).val() != '' && jQuery(this).val() != 0&& jQuery(this).val() != null)
					{
						data[jQuery(this).attr('name')] = jQuery(this).val();
					}
					
				});

				<?php
				$page_link = '';
				
				// get results page ID
				$results_page_id = sw_settings('results_page');
				if(!empty($results_page_id))
				{
					// get results page link
					$page_link = get_page_link($results_page_id);
				}
					
				?>
				
				var gen_url = selio_generateUrl("<?php echo $page_link; ?>", data)+"#header-search";

				window.location = gen_url;

				return false;
			});


		});

		function selio_generateUrl(url, params) {
			var i = 0, key;
			for (key in params) {
				if (i === 0 && url.indexOf("?")===-1) {
					url += "?";
				} else {
					url += "&";
				}
				url += key;
				url += '=';
				url += params[key];
				i++;
			}
			return url;
		}

            </script>
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
