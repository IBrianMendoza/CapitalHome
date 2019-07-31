<?php
/**************************************************************************
Customizer include file
Includes all functions for the customizer with this theme
**************************************************************************/

/**************************************************************************
Add theme customizer controls, settings etc.
**************************************************************************/
function selio_customize_register( $wp_customize ) {
	
	/********************
	Define generic controls
    *********************/
    
    // add a setting for the site logo
    $wp_customize->add_setting('made_by', array('sanitize_callback' => 'selio_sanitize_callback'));
    // Add a control to upload the logo
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'made_by',
		    array(
		        'label'    => 'Made by text',
		        'section'  => 'title_tagline',
		        'settings' => 'made_by',
		        'type'     => 'text'
		    )
	    )
    );
    
	// create class to define textarea controls in Customizer
	class Selio_Customize_Textarea_Control extends WP_Customize_Control {
		
		public $type = 'textarea';
		public function render_content() {
			
			echo '<label>';
				echo '<span class="customize-control-title">' . esc_html( $this-> label ) . '</span>';
				echo '<textarea rows="2" style ="width: 100%;"';
				$this->link();
				echo '>' . esc_textarea( $this->value() ) . '</textarea>';
			echo '</label>';
			
		}
	}	
                
	/*******************************************
	Image upload
	********************************************/
	// add the section
	$wp_customize->add_section( 'selio_image_upload', array(
		'title' => esc_html__( 'Images', 'selio' )
	));
	
	//logo
	$wp_customize->add_setting( 'selio_logo_upload', array('sanitize_callback' => 'selio_sanitize_callback') );
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'selio_logo_upload',
		array( 
			'label' => esc_html__( 'Upload your logo', 'selio' ),
			'section' => 'selio_image_upload',
			'settings' => 'selio_logo_upload'
		)
	));
        
        /*******************************************
        Share PLugin
        * ****************************************** */
       // add the section
       $wp_customize->add_section('selio_share_plugin', array(
           'title' => esc_html__('Share Plugin ', 'selio'),
           'description' => esc_html__( 'If not defined, plugin shared current page. Please share full link, example: https://facebook.com', 'selio' ),
       ));

       //Facebook
       $wp_customize->add_setting('selio_share_plugin_facebook', array('sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_facebook', array(
           'label' => esc_html__('Facebook link', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_facebook',
           'type' => 'text',
       ));

       $wp_customize->add_setting('selio_share_plugin_facebook_hide', array('default' => '1', 'sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_facebook_hide', array(
           'label' => esc_html__('Facebook link hide', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_facebook_hide',
           'type' => 'checkbox',
       ));

       //Twitter
       $wp_customize->add_setting('selio_share_plugin_twitter', array('sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_twitter', array(
           'label' => esc_html__('Twitter link', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_twitter',
           'type' => 'text',
       ));

       $wp_customize->add_setting('selio_share_plugin_twitter_hide', array('default' => '1', 'sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_twitter_hide', array(
           'label' => esc_html__('Twitter link hide', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_twitter_hide',
           'type' => 'checkbox',
       ));

       //Linkedin
       $wp_customize->add_setting('selio_share_plugin_linkedin', array('sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_linkedin', array(
           'label' => esc_html__('Linkedin link', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_linkedin',
           'type' => 'text',
       ));

       $wp_customize->add_setting('selio_share_plugin_linkedin_hide', array('default' => '1', 'sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_linkedin_hide', array(
           'label' => esc_html__('Linkedin link hide', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_linkedin_hide',
           'type' => 'checkbox',
       ));

       //Google +
       $wp_customize->add_setting('selio_share_plugin_google', array('sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_google', array(
           'label' => esc_html__('Google+ link', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_google',
           'type' => 'text',
       ));

       $wp_customize->add_setting('selio_share_plugin_google_hide', array('default' => '1', 'sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_google_hide', array(
           'label' => esc_html__('Google+ link hide', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_google_hide',
           'type' => 'checkbox',
       ));

       //Google +
       $wp_customize->add_setting('selio_share_plugin_instagram', array('sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_instagram', array(
           'label' => esc_html__('Instagram link', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_instagram',
           'type' => 'text',
       ));

       $wp_customize->add_setting('selio_share_plugin_instagram_hide', array('default' => '1', 'sanitize_callback' => 'selio_sanitize_callback'));
       $wp_customize->add_control('selio_share_plugin_instagram_hide', array(
           'label' => esc_html__('Instagram link hide', 'selio'),
           'section' => 'selio_share_plugin',
           'settings' => 'selio_share_plugin_instagram_hide',
           'type' => 'checkbox',
       ));
        
	/*******************************************
	Contact details in header
	********************************************/
        
	// add the section
	$wp_customize->add_section( 'selio_contact' , array(
		'title' => esc_html__( 'Contact Details', 'selio')
	) );
	
	// email
	$wp_customize->add_setting( 'selio_email_setting', array (
		'default' => '', 'sanitize_callback' => 'selio_sanitize_callback'
	) );
	$wp_customize->add_control( new Selio_Customize_Textarea_Control(
		$wp_customize,
		'selio_email_setting',
		array( 
			'label' => esc_html__( 'Email', 'selio' ),
			'section' => 'selio_contact',
			'settings' => 'selio_email_setting'
	)));
        
        // phone
        $wp_customize->add_setting('selio_phone_setting', array(
            'default' => '', 'sanitize_callback' => 'selio_sanitize_callback'
        ));
        
        $wp_customize->add_control(new Selio_Customize_Textarea_Control(
                $wp_customize, 'selio_phone_setting', array(
            'label' => esc_html__('Phone', 'selio'),
            'section' => 'selio_contact',
            'settings' => 'selio_phone_setting'
        )));
        
        // address
        $wp_customize->add_setting('selio_address_setting', array(
            'default' => '', 'sanitize_callback' => 'selio_sanitize_callback'
        ));
        $wp_customize->add_control(new Selio_Customize_Textarea_Control(
                $wp_customize, 'selio_address_setting', array(
                    'label' => esc_html__('Address', 'selio'),
                    'section' => 'selio_contact',
                    'settings' => 'selio_address_setting'
                )));
        

        $wp_customize->add_section('selio_layout', array(
            'title' => esc_html__('Layout', 'selio')
        ));

        $wp_customize->add_setting('selio_gallery_in_box_enabled', array('sanitize_callback' => 'selio_sanitize_callback'));
        $wp_customize->add_control('selio_gallery_in_box_enabled', array(
            'label' => esc_html__('Gallery in separate box enabled', 'selio'),
            'section' => 'selio_layout',
            'settings' => 'selio_gallery_in_box_enabled',
            'type' => 'checkbox',
        ));

        
        $wp_customize->add_setting('selio_author_section_enabled', array('sanitize_callback' => 'selio_sanitize_callback'));
        $wp_customize->add_control('selio_author_section_enabled', array(
            'label' => esc_html__('Author section enabled', 'selio'),
            'section' => 'selio_layout',
            'settings' => 'selio_author_section_enabled',
            'type' => 'checkbox',
        ));

        
        $wp_customize->add_setting('selio_listing_subm_enabled', array('sanitize_callback' => 'selio_sanitize_callback'));
        $wp_customize->add_control('selio_listing_subm_enabled', array(
            'label' => esc_html__('Submit listing enable', 'selio'),
            'section' => 'selio_layout',
            'settings' => 'selio_listing_subm_enabled',
            'type' => 'checkbox',
        ));

        $wp_customize->add_setting('selio_login_enabled', array('sanitize_callback' => 'selio_sanitize_callback'));
        $wp_customize->add_control('selio_login_enabled', array(
            'label' => esc_html__('Ligin button enabled', 'selio'),
            'section' => 'selio_layout',
            'settings' => 'selio_login_enabled',
            'type' => 'checkbox',
        ));    

        $wp_customize->add_setting('header_sticky_enable', array('sanitize_callback' => 'selio_sanitize_callback'));
        $wp_customize->add_control('header_sticky_enable', array(
            'label' => esc_html__('header sticky enable', 'selio'),
            'section' => 'selio_layout',
            'settings' => 'header_sticky_enable',
            'type' => 'checkbox',
        ));    

        $wp_customize->add_setting('footer_placeholder', array('sanitize_callback' => 'selio_sanitize_callback'));
        $wp_customize->add_control(new WP_Customize_Image_Control(
            $wp_customize, 'footer_placeholder', array(
            'label' => esc_html__('Upload your custom header image', 'selio'),
            'section' => 'selio_layout',
            'settings' => 'footer_placeholder'
            )
        ));

}
add_action( 'customize_register', 'selio_customize_register' );


function selio_get_footer_placeholder() {
    $footer_placeholder = get_theme_mod('footer_placeholder', '');

    if (empty($footer_placeholder))
        $footer_placeholder = SELIO_THEMEROOT.'/assets/images/placeholder-footer.png';

    return $footer_placeholder;
}


function selio_email()
{
    $output = '';
    
    // email
    $email = get_theme_mod( 'selio_email_setting', '' );
    
    if(empty($email))
        $email = get_option( 'admin_email' );
    
    $output.='<a href="mailto:' . esc_arrt($email) . '"><i class="fa fa-envelope"></i> ';
    $output.=$email;
    $output.='</a>';
    
    return $output;
}

function selio_logo_in_header()
{
    $logo_url = get_theme_mod( 'selio_logo_upload', '' );
    
    if(empty($logo_url))
        $logo_url = esc_html(DEVON_IMAGES).'/logo.png';
    
    return $logo_url;
}

function selio_logomini_in_header()
{
    $logo_url = get_theme_mod( 'selio_logomini_upload', '' );
    
    if(empty($logo_url))
        $logo_url = esc_html(DEVON_IMAGES).'/logo_mini.png';
    
    return $logo_url;
}

function selio_textcolor($color_slug, $prefix='', $suffix='')
{
    $color_hex = get_option( $color_slug, '' );
    
    if(empty($color_hex))
    {
        $color_hex = '';
    }
    else
    {
        $color_hex=$prefix.$color_hex.$suffix;
    }
    
    return $color_hex;
}

function selio_background_img()
{
    $img_url = get_theme_mod( 'selio_background_upload', '' );
    
    if(empty($img_url))
        $img_url = esc_html(DEVON_IMAGES).'/patterns/full-bg-road.jpg';
    
    return $img_url;
}

function selio_layout()
{
    $return = get_theme_mod( 'selio_layout_width', 'wide' );

    if(strpos(get_page_template(), 'template-results-side') > 1)
        return 'wide';

    return $return;
}

function selio_blog_design()
{
    $return = get_theme_mod( 'selio_blog_design', '1' );

    return $return;
}



/*******************************************************************************
 add class from selio_layout to body
 ********************************************************************************/
add_filter('body_class','layout_class_names');
function layout_class_names( $classes ) {
        $classes[] = selio_layout();
	return $classes;
}

function selio_sanitize_callback($value) {
    return $value;
}
