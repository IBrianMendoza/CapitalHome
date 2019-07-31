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
class Clients_Say extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Clients Say',
			'big_title' => 'Testimonials',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'client_image' => '',
			'client_message' => 'Aenean sollicitudin, lorem quis bibendum auctor, nisi elitco nsequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accum sanpsum velit. Nam nec tellus a odio tinci.',
			'client_name' => 'Kritsofer Nolan',
			'client_position' => 'Property Owner',
		),
		array(
			'client_image' => '',
			'client_message' => 'Aenean sollicitudin, lorem quis bibendum auctor, nisi elitco nsequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accum sanpsum velit. Nam nec tellus a odio tinci.',
			'client_name' => 'Kritsofer Nolan',
			'client_position' => 'Property Owner',
		),
		array(
			'client_image' => '',
			'client_message' => 'Aenean sollicitudin, lorem quis bibendum auctor, nisi elitco nsequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accum sanpsum velit. Nam nec tellus a odio tinci.',
			'client_name' => 'Kritsofer Nolan',
			'client_position' => 'Property Owner',
		),
		array(
			'client_image' => '',
			'client_message' => 'Aenean sollicitudin, lorem quis bibendum auctor, nisi elitco nsequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accum sanpsum velit. Nam nec tellus a odio tinci.',
			'client_name' => 'Kritsofer Nolan',
			'client_position' => 'Property Owner',
		),
	);

	private $items_num = 7;

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
		return substr(str_replace('_', '-', strtolower(get_class($this))), strrpos(get_class($this), '\\')+1);
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
		return __( 'Clients Say', 'selio-blocks' );
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
		return 'eicon-comments';
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

			$item_elements = $this->defaults[1];

			if(isset($this->defaults[$i]))
			{
				$item_elements = $this->defaults[$i];
			}

			foreach($item_elements as $key=>$val)
			{

				$gen_item = 'item_'.($i+1).'_'.$key;

				$gen_label = ucwords(str_replace('_', ' ', $key));

				if(substr_count($key, 'service') > 0)
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
				elseif(substr_count($key, 'description') > 0 || 
					   substr_count($key, 'message') > 0)
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
		
		
		if ( empty( $settings['item_3_client_image']['id'] ) ) {
			$settings['item_3_client_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/cm-img3.png';
		}

		if ( empty( $settings['item_4_client_image']['id'] ) ) {
			$settings['item_4_client_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/cm-img2.png';
		}

		if ( empty( $settings['item_5_client_image']['id'] ) ) {
			$settings['item_5_client_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/cm-img3.png';
		}

		if ( empty( $settings['item_2_client_image']['id'] ) ) {
			$settings['item_2_client_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/cm-img2.png';
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
				elseif(substr_count($gen_item, 'description') > 0 ||
						substr_count($gen_item, 'message') > 0)
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
<h2 class="vis-hid"><?php echo esc_html__('Clients say elementor','selio-blocks');?></h2>
<section class="testimonial-sec section-padding hp2 section-clients-say <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
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
		<div class="row testimonail-sect">
			<div class="comment-carousel">

			<?php for($i=2;$i<$this->items_num+1;$i++): ?>
			<?php if(!empty($settings['item_'.$i.'_client_message'])): ?>

				<div class="comment-info">
					<p <?php echo $this->get_render_attribute_string( 'item_'.$i.'_client_message' ); ?>><?php echo $settings['item_'.$i.'_client_message']; ?></p>
					<div class="cm-info-sec">
						<div class="cm-img">
							<img src="<?php echo $settings['item_'.$i.'_client_image']['url']; ?>" alt="">
						</div><!--author-img end-->
						<div class="cm-info">
							<h3 <?php echo $this->get_render_attribute_string( 'item_'.$i.'_client_name' ); ?>><?php echo $settings['item_'.$i.'_client_name']; ?></h3>
							<h4 <?php echo $this->get_render_attribute_string( 'item_'.$i.'_client_position' ); ?>><?php echo $settings['item_'.$i.'_client_position']; ?></h4>
						</div>
					</div><!--cm-info-sec end-->
				</div><!--comment-info end-->
			<?php endif; ?>
			<?php endfor; ?>

			</div><!--comment-carousel end-->
		</div><!--testimonail-sect end-->

		<br style="clear:both;" />
	</div>
</section>


	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
	<script>	
	
    jQuery('.comment-carousel').slick({
        slidesToShow: 2,
        slck:true,
        slidesToScroll: 1,
        autoplay: false,
        dots: false,
        arrows:false,
        autoplaySpeed: 2000,
        responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
            infinite: true,
            dots: false
          }
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });

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
