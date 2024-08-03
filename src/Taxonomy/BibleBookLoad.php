<?php
/**
 * Loads bible books taxomony data.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
namespace DRPSermonManager\Taxonomy;

use DRPSermonManager\App;
use DRPSermonManager\Constants\BIBLE;
use DRPSermonManager\Constants\TAX;
use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Loads bible books taxomony data.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class BibleBookLoad implements Initable, Registrable {

	/**
	 * Get initialize object.
	 *
	 * @return BibleBookLoad
	 *
	 * @since 1.0.0
	 */
	public static function init(): BibleBookLoad {
		return new self();
	}

	/**
	 * Register callbacks.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register(): void {
		add_action( 'drpsermon_after_post_setup', array( $this, 'run' ), 10, 1 );
	}

	/**
	 * Check if Bible books have been loaded.
	 * - Call function to load them.
	 *
	 * @return void
	 */
	public function run(): void {
		$opts = App::getOptionsInt();

		$ran = $opts->get( 'bible_books_loaded', false );
		if ( $ran && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}

		$this->load();
		$opts->set( 'bible_books_loaded', true );
	}

	/**
	 * Load Bible books
	 *
	 * @return void
	 */
	private function load() {
		$books = BIBLE::BOOKS;
		$tax   = TAX::BIBLE_BOOK;

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
