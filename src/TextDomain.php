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

use DRPPSM\Constants\Actions;
use DRPPSM\Interfaces\TextDomainInt;

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
	 * Initialize and register callbacks.
	 *
	 * @return TextDomainInt
	 * @since 1.0.0
	 */
	public static function exec(): TextDomainInt {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register callbacks
	 *
	 * @return null|bool Return true if hooks were initialized.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( 'plugins_loaded', array( $this, 'load_domain' ) ) ) {
			return false;
		}
		add_action( 'plugins_loaded', array( $this, 'load_domain' ) );
		return true;
	}

	/**
	 * Load domain locales
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function load_domain(): bool {
		if ( did_action( Actions::TEXT_DOMAIN_LOADED ) ) {
			return false;
		}
		$locale = apply_filters( 'plugin_locale', determine_locale(), 'drppsm' );
		$path   = dirname( plugin_basename( FILE ) ) . '/languages/';
		$mofile = 'drppsm' . '-' . $locale . '.mo';

		$result = load_plugin_textdomain( DOMAIN, false, $path );
		did_action( Actions::TEXT_DOMAIN_LOADED );

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
		if ( ! $result ) {
			return false;
		}
		return true;
	}
}
