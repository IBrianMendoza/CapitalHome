<?php
/**
 * widget-fullwidth-discover.php
 * 
 * Plugin Name: Selio Widget Right Posts
 * Description: A widget that displays horizontal news in bottom.
 * Version: 1.0
 * Author: sanljiljan
*/

class Selio_Widget_Fullwidth_Discover extends WP_Widget {
    
    public static $multiple_instance=false;
    
	// Default widget settings
	private $defaults = array(
		'title'               => 'Discover a home you\'ll love to stay',
		'ads_link'               => '#',
	);

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
                $this->defaults['title']=__('Discover a home you\'ll love to stay', 'selio');
		parent::__construct( 
			'widget-fullwidth-discover',
			__( 'Selio: Ads section', 'selio' ),
			array(
				'title'   => 'Discover',
				'classname'   => 'widget-fullwidth-discover block-selio',
				'description' => esc_html__( 'Displays Your Discover', 'selio' )
			) 
		);
	}


	/**
	 * Generates the back-end layout for the widget
	 */
        public function form($instance) {

              static $form_counter = 1;

              $form_counter++;

              $instance = wp_parse_args((array) $instance, $this->defaults);

              // The widget content 

              foreach ($this->defaults as $key => $val) {
                  if (substr_count($key, 'image') > 0) {
                      selio_upload_media_element('imu_' . $form_counter, $this->get_field_id($key), $this->get_field_name($key), esc_attr($instance[$key]));
                  } elseif (substr_count($key, 'description') > 0) {
                      ?>
                      <p>
                          <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
                          <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" ><?php echo esc_attr($instance[$key]); ?></textarea>
                      </p>
                      <?php
                  } 
                  elseif(substr_count($key, 'checkbox') > 0)
                  {
                      ?>
                              <p>
                                      <label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html(ucfirst(str_replace(array('_','checkbox'), ' ', $key))); ?></label>
                                      <input type="checkbox" class="widefat" id="<?php echo esc_attr($this->get_field_id( $key )); ?>" name="<?php echo esc_attr($this->get_field_name( $key )); ?>" value="1" <?php if(esc_attr( $instance[$key] )==1) echo 'checked'; ?> />
                              </p>
                      <?php
                  }  elseif(substr_count($key, 'textarea') > 0) {
                      ?>
                      <p>
                          <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
                          <textarea type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>"/><?php esc_viewe($instance[$key]); ?></textarea>
                      </p>
                      <?php
                  } else {
                      ?>
                      <p>
                          <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
                          <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id($key)); ?>" name="<?php echo esc_attr($this->get_field_name($key)); ?>" value="<?php echo esc_attr($instance[$key]); ?>" />
                      </p>
                      <?php
                  }
              }
          }



	/**
	 * Processes the widget's values
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Update values
        foreach($this->defaults as $key=>$val)
        {
            $instance[$key] = strip_tags( stripslashes( $new_instance[$key] ) );
        }

		return $instance;
	}


	/**
	 * Output the contents of the widget
	 */
	public function widget( $args, $instance ) 
    {
        
        /* if empty use defaults */ 
        if(empty($instance)) {
            $instance=$this->defaults;
        }
            
        // Extract the arguments
        extract( $args );

                
        if(stripos($args['id'], 'sidebar') === FALSE && FALSE)
        {
            echo '<div class="clearfix"></div><div class="alert alert-danger">';
            echo '"'.esc_html($args['widget_name']).'" '.esc_html__("can't be used in this widget placeholder","selio").'';
            echo '</div>';
            return;
        }
        
        if(self::$multiple_instance === true)return;

        $title = apply_filters( 'widget_title', $instance['title'] );
        
        $ads_image = SELIO_IMAGES . '/ad-img.jpg';
        
        if (isset($instance['ads_image']) && !empty($instance['ads_image'])) {
            $img = wp_get_attachment_image_src($instance['ads_image'], 'full', true, '');
            if (isset($img[0]) && substr_count($img[0], 'media/default.png') == 0) {
                $ads_image = $img[0];
            }
        }
        // Display the markup before the widget (as defined in functions.php)
        $before_widget = str_replace(array('col-sm-3'), 'block-wide', $before_widget);
        esc_viewe($before_widget);
        ?>
        <a href="<?php echo esc_url(selio_ch($instance['ads_link'],'#'));?>" title="<?php echo esc_attr($title);?>">
            <section class="cta st2 section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="cta-text">
                                <h2><?php echo esc_attr($title);?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </a>
            
        <?php
        esc_viewe($after_widget);
        self::$multiple_instance = true;
	}
}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Fullwidth_Discover" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Fullwidth_Discover' );});
