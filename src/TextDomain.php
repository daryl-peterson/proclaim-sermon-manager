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

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\TextDomainInt;
use DRPPSM\Traits\ExecutableTrait;

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

	use ExecutableTrait;

	/**
	 * Register hooks
	 *
	 * @return null|bool Return true if hooks were initialized.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( did_action( 'init', array( $this, 'load_domain' ) ) ) {
			return false;
		}

		if ( has_action( 'init', array( $this, 'load_domain' ) ) ) {
			return false;
		}
		add_action( 'init', array( $this, 'load_domain' ) );
		return true;
	}

	/**
	 * Load domain locales.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function load_domain(): bool {

		if ( ! has_action( 'init', array( $this, 'load_domain' ) ) && ! PHPUNIT_TESTING ) {
			return false;
		}

		$locale = apply_filters( 'plugin_locale', determine_locale(), 'drppsm' );
		$path   = dirname( plugin_basename( FILE ) ) . '/languages/';

		// phpcs:disable
		$mofile = 'drppsm' . '-' . $locale . '.mo';
		// phpcs:enable

		$result = load_plugin_textdomain( DRPSM_DOMAIN, false, $path );
		Logger::debug(
			array(
				'PATH'   => $path,
				'MOFILE' => $mofile,
				'RESULT' => $result,
			)
		);

		remove_action( 'init', array( $this, 'load_domain' ) );
		return $result;
	}

	/**
	 * Switch to site language.
	 *
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function switch_to_site_locale(): bool {
		$result = false;

		Logger::debug( 'SWITCHING LOCALE' );
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

		return $result;
	}
}
