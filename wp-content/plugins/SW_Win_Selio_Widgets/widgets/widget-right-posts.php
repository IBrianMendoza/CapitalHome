<?php
/**
 * widget-right-posts.php
 * 
 * Plugin Name: Selio Widget Right Posts
 * Description: A widget that displays horizontal news in bottom.
 * Version: 1.0
 * Author: sanljiljan
*/

class Selio_Widget_Right_Posts extends WP_Widget {
    
    public static $multiple_instance=false;
    
	// Default widget settings
	private $defaults = array(
		'title'               => 'Latest News'
	);

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
            $this->defaults['title']=__('Latest News', 'selio');
		parent::__construct( 
			'widget-right-posts',
			__( 'Selio: Widget Right Posts', 'selio' ),
			array(
				'title'   => 'Latest News',
				'classname'   => 'widget-posts block-selio',
				'description' => esc_html__( 'Displays Posts', 'selio' )
			) 
		);
	}



	/**
	 * Generates the back-end layout for the widget
	 */
	public function form( $instance ) {


		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// The widget content 
        
        foreach($this->defaults as $key=>$val)
        {
        ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))).':'; ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( $key )); ?>" name="<?php echo esc_attr($this->get_field_name( $key )); ?>" value="<?php echo esc_attr( $instance[$key] ); ?>" />
		</p>
        <?php
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

                
        if(stripos($args['id'], 'sidebar') === FALSE)
        {
            echo '<div class="clearfix"></div><div class="alert alert-danger">';
            echo '"'.esc_html($args['widget_name']).'" '.esc_html__("can't be used in this widget placeholder","selio").'';
            echo '</div>';
            return;
        }
        
        if(self::$multiple_instance === true)return;

        $title = apply_filters( 'widget_title', $instance['title'] );

        // Display the markup before the widget (as defined in functions.php)
        $before_widget = str_replace(array('col-sm-3'), 'block-wide', $before_widget);
        esc_viewe($before_widget);

        /* Create a custom query and get the most recent 6 projects. */
        $queryArgs = array(
            'orderby'           => 'date',
            'post_status'    => 'publish',
            'posts_per_page' => '3'
        );
        $query = new WP_Query( $queryArgs );
        
        ?>
                
            <h3 class="widget-title"><?php echo esc_html($title);?></h3>
            <ul>
            <?php
                $i=0;
                if ( $query->have_posts() ) :  
            ?>
            <?php
               while ( $query->have_posts() ) : $query->the_post();  

                $post_img = get_the_post_thumbnail_url();
                $content = get_the_excerpt();
            ?>
                <li>
                    <div class="wd-posts">
                        <div class="ps-img">
                            <a href="<?php echo esc_url(get_permalink());?>" title="<?php echo esc_html(get_the_title());?>">
                                <?php if(!empty($post_img)):?>
                                <img src="<?php echo esc_url($post_img);?>" alt="<?php echo esc_html(get_the_title());?>">
                                <?php else:?>
                                <img src="<?php echo esc_url(YORDY_IMAGES.'/no-photo.png');?>" alt="<?php echo esc_html(get_the_title());?>">
                    <?php endif;?>
                            </a>
                        </div><!--ps-img end-->
                        <div class="ps-info">
                            <h3><a href="<?php echo esc_url(get_permalink());?>" title="<?php echo esc_html(get_the_title());?>"><?php echo esc_html(get_the_title());?></a></h3>
                            <span><i class="la la-calendar"></i><?php echo get_the_date('M j, Y'); ?></span>
                        </div><!--ps-info end-->
                    </div><!--wd-posts end-->
                </li>
            <?php endwhile;?>
            <?php endif;?>
            </ul>
                
        <?php
        esc_viewe($after_widget);
        self::$multiple_instance = true;
	}
}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Right_Posts" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Right_Posts' );});
