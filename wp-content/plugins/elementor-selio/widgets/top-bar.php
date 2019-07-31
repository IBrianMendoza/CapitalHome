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
class Top_Bar extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'phone_number' => '(647) 346-0855',
			'link'	=> '#',
		),
		array(
			'address' => 'CF Fairview Mall, Toronto, ON',
			'link'	=> '#',
		),
		array(
			'social_1_icon' => 'fa-facebook',
			'link'	=> '#',
		),
		array(
			'social_2_icon' => 'fa-twitter',
			'link'	=> '#',
		),
		array(
			'social_3_icon' => 'fa-instagram',
			'link'	=> '#',
		),
		array(
			'social_4_icon' => 'fa-linkedin',
			'link'	=> '#',
		),
	);

	private $items_num = 6;

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
		return 'top-bar';
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
		return __( 'Top Bar', 'selio-blocks' );
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
		return 'fa fa-address-card';
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
        
        //dump($settings);

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
                
    $langs = sw_get_languages();
            
		?>
<h2 class="vis-hid"><?php echo esc_html__('Top bar elementor','selio-blocks');?></h2>
<div class="top-header section-top-bar">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-xl-6 col-md-7 col-sm-12">
                <div class="header-address">
                    <?php if(function_exists('config_item') && config_item('app_type') == 'demo'): ?>
                        <?php if(!empty($settings['item_1_phone_number'])): ?>
                        <a href="tel://<?php echo esc_attr(urlencode($settings['item_1_phone_number']));?>">
                            <i class="la la-phone-square"></i>
                            <span <?php echo $this->get_render_attribute_string( 'item_1_phone_number' ); ?>><?php echo $settings['item_1_phone_number']; ?></span>
                        </a>
                        <?php endif; ?>
                        <?php if(!empty($settings['item_2_address'])): ?>
                        <a href="<?php echo $settings['item_2_link']; ?>">
                            <i class="la la-map-marker"></i>
                            <span <?php echo $this->get_render_attribute_string( 'item_2_address' ); ?>><?php echo $settings['item_2_address']; ?></span>
                        </a>
                        <?php endif; ?>
                    <?php else: ?>
                    <span class='h-text'><?php echo esc_html(get_bloginfo('description'));?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (function_exists('sw_count') && count($langs) > 1):?>
            <div class="col-xl-3 col-lg-3 col-md-2 col-sm-6 col-6">
            <?php else:?>
            <div class="col-xl-3 col-md-5 col-sm-12">
            <?php endif;?>
                
                <div class="header-social">
                    <?php if(!empty($settings['item_3_social_1_icon'])): ?>
                    <a href="<?php echo $settings['item_3_link']; ?>">
                        <i class="fa <?php echo $settings['item_3_social_1_icon']; ?>"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(!empty($settings['item_4_social_2_icon'])): ?>
                    <a href="<?php echo $settings['item_4_link']; ?>">
                        <i class="fa <?php echo $settings['item_4_social_2_icon']; ?>"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(!empty($settings['item_5_social_3_icon'])): ?>
                    <a href="<?php echo $settings['item_5_link']; ?>">
                        <i class="fa <?php echo $settings['item_5_social_3_icon']; ?>"></i>
                    </a>
                    <?php endif; ?>
                    <?php if(!empty($settings['item_6_social_4_icon'])): ?>
                    <a href="<?php echo $settings['item_6_link']; ?>">
                        <i class="fa <?php echo $settings['item_6_social_4_icon']; ?>"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php
            if (function_exists('sw_count') && count($langs) > 1):
                ?>
                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-6 col-6">
                    <div class="language-selector">
                        <div class="drop-menu">
                            <div class="select">
                                <span><img src="<?php echo esc_url(SELIO_IMAGES . '/flags/' . sw_current_language() . '.png'); ?>" alt="<?php esc_viewe(sw_get_language_name(sw_current_language())); ?>"><?php esc_viewe(sw_get_language_name(sw_current_language())); ?></span>
                                <i class="la la-caret-down"></i>
                            </div>
                            <input type="hidden" name="gender">
                            <ul class="dropeddown" style="display: none;">
                            <?php foreach ($langs as $lang): if ($lang['lang_code'] == sw_current_language()) continue; ?>
                                <li> 
                                    <a class="dropdown-item" href="<?php echo esc_url(sw_get_language_url($lang['lang_code'])); ?>">
                                        <img src="<?php echo esc_url(SELIO_IMAGES . '/flags/' . $lang['lang_code'] . '.png'); ?>" alt="<?php echo esc_html($lang['title']); ?>" /> <?php echo esc_html($lang['title']); ?>
                                    </a>
                                </li> 
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div><!--language-selector end-->
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


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
