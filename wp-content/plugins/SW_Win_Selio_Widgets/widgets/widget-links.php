<?php

/**
 * widget-links.php
 * 
 * Plugin Name: Selio_Widget_Links
 * Description: A widget that displays links for right sidebar.
 * Version: 1.0
 * Author: sanljiljan
 */
class Selio_Widget_Links extends WP_Widget {

    public static $multiple_instance = false;

    private $num_slides = 5;

    // Default widget settings
    private $defaults = array(
        'title' => 'Helpful Links',
        'css_class' => ''
    );

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
        $this->defaults['title']=__('Helpful Links', 'selio');

        for ($i = 1; $i <= $this->num_slides; $i++) {
            $this->defaults['title_' . $i] = '';
            $this->defaults['link_' . $i] = '';
        }

        parent::__construct(
                'widget-right-links', __('Selio: Widget links', 'selio'), array(
                'classname' => 'widget-links block-selio',
                'description' => esc_html__('Displays helpful links', 'selio')
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
                    <label for="<?php echo esc_html($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
                    <textarea class="widefat" id="<?php echo esc_html($this->get_field_id($key)); ?>" name="<?php echo esc_html($this->get_field_name($key)); ?>" ><?php echo esc_attr($instance[$key]); ?></textarea>
                </p>
                <?php
            } else {
                ?>
                <p>
                    <label for="<?php echo esc_html($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
                    <input type="text" class="widefat" id="<?php echo esc_html($this->get_field_id($key)); ?>" name="<?php echo esc_html($this->get_field_name($key)); ?>" value="<?php echo esc_attr($instance[$key]); ?>" />
                </p>
                <?php
            }
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

        static $slider_id = 0;
        $slider_id++;

        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $blog_title = get_bloginfo( 'name' );
        $tagline = get_bloginfo( 'description' );

        if(empty($instance['css_class']))
        {
            echo $args['before_widget'];
        }
        else
        {
            // col-xl-3 col-sm-6 col-md-3
            echo '<div class="'.$instance['css_class'].'">';
        }
        
        echo '<div class="bottom-list">';

        if(!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo '<ul>';

        $counter = 0;

        $keys = array_keys($this->defaults);

        for ($i = 2; $i < count($keys); $i += 2) {
            if (!empty($instance[$keys[$i]]) && !empty($instance[$keys[$i + 1]])) {
                $counter++;
                echo '<li><a href="' . esc_url($instance[$keys[$i + 1]]) . '"' . esc_html($instance[$keys[$i]]) . '</a></li>';
            }
        }

        if ($counter == 0) {

            echo '
                <li>
                    <a href="#" title="">Half Map</a>
                </li>
                <li>
                    <a href="#" title="">Register</a>
                </li>
                <li>
                    <a href="#" title="">Pricing</a>
                </li>
                <li>
                    <a href="#" title="">Add Listing</a>
                </li>
            ';

        }

        echo '</ul>';

        echo '</div>';

        echo $args['after_widget'];

        self::$multiple_instance = true;
    }

}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Links" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Links' );
});
?>