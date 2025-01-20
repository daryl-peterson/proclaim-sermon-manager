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
	 * Bible taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_bible;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->tax_bible = DRPPSM_TAX_BOOK;
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
	 * Register hooks.
	 *
	 * @return boolean|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( Actions::AFTER_POST_SETUP, array( $this, 'run' ) ) ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}
		return add_action( Actions::AFTER_POST_SETUP, array( $this, 'run' ) );
	}

	/**
	 * Check if Bible books have been loaded.
	 * - Call function to load them.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function run(): bool {
		$result = false;

		try {

			$load = Settings::get( Settings::BIBLE_BOOK_LOAD, false );

			if ( ! $load ) {
				return false;
			} else {

				$result = $this->load();
				Settings::set( Settings::BIBLE_BOOK_LOAD, false );
			}
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'ERROR' => $th->getMessage(),
					'TRACE' => $th->getTrace(),
				)
			);
			$result = false;
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

		try {
			$result = false;
			foreach ( $books as $book ) {
				$book = $this->fix_book( $book );
				$slug = sanitize_title( $book );
				$term = term_exists( $slug, $this->tax_bible );

				if ( isset( $term ) ) {
					$result = true;
					// @codeCoverageIgnoreStart
					continue;
					// @codeCoverageIgnoreEnd
				}

				$result = $this->insert_term( $book, $this->tax_bible, $slug );
			}

			return $result;
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

	/**
	 * Fix book name if unit testing.
	 *
	 * @param string $book Bible book.
	 * @return string
	 * @since 1.0.0
	 */
	private function fix_book( string $book ): string {

		if ( defined( DRPPSM_TESTING ) ) {
			$book .= ' Test';
		}
		return $book;
	}

	/**
	 * Insert term and delete it if unit testing.
	 *
	 * @param string $book Bible book.
	 * @param string $tax Taxonomy.
	 * @param string $slug Slug.
	 * @return boolean
	 * @since 1.0.0
	 */
	private function insert_term( string $book, string $tax, string $slug ): bool {
		$result     = wp_insert_term(
			$book,
			$tax,
			array(
				'slug' => $slug,
			)
		);
		$result_org = $result;
		$term_id    = null;

		if ( is_array( $result ) && isset( $result['term_id'] ) ) {
			$term_id = $result['term_id'];
			$result  = true;
		} else {
			// @codeCoverageIgnoreStart
			$result = false;
			// @codeCoverageIgnoreEnd
		}

		$delete = null;
		if ( defined( DRPPSM_TESTING ) && isset( $term_id ) ) {
			$delete = wp_delete_term( $term_id, $tax );
		}

		Logger::debug(
			array(
				'BOOK'          => $book,
				'TAX'           => $tax,
				'TERM_ID'       => $term_id,
				'INSERT RESULT' => $result_org,
				'DELETE RESULT' => $delete,
			)
		);

		return $result;
	}
}
