<?php

/**
 * widget-popular-posts.php
 * 
 * Plugin Name: Selio_Widget_Popular_Posts
 * Description: A widget that displays horizontal ads in bottom.
 * Version: 1.0
 * Author: sanljiljan
 */
class Selio_Widget_Popular_Posts extends WP_Widget {

    // Default widget settings
    private $defaults = array(
        'title' => 'Popular posts',
        'css_class' => '',
        'show_posts' => '3',
    );

    /**
     * Specifies the widget name, description, class name and instatiates it
     */
    public function __construct() {
        $this->defaults['title']=__('Popular posts', 'selio');
        parent::__construct(
                'widget-popular-posts', __('Selio: Popular Posts', 'selio'), array(
                'classname' => 'widget-popular-posts block-selio',
                'description' => esc_html__('Displays Popular Posts with images.', 'selio')
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
                <label for="<?php echo esc_html($this->get_field_id($key)); ?>"><?php echo esc_html(ucfirst(str_replace('_', ' ', $key))); ?></label>
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
            echo '<div class="'.$instance['css_class'].'">';
        }
        
        if(!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $show_posts = (!empty($instance['show_posts'])) ? $instance['show_posts'] : 3;

        $queryArgs = array(
            'post_type' => 'post',
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'post_status' => 'publish',
            'posts_per_page' => $show_posts
        );
        $query = new WP_Query($queryArgs);

        ?>
        <ul class="list-popular-posts">
        <?php
        if ($query->have_posts()) :
            ?>
            <?php
            while ($query->have_posts()) : $query->the_post();

                $post_img = get_the_post_thumbnail_url();
                $content = get_the_excerpt();
                ?>
                <li>
                    <div class="wd-posts">
                        <div class="ps-img">
                            <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php echo get_the_title(); ?>">
                            <?php if (!empty($post_img)): ?>
                                    <img src="<?php echo esc_url($post_img); ?>" alt="<?php echo get_the_title(); ?>">
                            <?php else: ?>
                                            <img src="<?php echo esc_url(SELIO_IMAGES . '/no-photo.png'); ?>" alt="<?php echo get_the_title(); ?>">
                            <?php endif; ?>
                            </a>
                        </div><!--ps-img end-->
                        <div class="ps-info">
                            <h3><a href="<?php echo esc_url(get_permalink()); ?>" title=""><?php echo get_the_title(); ?></a><?php if(function_exists('the_views')) { the_views(); } ?></h3>
                            <span><i class="la la-calendar"></i><?php echo get_the_date('F j, y'); ?></span>
                        </div><!--ps-info end-->
                    </div><!--wd-posts end-->
                </li>
            <?php endwhile; ?>
        <?php endif; ?>
        </ul>
        <?php

        echo $args['after_widget'];
    }

}

// Register the widget using an annonymous function
if(function_exists('create_function') && version_compare(PHP_VERSION, '7.0', '<'))
   add_action('widgets_init', create_function('', 'register_widget( "Selio_Widget_Popular_Posts" );'));
else
   add_action( 'widgets_init', function(){register_widget( 'Selio_Widget_Popular_Posts' );
});

return;
?>
