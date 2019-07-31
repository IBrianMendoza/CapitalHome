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
class Contact_Section extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		
		
		array(
			'form_title' => 'Contact',
			'form_text' => 'Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulpu tate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat consequat auctor eu in elit.',
			'form_email' => '',
                        'zebra_enable' => '',
			'elementor_custom_padding_enable' => '',
		),
		array(
			'contact_title' => '',
			'contact_address' => '',
			'contact_tel' => '',
			'contact_email' => '',
			'contact_description' => 'Aenean sollicitudin, lorem quis bibendum auctor, nisi elitco nsequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accum sanpsum velit. Nam nec tellus a odio tinci.',
		),
		array(
			'facebook_link' => '#',
			'twitter_link' => '#',
			'instagram_link' => '#',
			'linkedin_link' => '#',
		),

	);

	private $items_num = 3;

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
		return 'contact-section';
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
		return __( 'Contact Section', 'selio-blocks' );
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
		return 'eicon-info';
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

				if(substr_count($key, 'lat') > 0)
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

	private function check_post($name)
	{
		if(isset($_POST[$name]))
		{
			echo $_POST[$name];
		}
	}

	private function invalid_post($name)
	{
		if(count($_POST) > 0 && isset($_POST['your_name']))
		if(empty($_POST[$name]))
		{
			echo 'required error wobble-error';
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
		
		$id_int = substr( $this->get_id_int(), 0, 3 );
        
		if(self::$multiple_instance === true)
		{
			echo '<div class="alert alert-info" role="alert" style="margin-top:20px;">'
				.__('Multiple instance not allowed for: ','selio-blocks').' '.$this->get_title()
				.'</div>';
				
			return;
        }
		
		/*
		if ( empty( $settings['item_1_background_image']['id'] ) ) {
			$settings['item_1_background_image']['url'] = SELIO_THEMEROOT.'/assets/images/banner/1.jpg';
		}
		*/

		/* Form proccessing */

		if(empty($settings['item_1_form_email']))
			$settings['item_1_form_email'] = get_option( 'admin_email' );

		$validation_message = '';
		if(isset($_POST['your_name']) && !empty($_POST['your_name']) && !empty($_POST['mail']) && !empty($_POST['message']))
		{
			$to = $settings['item_1_form_email'];
			$subject = __( 'Web contact message from:', 'selio-blocks' ).' '.$_POST['your_name'];
			$body = $_POST['message'];
			if(!empty($_POST['website']))
				$body.='<br /><br />'.__( 'Sender website:', 'selio-blocks' ).' '.$_POST['website'];
			
			if(!empty($_POST['phone']))
				$body.='<br /><br />'.__( 'Phone:', 'selio-blocks' ).' '.$_POST['phone'];

			$headers = array('Content-Type: text/html; charset=UTF-8');
			$headers[] = 'From: '.$_POST['mail'];
	
			$ret = wp_mail( $to, $subject, $body, $headers );

			$_POST = array();

			if($ret === TRUE)
			{
				$validation_message = '<div class="success-message" style="display: block;">'.__( 'Thanks on your message!', 'selio-blocks' ).'</div>';
			}
			else
			{
				$validation_message = '<div class="error-message" style="display: block;">'.__( 'Error sending message!', 'selio-blocks' ).'</div>';
			}
			
		}
		elseif(isset($_POST['your_name']))
		{
			$validation_message = '<div class="error-message" style="display: block;">'.__( 'Please populate all fields', 'selio-blocks' ).'</div>';
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
                   substr_count($gen_item, 'text') > 0 ||
                   substr_count($gen_item, 'contact') > 0 )
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


<div class="contact-sec">
<div class="container">
	<div class="contact-details-sec">
		<div class="row">
		<?php if(!empty($settings['item_2_contact_title']) || !empty($settings['item_2_contact_description'])): ?>
			<div class="col-lg-8 col-md-8 pl-0">
		<?php else: ?>
			<div class="col-lg-12 col-md-12 pl-0">
		<?php endif; ?>
				<div class="contact_form" id="contact_form_<?php echo $id_int; ?>">
					<h3 <?php echo $this->get_render_attribute_string( 'item_1_form_title' ); ?>><?php echo $settings['item_1_form_title']; ?></h3>
                                         <?php if(!empty($settings['item_1_form_text'])): ?>
                                            <p <?php echo $this->get_render_attribute_string( 'item_1_form_text' ); ?>><?php echo $settings['item_1_form_text']; ?></p>
                                        <?php endif;?>
					<form action="#contact_form_<?php echo $id_int; ?>" class="js-ajax-form" method="POST">
						<div class="form-group no-pt">
							<?php echo $validation_message; ?>

							<?php if(false): ?>
							<div class="missing-message">
								<?php echo esc_html__('Populate Missing Fields','selio-blocks');?>
							</div>
							<div class="success-message">
								<i class="fa fa-check text-primary"></i> <?php echo esc_html__('Thank you!. Your message is successfully sent...','selio-blocks');?>
							</div>
							<div class="error-message"><?php echo esc_html__('Populate Missing Fields','selio-blocks');?></div>
							<?php endif; ?>

						</div><!--form-group end-->
						<div class="form-fieldss">
							<div class="row">
								<div class="col-lg-4 col-md-4 pl-0">
									<div class="form-field">
										<input type="text" name="your_name" value="<?php $this->check_post('your_name'); ?>" placeholder="<?php echo esc_html__('Your Name','selio-blocks');?>" id="your_name" class="<?php echo $this->invalid_post('your_name'); ?>">
									</div><!-- form-field end-->
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="form-field">
										<input type="email" name="mail" value="<?php $this->check_post('mail'); ?>" placeholder="<?php echo esc_html__('Your Email','selio-blocks');?>" id="mail" class="<?php echo $this->invalid_post('mail'); ?>">
									</div><!-- form-field end-->
								</div>
								<div class="col-lg-4 col-md-4 pr-0">
									<div class="form-field">
										<input type="text" name="phone" value="<?php $this->check_post('phone'); ?>" placeholder="<?php echo esc_html__('Your Phone','selio-blocks');?>" class="<?php echo $this->invalid_post('phone'); ?>">
									</div><!-- form-field end-->
								</div>
								<div class="col-lg-12 col-md-12 pl-0 pr-0">
									<div class="form-field">
										<textarea name="message" placeholder="<?php echo esc_html__('Your Message','selio-blocks');?>" class="<?php echo $this->invalid_post('message'); ?>"><?php $this->check_post('message'); ?></textarea>
									</div><!-- form-field end-->
								</div>
								<div class="col-lg-12 col-md-12 pl-0">
									<button type="submit" class="btn-default submit" ><?php echo esc_html__('Send Message','selio-blocks');?></button>
								</div>
								
							</div>
						</div><!--form-fieldss end-->
					</form>
				</div><!--contact_form end-->
			</div>
			<?php if(!empty($settings['item_2_contact_title']) || !empty($settings['item_2_contact_description'])): ?>
			<div class="col-lg-4 col-md-4 pr-0">
				<div class="contact_info">
                                        <?php if(!empty($settings['item_2_contact_title'])): ?>
                                            <h3><?php echo $settings['item_2_contact_title']; ?></h3>
                                        <?php endif;?>
					<ul class="cont_info">
						<?php if(!empty($settings['item_2_contact_address'])): ?>
						<li><i class="la la-map-marker"></i> <span <?php echo $this->get_render_attribute_string( 'item_2_contact_address' ); ?>><?php echo $settings['item_2_contact_address']; ?></span></li>
						<?php endif; ?>
						<?php if(!empty($settings['item_2_contact_tel'])): ?>
						<li><i class="la la-phone"></i> <span <?php echo $this->get_render_attribute_string( 'item_2_contact_tel' ); ?>><?php echo $settings['item_2_contact_tel']; ?></span></li>
						<?php endif; ?>
						<?php if(!empty($settings['item_2_contact_email'])): ?>
						<li><i class="la la-envelope"></i><a href="mailto:<?php echo $settings['item_2_contact_email']; ?>" title="" <?php echo $this->get_render_attribute_string( 'item_2_contact_email' ); ?>><?php echo $settings['item_2_contact_email']; ?></a></li>
						<?php endif; ?>
					</ul>
                                        <?php if(!empty($settings['item_3_facebook_link'])
                                                || !empty($settings['item_3_twitter_link'])
                                                || !empty($settings['item_3_instagram_link'])
                                                || !empty($settings['item_3_linkedin_link'])
                                                ): ?>
					<ul class="social_links">
						<?php if(!empty($settings['item_3_facebook_link'])): ?>
							<li><a href="<?php echo $settings['item_3_facebook_link']; ?>" title=""><i class="fa fa-facebook"></i></a></li>
						<?php endif; ?>
						<?php if(!empty($settings['item_3_twitter_link'])): ?>
						<li><a href="<?php echo $settings['item_3_twitter_link']; ?>" title=""><i class="fa fa-twitter"></i></a></li>
						<?php endif; ?>
						<?php if(!empty($settings['item_3_instagram_link'])): ?>
						<li><a href="<?php echo $settings['item_3_instagram_link']; ?>" title=""><i class="fa fa-instagram"></i></a></li>
						<?php endif; ?>
						<?php if(!empty($settings['item_3_linkedin_link'])): ?>
						<li><a href="<?php echo $settings['item_3_linkedin_link']; ?>" title=""><i class="fa fa-linkedin"></i></a></li>
						<?php endif; ?>
					</ul>
                                        <?php endif; ?>
                                        
                                        <?php if(!empty($settings['item_2_contact_description'])): ?>
                                            <div class="text"><span <?php echo $this->get_render_attribute_string( 'item_2_contact_description' ); ?>><?php echo $settings['item_2_contact_description']; ?></span></div>
                                        <?php endif; ?>
				</div><!--contact_info end-->
			</div>
			<?php endif; ?>
		</div>
	</div><!--contact-details-sec end-->
	<br style="clear:both;" />
</div>
<br style="clear:both;" />
</div><!--contact-sec end-->

	<?php if(Plugin::$instance->editor->is_edit_mode()): ?>
	
	<script>	
	
	</script>

	<style>

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
