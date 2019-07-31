<?php

/**
 * widget-follow-us.php
 * 
 * Plugin Name: Selio_Widget_Followus
 * Description: A widget that displays horizontal ads in bottom.
 * Version: 1.0
 * Author: sanljiljan
 */
class Selio_Widget_Followus extends WP_Widget {

    public static $multiple_instance = false;
    // Default widget settings
    private $defaults = array(
        'title' => 'Follow us',
        'css_class' => '',
        'aditional_info' => ''
    );

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
        $this->defaults['title']=__('Follow us', 'selio');
        parent::__construct(
                'widget-follow-us', __('Selio: Info', 'selio'), array(
                'classname' => 'widget-follow-us block-selio',
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
        
        echo '<div class="bottom-list widget-follow-us">';

        if(!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $content ='<div class="footer-social">';
        
        if(!get_theme_mod('nexos_share_plugin_facebook_hide'))
        if(get_theme_mod('nexos_share_plugin_facebook'))
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_facebook')).'"><i class="fa fa-facebook"></i></a>';
        else  
            $content .='<a href="'.esc_url('https://www.facebook.com/share.php?u='.urlencode(get_current_url())).'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-facebook"></i></a>';
        
        if(!get_theme_mod('nexos_share_plugin_twitter_hide'))
        if(get_theme_mod('nexos_share_plugin_twitter'))    
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_twitter')).'"><i class="fa fa-twitter"></i></a>';
        else
            $content .='<a href="'.esc_url('https://twitter.com/home?status='. urlencode(get_current_url())).'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-twitter"></i></a>';
            
        if(!get_theme_mod('nexos_share_plugin_linkedin_hide'))
        if(get_theme_mod('nexos_share_plugin_linkedin'))    
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_linkedin')).'"><i class="fa fa-linkedin"></i></a>';
        else
            $content .='<a href="'.esc_url('https://www.linkedin.com/shareArticle?mini=true&url='.urlencode(get_current_url()).'&title=&summary=&source=').'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-linkedin"></i></a>';
            
        if(!get_theme_mod('nexos_share_plugin_instagram_hide'))    
        if(get_theme_mod('nexos_share_plugin_instagram'))
            $content .='<a href="'.esc_url(get_theme_mod('nexos_share_plugin_instagram')).'"><i class="fa fa-instagram"></i></a>';
        else
            $content .='<a href="'.esc_url('https://www.instagram.com').'" onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"><i class="fa fa-instagram"></i></a>';
        
        $content .= '</div>';
        echo ($content);
          
        echo '</div>';

        echo $args['after_widget'];

        self::$multiple_instance = true;
    }

}


// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Followus" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Followus' );
});
?>