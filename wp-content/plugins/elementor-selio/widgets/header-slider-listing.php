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
class Header_Slider_Listing extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'listing_id' => '',
			'listing_custom_id' => '',
			'image_from_listing_enable' => 'no',
			'listing_custom_image' => '',
		),
	);
        
        public $listings_select = array();

	private $items_num = 10;

        public function __construct($data = array(), $args = null) {

            if(function_exists('sw_win_load_ci_frontend')){
                sw_win_load_ci_frontend();
                $CI = &get_instance();
                $CI->load->model('listing_m');
                $listings = $CI->listing_m->get_pagination_lang(200, 0, sw_current_language_id());

                foreach($listings as $listing)
                {
                    $this->listings_select[$listing->idlisting] = $listing->idlisting.', '._field($listing, 10);
                }
            }
            parent::__construct($data, $args);
        }
        
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
		return 'header-slider-listing';
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
		return __( 'Header Slider Listing', 'selio-blocks' );
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
		return 'eicon-slideshow';
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
                        'num_count',
                        [
                                'label' => 'Num count',
                                'type' => Controls_Manager::NUMBER,
                                'min' => 1,
				'max' => 10,
                                'default' => 3,
                        ]
                );     
		for($i=0;$i<$this->items_num;$i++)
		{
                                    
                $this->start_controls_tabs(
			'style_'.$i.'_tabs',[
				'dynamic' => [
					'active' => true,
				],
                                'conditions' => [
                                           'terms' => [
                                                   [
                                                           'name' => 'num_count',
                                                           'operator' => '>',
                                                           'value' => $i,
                                                   ]
                                           ],
                                   ],
			]
		);
                
			$item_elements = $this->defaults[0];

			if(isset($this->defaults[$i]))
			{
				$item_elements = $this->defaults[$i];
			}

			foreach($item_elements as $key=>$val)
			{

				$gen_item = 'item_'.($i+1).'_'.$key;

				$gen_label = 'Slider '.($i+1).' '.ucwords(str_replace('_', ' ', $key));

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
							],
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
				elseif(substr_count($key, 'listing_id') > 0)
				{
                                    
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
                                                        'type' => Controls_Manager::SELECT2,
                                                        'options' => $this->listings_select,
                                                        'default' => '',
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
               $this->end_controls_tabs();
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
        
                if(!function_exists('sw_win_load_ci_frontend'))
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.$this->get_title().' '.__('Avaible only with Visual Listings - Agency Directory and Management Plugin ','selio-blocks')
				.'</div>';
				
                        return;
                }
        
        
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
        }
		/* default install options */
                
		if ( empty( $settings['item_1_listing_custom_image']['id'] ) ) {
			$settings['item_1_listing_custom_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/banner-img1.jpg';
		}

		if ( empty( $settings['item_2_listing_custom_image']['id'] ) ) {
			$settings['item_2_listing_custom_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/banner-img3.jpg';
		}
	
		if ( empty( $settings['item_3_listing_custom_image']['id'] ) ) {
			$settings['item_3_listing_custom_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/banner-img4.jpg';
		}

		for($i=0;$i<$settings['num_count'];$i++)
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

                sw_win_load_ci_frontend();
                $CI = &get_instance();
                $CI->load->model('review_m');
                $CI->load->model('listing_m');
                $CI->load->model('favorite_m');
                
?>
<h2 class="vis-hid"><?php echo esc_html__('Header elementor','selio-blocks');?></h2>
<section class="main-banner-sec">
    <div class="banner-carousel">
    
    <?php for($i=1;$i<$settings['num_count']+1;$i++): ?>
        <?php           
        
        $listing = '';
        
        if(!empty($settings['item_'.$i.'_listing_custom_id']))
            $settings['item_'.$i.'_listing_id'] = $settings['item_'.$i.'_listing_custom_id'];
        
        if(!empty($settings['item_'.$i.'_listing_id']) && $settings['item_'.$i.'_listing_id'] !='#') {
            $conditions = array('search_idlisting'=>$settings['item_'.$i.'_listing_id'], 'search_is_activated'=>1);
            prepare_frontend_search_query_GET('listing_m', $conditions);
            $listing = $CI->listing_m->get_pagination_lang(1, 0, sw_current_language_id());
        } else {
            $conditions = array('search_is_activated'=>1);
            prepare_frontend_search_query_GET('listing_m', $conditions);
            $listing = $CI->listing_m->get_pagination_lang(1, $i, sw_current_language_id());
        }
        ?>

    <?php if(sw_count($listing)>0): ?>
    <?php
        $listing = $listing[0];
    ?>

    <div class="banner-slide">
        <?php if($settings['item_'.$i.'_image_from_listing_enable'] == ''):?>
            <img src="<?php echo $settings['item_'.$i.'_listing_custom_image']['url']; ?>" alt="<?php echo esc_html(_field($listing, 10)); ?>">
        <?php else:?>
            <img src="<?php echo (empty($listing->image_filename)) ? $settings['item_'.$i.'_listing_custom_image']['url'] : _show_img($listing->image_filename, '1800x1200', true); ?>" alt="<?php echo esc_html(_field($listing, 10)); ?>">
        <?php endif;?>
            <div class="banner_text">
                    <div class="rate-info">
                            <span class="purpose-<?php echo esc_html(url_title(_field($listing, 4), '-', TRUE)); ?>"><?php echo esc_html(_field($listing, 4)); ?></span>
                            <h5>                   
                                <?php // @codingStandardsIgnoreStart ?>
                                <?php if(!empty(_field($listing, 37)) && _field($listing, 37) !='-'):?>
                                <?php echo esc_html(_field($listing, 37)); ?>
                                <?php else:?>
                                <?php echo esc_html(_field($listing, 36)); ?>
                                <?php endif;?>
                                <?php // @codingStandardsIgnoreEnd ?>
                            </h5>
                    </div>
                    <div class="card">
                            <div class="card-body">
                                    <a href="<?php echo esc_html(listing_url($listing)); ?>">
                                            <h3><?php echo esc_html(_field($listing, 10)); ?></h3>
                                            <p> <i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?></p>
                                    </a>
                                    <div class="resul-items">
                                        <?php
                                            // show items from visual result item builder
                                            _show_items($listing, 2);
                                        ?>
                                    </div>
                            </div>
                            <div class="card-footer">
                                    <a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(_field($listing, 10)); ?>"><?php echo esc_html__('Read More','selio-blocks');?> <i class="la la-arrow-right"></i></a>
                            </div>
                    </div>
            </div><!--banner_text end-->
    </div><!--banner-slide end-->

    <?php endif; ?>
    <?php endfor; ?>

</div><!--banner-carousel end-->
</section><!--main-banner-sec end-->


	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
	<script>	
	    jQuery('.banner-carousel').slick({
			slidesToShow: 1,
			slck:true,
			slidesToScroll: 1,
			autoplay: false,
			dots: false,
			autoplaySpeed: 2000
		});
	
	</script>

	<style>

	.main-banner-sec
	{
	background:red;
	}

	.banner-carousel.slick-initialized.slick-slider
	{
	background: green;
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
