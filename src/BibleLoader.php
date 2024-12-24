<?php
/**
 * Loads bible books taxomony data.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Actions;
use DRPPSM\Constants\Bible;
use DRPPSM\Interfaces\BibleLoaderInt;
use DRPPSM\Logger;
use WP_Error;

/**
 * Loads bible books taxomony data.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class BibleLoader implements BibleLoaderInt {

	/**
	 * Register hooks.
	 *
	 * @return boolean|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( Actions::AFTER_POST_SETUP, array( $this, 'run' ) ) ) {
			return false;
		}
		return add_action( Actions::AFTER_POST_SETUP, array( $this, 'run' ) );
	}

	/**
	 * Initailize and register hooks.
	 *
	 * @return BibleLoaderInt
	 * @since 1.0.0
	 */
	public static function exec(): BibleLoaderInt {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Check if Bible books have been loaded.
	 * - Call function to load them.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function run(): bool {

		$hook    = Tax::BIBLE_BOOK . '_loaded';
		$options = options();
		$ran     = $options->get( $hook, false );

		if ( $ran && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}

		$result = $this->load();
		if ( $result ) {
			$options->set( $hook, true );
		}
		return $result;
	}

	/**
	 * Load Bible books.
	 *
	 * @return bool True if the books were loaded, false otherwise.
	 * @since 1.0.0
	 */
	private function load(): bool {
		$books = Bible::BOOKS;
		$tax   = Tax::BIBLE_BOOK;

		try {
			$result = false;
			foreach ( $books as $book ) {
				$slug   = sanitize_title( $book );
				$result = term_exists( $slug, $tax );

				Logger::debug( array( $result ) );

				if ( isset( $result ) ) {
					continue;
				}

				$result = wp_insert_term(
					$book,
					$tax,
					array(
						'slug' => $slug,
					)
				);

				if ( $result instanceof WP_Error ) {
					$result = false;
					break;
				}
			}
			if ( $result ) {
				return true;
			}

			return false;
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			return false;
			// @codeCoverageIgnoreEnd
		}
	}
}
