<?php
/**
 * Overwrite query vars if conflicts exist.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Overwrite query vars if conflicts exist.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class QueryVars implements Executable, Registrable {

	/**
	 * Initialize object and register callbacks.
	 *
	 * @return QueryVars
	 * @since 1.0.0
	 */
	public static function exec(): QueryVars {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register callbacks.
	 *
	 * @return boolean|null True if callbacks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_filter( 'request', array( $this, 'overwrite_query_vars' ) ) ) {
			return false;
		}
		add_filter( 'request', array( $this, 'overwrite_query_vars' ), 10, 1 );
		return true;
	}

	/**
	 * Overwrite query vars if needed.
	 *
	 * @param array $query
	 * @return void
	 * @since 1.0.0
	 */
	public function overwrite_query_vars( array $query ) {
		global $wpdb;

		$conflict = $this->get_conflict();

		// If no conflicts exist just return the query.
		if ( ! $conflict ) {
			return $query;
		}

		if ( isset( $query['favicon'] ) ) {
			return $query;
		}

		$query_org = $query;

		$found = false;
		if ( isset( $query['name'] ) && ! $found ) {
			$name    = $query['name'];
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}posts WHERE post_name = \"$name\" LIMIT 1" );
			if ( is_array( $results ) && isset( $results ) ) {
				$results            = $results[0];
				$query['post_type'] = $results->post_type;
				$found              = true;
			}
		}

		if ( ! $found ) {
			$terms = get_terms(
				array(
					'slug'   => $query,
					'number' => 1,
				)
			);
			if ( is_array( $terms ) && isset( $terms[0] ) ) {
				$terms = $terms[0];
				$query = null;
				$query = array( $terms->taxonomy => $terms->slug );
			}
		}
		Logger::debug(
			array(
				'QUERY ORG' => $query_org,
				'QUERY'     => $query,
			)
		);

		return $query;
	}

	/**
	 * Get transient to see if conflicts have been detected.
	 *
	 * @return void
	 */
	private function get_conflict() {
		$trans    = get_transient( Rewrite::TRANS_NAME );
		$conflict = true;

		if ( $trans ) {
			if ( is_array( $trans ) && ! isset( $trans['conflict'] ) ) {
				$conflict = $trans['conflict'];
			}
		}
		return $conflict;
	}
}
