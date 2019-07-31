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
class Popular_Cities extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Popular Cities',
			'big_title' => 'Find Perfect Place',
			'view' => '',
			'limit' => '3',
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
		return 'popular-cities';
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
		return __( 'Popular Cities', 'selio-blocks' );
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

                $limit = 3 ;
                if(!empty($settings['item_1_limit']))
                    $limit = $settings['item_1_limit'];
                
		$col_class = 'col-lg-'.intval(12/$limit);
                
                sw_win_load_ci_frontend();
                $CI = &get_instance();
		$CI->load->model('review_m');
		$CI->load->model('treefield_m');
		$CI->load->model('listing_m');
		
		$field_id = 2;
		$treefield_list = $CI->treefield_m->get_all_list(array('field_id'=>$field_id,'level !='=>'0'), $limit);
		?>
<h2 class="vis-hid"><?php echo esc_html__('Cities elementor','selio-blocks');?></h2>
<?php if($settings['item_1_view'] == 'flexbox'): ?>

<section class="popular-cities hp3 section-padding section-popular-cities section-popular-cities-flexbox <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
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
       <?php if(count($treefield_list) == 0): ?>
            <div class="alert alert-info" role="alert"><?php echo __('Not available', 'selio-blocks'); ?></div>
        <?php else: ?>
        <?php
         $treefield_list_section = sw_count($treefield_list) / 4;
         $treefield_list_section = (int) $treefield_list_section;
         sort($treefield_list);
        ?>
        <?php if($treefield_list_section == 0): ?>
            <div class="alert alert-info" role="alert"><?php echo __('Should be %4', 'selio-blocks'); ?></div>
        <?php else: ?>   
        <?php for($i = 0; $i<$treefield_list_section;$i++): ?>
            <div class="row row-cities-flexbox">
                    <div class="col-lg-6">
                        <?php
                                $item = $treefield_list[$i];
                                $featured_image = SELIO_IMAGES.'/placeholder.jpg';

                                // check for version with category related marker
                                if(isset($item->featured_image_id))
                                {
                                        $img = wp_get_attachment_image_src($item->featured_image_id, '', true, '' );
                                        if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
                                        {
                                                $featured_image = $img[0];
                                        }
                                }

                                $where = array();
                                $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
                                $conditions['search_location'] = $item->idtreefield;
                                prepare_frontend_search_query_GET('listing_m', $conditions);

                                $listings_location_count = $CI->listing_m->total_lang($where, sw_current_language_id());
                        ?>
                        <a href="<?php echo search_url('search_location='.$item->idtreefield); ?>">
                                <div class="card cities-flexbox-1">
                                        <div class="overlay"></div>
                                        <div class="overlay-stick"></div>
                                         <img src="<?php echo $featured_image; ?>" alt="<?php echo $item->value; ?>" class="img-fluid">
                                        <div class="card-body">
                                               <h4><?php echo $item->value; ?></h4>
                                                <i class="fa fa-angle-right"></i>
                                        </div>
                                </div>
                        </a>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                        $item = $treefield_list[$i+3];
                                        $featured_image = SELIO_IMAGES.'/placeholder.jpg';

                                        // check for version with category related marker
                                        if(isset($item->featured_image_id))
                                        {
                                                $img = wp_get_attachment_image_src($item->featured_image_id, '', true, '' );
                                                if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
                                                {
                                                        $featured_image = $img[0];
                                                }
                                        }

                                        $where = array();
                                        $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
                                        $conditions['search_location'] = $item->idtreefield;
                                        prepare_frontend_search_query_GET('listing_m', $conditions);

                                        $listings_location_count = $CI->listing_m->total_lang($where, sw_current_language_id());
                                ?>
                                <a href="<?php echo search_url('search_location='.$item->idtreefield); ?>">
                                        <div class="card cities-flexbox-2">
                                                <div class="overlay"></div>
                                                <div class="overlay-stick"></div>
                                                 <img src="<?php echo $featured_image; ?>" alt="<?php echo $item->value; ?>" class="img-fluid">
                                                <div class="card-body">
                                                       <h4><?php echo $item->value; ?></h4>
                                                        <i class="fa fa-angle-right"></i>
                                                </div>
                                        </div>
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                $item = $treefield_list[$i+1];
                                $featured_image = SELIO_IMAGES.'/placeholder.jpg';

                                // check for version with category related marker
                                if(isset($item->featured_image_id))
                                {
                                        $img = wp_get_attachment_image_src($item->featured_image_id, '', true, '' );
                                        if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
                                        {
                                                $featured_image = $img[0];
                                        }
                                }

                                $where = array();
                                $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
                                $conditions['search_location'] = $item->idtreefield;
                                prepare_frontend_search_query_GET('listing_m', $conditions);

                                $listings_location_count = $CI->listing_m->total_lang($where, sw_current_language_id());
                                ?>
                                <a href="<?php echo search_url('search_location='.$item->idtreefield); ?>">
                                        <div class="card cities-flexbox-3">
                                                <div class="overlay"></div>
                                                <div class="overlay-stick"></div>
                                                 <img src="<?php echo $featured_image; ?>" alt="<?php echo $item->value; ?>" class="img-fluid">
                                                <div class="card-body">
                                                       <h4><?php echo $item->value; ?></h4>
                                                        <i class="fa fa-angle-right"></i>
                                                </div>
                                        </div>
                                </a>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                        $item = $treefield_list[$i+2];
                                        $featured_image = SELIO_IMAGES.'/placeholder.jpg';

                                        // check for version with category related marker
                                        if(isset($item->featured_image_id))
                                        {
                                                $img = wp_get_attachment_image_src($item->featured_image_id, 'selio-300x250', true, '' );
                                                if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
                                                {
                                                        $featured_image = $img[0];
                                                }
                                        }

                                        $where = array();
                                        $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
                                        $conditions['search_location'] = $item->idtreefield;
                                        prepare_frontend_search_query_GET('listing_m', $conditions);

                                        $listings_location_count = $CI->listing_m->total_lang($where, sw_current_language_id());
                                ?>
                                <a href="<?php echo search_url('search_location='.$item->idtreefield); ?>">
                                    <div class="card cities-flexbox-4">
                                            <div class="overlay"></div>
                                            <div class="overlay-stick"></div>
                                             <img src="<?php echo $featured_image; ?>" alt="<?php echo $item->value; ?>" class="img-fluid">
                                            <div class="card-body">
                                                   <h4><?php echo $item->value; ?></h4>
                                                    <i class="fa fa-angle-right"></i>
                                            </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            
        <?php endfor;?>
        <?php endif;?>
        <?php endif;?>
