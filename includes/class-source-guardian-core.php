<?php
/**
 * Source_Guarduan_Core
 *
 * @author NasimNet <nasimnet.ir@gmail.com>
 * @package sg-check/includes
 * @since 1.5
 */

/**
 * Source_Guardian_Core class
 *
 * @package sg-check/includes
 * @version  1.0.0
 */
class Source_Guardian_Core {

	/**
	 * The latest version of Source Guardian
	 *
	 * @var integer
	 */
	private $version = 12.0;

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_management_page' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice' ) );
	}

	/**
	 * Add Managment Page
	 *
	 * @return void
	 */
	public function add_management_page() {
		add_management_page(
			__( 'Source Guardian Cheker', 'sg-check' ),
			__( 'Source Guardian Cheker', 'sg-check' ),
			'manage_options',
			'source-guardian-check',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Siurce Guardian Details
	 *
	 * @return string html
	 */
	public function source_guardian_content() {

		$extensions = get_loaded_extensions();
		$content    = '';

		// Access to the server is limited !
		if ( @ ! $extensions ) { //phpcs:ignore

			$content .= '<span class="dashicons dashicons-hidden icon-sg red"></span>';
			$content .= '<p class="red">';
			$content .= __( 'Unfortunately, server information cannot be accessed!', 'sg-check' ) . '<br>';
			$content .= __( 'Contact your hosting provider for more information', 'sg-check' ) . '<br>';
			$content .= sprintf(
				/* translators: latest source guardian version */
				__( 'The latest version is the current version of %s.', 'sg-check' ),
				$this->version
			);
			$content .= '</p>';

			return $content;
		}

		// The Source Guardian Loader not installed !
		if ( ! in_array( 'SourceGuardian', $extensions, true ) ) {

			$content .= '<span class="dashicons dashicons-dismiss icon-sg red"></span>';
			$content .= '<p class="red">';
			$content .= __( 'Source Guardian Loader is not installed !', 'sg-check' ) . '<br>';
			$content .= __( 'Ask your host to install and enable it for you', 'sg-check' ) . '<br>';
			$content .= '<a href="https://www.sourceguardian.com/loaders.html" target="_balnk">' . __( 'more information', 'sg-check' ) . '</a>';
			$content .= '</p>';

			return $content;
		}

		$current_version = phpversion( 'SourceGuardian' );
		$content        .= __( 'Source Guardian Version : ', 'sg-check' ) . ' <b>' . $current_version . '</b><br>';

		// The version of Source Guardian Loader is old !
		if ( version_compare( $current_version, $this->version, '<' ) ) {

			$content .= '<span class="dashicons dashicons-warning icon-sg orange"></span>';
			$content .= '<p class="orange">';
			$content .= __( 'Source Guardian version is outdated !', 'sg-check' ) . '<br>';
			$content .= __( 'Ask your hosting provider to update the Source Guardian loader.', 'sg-check' ) . '<br>';
			$content .= sprintf(
				/* translators: latest source guardian version */
				__( 'The latest version is the current version of %s.', 'sg-check' ),
				$this->version
			) . '<br>';
			$content .= '<a href="https://www.sourceguardian.com/loaders.html" target="_balnk">' . __( 'more information', 'sg-check' ) . '</a>';
			$content .= '</p>';

			return $content;
		}

		// The Source Guardian Loader is up to date.
		$content .= '<span class="dashicons dashicons-smiley icon-sg green"></span>';
		$content .= '<p class="green">';
		$content .= __( 'congratulations ! Source Guardian loaders are up to date', 'sg-check' ) . '<br>';
		$content .= __( 'Good luck !', 'sg-check' );
		$content .= '</p>';

		return $content;
	}

	/**
	 * Server Info
	 *
	 * @return array
	 */
	public function server_info() {
		global $wpdb;

		$server_info_fields = array(
			array(
				'title' => __( 'Server OS', 'sg-check' ),
				'desc'  => php_uname(),
			),
			array(
				'title' => __( 'Server Software', 'sg-check' ),
				'desc'  => $_SERVER['SERVER_SOFTWARE'], //phpcs:ignore
			),
			array(
				'title' => __( 'PHP version', 'sg-check' ),
				'desc'  => ( function_exists( 'phpversion' ) ) ? phpversion() : __( 'phpversion() function not available!', 'sg-check' ),
			),
			array(
				'title' => __( 'PHP Post Max Size', 'sg-check' ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ) : __( 'Ini_get() function not available!', 'sg-check' ),
			),
			array(
				'title' => __( 'PHP Time Limit', 'sg-check' ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? ini_get( 'max_execution_time' ) . ' ' . __( 'Seconds', 'sg-check' ) : __( 'Ini_get() function not available!', 'sg-check' ),
			),
			array(
				'title' => __( 'PHP Max Input Vars', 'sg-check' ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? number_format_i18n( ini_get( 'max_input_vars' ) ) : __( 'Ini_get() function not available!', 'sg-check' ),
			),
			array(
				'title' => __( 'SUHOSIN', 'sg-check' ),
				'desc'  => ( extension_loaded( 'suhosin' ) ) ? '&#10004;' : '&ndash;',
			),
			array(
				'title' => __( 'MySQL Version', 'sg-check' ),
				'desc'  => $wpdb->db_version(),
			),
			array(
				'title' => __( 'Max Upload Size', 'sg-check' ),
				'desc'  => size_format( wp_max_upload_size() ),
			),
			array(
				'title' => __( 'Display Errors', 'sg-check' ),
				'desc'  => ( function_exists( 'ini_get' ) ) ? ( ini_get( 'display_errors' ) ? '&#10004;' : '&ndash;' ) : '<span class="notice-error">' . __( 'Ini_get() function not available!', 'sg-check' ) . '</span>',
			),
		);

		return apply_filters( 'sgcheck_server_info_default_fields', $server_info_fields );
	}

	/**
	 * Admin Page
	 *
	 * @return void
	 */
	public function admin_page() {
		if ( ! get_option( SGCHECK_OPT_NAME ) ) {
			update_option( SGCHECK_OPT_NAME, 1 );
		}

		$sg_details  = $this->source_guardian_content();
		$server_info = $this->server_info();

		include dirname( __FILE__ ) . '/template.php';
	}

	/**
	 * Admin Notice
	 *
	 * @return void
	 */
	public function admin_notice() {
		if ( ! get_option( SGCHECK_OPT_NAME ) ) {

			$class       = 'notice notice-success';
			$message     = __( 'The Source Guardian Check plugin has been successfully activated', 'sg-check' );
			$url         = admin_url( 'tools.php?page=source-guardian-check' );
			$url_message = __( 'Click to view status', 'sg-check' );

			printf(
				'<div class="%1$s"><p>%2$s . <a href="%3$s">%4$s</a></p></div>',
				esc_attr( $class ),
				esc_html( $message ),
				esc_url( $url ),
				esc_html( $url_message )
			);

		}
	}
}

if ( is_admin() ) {
	new Source_Guardian_Core();
}
