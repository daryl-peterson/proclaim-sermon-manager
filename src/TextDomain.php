<?php
/**
 * Language locales.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\TextDomainInt;
use DRPSermonManager\Logging\Logger;

/**
 * Language locales.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TextDomain implements TextDomainInt {

	public const INIT_KEY = 'TEXT_DOMAIN_INIT';

	/**
	 * Register callbacks
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register(): void {
		$hook = Helper::get_key_name( self::INIT_KEY );

		if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}

		add_action( 'init', array( $this, 'load_domain' ) );
		Logger::debug( 'PLUGIN HOOKS INITIALIZED' );
		do_action( $hook );
	}

	/**
	 * Load domain locales
	 *
	 * @since 1.0.0
	 */
	public function load_domain(): void {
		load_plugin_textdomain( DOMAIN, false, basename( dirname( FILE ) ) . '/languages/' );
	}

	/**
	 * Switch to site language.
	 *
	 * @since 1.0
	 */
	public function switch_to_site_locale(): void {
		try {
			if ( ! function_exists( 'switch_to_locale' ) ) {
				// @codeCoverageIgnoreStart
				return;
				// @codeCoverageIgnoreEnd
			}
			switch_to_locale( get_locale() );

			// Filter on plugin_locale so load_plugin_textdomain loads the correct locale.
			add_filter( 'plugin_locale', 'get_locale' );

			// Init Sermon Manager locale.
			$this->load_domain();

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Restore language to original.
	 *
	 * @since 1.0
	 */
	public function restore_locale(): void {
		try {
			if ( ! function_exists( 'restore_previous_locale' ) ) {
				// @codeCoverageIgnoreStart
				return;
				// @codeCoverageIgnoreEnd
			}
			restore_previous_locale();

			// Remove filter.
			remove_filter( 'plugin_locale', 'get_locale' );

			// Init Sermon Manager locale.
			$this->load_domain();

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}
	}
}
