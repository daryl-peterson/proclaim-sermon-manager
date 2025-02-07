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

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Exceptions\PluginException;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Run checks to see if plugin can be activated / installed.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Requirements implements Executable, Registrable {
	use ExecutableTrait;

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

		$this->notice = Notice::exec();
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
	 * Run checks.
	 *
	 * @return bool Always true.
	 * @since 1.0.0
	 */
	public function run(): bool {
		try {
			$this->check_php_ver();
			$this->check_wp_ver();

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
			// @codeCoverageIgnoreEnd
		}
		return true;
	}

	/**
	 * Check PHP version.
	 *
	 * @param string $version Required PHP version.
	 * @return ?bool True if requirements are met.
	 * @throws PluginException Throws exception if requirements are not met.
	 * @since 1.0.0
	 */
	public function check_php_ver( string $version = '' ): ?bool {
		if ( empty( $version ) ) {
			$version = DRPPSM_MIN_PHP;
		}
		$message = __( 'This Plugin requires PHP : ', 'drppsm' ) . $version;
		if ( version_compare( PHP_VERSION, $version ) >= 0 ) {
			return true;
		}
		$this->notice->set_error( '- Requirement Not Met', esc_html( $message ) );
		throw new PluginException( esc_html( $message ) );
	}

	/**
	 * Check WordPress verson
	 *
	 * @param string $version Required WordPress version.
	 * @return ?bool True if requirements are met.
	 *
	 * @throws PluginException Throws exception if requirements are not met.
	 * @since 1.0.0
	 */
	public function check_wp_ver( string $version = '' ): ?bool {
		global $wp_version;

		if ( empty( $version ) ) {
			$version = DRPPSM_MIN_WP;
		}

		$message = __( 'This Plugin requires WP : ', 'drppsm' ) . $version;
		if ( version_compare( $wp_version, $version ) >= 0 ) {
			return true;
		}

		throw new PluginException( esc_html( $message ) );
	}
}
