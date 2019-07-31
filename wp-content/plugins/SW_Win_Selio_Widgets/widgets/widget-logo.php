<?php

/**
 * widget-logo-social.php
 * 
 * Plugin Name: Selio_Widget_Logo
 * Description: A widget that displays logo and address in footer.
 * Version: 1.0
 * Author: sanljiljan
 */
class Selio_Widget_Logo extends WP_Widget {

    // Default widget settings
    private $defaults = array(
        'title' => '',
        'css_class' => '',
        'address' => '',
        'phone_1' => '',
        'phone_2' => '',
        'email' => '',
        'custom_line' => ''
    );

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
        parent::__construct(
                'widget-logo-social', __('Selio: Logo', 'selio'), array(
            'classname' => 'widget-logo-social block-selio',
            'description' => esc_html__('Displays logo and address.', 'selio')
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

        $logo_url = SELIO_IMAGES . '/logo.png';

        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $blog_title = get_bloginfo( 'name' );
        $tagline = get_bloginfo( 'description' );

        if(empty($instance['css_class']))
        {
            echo $args['before_widget'];
        }
        else
        {
            // col-xl-4 col-sm-6 col-md-4
            echo '<div class="'.$instance['css_class'].'">';
        }
        
        if(!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo '<div class="bottom-logo">
                <a href="'.esc_url( home_url( '/' ) ).'">
                    <img src="'.$logo_url.'" alt="" class="img-fluid">
                </a>';
        ?>
                
        <div class="content">       
        <p class="description"><?php echo esc_html__('Nam nec tellus a odio tincidunt auctor a ornare odio. Sed non  mauris vitae erat co nsequat auctor eu in elit.','selio');?></p>  
        </div> 
        <?php
        
        $keys = array_keys($this->defaults);
        for ($i = 2; $i < count($keys); $i += 2) {
            if (!empty($instance[$keys[$i]])) {
                echo '<div>';
                if (substr_count($keys[$i], 'address') > 0)
                    echo '<i class="fa fa-map-marker"></i> ';
                if (substr_count($keys[$i], 'phone') > 0)
                    echo '<i class="fa fa-phone"></i> ';
                if (substr_count($keys[$i], 'email') > 0)
                    echo '<i class="fa fa-envelope"></i> ';

                echo '<span class="value">';
                if (substr_count($keys[$i], 'email') > 0)
                    echo '<a href="' . esc_url('mailto:' . $instance[$keys[$i]]) . '">' . esc_html($instance[$keys[$i]]) . '</a>';
                else if (substr_count($keys[$i], 'phone') > 0)
                    echo '<a href="' . esc_url('tel:' . $instance[$keys[$i]]) . '">' . esc_html($instance[$keys[$i]]) . '</a>';
                else
                    echo esc_html($instance[$keys[$i]]);
                echo '</span>';
                echo '</div>';
            }
        }

        echo '</div>';

        echo $args['after_widget'];
    }

}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Logo" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Logo' );
});
?>
