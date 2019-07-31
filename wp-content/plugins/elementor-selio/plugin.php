<?php
namespace ElementorSelio;
use ElementorSelio\Widgets;
/*
use ElementorSelio\Widgets\Hello_World;
use ElementorSelio\Widgets\Inline_Editing;
use ElementorSelio\Widgets\Parallax_Title;
use ElementorSelio\Widgets\Menu_White;
use ElementorSelio\Widgets\Menu_Parallax_Search;
use ElementorSelio\Widgets\Listings_Featured;
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class Plugin {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function add_actions() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

		add_action( 'elementor/frontend/after_register_scripts', function()
		{
			wp_enqueue_script( 'screenshot', plugins_url( '/assets/js/screenshot.js', ELEMENTOR_SELIO__FILE__ ), [ 'jquery' ], false, true );
			// wp_register_script( 'menu-mapbkg-search', plugins_url( '/assets/js/menu-mapbkg-search.js', ELEMENTOR_SELIO__FILE__ ), [ 'jquery' ], false, true );
			//wp_enqueue_script( 'blueimp-gallery', SELIO_THEMEROOT . '/assets/js/blueimp-gallery.js', array( 'jquery' ), '', true );


			// wp_enqueue_script( 'blueimp-gallery', SELIO_THEMEROOT . '/assets/js/blueimp-gallery.js', array( 'jquery' ), '', true );
			// wp_enqueue_script( 'slick', SELIO_THEMEROOT . '/assets/slick/slick.js', array( 'jquery' ), '1.0', true );
			// wp_enqueue_script( 'slider', SELIO_THEMEROOT . '/assets/js/slider.js', array( 'jquery' ), '1.0', true );
			// wp_enqueue_script( 'selio-common', SELIO_THEMEROOT . '/assets/js/common.js', array( 'jquery' ), '1.0', true );
		} );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Includes
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function includes() {

		$widgets_dir = __DIR__ . '/widgets/';

		// Load all menu walker files
		if (is_dir($widgets_dir)){
			if ($dh = opendir($widgets_dir)){
				while (($file = readdir($dh)) !== false){
					if(strrpos($file, ".php") !== FALSE)
						require_once($widgets_dir.$file);
				}
				closedir($dh);
			}
		}

	}

	/**
	 * Register Widget
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function register_widget() {

		$widgets_dir = __DIR__ . '/widgets/';


		if (is_dir($widgets_dir)){
			if ($dh = opendir($widgets_dir)){
				while (($file = readdir($dh)) !== false){
					if(strrpos($file, ".php") !== FALSE && $file !== 'index.php')
					{
						$file_name = pathinfo($file, PATHINFO_FILENAME);
						$file_name = ucwords(str_replace('-', ' ', $file_name));
						$widget_name = str_replace(' ', '_', $file_name);

						//$widget_name = 'Hello_World';
						$class = 'ElementorSelio\Widgets\\'.$widget_name;

						$object = new $class();

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( $object );

					}
				}
				closedir($dh);
			}
		}

/*
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Inline_Editing() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Parallax_Title() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Menu_White() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Menu_Parallax_Search() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Listings_Featured() );
*/
	}
}

new Plugin();
