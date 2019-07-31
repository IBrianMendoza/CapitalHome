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
class Slogan extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'slogan_title' => 'Discover a home you\'ll love to stay',
			'slogan_link' => '#',
			'slogan_color' => '#303e94',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'inspiring_enable' => 'no',
			'inspiring_message' => 'Aenean sollicitudin, lorem quis bibendum aucto elit consequat ipsumas nec sagittis sem nibh id elit. Duis sed odio sit amet nibhulpu tate cursus a sit amet mauris. Morbi accumsan ipsum torquent.',
			'inspiring_buton_text' => 'Get Started Now',
			'inspiring_buton_link' => '',
			'inspiring_background_image' => '',
		),
	);

	private $items_num = 2;

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
		return 'slogan';
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
		return __( 'Slogan', 'selio-blocks' );
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
		return 'fa fa-text-width';
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
				elseif(substr_count($key, 'color') > 0)
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::COLOR,
							'scheme' => [
								'type' => \Elementor\Scheme_Color::get_type(),
								'value' => \Elementor\Scheme_Color::COLOR_1,
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
		
		/*
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
		}
		*/
		
		if ( empty( $settings['item_2_inspiring_background_image']['id'] ) ) {
			$settings['item_2_inspiring_background_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/discov-bg.jpg';
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
                   substr_count($gen_item, 'text') > 0 ||
                   substr_count($gen_item, 'message') > 0 )
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
<h2 class="vis-hid"><?php echo esc_html__('Slogan elementor','selio-blocks');?></h2>
<?php if($settings['item_2_inspiring_enable'] != 'yes'): ?>

<a href="<?php echo $settings['item_1_slogan_link']; ?>" class="section-slogan" title="">
	<section class="cta st2 section-padding" style="background-color:<?php echo $settings['item_1_slogan_color']; ?>">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="cta-text">
						<h2 <?php echo $this->get_render_attribute_string( 'item_1_slogan_title' ); ?>><?php echo $settings['item_1_slogan_title']; ?></h2>
					</div>
				</div>
			</div>
		</div>
	</section>
</a>

<?php else: ?>

<section class="discover-propt section-slogan-discover" style="background-image: url(<?php echo $settings['item_2_inspiring_background_image']['url']; ?>);">
	<div class="overlay-bg" style="background-color:<?php echo $settings['item_1_slogan_color']; ?>"></div>
	<div class="container">
		<div class="discover-text">
			<h3 <?php echo $this->get_render_attribute_string( 'item_1_slogan_title' ); ?>><?php echo $settings['item_1_slogan_title']; ?></h3>
			<p <?php echo $this->get_render_attribute_string( 'item_2_inspiring_message' ); ?>><?php echo $settings['item_2_inspiring_message']; ?></p>
			<?php if(!empty($settings['item_2_inspiring_buton_link'])): ?>
			<a href="<?php echo $settings['item_2_inspiring_buton_link']; ?>" title="" class="btn btn-default"><?php echo $settings['item_2_inspiring_buton_text']; ?></a>
			<?php endif; ?>
		</div><!--discover-text end-->
	</div>
</section><!--discover-propt end-->

<?php endif; ?>

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
