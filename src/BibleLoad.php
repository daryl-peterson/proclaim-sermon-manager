<?php
/**
 * Loads bible books taxomony data.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\Filters;
use DRPSermonManager\Constants\Bible;
use DRPSermonManager\Constants\Tax;
use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\OptionsInt;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Loads bible books taxomony data.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class BibleLoad implements Registrable {

	/**
	 * Options interface.
	 *
	 * @var OptionsInt
	 *
	 * @since 1.0.0
	 */
	public OptionsInt $options;

	/**
	 * Initialize object.
	 *
	 * @param OptionsInt $options Options interface.
	 *
	 * @since 1.0.0
	 */
	public function __construct( OptionsInt $options ) {
		$this->options = $options;
	}

	/**
	 * Register callbacks.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register(): void {
		add_action( Filters::AFTER_POST_SETUP, array( $this, 'run' ), 10, 1 );
	}

	/**
	 * Check if Bible books have been loaded.
	 * - Call function to load them.
	 *
	 * @return void
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
	private function load() {
		$books = Bible::BOOKS;
		$tax   = Tax::BIBLE_BOOK;

		try {
			foreach ( $books as $book ) {
				$slug = trim( strtolower( str_replace( array( ' ', '_' ), array( '-', '-' ), $book ) ) );
				wp_insert_term( $book, $tax, array( 'slug' => $slug ) );
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
