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
class Agents extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'mini_title' => 'Perfect Team',
			'big_title' => 'Meet Our Agents',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'agent_name' => 'Tomas Wilkinson',
			'agent_position' => 'Douglas and Eleman Agency',
			'agent_phone' => '+1 212-925-3797',
			'agent_link' => '#',
			'agent_button_text' => 'View Profile',
			'agent_image' => '',
			'agent_exists_select' => '',
		),
		array(
			'agent_name' => 'Alexandra Pirlo',
			'agent_position' => 'Douglas and Eleman Agency',
			'agent_phone' => '+1 212-925-3797',
			'agent_link' => '#',
			'agent_button_text' => 'View Profile',
			'agent_image' => '',
                        'agent_exists_select' => '',
		),
		array(
			'agent_name' => 'Amanda Gates',
			'agent_position' => 'Douglas and Eleman Agency',
			'agent_phone' => '+1 212-925-3797',
			'agent_link' => '#',
			'agent_button_text' => 'View Profile',
			'agent_image' => '',
                        'agent_exists_select' => '',
		),
		array(
			'agent_name' => 'Tayler Gronos',
			'agent_position' => 'Douglas and Eleman Agency',
			'agent_phone' => '+1 212-925-3797',
			'agent_link' => '#',
			'agent_button_text' => 'View Profile',
			'agent_image' => '',
                        'agent_exists_select' => '',
		),
	);

	private $items_num = 5;

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
		return 'agents';
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
		return __( 'Agents', 'selio-blocks' );
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
		return 'eicon-lock-user';
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
				'label' => __( 'Main Options', 'selio-blocks' ),
			]
		);
                
                
                $this->add_control(
			'important_note',
			[
				'label' => __( '', 'selio-blocks' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'If dynamic agents enabled, sections Card 1 - 4 will be ignored', 'selio-blocks' ),
				'content_classes' => 'selio-elementro-guide',
                                'separator' => 'after',
			]
		);
                
                $this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
            
                $this->add_control(
			'dynamic_agents',
			[
				'label' => __( 'Dynamic agents', 'selio-blocks' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'True', 'selio-blocks' ),
				'label_off' => __( 'False', 'selio-blocks' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
                
                $this->end_controls_section();

		for($i=0;$i<$this->items_num;$i++)
		{
                    if($i>0)
                        $this->start_controls_section(
                                'section_'.$i,
                                [
                                        'label' => __( 'Card '.($i), 'selio-blocks' ),
                                ]
                        );
                    else
                        $this->start_controls_section(
                                'section_'.$i,
                                [
                                        'label' => __( 'Block element', 'selio-blocks' ),
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

				$gen_label = ucwords(str_replace('_', ' ', $key));

				if(substr_count($key, 'agent') > 0)
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
				elseif(substr_count($key, 'select') > 0)
				{
                                    
                                    // agents
                                    $role__in = array('AGENT');
                                    $data_users = get_users( array( 'search' => '', 'role__in' => $role__in, 
                                                                          'order_by' => 'ID', 'order' => 'DESC'));
                                    $agents_select = array();
                                    $agents_select[] = __('Not selected', 'selio-blocks');
                                    foreach($data_users as $object)
                                    {
                                        $agents_select[$object->ID] = $object->ID.', '.$object->display_name;
                                    }
                                    $this->add_control(
                                            $gen_item,
                                            [
                                                    'label' => $gen_label,
                                                    'type' => Controls_Manager::SELECT,
                                                    'options' => $agents_select,
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
                    $this->end_controls_section();
		}

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
		
		/*
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
		}
		*/
		
		if ( empty( $settings['item_2_agent_image']['id'] ) ) {
			$settings['item_2_agent_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/ag-img1.jpg';
		}

		if ( empty( $settings['item_3_agent_image']['id'] ) ) {
			$settings['item_3_agent_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/ag-img2.jpg';
		}

		if ( empty( $settings['item_4_agent_image']['id'] ) ) {
			$settings['item_4_agent_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/ag-img3.jpg';
		}

		if ( empty( $settings['item_5_agent_image']['id'] ) ) {
			$settings['item_5_agent_image']['url'] = SELIO_THEMEROOT.'/assets/images/resources/ag-img4.jpg';
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
                   substr_count($gen_item, 'name') > 0 ||
                   substr_count($gen_item, 'position') > 0 ||
                   substr_count($gen_item, 'phone') > 0 )
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
<h2 class="vis-hid"><?php echo esc_html__('Agents elementor','selio-blocks');?></h2>
<section class="agents-sec section-padding section-agents <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
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
		<div class="agents-details">
			<div class="row">
                            <?php if($settings['dynamic_agents'] =='yes') :?>
                                <?php
                                    $role__in = array('AGENT');
                                    $agents = get_users(array('role__in' => $role__in, 'number' => 4,
                                        'order_by' => 'ID', 'order' => 'DESC'));
                                ?>
				<?php if(sw_count($agents) > 0): ?>
				<?php  foreach ($agents as $key => $agent): ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="agent">
                                                    <div class="agent_img">
                                                            <a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name)); ?>">
                                                                    <img src="<?php echo  esc_url(sw_profile_image($agent, 500)); ?>" alt="<?php echo esc_html(strip_tags($agent->display_name)); ?>">
                                                            </a>
                                                            <div class="view-post">
                                                                    <a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name)); ?>" class="view-posts"><?php echo esc_html__('View profile', 'selio-blocks'); ?></a>
                                                            </div>
                                                    </div><!--agent-img end-->
                                                    <div class="agent_info">
                                                            <h3><a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name));?>"><?php echo esc_html(strip_tags($agent->display_name)); ?></a></h3>
                                                            <span><?php echo esc_html(strip_tags(profile_data($agent, 'position_title'))); ?></span>
                                                            <strong><i class="la la-phone"></i><span> <?php echo esc_html(profile_data($agent, 'phone_number'));?></span></strong>
                                                    </div><!--agent-info end-->
                                                    <a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name)); ?>" class="ext-link"></a>
                                            </div><!--agent end-->
                                    </div>      
				<?php endforeach; ?>
				<?php else: ?>
                                    <div class="col-12">
                                        <div class="alert alert-primary" role="alert"><?php echo __('Not available', 'selio-blocks'); ?></div>
                                    </div>
				<?php endif; ?>
                            <?php else:?>
				<?php for($i=1;$i<$this->items_num+1;$i++): ?>
				<?php if(!empty($settings['item_'.$i.'_agent_name'])): ?>
                                        <?php if($settings['item_'.$i.'_agent_exists_select']!=''):?>
                                        <?php
                                        $agent = get_userdata($settings['item_'.$i.'_agent_exists_select']);
                                        ?>
                                            <?php if($agent):?>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="agent">
                                                            <div class="agent_img">
                                                                    <a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name)); ?>">
                                                                            <img src="<?php echo  esc_url(sw_profile_image($agent, 500)); ?>" alt="<?php echo esc_html(strip_tags($agent->display_name)); ?>">
                                                                    </a>
                                                                    <div class="view-post">
                                                                            <a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name)); ?>" class="view-posts"><?php echo esc_html__('View profile', 'selio-blocks'); ?></a>
                                                                    </div>
                                                            </div><!--agent-img end-->
                                                            <div class="agent_info">
                                                                    <h3><a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name));?>"><?php echo esc_html(strip_tags($agent->display_name)); ?></a></h3>
                                                                    <span><?php echo esc_html(strip_tags(profile_data($agent, 'position_title'))); ?></span>
                                                                    <strong><i class="la la-phone"></i><span> <?php echo esc_html(profile_data($agent, 'phone_number'));?></span></strong>
                                                            </div><!--agent-info end-->
                                                            <a href="<?php echo esc_url(agent_url($agent)); ?>" title="<?php echo esc_html(strip_tags($agent->display_name)); ?>" class="ext-link"></a>
                                                    </div><!--agent end-->
                                            </div>
                                            <?php endif;?>
                                        <?php else:?>
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="agent">
                                                            <div class="agent_img">
                                                                    <a href="<?php echo $settings['item_'.$i.'_agent_link']; ?>" title="<?php echo $settings['item_'.$i.'_agent_name']; ?>">
                                                                            <img src="<?php echo $settings['item_'.$i.'_agent_image']['url']; ?>" alt="<?php echo $settings['item_'.$i.'_agent_name']; ?>">
                                                                    </a>
                                                                    <div class="view-post">
                                                                            <a href="<?php echo $settings['item_'.$i.'_agent_link']; ?>" title="<?php echo $settings['item_'.$i.'_agent_name']; ?>" class="view-posts"><?php echo $settings['item_'.$i.'_agent_button_text']; ?></a>
                                                                    </div>
                                                            </div><!--agent-img end-->
                                                            <div class="agent_info">
                                                                    <h3><a href="<?php echo $settings['item_'.$i.'_agent_link']; ?>" title="<?php echo $settings['item_'.$i.'_agent_name']; ?>" <?php echo $this->get_render_attribute_string( 'item_'.$i.'_agent_name' ); ?>><?php echo $settings['item_'.$i.'_agent_name']; ?></a></h3>
                                                                    <span <?php echo $this->get_render_attribute_string( 'item_'.$i.'_agent_position' ); ?>><?php echo $settings['item_'.$i.'_agent_position']; ?></span>
                                                                    <strong><i class="la la-phone"></i><span <?php echo $this->get_render_attribute_string( 'item_'.$i.'_agent_phone' ); ?>><?php echo $settings['item_'.$i.'_agent_phone']; ?></span></strong>
                                                            </div><!--agent-info end-->
                                                            <a href="<?php echo $settings['item_'.$i.'_agent_link']; ?>" title="<?php echo $settings['item_'.$i.'_agent_name']; ?>" class="ext-link"></a>
                                                    </div><!--agent end-->
                                            </div>
                                        <?php endif; ?>
				<?php endif; ?>
				<?php endfor; ?>
                             <?php endif;?>
			</div>
		</div><!--agents-details end-->
	</div>
</section><!--agents-sec end-->


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
