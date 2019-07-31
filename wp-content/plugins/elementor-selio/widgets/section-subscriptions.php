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
class Section_Subscriptions extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Get all the best in your inbox ',
			'big_title' => 'Subscribe for fresh news',
			'mailchimp_api_key' => '4b0468535674443390e882829b90a306-us17',
			'mailchimp_list_id' => '3dcf475efc',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
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
		return 'section-subscriptions';
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
		return __( 'Section Subscriptions', 'selio-blocks' );
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
				elseif(substr_count($key, 'icon') > 0)
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
                                                        'type' => \Elementor\Controls_Manager::ICON,
                                                        'include' => $this->icons,
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

                sw_win_load_ci_frontend();
                $CI = &get_instance();
                $CI->load->model('review_m');
                $CI->load->model('listing_m');
                $CI->load->model('favorite_m');
                
?>
<h2 class="vis-hid"><?php echo esc_html__('Header elementor','selio-blocks');?></h2>
<section class="subscribe-sect section-padding <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
    <div class="container">
        <div class="subscribe-content">
            <div class="section-heading">
                <?php if(!empty($settings['item_1_mini_title'])): ?>
                <span <?php echo $this->get_render_attribute_string( 'item_1_mini_title' ); ?>><?php echo $settings['item_1_mini_title']; ?></span>
                <?php endif; ?>

                <?php if(!empty($settings['item_1_big_title'])): ?>
                <h3 <?php echo $this->get_render_attribute_string( 'item_1_big_title' ); ?>><?php echo $settings['item_1_big_title']; ?></h3>
                <?php endif; ?>
            </div>
            <form class="subscribe" action="#sw_footer_subscribe_form"  method="POST" id="sw_footer_subscribe_form">
                     <div class="config" data-url="<?php echo esc_url(admin_url('admin-ajax.php')).'?'.esc_html(sw_lang_query()); ?>"></div>
                <div class="form_field">
                    <div class="form-group">
                        <input name="name" type="text" class="form-control" placeholder="<?php echo esc_html__('Your Name','moison');?>" value="" />
                    </div>
                </div>
                <div class="form_field">
                    <div class="form-group">
                        <input name="subscriber_email" type="email" required class="form-control" placeholder="<?php echo esc_html__('Your Email','moison');?>" value="" />
                    </div>
                </div>
                <div class="form_field">
                    <button type="submit" class="btn-default">
                        <span><?php echo esc_html__('Subscribe', 'selio'); ?></span>
                        <i class="la la-paper-plane-o"></i>
                        <i class="fa fa-spinner fa-spin fa-ajax-indicator load-indicator" style="display: none;"></i>
                    </button>
                </div>
                <input type="hidden" name="sw_submit_subscription" value="Submit">
                <input type="hidden" name="subscriber_api_key" value="<?php echo esc_attr($settings['item_1_mailchimp_api_key']); ?>">
                <input type="hidden" name="subscriber_lsit_id" value="<?php echo esc_attr($settings['item_1_mailchimp_list_id']); ?>">
            </form>
        </div>
    </div>
</section><!--main-banner-subscribe end-->


	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
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
