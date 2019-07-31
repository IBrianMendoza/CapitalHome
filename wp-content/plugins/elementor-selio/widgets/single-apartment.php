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
class Single_Apartment extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		
		array(
			'custom_description_text' => '',
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
		return 'single-apartment';
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
		return __( 'Single Apartment', 'selio-blocks' );
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
		return 'eicon-image-hotspot';
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


		$this->start_controls_section(
			'section_elements',
			[
				'label' => __( 'Block elements', 'selio-blocks' ),
			]
		);
                if(function_exists('sw_win_load_ci_frontend')){
                    sw_win_load_ci_frontend();
                    $CI = &get_instance();
                    $CI->load->model('listing_m');
                    $listings = $CI->listing_m->get_pagination_lang(200, 0, sw_current_language_id());
                    $listings_select = array();

                    foreach($listings as $listing)
                    {
                        $listings_select[$listing->idlisting] = $listing->idlisting.', '._field($listing, 10);
                    }

                    $this->add_control(
                            'listing_id',
                            [
                                    'label' => __('Listing', 'selio-blocks'),
                                    'type' => Controls_Manager::SELECT2,
                                    'options' => $listings_select,
                                    'default' => '',
                            ]
                    );
                }
                $this->add_control(
                        'listing_custom_id',
                        [
                                'label' => __('Custom lising ID', 'selio-blocks'),
                                'type' => Controls_Manager::NUMBER,
                                'min'=>1,
                                'default' => '',
                        ]
                );
                
                if(isset($this->defaults[0]))
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
							'type' => Controls_Manager::TEXTAREA,
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
		
		/*
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
		}
		*/
		
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
                
                if(!empty($settings['listing_custom_id'])) {
                    $settings['listing_id'] = $settings['listing_custom_id'];
                }
                
                sw_win_load_ci_frontend();
                $CI = &get_instance();
                $CI->load->model('review_m');
                $CI->load->model('listing_m');
                $CI->load->model('favorite_m');
                $listing = '';
                if(!empty($settings['listing_id'])) {
                    $conditions = array('search_idlisting'=>$settings['listing_id'], 'search_is_activated'=>1);
                    prepare_frontend_search_query_GET('listing_m', $conditions);
                    $listing = $CI->listing_m->get_pagination_lang(1, 0, sw_current_language_id());
                } 
                
                if(!sw_count($listing)) {
                    /* random if not set listing */
                    $conditions = array('search_is_activated'=>1);
                    prepare_frontend_search_query_GET('listing_m', $conditions);
                    $listings = $CI->listing_m->get_pagination_lang(false, 0, sw_current_language_id());
                    if(sw_count($listings)) {
                        $listing = $CI->listing_m->get_pagination_lang($listings[array_rand($listings)]->idlisting, 0, sw_current_language_id());
                    }
                }
                
		?>
<h2 class="vis-hid"><?php echo esc_html__('Apartment elementor','selio-blocks');?></h2>
<section class="apartment-sec section-padding section-single-apartment <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
	<div class="container">
                <?php if(!sw_count($listing)): ?>
                    <div class="">
                        <div class="alert alert-primary" role="alert"><?php echo __('Not available', 'selio-blocks'); ?></div>
                    </div>
                <?php else: ?>
                
		<div class="card">
                        <?php
                        $listing = $listing[0];
                        $favorite_added=false;
                        if(get_current_user_id() != 0)
                        {
                            $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                                               $listing->idlisting);
                            if($favorite_added>0)$favorite_added = true;
                        }
                        ?>
                    
			<a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(_field($listing, 10)); ?>" class="left-s">
				<div class="img-block">
					<div class="overlay"></div>
                                        <div style="background:url(<?php echo esc_html(_show_img($listing->image_filename, '851x678', true)); ?>);" class="img-fluid"></div>
                                </div>
			</a>
			<div class="card_bod_full">
				<div class="card-body">
					<a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(_field($listing, 10)); ?>">
						<h3><?php echo esc_html(_field($listing, 10)); ?></h3>
						<p> <i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?></p>
					</a>
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
					<p>
                                            <?php if($settings['item_1_custom_description_text'] !=''):?>
                                                <?php echo $settings['item_1_custom_description_text'];?>
                                            <?php else:?>
                                                <?php echo esc_html(strip_tags(_field($listing, 13,195))); ?>
                                            <?php endif;?>
                                        </p>
                                        <div class="resul-items">
                                            <?php
                                                // show items from visual result item builder
                                                _show_items($listing, 2);
                                            ?>
                                        </div>
				</div>
				<div class="card-footer">
					<div class="crd-links">
                                                <?php if(function_exists('sw_show_favorites')): ?>
                                                    <span class="favorites-actions pull-left">
                                                        <a href="#" data-id="<?php echo esc_attr($listing->idlisting);?>" class="add-favorites-action <?php echo (esc_html($favorite_added))?'hidden':''; ?>" <?php if (!is_user_logged_in()): ?> data-loginpopup="true" <?php endif;?>  data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_html(sw_lang_query()); ?>">
                                                            <i class="la la-heart-o"></i>
                                                        </a>
                                                        <a href="#" data-id="<?php echo esc_attr($listing->idlisting);?>" class="remove-favorites-action <?php echo (!esc_html($favorite_added))?'hidden':''; ?>" data-ajax="<?php echo esc_url(admin_url( 'admin-ajax.php' )).'?'.esc_html(sw_lang_query()); ?>">
                                                            <i class="la la-heart-o"></i>
                                                        </a>
                                                        <i class="fa fa-spinner fa-spin fa-custom-ajax-indicator"></i>
                                                    </span>
                                                <?php endif; ?>
                                                <a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(date('Y-m-d H:i:s'),strtotime($listing->date_modified));?>" class="pull-right">
                                                    <i class="la la-calendar-check-o"></i>
                                                    <?php 
                                                        $date_modified = $listing->date_modified;
                                                        $date_modified_str = strtotime($date_modified);
                                                        echo human_time_diff($date_modified_str);
                                                        echo ' '.esc_html__('Ago', 'selio');
                                                    ?>
                                                </a>
					</div><!--crd-links end-->
				</div>
			</div><!--card_bod_full end-->
			<a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(_field($listing, 10)); ?>" class="ext-link"></a>
                        
		</div>
                <?php endif;?>
	</div>
</section><!--apartment-sec end-->


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