</div>
</section>

<?php else: ?>

<section class="popular-cities hp_s1 section-padding section-popular-cities <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
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
		<div class="row">
                     <?php if(count($treefield_list) == 0): ?>
                            <div class="col-12">
                                <div class="alert alert-info" role="alert"><?php echo __('Not available', 'selio-blocks'); ?></div>
                            </div>
                            <?php else: ?>
                                    <?php foreach($treefield_list as $key=>$item): ?>
                                    <?php
                                            $featured_image = SELIO_IMAGES.'/placeholder.jpg';

                                            // check for version with category related marker
                                            if(isset($item->featured_image_id))
                                            {
                                                    $img = wp_get_attachment_image_src($item->featured_image_id, 'selio-570x570', true, '' );
                                                    if(isset($img[0]) && substr_count($img[0], 'media/default.png') == 0)
                                                    {
                                                        $featured_image = $img[0];
                                                    }
                                            }

                                            $where = array();
                                            $conditions = array('search_smart'=>'', 'search_is_activated'=>1);
                                            $conditions['search_location'] = $item->idtreefield;
                                            prepare_frontend_search_query_GET('listing_m', $conditions);

                                            $listings_location_count = $CI->listing_m->total_lang($where, sw_current_language_id());
                                    ?>
                                    <div class="<?php echo esc_attr($col_class);?> col-md-6">
                                            <a href="<?php echo search_url('search_location='.$item->idtreefield); ?>">
                                                    <div class="card">
                                                            <div class="overlay"></div>
                                                             <img src="<?php echo $featured_image; ?>" alt="<?php echo $item->value; ?>" class="img-fluid">
                                                            <div class="card-body">
                                                                   <h4><?php echo $item->value; ?></h4>
                                                                    <p>
                                                                        <?php
                                                                           printf(esc_attr_x('%1$s Listing', '%1$s Listings', 'selio'), esc_html($listings_location_count));
                                                                        ?>
                                                                    </p>
                                                                    <i class="fa fa-angle-right"></i>
                                                            </div>
                                                    </div>
                                            </a>
                                    </div>
                                <?php endforeach; ?>
                        <?php endif; ?>
		</div>
	</div>
</section>

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
