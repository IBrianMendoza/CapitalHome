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
class Partners_Agencies extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Trusted by the Best',
			'big_title' => 'Real Estate Partners',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),
		array(
			'par_image' => '',
			'par_link' => '#',
		),

	);

	private $items_num = 11;

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
		return 'partners-agencies';
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
		return __( 'Partners Agencies', 'selio-blocks' );
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
		return 'eicon-logo';
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
				
				if(substr_count($key, 'par') > 0)
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
		
		
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
		}
		

		for($i=1;$i<=11;$i++)
		{
                        
			if ( empty( $settings['item_'.($i+1).'_par_image']['id'] ) ) {
                            $num = ($i > 5 ) ? $i-5 : $i;
                            $settings['item_'.($i+1).'_par_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/pt-logo'.($num).'.png';
			}

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

<h2 class="vis-hid"><?php echo esc_html__('Partners elementor','selio-blocks');?></h2>
<section class="partner-sec hp5 section-padding">
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
		</div><!--justify-content-center end-->
		<div class="partner-carousel">
			<?php for($i=1;$i<$this->items_num+1;$i++): ?>
			<?php if(!empty($settings['item_'.$i.'_par_link'])): ?>

				<div class="partner-logo">
					<a href="<?php echo $settings['item_'.$i.'_par_link']; ?>" title=""><img src="<?php echo $settings['item_'.$i.'_par_image']['url']; ?>" alt=""></a>
				</div><!--partner-logo end-->
			<?php endif; ?>
			<?php endfor; ?>
		</div><!--partner-carousel end-->
	</div>
	<br style="clear:both;" />
</section><!--agents-sec end-->

	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
	<script>	
    jQuery('.partner-carousel').slick({
        slidesToShow: 5,
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
            slidesToShow: 5,
            slidesToScroll: 1,
            infinite: true,
            dots: false
          }
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
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
