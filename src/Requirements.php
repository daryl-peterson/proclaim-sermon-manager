<?php
/**
 * Run checks to see if plugin can be activated / installed.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Exceptions\PluginException;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\RequirementsInt;

/**
 * Run checks to see if plugin can be activated / installed.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Requirements implements RequirementsInt {

	/**
	 * Notice interface
	 *
	 * @var NoticeInt
	 */
	private NoticeInt $notice;

	/**
	 * Initialize object.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {

		$this->notice = notice();
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( ! is_admin() || has_action( 'admin_init', array( $this, 'run' ) ) ) {
			return false;
		}

		add_action( 'admin_init', array( $this, 'run' ) );
		return true;
	}

	/**
	 * Initialize and register.
	 *
	 * @return RequirementsInt
	 * @since 1.0.0
	 */
	public static function exec(): RequirementsInt {
		Logger::debug( 'GETTINGS ' . __CLASS__ );
		$obj = new self();
		$obj->register();

		return $obj;
	}

	/**
	 * Run checks.
	 *
	 * @return bool Always true.
	 * @since 1.0.0
	 */
	public function run(): bool {
		try {
			$this->check_php_ver();
			$this->check_wp_ver();
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
		}
		return true;
	}

	/**
	 * Check PHP version
	 *
	 * @param string $version Required PHP version.
	 * @return void
	 * @throws PluginException Throws exception if requirements are not met.
	 */
	public function check_php_ver( string $version = '' ): void {
		if ( empty( $version ) ) {
			$version = PLUGIN_MIN_PHP;
		}
		$message = __( 'This Plugin requires PHP : ', 'drppsm' ) . $version;
		if ( version_compare( PHP_VERSION, $version ) >= 0 ) {
			return;
		}
		$this->notice->set_error( '- Requirement Not Met', esc_html( $message ) );
		throw new PluginException( esc_html( $message ) );
	}

	/**
	 * Check WordPress verson
	 *
	 * @param string $version Required WordPress version.
	 * @return void
	 *
	 * @throws PluginException Throws exception if requirements are not met.
	 * @since 1.0.0
	 */
	public function check_wp_ver( string $version = '' ): void {
		global $wp_version;

		if ( empty( $version ) ) {
			$version = PLUGIN_MIN_WP;
		}
		$title   = __( 'Requiment Not Met', 'drppsm' );
		$message = __( 'This Plugin requires WP : ', 'drppsm' ) . $version;
		if ( version_compare( $wp_version, $version ) >= 0 ) {
			return;
		}
		// $this->notice->set_error( esc_html( $title ), esc_html( $message ) );
		throw new PluginException( esc_html( $message ) );
	}
}
