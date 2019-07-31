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
class Categories_Presentation extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'main_image' => '',
			'main_title' => 'Homes around the world',
			'main_subtitle' => '',
			'main_description' => 'Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat consequat auctor eu in elit. ',
			'button_text' => 'View for rent',
			'button_link' => '#',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'category_title' => 'Homes',
			'category_image' => '',
			'category_link' => '#',
		),
		array(
			'category_title' => 'Appartments',
			'category_image' => '',
			'category_link' => '#',
		),
		array(
			'category_title' => 'Garages',
			'category_image' => '',
			'category_link' => '#',
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
		return 'categories-presentation';
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
		return __( 'Categories Presentation', 'selio-blocks' );
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
		return 'fa fa-list-ul';
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
		
		if ( empty( $settings['item_1_main_image']['id'] ) ) {
                    if(!empty($settings['item_1_main_subtitle'])){ 
			$settings['item_1_main_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/about-img.jpg';
                    } else {
			$settings['item_1_main_image']['url'] = SELIO_THEMEROOT.'/assets/images/intro/1.jpg';
                    }
                        
		}

		if ( empty( $settings['item_2_category_image']['id'] ) ) {
			$settings['item_2_category_image']['url'] = SELIO_THEMEROOT.'/assets/images/intro/thumb1.jpg';
		}

		if ( empty( $settings['item_3_category_image']['id'] ) ) {
			$settings['item_3_category_image']['url'] = SELIO_THEMEROOT.'/assets/images/intro/thumb2.jpg';
		}

		if ( empty( $settings['item_4_category_image']['id'] ) ) {
			$settings['item_4_category_image']['url'] = SELIO_THEMEROOT.'/assets/images/intro/thumb3.jpg';
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

<h2 class="vis-hid"><?php echo esc_html__('Category elementor','selio-blocks');?></h2>
	<section class="intro section-padding section-categories-presentation <?php echo !empty($settings['item_1_main_subtitle'])?'alt':''; ?> <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
		<div class="container">
			<div class="row">
				<div class="col-xl-6 col-lg-6 pl-0">
					<div class="intro-content <?php echo !empty($settings['item_1_main_subtitle'])?'alternative':''; ?>">
						<h3 <?php echo $this->get_render_attribute_string( 'item_1_main_title' ); ?>><?php echo $settings['item_1_main_title']; ?></h3>
						<?php if(!empty($settings['item_1_main_subtitle'])): ?>
						<h4 <?php echo $this->get_render_attribute_string( 'item_1_main_subtitle' ); ?>><?php echo $settings['item_1_main_subtitle']; ?></h4>
						<?php endif; ?>
						<p <?php echo $this->get_render_attribute_string( 'item_1_main_description' ); ?>><?php echo $settings['item_1_main_description']; ?></p>
						<?php if(!empty($settings['item_1_button_text'])): ?>
						<a href="<?php echo $settings['item_1_button_link']; ?>" class="btn btn-outline-primary view-btn">
							<i class="icon-arrow-right-circle"></i><?php echo $settings['item_1_button_text']; ?></a>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 pr-0">
					<div class="intro-img">
						<img src="<?php echo $settings['item_1_main_image']['url']; ?>" alt="" class="img-fluid">
					</div>
				</div>
			</div>
                        <?php if(!empty($settings['item_2_category_title']) || !empty($settings['item_3_category_title']) || !empty($settings['item_4_category_title'])): ?>
			<div class="intro-thumb-row">
				<?php if(!empty($settings['item_2_category_title'])): ?>
				<a href="<?php echo $settings['item_2_category_link']; ?>" class="intro-thumb">
					<img src="<?php echo $settings['item_2_category_image']['url']; ?>" alt="">
					<h6 <?php echo $this->get_render_attribute_string( 'item_2_category_title' ); ?>><?php echo $settings['item_2_category_title']; ?></h6>
				</a>
				<?php endif; ?>
				<?php if(!empty($settings['item_3_category_title'])): ?>
				<a href="<?php echo $settings['item_3_category_link']; ?>" class="intro-thumb">
					<img src="<?php echo $settings['item_3_category_image']['url']; ?>" alt="">
					<h6 <?php echo $this->get_render_attribute_string( 'item_3_category_title' ); ?>><?php echo $settings['item_3_category_title']; ?></h6>
				</a>
				<?php endif; ?>
				<?php if(!empty($settings['item_4_category_title'])): ?>
				<a href="<?php echo $settings['item_4_category_link']; ?>" class="intro-thumb">
					<img src="<?php echo $settings['item_4_category_image']['url']; ?>" alt="">
					<h6 <?php echo $this->get_render_attribute_string( 'item_4_category_title' ); ?>><?php echo $settings['item_4_category_title']; ?></h6>
				</a>
				<?php endif; ?>
			</div>
                        <?php endif; ?>
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
