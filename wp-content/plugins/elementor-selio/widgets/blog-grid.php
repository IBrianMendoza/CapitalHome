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
class Blog_Grid extends Widget_Base {

	public static $multiple_instance=false;
	
	// Default widget settings
	private $defaults = array(
		array(
			'columns_in_grid' => '4',
			'limit_per_page' => '6',
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
		return substr(str_replace('_', '-', strtolower(get_class($this))), strrpos(get_class($this), '\\')+1);
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
		return __( 'Blog Grid', 'selio-blocks' );
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
		return 'eicon-posts-grid';
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
				elseif(substr_count($key, 'columns') > 0)
				{
					$this->add_control(
						$gen_item,
						[
							'label' => $gen_label,
							'type' => Controls_Manager::SELECT,
							'options' => [
								'3' => '4',
								'4' => '3',
								'6' => '2'
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

		?>
<h2 class="vis-hid"><?php echo esc_html__('Blog grid elementor','selio-blocks');?></h2>
<section class="blog-grid section-padding section-popular-listings  <?php if($settings['item_1_zebra_enable']=='yes'):?> zebra-section<?php endif;?> <?php if($settings['item_1_elementor_custom_padding_enable']=='yes'):?> no-padding<?php endif;?>">
	<div class="container">
		<div class="blog-grid-posts">
			<div class="row">
			<?php
						global $paged;

						$allposts = array( 
							'post_type'           =>  'post',
							'posts_per_page'      =>  $settings['item_1_limit_per_page'],
							'post_status'		  => 'publish',	
							'ignore_sticky_posts' => true,
							'paged'			      => $paged
						);

                		$wp_query = new \WP_Query($allposts); //$wp_query->query($allposts);
                		while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

				<div class="col-lg-<?php echo $settings['item_1_columns_in_grid']; ?> col-md-6 col-sm-6 col-12" id="post-grid-<?php the_ID(); ?>">
					<div class="blog-single-post">
						<div class="blog-img">
							<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title(); ?>">
							<?php if ( '' !== get_the_post_thumbnail() ) : ?>
								<img src="<?php echo get_the_post_thumbnail_url(null, 'selio-555x442'); ?>" alt="<?php the_title(); ?>">
							<?php else: ?>
								<img src="<?php echo SELIO_THEMEROOT; ?>/assets/images/no-photo.png" alt="<?php the_title(); ?>">
							<?php endif; ?>
							</a>
							<div class="view-post">
								<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title(); ?>" class="view-posts"><?php echo esc_html__('View Post','selio-blocks');?></a>
							</div>
						</div><!--blog-img end-->
						<div class="post_info">
							<ul class="post-nfo">
								<li><i class="la la-calendar"></i><?php echo get_the_date() ?></li>
							</ul>
							<?php the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
							<p>
                                                            <?php
                                                            $content = get_the_excerpt();
                                                            $content = strip_shortcodes($content);
                                                            esc_viewe(wp_strip_all_tags(html_entity_decode(wp_trim_words(htmlentities(wpautop($content)), 14, '...'))));
                                                            ?>
                                                        </p>
							<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title(); ?>">Read More <i class="la la-long-arrow-right"></i></a>
						</div>
						<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title(); ?>" class="ext-link"></a>
					</div><!--blog-single-post end--> 
				</div>

				<?php endwhile; ?>

				<div class="col-lg-12">
					<div class="load-more-posts-grid">
					<nav class="navigation pagination" role="navigation">
						<div class="nav-links">
						<?php
							echo paginate_links( array(
									'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
									'total'        => $wp_query->max_num_pages,
									'current'      => max( 1, get_query_var( 'paged' ) ),
									'format'       => '?paged=%#%',
									) );
						?>
						</div>
					</nav>
					</div><!--load-more-posts end-->
				</div>
			</div>
		</div><!--blog-grid-posts end-->
	</div>
</section><!--blog-single-sec end-->

<?php wp_reset_postdata(); ?>

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
