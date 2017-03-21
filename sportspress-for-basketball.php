<?php
/*
 * Plugin Name: SportsPress for Basketball
 * Plugin URI: http://themeboy.com/
 * Description: A suite of basketball features for SportsPress.
 * Author: ThemeBoy
 * Author URI: http://themeboy.com/
 * Version: 0.9.1
 *
 * Text Domain: sportspress-for-basketball
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Basketball' ) ) :

/**
 * Main SportsPress Basketball Class
 *
 * @class SportsPress_Basketball
 * @version	0.9.1
 */
class SportsPress_Basketball {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), 0 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 30 );
		add_action( 'tgmpa_register', array( $this, 'require_core' ) );

		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );

		// Define default sport
		add_filter( 'sportspress_default_sport', array( $this, 'default_sport' ) );

		// Include required files
		$this->includes();
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_BASKETBALL_VERSION' ) )
			define( 'SP_BASKETBALL_VERSION', '0.9.1' );

		if ( !defined( 'SP_BASKETBALL_URL' ) )
			define( 'SP_BASKETBALL_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_BASKETBALL_DIR' ) )
			define( 'SP_BASKETBALL_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sportspress-for-basketball' );
		
		// Global + Frontend Locale
		load_textdomain( 'sportspress-for-basketball', WP_LANG_DIR . "/sportspress-for-basketball/sportspress-for-basketball-$locale.mo" );
	}

	/**
	 * Enqueue styles.
	 */
	public static function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_event', 'edit-sp_event' ) ) ) {
			wp_enqueue_script( 'sportspress-basketball-admin', SP_BASKETBALL_URL . 'js/admin.js', array( 'jquery' ), SP_BASKETBALL_VERSION, true );
		}

		wp_enqueue_style( 'sportspress-basketball-admin', SP_BASKETBALL_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), '0.9' );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
		require_once dirname( __FILE__ ) . '/includes/class-tgm-plugin-activation.php';
	}

	/**
	 * Require SportsPress core.
	*/
	public static function require_core() {
		$plugins = array(
			array(
				'name'        => 'SportsPress',
				'slug'        => 'sportspress',
				'required'    => true,
				'version'     => '2.3',
				'is_callable' => array( 'SportsPress', 'instance' ),
			),
		);

		$config = array(
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'has_notices'  => true,
			'dismissable'  => true,
			'is_automatic' => true,
			'message'      => '',
			'strings'      => array(
				'nag_type' => 'updated'
			)
		);

		tgmpa( $plugins, $config );
	}

	/** 
	 * Text filter.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		if ( $domain == 'sportspress' ) {
			switch ( $untranslated_text ) {
				case 'Events':
					$translated_text = __( 'Games', 'sportspress-for-basketball' );
					break;
				case 'Event':
					$translated_text = __( 'Game', 'sportspress-for-basketball' );
					break;
				case 'Add New Event':
					$translated_text = __( 'Add New Game', 'sportspress-for-basketball' );
					break;
				case 'Edit Event':
					$translated_text = __( 'Edit Game', 'sportspress-for-basketball' );
					break;
				case 'View Event':
					$translated_text = __( 'View Game', 'sportspress-for-basketball' );
					break;
				case 'View all events':
					$translated_text = __( 'View all games', 'sportspress-for-basketball' );
					break;
				case 'Venues':
					$translated_text = __( 'Courts', 'sportspress-for-basketball' );
					break;
				case 'Venue':
					$translated_text = __( 'Court', 'sportspress-for-basketball' );
					break;
				case 'Edit Venue':
					$translated_text = __( 'Edit Court', 'sportspress-for-basketball' );
					break;
				case 'Substitute':
				case 'Substituted':
					$translated_text = __( 'Bench', 'sportspress-for-basketball' );
					break;
			}
		}
		
		return $translated_text;
	}

	/**
	 * Define default sport.
	*/
	public function default_sport() {
		return 'basketball';
	}
}

endif;

new SportsPress_Basketball();
