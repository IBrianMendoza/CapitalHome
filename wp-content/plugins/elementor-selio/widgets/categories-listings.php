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
class Categories_Listings extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Our Blog',
			'big_title' => 'Recent Posts',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'category_limit' => '4',
		),
	);

	private $items_num = 4;

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
		return 'categories-listings';
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
		return __( 'Categories Listings', 'selio-blocks' );
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
		return 'eicon-apps';
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

				if(substr_count($key, 'category') > 0)
				{
					$gen_label = $i.'. '.ucwords(str_replace('_', ' ', $key));
				}

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
        
        //dump($settings);

        /*
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
        }
		*/

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

<h2 class="vis-hid"><?php echo esc_html__('Listings elementor','selio-blocks');?></h2>
<section class="categories-sec section-padding">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-6">
					<div class="section-heading">
					<?php if(!empty($settings['item_1_mini_title'])): ?>
					<span <?php echo $this->get_render_attribute_string( 'item_1_mini_title' ); ?>><?php echo $settings['item_1_mini_title']; ?></span>
					<?php endif; ?>

					<?php if(!empty($settings['item_1_big_title'])): ?>
					<h3 <?php echo $this->get_render_attribute_string( 'item_1_big_title' ); ?>><?php echo $settings['item_1_big_title']; ?></h3>
					<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="categories-details">
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6 col-6 full">
						<div class="categories-info">
							<a href="#" title="">
								<div class="catg-icon">
									<i class="la la-home"></i>
								</div>
							</a>
							<h3><a href="#" title="">Houses for Sale</a></h3>
							<a href="24_Property_Single.html" title="" class="ext-link"></a>
						</div><!--categories-info end-->
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-6 full">
						<div class="categories-info">
							<a href="#" title="">
								<div class="catg-icon">
									<i class="la la-building"></i>
								</div>
							</a>
							<h3><a href="#" title="">Apartments</a></h3>
							<a href="24_Property_Single.html" title="" class="ext-link"></a>
						</div><!--categories-info end-->
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-6 full">
						<div class="categories-info">
							<a href="#" title="">
								<div class="catg-icon">
									<i class="la la-folder"></i>
								</div>
							</a>
							<h3><a href="#" title="">Remodeled</a></h3>
							<a href="24_Property_Single.html" title="" class="ext-link"></a>
						</div><!--categories-info end-->
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-6 full">
						<div class="categories-info">
							<a href="#" title="">
								<div class="catg-icon">
									<i class="la la-university"></i>
								</div>
							</a>
							<h3><a href="#" title="">New Construction</a></h3>
							<a href="24_Property_Single.html" title="" class="ext-link"></a>
						</div><!--categories-info end-->
					</div>
				</div>
			</div><!--categories-details end-->
		</div>
	</section>


	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
	<script>	
	
	</script>
	
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
