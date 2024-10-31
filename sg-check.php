<?php //phpcs:ignore
/**
 * With this plugin you can check the version of your server's Source Guardian loader.
 *
 * @package Source Guardian Check
 *
 * Plugin Name: SG Check
 * Plugin URI: https://nasimnet.ir/
 * Description: With this plugin you can check the version of your server's Source Guardian loader.
 * Version: 1.5
 * Author: NasimNet
 * Author URI: https://nasimnet.ir
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

/**
 * Source_Guarduan_Check
 *
 * @package ncp-edit-adcat
 * @version  1.0.0
 */
class Source_Guarduan_Check {

	/**
	 * Instance
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Contruct
	 */
	public function __construct() {
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_style' ) );
		$this->define_constants();
		$this->load_files();
	}

	/**
	 * Create an instance from this class.
	 *
	 * @access public
	 * @since  4.0
	 * @return Class
	 */
	public static function instance() {
		if ( is_null( ( self::$instance ) ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load textdomain
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'sg-check', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Define Constans
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'SGCHECK_VER', '1.0' );
		define( 'SGCHECK_PATH', plugin_dir_path( __FILE__ ) );
		define( 'SGCHECK_URL', plugin_dir_url( __FILE__ ) );
		define( 'SGCHECK_OPT_NAME', 'source_guardian_checked' );
	}

	/**
	 * Load Files
	 *
	 * @return void
	 */
	public function load_files() {
		require_once SGCHECK_PATH . 'includes/class-source-guardian-core.php';
	}

	/**
	 * Admin Style
	 *
	 * @param string $hook page name.
	 * @return void
	 */
	public function admin_style( $hook ) {
		if ( 'tools_page_source-guardian-check' === $hook ) {
			wp_enqueue_style(
				'sgcheck_admin_css',
				plugins_url( 'assets/css/admin-style.css', __FILE__ ),
				array(),
				SGCHECK_VER
			);
		}
	}

	/**
	 *  Register deactivation hook
	 *
	 * @return void
	 */
	public function deactivate() {
		delete_option( SGCHECK_OPT_NAME );
	}

}

Source_Guarduan_Check::instance();
