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
class Post_Slider extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'num_count' => '4',
		),
	);
        
        public $listings_select = array();

	private $items_num = 1;

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
		return 'post-slider';
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
		return __( 'Post Slider', 'selio-blocks' );
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
        
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
        }
		/* default install options */
                
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
                
                $date_format = get_option('date_format');
                global $paged;

                $allposts = array( 
                        'post_type'           =>  'post',
                        'posts_per_page'      =>  $settings['item_1_num_count'],
                        'post_status'		  => 'publish',	
                        'ignore_sticky_posts' => true,
                        'paged'			      => $paged
                );

                $wp_query = new \WP_Query($allposts); //$wp_query->query($allposts);
                
?>
<h2 class="vis-hid"><?php echo esc_html__('Header elementor','selio-blocks');?></h2>
<?php  if($wp_query->have_posts()):?>
    <div class="sect-post-slider">
        <div class="container">
            <div class="posts-slider">
                <?php $first_class= 'active'; while ($wp_query->have_posts()) : $wp_query->the_post();?>
                <div class="slide">
                    <?php if ( '' !== get_the_post_thumbnail() ) : ?>
                        <img src="<?php echo get_the_post_thumbnail_url(null, 'full'); ?>" alt="" class="thumbnail" />
                    <?php else: ?>
                        <img src="<?php echo esc_url(SELIO_IMAGES.'/placeholder.jpg'); ?>" alt="" class="thumbnail" />
                    <?php endif; ?>
                    <div class="content">
                        <div class="meta"><i class="la la-calendar"></i><?php echo get_the_date($date_format) ?></div>
                        <h2 class="title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title();?></a></h2>
                        <div class="descr">
                            <?php
                            $content = get_the_excerpt();
                            $content = strip_shortcodes($content);
                            esc_viewe(wp_strip_all_tags(html_entity_decode(wp_trim_words(htmlentities(wpautop($content)), 16, '...'))));
                            ?>
                        </div>
                    </div>
                </div>
                <?php endwhile;?>
            </div>
            <div class="ps-list">
                <?php $first_class= 'active'; while ($wp_query->have_posts()) : $wp_query->the_post();?>
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="ps-item <?php echo esc_attr($first_class);?>">
                    <div class="meta"><i class="la la-calendar"></i><?php echo get_the_date($date_format); ?></div>
                    <h2 class="title"><?php the_title();?></h2>
                </a>
                <?php $first_class= ''; endwhile;?>
            </div>
        </div>
    </div> 
<?php endif;?>  


	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
	<script>	
	    selio_slider_ini();
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
