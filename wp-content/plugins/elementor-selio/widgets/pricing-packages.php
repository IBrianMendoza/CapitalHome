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
class Pricing_Packages extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Perfect Price',
			'big_title' => 'Explore Our Pricing',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'pac_title' => 'Basic',
			'pac_price' => 'Free',
			'pac_line_1' => 'One Property Included',
			'pac_line_2' => '90 days expiration',
			'pac_line_3' => 'Rent and sell real estate',
			'pac_line_4' => 'Image Gallery',
			'pac_line_5' => '30 Days Support',
			'pac_link' => '#',
			'pac_button_text' => 'Select Plan',
		),
		array(
			'pac_title' => 'Standard',
			'pac_price' => '$25.50',
			'pac_line_1' => 'Five Property Included',
			'pac_line_2' => '180 days expiration',
			'pac_line_3' => 'Rent and sell real estate',
			'pac_line_4' => 'Image Gallery',
			'pac_line_5' => '90 Days Support',
			'pac_link' => '#',
			'pac_button_text' => 'Select Plan',
		),
		array(
			'pac_title' => 'Premium',
			'pac_price' => '$99.00',
			'pac_line_1' => 'Unlimited Property Included',
			'pac_line_2' => '280 days expiration',
			'pac_line_3' => 'Rent and sell real estate',
			'pac_line_4' => 'Image Gallery',
			'pac_line_5' => '180 Days Support',
			'pac_link' => '#',
			'pac_button_text' => 'Select Plan',
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
		return 'pricing-packages';
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
		return __( 'Pricing Packages', 'selio-blocks' );
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
		return 'eicon-price-table';
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
				
				if(substr_count($key, 'pac') > 0)
					$gen_label = $i.'. '.ucwords(str_replace('_', ' ', $key));

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
				elseif(substr_count($key, 'view') > 0)
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::SELECT,
							'options' => [
								'' => __( 'Standard grid', 'selio-blocks' ),
								'flexbox' => __( 'Flexbox', 'selio-blocks' ),
							],
							'default' => '',
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
                   substr_count($gen_item, 'line') > 0 ||
                   substr_count($gen_item, 'price') > 0 )
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
				elseif(substr_count($gen_item, 'button') > 0)
				{
					$this->add_inline_editing_attributes( $gen_item, 'basic' );
					$this->add_render_attribute( $gen_item, [
						'class' => 'btn btn-default'
					] );
				}
				else
				{

				}

			}
		}

		?>
<h2 class="vis-hid"><?php echo esc_html__('Pricing elementor','selio-blocks');?></h2>
<section class="pricing-sec section-padding section-pricing-packages <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
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
		<div class="price-sec">
			<div class="row">
			<?php for($i=1;$i<$this->items_num+1;$i++): ?>
			<?php if(!empty($settings['item_'.$i.'_pac_title'])): ?>
				<div class="col-lg-4 col-md-6">
					<div class="price complete_box">
						<h4 <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_title' ); ?>><?php echo $settings['item_'.$i.'_pac_title']; ?></h4>
						<h2 <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_price' ); ?>><?php echo $settings['item_'.$i.'_pac_price']; ?></h2>
						<ul>
							<li <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_line_1' ); ?>><?php echo $settings['item_'.$i.'_pac_line_1']; ?></li>
							<li <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_line_2' ); ?>><?php echo $settings['item_'.$i.'_pac_line_2']; ?></li>
							<li <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_line_3' ); ?>><?php echo $settings['item_'.$i.'_pac_line_3']; ?></li>
							<li <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_line_4' ); ?>><?php echo $settings['item_'.$i.'_pac_line_4']; ?></li>
							<li <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_line_5' ); ?>><?php echo $settings['item_'.$i.'_pac_line_5']; ?></li>
						</ul>
						<a href="<?php echo $settings['item_'.$i.'_pac_link']; ?>" title="<?php echo $settings['item_'.$i.'_pac_title']; ?>" <?php echo $this->get_render_attribute_string( 'item_'.$i.'_pac_button_text' ); ?>><?php echo $settings['item_'.$i.'_pac_button_text']; ?></a>
                                                <a href="<?php echo $settings['item_'.$i.'_pac_link']; ?>" title="<?php echo $settings['item_'.$i.'_pac_title']; ?>" class="complete_box_link"></a>
					</div><!--price end-->
				</div>
			<?php endif; ?>
			<?php endfor; ?>

			</div>
		</div>
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
