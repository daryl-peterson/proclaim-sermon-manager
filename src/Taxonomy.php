<?php
/**
 * Taxonomy events.
 *
 * @package     DRPPSM\Taxonomy
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Term;

defined( 'ABSPATH' ) || exit;



/**
 * Store backup of taxonomy when taxomonmy is deleted.
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @todo Finish implementing.
 */
class Taxonomy implements Executable, Registrable {

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Returns true if hooks were registered.
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Returns true if hooks were registered.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		$tax    = array_values( DRPPSM_TAX_MAP );
		$result = false;
		foreach ( $tax as $tax_name ) {
			$deleted    = "delete_{$tax_name}";
			$deleted_cb = array( $this, 'delete_taxonomy' );
			$created    = "created_{$tax_name}";
			$created_cb = array( $this, 'created_taxonomy' );

			if ( ! has_action( $deleted, $deleted_cb ) ) {
				add_action( $deleted, $deleted_cb, 10, 4 );
			}
			if ( ! has_action( $created, $created_cb ) ) {
				add_action( $created, $created_cb, 10, 4 );
			}
			$result = true;
		}
		// Fires immediately after a term is updated in the database, but before its term-taxonomy relationship is updated.
		// do_action( ‘edited_terms’, int $term_id, string $taxonomy, array $args )

		/*
		do_action("create_term", $term_id, $tt_id, $taxonomy);
		do_action("edit_term", $term_id, $tt_id, $taxonomy);
		do_action('delete_term', $term, $tt_id, $taxonomy);
		*/
		return $result;
	}

	public static function get_transient_name( string $tax_name ): string {
		return "{$tax_name}_terms";
	}

	/**
	 *
	 * @param int     $term_id Term ID.
	 * @param int     $tax_id Term taxonomy ID.
	 * @param WP_Term $deleted_term Copy of the already-deleted term.
	 * @param array   $bject_ids List of term object IDs.
	 * @return void
	 * @since 1.0.0
	 */
	public function delete_taxonomy( int $term_id, int $tax_id, WP_Term $deleted_term, array $bject_ids ) {
		Transients::delete( Transients::TERM_OPTS );
	}

	public function created_taxonomy( int $term_id, int $tax_id, WP_Term $deleted_term, array $bject_ids ) {
	}
}
