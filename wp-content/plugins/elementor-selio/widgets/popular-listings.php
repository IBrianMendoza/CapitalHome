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
class Popular_Listings extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Discover',
			'big_title' => 'Popular Listing',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'results_limit' => '3',
			'enable_section_separator' => 'no',
			'enable_more_button' => 'no',
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
		return 'popular-listings';
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
		return __( 'Popular Listings', 'selio-blocks' );
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
		return 'fa fa-flag';
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
                
                $this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Additional query parameters', 'selio-blocks' ),
			]
		);
                
                $this->add_control(
                        'qr_type',
                        [
                                'label' => 'Type',
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'is_featured' => __( 'Featured', 'selio-blocks' ),
                                        'max_view' => __( 'Max views', 'selio-blocks' ),
                                ],
                                'default' => 'is_featured',
                                'dynamic' => [
					'active' => true,
				],
                                'conditions' => [
                                           'terms' => [
                                                   [
                                                           'name' => 'qr_string',
                                                           'operator' => '==',
                                                           'value' => '',
                                                   ]
                                           ],
                                   ],
                        ]
                );
                
                                
                $this->add_control(
			'important_note',
			[
				'label' => __( 'Custom Query', 'selio-blocks' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'Filter by query string, for example (like in search url string) "search_order=idlisting%20DESC&offset=0&search_4=Rent"', 'selio-blocks' ),
				'content_classes' => 'selio-elementro-guide',
                                'separator' => 'before',
			]
		);
                
                $this->add_control(
                        'qr_string',
                        [
                                'label' => 'Custom Query',
                                'type' => Controls_Manager::TEXTAREA,
                                'default' => '',
                        ]
                );
                
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

		$separator_class = '';
		if($settings['item_2_enable_section_separator'] == 'yes')
		{
			$separator_class = ' hp2';
		}

		if(!is_numeric($settings['item_2_results_limit']))
		{
			$settings['item_2_results_limit'] = 3;
		}
                
                
		/*
                    Fetch listings data
		*/
		$limit = 3 ;
                if(!empty($settings['item_2_results_limit']))
                    $limit = $settings['item_2_results_limit'];
                
		$col_class = 'col-lg-4';
                
                if($limit == 2) {
                    $col_class = 'col-lg-6';
                }
                elseif($limit == 3) {
                    $col_class = 'col-lg-4';
                }
                elseif($limit == 4) {
                    $col_class = 'col-lg-3';
                }
                
                sw_win_load_ci_frontend();
                $CI = &get_instance();
                $CI->load->model('review_m');
                $CI->load->model('listing_m');
                $CI->load->model('favorite_m');

                if(!empty($settings['qr_string'])) {
                    /* example */
                    //$settings['qr_string'] = '?search_order=idlisting%20DESC&search_4=For%20Sale&search_category=1&map_num_listings=undefined';
                    $conditions = array();
                    $settings['qr_string'] = trim($settings['qr_string'],'?');
                    parse_str($settings['qr_string'],$conditions);
                } else {
                    $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
                    
                    
                    $conditions['search_order'] = 'idlisting DESC';
                    if($settings['qr_type'] == 'is_featured') {
                        $conditions['search_is_featured'] = 1;
                    } else {
                        $conditions['search_order'] = 'counter_views DESC';
                    }
                }
                
                prepare_frontend_search_query_GET('listing_m', $conditions);
                $listings = $CI->listing_m->get_pagination_lang($limit, 0, sw_current_language_id());
                
		?>

<h2 class="vis-hid"><?php echo esc_html__('Listing elementor','selio-blocks');?></h2>
<section class="popular-listing section-padding <?php echo $separator_class; ?> section-popular-listings  <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
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
        <div class="row row-popu-listings">
                <?php if(count($listings) == 0): ?>
                <div class="col-12">
                    <div class="alert alert-primary" role="alert"><?php echo __('Not available', 'selio-blocks'); ?></div>
                </div>
                <?php else: ?>
                    <?php foreach($listings as $key=>$listing): ?>
                        <?php
                        $favorite_added=false;
                        if(get_current_user_id() != 0)
                        {
                            $favorite_added = $CI->favorite_m->check_if_exists(get_current_user_id(), 
                                                                               $listing->idlisting);
                            if($favorite_added>0)$favorite_added = true;
                        }
                    ?>
                    <div class="<?php echo esc_attr($col_class);?> col-md-6">
                            <div class="card">
                                <a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(_field($listing, 10)); ?>">
                                    <div class="img-block">
                                        <div class="overlay"></div>
                                        <img src="<?php echo esc_html(_show_img($listing->image_filename, '851x678', true)); ?>" alt="" class="img-fluid">
                                        <div class="rate-info">
                                            <h5>
                                                <?php // @codingStandardsIgnoreStart ?>
                                                <?php if(!empty(_field($listing, 37)) && _field($listing, 37) !='-'):?>
                                                <?php echo esc_html(_field($listing, 37)); ?>
                                                <?php else:?>
                                                <?php echo esc_html(_field($listing, 36)); ?>
                                                <?php endif;?>
                                                <?php // @codingStandardsIgnoreEnd ?>
                                            </h5>
                                            <span class="purpose-<?php echo esc_html(url_title(_field($listing, 4), '-', TRUE)); ?>"><?php echo esc_html(_field($listing, 4)); ?></span>
                                        </div>
                                    </div>
                                </a>
                                <div class="card-body">
                                    <a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(_field($listing, 10)); ?>">
                                        <h3><?php echo esc_html(_field($listing, 10)); ?></h3>
                                        <p>
                                            <i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?>
                                        </p>
                                    </a>
                                    <div class="resul-items">
                                        <?php
                                            // show items from visual result item builder
                                            _show_items($listing, 2);
                                        ?>
                                    </div>
                                </div>
                                <div class="card-footer">
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
                                </div>
                                <a href="<?php echo esc_html(listing_url($listing)); ?>" title="<?php echo esc_html(_field($listing, 10)); ?>" class="ext-link"></a>
                            </div>
                    </div>
                    <?php endforeach;?>
                <?php endif;?>
		<?php if($settings['item_2_enable_more_button'] == 'yes'): ?>
                    <div class="col-lg-12">
                            <div class="load-more-posts">
                                    <a href="<?php echo esc_url(get_page_link(sw_settings('results_page')));?>" title="" class="btn2"><?php echo esc_html__('Load More','selio-blocks');?></a>
                            </div><!--load-more end-->
                    </div>
		<?php endif; ?>

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
