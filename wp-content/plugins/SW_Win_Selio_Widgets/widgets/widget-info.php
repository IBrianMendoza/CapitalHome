<?php

/**
 * widget-info.php
 * 
 * Plugin Name: Selio_Widget_Info
 * Description: A widget that displays horizontal ads in bottom.
 * Version: 1.0
 * Author: sanljiljan
 */
class Selio_Widget_Info extends WP_Widget {

    public static $multiple_instance = false;
    // Default widget settings
    private $defaults = array(
        'title' => 'Aditional Information',
        'css_class' => '',
        'aditional_info' => ''
    );

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
        $this->defaults['title']=__('Aditional Information', 'selio');
        parent::__construct(
                'widget-info', __('Selio: Info', 'selio'), array(
                'classname' => 'widget-info block-selio',
                'description' => esc_html__('Displays Info.', 'selio')
                )
        );
    }

    /**
     * Generates the back-end layout for the widget
     */
    public function form($instance) {


        $instance = wp_parse_args((array) $instance, $this->defaults);

        // The widget content 

        foreach ($this->defaults as $key => $val) {
            ?>
            <p>
                <label for="<?php echo esc_html($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))) . ':'; ?></label>
                <input type="text" class="widefat" id="<?php echo esc_html($this->get_field_id($key)); ?>" name="<?php echo esc_html($this->get_field_name($key)); ?>" value="<?php echo esc_attr($instance[$key]); ?>" />
            </p>
            <?php
        }
    }

    /**
     * Processes the widget's values
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        // Update values
        foreach ($this->defaults as $key => $val) {
            $instance[$key] = strip_tags(stripslashes($new_instance[$key]));
        }

        return $instance;
    }

    /**
     * Output the contents of the widget
     */
    public function widget($args, $instance) {
        /* if empty use defaults */
        if (empty($instance)) {
            $instance = $this->defaults;
        }
        // Extract the arguments
        extract($args);


        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $blog_title = get_bloginfo( 'name' );
        $tagline = get_bloginfo( 'description' );

        if(empty($instance['css_class']))
        {
            echo $args['before_widget'];
        }
        else
        {
            // col-xl-5 col-sm-12 col-md-5 pl-0
            echo '<div class="'.$instance['css_class'].'">';
        }
        
        echo '<div class="bottom-list">';

        if(!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo '<ul>';

        $keys = array_keys($this->defaults);

        if(!empty($instance['aditional_info']))
        {
            echo esc_viewe($instance['aditional_info']);
        }
        else
        {
            echo '
            <p>Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non mauris vitae erat consequat auctor eu in elit. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos.</p>
        ';
        }

        echo '</div>';

        echo $args['after_widget'];

        self::$multiple_instance = true;
    }

}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Info" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Info' );
});
?>