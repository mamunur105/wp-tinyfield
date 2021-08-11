<?php
/**
 * Field displayed by this function.
 *
 * @package    Codexin Metabox
 * @subpackage Codexin_Metabox
 */

namespace Codexin\MetaboxesClasses;

/**
 * Display Metabox.
 */
class MetaboxLoded {

	/**
	 * Get instance;
	 *
	 * @var obaject
	 */
	public static $instance;

	/**
	 * Metabox container array
	 *
	 * @var array
	 */
	private $meta_boxes;

	/**
	 * Metabox assets suffix.
	 *
	 * @var array
	 */
	private $suffix;

	/**
	 * An array inside container.
	 *
	 * @var array
	 */
	private $fields;
	/**
	 * Metabox screen
	 *
	 * @var array
	 */
	private $screen_container;

	/**
	 * Metaboxes.
	 */
	private function __construct() {
		if ( ! defined( 'CDXN_MB_VERSION' ) ) {
			define( 'CDXN_MB_VERSION', '1.0.0' );
		}
		if ( ! defined( 'CDXN_MB_PLUGIN_NAME' ) ) {
			define( 'CDXN_MB_PLUGIN_NAME', 'cdxn-metaboxes' );
		}
		if ( ! defined( 'CDXN_MB_PATH' ) ) {
			define( 'CDXN_MB_PATH', __DIR__ . '/../../' );
		}
		if ( ! defined( 'CDXN_MB_URL' ) ) {
			define( 'CDXN_MB_URL', $this->get_url_info_from_file( CDXN_MB_PATH )['url'] );
		}
		if ( ! defined( 'CDXN_MB_ASSETS' ) ) {
			define( 'CDXN_MB_ASSETS', CDXN_MB_URL . 'assets' );
		}
		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Create instance
	 *
	 * @return object
	 */
	public static function init() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Metaboxes.
	 *
	 * @return void
	 */
	public function metaboxes() {
		$configs = apply_filters( 'cdxn_meta_boxes', array(), 10, 1 );
		if ( is_array( $configs ) && count( $configs ) ) {
			foreach ( $configs as $container ) {
				if( isset( $container['tab'] ) ){
					new CodexinMetaboxTab( $container );
				}else{
					new CodexinMetabox( $container );
				}
			}
		}
	}
	/**
	 * Scripts.
	 *
	 * @return void
	 */
	public function admin_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined in that particular
		 * class.
		 *
		 * The Loader will then create the relationship between the defined
		 * hooks and the functions defined in this class.
		 */
		\wp_enqueue_style(
			'cdxn-metabox',
			CDXN_MB_ASSETS . '/styles/admin' . $this->suffix . '.css',
			array(),
			CDXN_MB_VERSION,
			'all'
		);
		\wp_enqueue_style(
			'select2',
			CDXN_MB_ASSETS . '/vendor/select2.min.css',
			array(),
			CDXN_MB_VERSION,
			'all'
		);
		\wp_register_script(
			'select2',
			CDXN_MB_ASSETS . '/vendor/select2.min.js',
			array( 'jquery' ),
			CDXN_MB_VERSION,
			true
		);
		\wp_register_script(
			'wp-color-picker-alpha',
			CDXN_MB_ASSETS . '/vendor/wp-color-picker-alpha.min.js',
			array( 'jquery', 'wp-color-picker' ),
			CDXN_MB_VERSION,
			true
		);
		\wp_register_script(
			'range-slider',
			CDXN_MB_ASSETS . '/vendor/range-slider.js',
			array(),
			CDXN_MB_VERSION,
			true
		);
		\wp_enqueue_script(
			'cdxn-gallery-admin',
			CDXN_MB_ASSETS . '/scripts/admin' . $this->suffix . '.js',
			array( 'jquery' ),
			CDXN_MB_VERSION,
			true
		);

	}

	/**
	 * Converts a system file path to a URL.
	 * Returns URL and the detected location of the file.
	 *
	 * Based on get_url_from_dir() via CMB2
	 *
	 * @link https://github.com/CMB2/CMB2
	 *
	 * @param  string $file file path to convert.
	 * @return string Converted URL.
	 *  array
	 *    $url string Converted URL.
	 *    $location string location of dir (mu-plugins, plugins, theme)
	 */
	public function get_url_info_from_file( $file ) {
		$file     = wp_normalize_path( $file );
		$test_dir = pathinfo( $file );
		if ( ! $test_dir ) {
			return false;
		}
		$test_dir = trailingslashit( $test_dir['dirname'] );
		// Test if we are in the mu-plugins dir.
		if ( 0 === strpos( $test_dir, wp_normalize_path( WPMU_PLUGIN_DIR ) ) ) {
			return array(
				'url'      => trailingslashit( plugins_url( '', $file ) ),
				'location' => 'mu-plugins',
			);
		}
		// Test if we are in the plugins dir.
		if ( 0 === strpos( $test_dir, wp_normalize_path( WP_PLUGIN_DIR ) ) ) {
			return array(
				'url'      => trailingslashit( plugins_url( '', $file ) ),
				'location' => 'plugins',
			);
		}
		// Now let's test if we are in the theme dir.
		$theme_root = wp_normalize_path( get_theme_root() );
		if ( 0 === strpos( $file, $theme_root ) ) {
			// Ok, then use get_theme_root_uri.
			$url = set_url_scheme(
				trailingslashit(
					str_replace(
						untrailingslashit( $theme_root ),
						untrailingslashit( get_theme_root_uri() ),
						$test_dir
					)
				)
			);
			return array(
				'url'      => $url,
				'location' => 'theme',
			);
		}
	}
}
