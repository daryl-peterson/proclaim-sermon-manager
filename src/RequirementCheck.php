<?php
/**
 * Run check to see if plugin can be activated / installed.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace DRPSermonManager;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\RequirementCheckInt;

/**
 * Run checks to see if requirements are met. If not throw PluginException.
 */
class RequirementCheck implements RequirementCheckInt {

	private NoticeInt $notice;

	/**
	 * Set object properties.
	 */
	protected function __construct() {

		$this->notice = App::getNoticeInt();
	}

	/**
	 * Initialize object properties.
	 *
	 * @return RequirementCheckInt
	 *
	 * @since 1.0.0
	 */
	public static function init(): RequirementCheckInt {
		return new self();
	}

	/**
	 * Run checks.
	 *
	 * @since 1.0.0
	 */
	public function run(): void {
		$this->check_php_ver();
		$this->check_wp_ver();
	}

	/**
	 * Check PHP version
	 *
	 * @param string $version Required PHP version.
	 *
	 * @return void
	 *
	 * @throws PluginException Throws exception if requirements are not met.
	 */
	public function check_php_ver( string $version = '' ): void {
		if ( empty( $version ) ) {
			$version = PLUGIN_MIN_PHP;
		}
		$message = __( 'This Plugin requires PHP : ', 'drpsermon' ) . $version;
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
		$title   = __( 'Requiment Not Met', 'drpsermon' );
		$message = __( 'This Plugin requires WP : ', 'drpsermon' ) . $version;
		if ( version_compare( $wp_version, $version ) >= 0 ) {
			return;
		}
		$this->notice->set_error( esc_html( $title ), esc_html( $message ) );
		throw new PluginException( esc_html( $message ) );
	}
}
