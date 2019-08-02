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
class Top_Navigation_White extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'logo_image' => '',
		),
		array(
			'enable_menu' => 'yes',
		),
		array(
			'enable_register' => 'yes',
		),
		array(
			'enable_submit' => 'yes',
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
		return 'top-navigation-white';
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
		return __( 'Top Navigation White', 'selio-blocks' );
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
		return 'eicon-toggle';
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
		
		if ( empty( $settings['item_1_logo_image']['id'] ) ) {
			$settings['item_1_logo_image']['url'] = SELIO_THEMEROOT.'/assets/images/logo.png';
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

		?>
<h2 class="vis-hid"><?php echo esc_html__('Nav elementor','selio-blocks');?></h2>
<div class="header section-top-navigation-white">
<div class="container">
	<div class="row">
		<div class="col-xl-12">
			<nav class="navbar navbar-expand-lg navbar-light">
				<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                                    <?php if (get_theme_mod('selio_logo_upload')): ?>
                                        <img src="<?php echo esc_url(get_theme_mod('selio_logo_upload')); ?>" alt="<?php bloginfo( 'title' ); ?>" />
                                    <?php else: ?>
					<img src="<?php echo $settings['item_1_logo_image']['url']; ?>" alt="">
                                    <?php endif; ?>
				</a>
				<button class="menu-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent">
					<span class="icon-spar"></span>
					<span class="icon-spar"></span>
					<span class="icon-spar"></span>
				</button>

				<div class="navbar-collapse" id="navbarSupportedContent">

				<?php if(has_nav_menu('top') && $settings['item_2_enable_menu'] == 'yes'): ?>
				<?php wp_nav_menu(array(
							'theme_location' => 'top',
							'menu_id'        => 'top-menu',
							'container'      => '',
							'menu_class'     => 'navbar-nav mr-auto',
							'walker' => new Top_Nav_Menu_Walker
							) 
						); 

					else:
						echo '<div class="mr-auto"></div>';
				?>
				<?php endif; ?>
					<div class="d-inline my-2 my-lg-0">
						<ul class="navbar-nav">
							<?php if($settings['item_3_enable_register'] == 'yes'): ?>
                                                            <?php if (!is_user_logged_in()): ?> 
                                                            <li class="nav-item signin-btn">
                                                                    <span class="nav-link">
                                                                            <i class="la la-sign-in"></i>
                                                                            <span>
                                                                                <a href="<?php echo esc_url( selio_login_page() ); ?>" class=" <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>">
                                                                                    <b class="signin-op"><?php echo esc_html__('Ingresar','selio-blocks');?></b> 
                                                                                </a>
                                                                                <?php echo esc_html__('or','selio-blocks');?> 
                                                                                <a href="<?php echo esc_url( selio_login_page() ); ?>#sw_register" class="">
                                                                                    <b class="reg-op"><?php echo esc_html__('Registrarse','selio-blocks');?></b>
                                                                                </a>
                                                                            </span>
                                                                    </span>
                                                            </li>
                                                            <?php else: ?>
                                                            <li class="nav-item signin-btn">
                                                                    <a href="<?php echo wp_logout_url(); ?>" class="nav-link ">
                                                                            <i class="la la-sign-in"></i>
                                                                            <span><b class="signin-op"><?php echo esc_html__('Salir','selio-blocks');?></b></span>
                                                                    </a>
                                                            </li>
                                                            <?php endif; ?>
							<?php endif; ?>
							<?php if($settings['item_4_enable_submit'] == 'yes'): ?>
							<li class="nav-item submit-btn">
                                                            <?php if(function_exists('sw_settings')):?>
								<a href="<?php echo esc_url(get_permalink(sw_settings('quick_submission'))); ?>" class="my-2 my-sm-0 nav-link sbmt-btn">
									<i class="icon-plus"></i>
									<span><?php echo esc_html__('Añadir listado','selio-blocks');?></span>
								</a>
                                                            <?php else:?>
                                                            <div class="alert alert-info"><?php echo esc_html__('Submit feature, Possible only if installed  Visual Listings - Agency Directory','selio-blocks');?></div>
                                                            <?php endif;?>
							</li>
							<?php endif; ?>
						</ul>
					</div>
					<a href="#" title="" class="close-menu"><i class="la la-close"></i></a>
				</div>
			</nav>
		</div>
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
