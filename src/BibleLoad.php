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

use DRPPSM\Constants\Bible;
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Logging\Logger;

/**
 * Loads bible books taxomony data.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class BibleLoad implements Initable {

	/**
	 * Options interface.
	 *
	 * @var OptionsInt
	 *
	 * @since 1.0.0
	 */
	public OptionsInt $options;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->options = get_options_int();
	}

	/**
	 * Get intialize object.
	 *
	 * @return BibleLoad
	 * @since 1.0.0
	 */
	public static function init(): BibleLoad {
		return new static();
	}

	/**
	 * Check if Bible books have been loaded.
	 * - Call function to load them.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function run(): void {

		$ran = $this->options->get( 'bible_books_loaded', false );
		if ( $ran && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}

		$this->load();
		$this->options->set( 'bible_books_loaded', true );
	}

	/**
	 * Load Bible books
	 *
	 * @return void
	 */
	private function load(): void {
		$books = Bible::BOOKS;
		$tax   = Tax::BIBLE_BOOK;

		try {
			foreach ( $books as $book ) {
				$slug = trim(
					strtolower(
						str_replace(
							array( ' ', '_' ),
							array( '-', '-' ),
							$book
						)
					)
				);

				// @codeCoverageIgnoreStart
				if ( ! term_exists( $book, $tax ) ) {
					wp_insert_term( $book, $tax, array( 'slug' => $slug ) );
				}
				// @codeCoverageIgnoreEnd
			}
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
