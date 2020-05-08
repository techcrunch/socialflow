<?php
/**
 * Holds main SocialFlow plugin class
 *
 * @package SocialFlow
 */

if ( ! function_exists( 'sf_debug' ) ) {
	/**
	 * Social flow debug
	 *
	 * @param string $msg sf debug message.
	 * @param array  $data sf debug data.
	 */
	function sf_debug( $msg, $data = array() ) {
		SF_Debug::get_instance()->log( $msg, $data, 'debug' );
	}
}

require_once(ABSPATH . 'wp-admin/includes/file.php');

if ( ! function_exists( 'sf_log' ) ) {
	/**
	 * Social flow debug
	 *
	 * @param string $msg sf debug message.
	 * @param array  $data sf debug data.
	 */
	function sf_log( $msg, $data = array() ) {
		SF_Debug::get_instance()->log( $msg, $data, 'post' );
	}
}

if ( ! function_exists( 'sf_log_post' ) ) {
	/**
	 * Social flow log post
	 *
	 * @param string $msg sf debug message.
	 * @param object $post sf debug data.
	 */
	function sf_log_post( $msg, $post ) {
		SF_Debug::get_instance()->log_post( $msg, $post );
	}
}

/**
 * Social flow debug
 *
 * @package SF_Debug
 */
class SF_Debug {

	/**
	 *  Field Instance
	 *
	 * @since 1.0
	 * @var object
	 */
	protected static $instance;

	/**
	 * Use debug
	 *
	 * @since 1.0
	 * @var bool
	 */
	protected $debug = true;

	/**
	 * Create Add actions
	 *
	 * @since 1.0
	 * @access public
	 */
	protected function __construct() {
		add_action( 'init', array( $this, 'on_init' ) );
	}

	/**
	 * Init debug
	 *
	 * @since 1.0
	 * @access public
	 */
	public function on_init() {
		/** TC Edit - Fix "Debug mode" checkbox not actually enabling/disabling debug mode. */
		global $socialflow;
		$this->debug = $socialflow->options->get( 'debug_mode' );

		if ( ! $this->debug ) {
			return;
		}

		if ( ! defined( 'SF_DEBUG' ) ) {
			define( 'SF_DEBUG', true );
		}
	}

	/**
	 * Get instance field
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Log
	 *
	 * @param string $msg .
	 * @param array  $data .
	 * @param string $file .
	 */
	public function log( $msg, $data = array(), $file = 'post' ) {
		if ( ! $this->debug ) {
			return;
		}

		$date = date( 'Y-m-d H:i:s' );

		if ( $msg ) {
			if ( $data ) {
				$msg .= "\n" . print_r( $data, true );
			}

			$msg = "$date: $msg";
		}

		$this->write_log( $file, $msg );
	}

	/**
	 * Log post
	 *
	 * @param string $msg .
	 * @param object $post .
	 */
	public function log_post( $msg, $post ) {
		if ( is_object( $post ) ) {
			if ( 'post' !== $post->post_type ) {
				return;
			}

			if ( in_array( $post->post_status, array( 'new', 'any', 'auto-draft' ), true ) ) {
				return;
			}

			$post_id = $post->ID;
		} else {
			$post_id = $post;
		}

		$this->log( "post_ID: {$post_id} - $msg" );
	}

	/**
	 * Write in log
	 *
	 * @param string $file .
	 * @param string $msg .
	 * @return bool
	 */
	protected function write_log( $file, $msg = '' ) {
		$key = 'tc_socialflow_log_' . $file;

		$log   = (array) get_option( $key, [] );
		$log[] = $msg;

		// Limit log size.
		if ( count( $log ) > 5000 ) {
			array_shift( $log );
		}

		update_option( $key, $log, false );
	}
}
if ( is_admin() ) {
	SF_Debug::get_instance();
}
