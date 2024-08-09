<?php
/**
 * Language locales.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\TextDomainInt;
use DRPPSM\Logging\Logger;

/**
 * Language locales.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TextDomain implements TextDomainInt {

	public const INIT_KEY = 'TEXT_DOMAIN_INIT';

	/**
	 * Register callbacks
	 *
	 * @return null|bool Return true is default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		$hook = Helper::get_key_name( self::INIT_KEY );

		if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return true;
			// @codeCoverageIgnoreEnd
		}

		add_action( 'init', array( $this, 'load_domain' ) );
		do_action( $hook );

		return true;
	}

	/**
	 * Load domain locales
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function load_domain(): void {
		load_plugin_textdomain( DOMAIN, false, basename( dirname( FILE ) ) . '/languages/' );
	}

	/**
	 * Switch to site language.
	 *
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function switch_to_site_locale(): bool {
		$result = false;
		try {
			if ( ! function_exists( 'switch_to_locale' ) ) {
				// @codeCoverageIgnoreStart
				return false;
				// @codeCoverageIgnoreEnd
			}
			switch_to_locale( get_locale() );

			// Filter on plugin_locale so load_plugin_textdomain loads the correct locale.
			add_filter( 'plugin_locale', 'get_locale' );

			// Init Sermon Manager locale.
			$this->load_domain();

			$result = true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			$result = false;
			// @codeCoverageIgnoreEnd
		}
		return $result;
	}

	/**
	 * Restore language to original.
	 *
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function restore_locale(): bool {
		$result = false;
		try {
			if ( ! function_exists( 'restore_previous_locale' ) ) {
				// @codeCoverageIgnoreStart
				return false;
				// @codeCoverageIgnoreEnd
			}
			restore_previous_locale();

			// Remove filter.
			remove_filter( 'plugin_locale', 'get_locale' );

			// Init Sermon Manager locale.
			$this->load_domain();

			$result = true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			$result = false;
			// @codeCoverageIgnoreEnd
		}
		if ( ! $result ) {
			return false;
		}
		return true;
	}
}
