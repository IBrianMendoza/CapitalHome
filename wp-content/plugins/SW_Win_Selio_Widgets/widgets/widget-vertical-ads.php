<?php

/**
 * widget-vertical-ads.php
 * 
 * Plugin Name: Selio_Widget_Vertical_Ads
 * Description: A widget that displays vertical ads in bottom.
 * Version: 1.0
 * Author: sanljiljan
 */
class Selio_Widget_Vertical_Ads extends WP_Widget {

    // Default widget settings
    private $defaults = array(
        'title' => 'Sponsor',
        'ads_image' => '',
        'ads_link' => '#',
        'ads_code_embed' => ''
    );

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
        $this->defaults['title']=__('Sponsor', 'selio');
        parent::__construct(
                'widget-vertical-ads', __('Selio: Vertical Ads', 'selio'), array(
                'classname' => 'widget-vertical-ads widget-adver block-selio',
                'description' => esc_html__('Displays Vertical Ads.', 'selio')
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
            } elseif (substr_count($key, 'embed') > 0) {
                ?>
                <p>
                    <label for="<?php echo esc_html($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
                    <textarea class="widefat" id="<?php echo esc_html($this->get_field_id($key)); ?>" name="<?php echo esc_html($this->get_field_name($key)); ?>" ><?php echo esc_html($instance[$key]); ?></textarea>
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
            if (substr_count($key, 'embed') > 0) {
                $instance[$key] = $new_instance[$key];
            } else {
                $instance[$key] = strip_tags(stripslashes($new_instance[$key]));
            }
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


        $title = apply_filters('widget_title', $instance['title']);

        // Display the markup before the widget (as defined in functions.php)
        esc_viewe($before_widget);

        $img_url = SELIO_THEMEROOT.'/assets/images/resources/ad-img.jpg';

        if (!empty($instance['ads_image']) && is_numeric($instance['ads_image'])) {
            $img = wp_get_attachment_image_src($instance['ads_image'], 'full', true, '');
            if (isset($img[0]) && substr_count($img[0], 'media/default.png') == 0) {
                $img_url = $img[0];
            }
        }

        if (empty($instance['ads_link']))
            $instance['ads_link'] = '#';

        if (!empty($instance['ads_code_embed'])) {
            echo esc_html($instance['ads_code_embed']);
        } else {
            echo '<a target="_blank" href="' . esc_url($instance['ads_link']) . '"><img src="' . esc_html($img_url) . '" alt="..." class="center-block" /></a>';
        }


        // Display the markup after the widget (as defined in functions.php)
        esc_viewe($after_widget);
    }

}

// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Vertical_Ads" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Vertical_Ads' );
});
?>