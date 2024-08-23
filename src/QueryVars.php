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
	 * Initialize object and register hooks.
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
	 * Register hooks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
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
	 * @param array $query Query array.
	 * @return array
	 * @since 1.0.0
	 */
	public function overwrite_query_vars( array $query ): array {
		global $wpdb;

		$conflict = $this->get_conflict();

		// @codeCoverageIgnoreStart
		if ( ! $conflict ) {
			return $query;
		}
		// @codeCoverageIgnoreEnd

		if ( isset( $query['favicon'] ) ) {
			return $query;
		}

		$query_org = $query;

		$found = false;
		if ( isset( $query['name'] ) && ! $found ) {
			$name = $query['name'];

			// phpcs:disable
			$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}posts WHERE post_name = %s LIMIT 1", array( $name ) );
			$results = $wpdb->get_results( $sql );
			if ( is_array( $results ) && isset( $results ) ) {
				$results            = $results[0];
				$query['post_type'] = $results->post_type;
				$found              = true;
			}
			// phpcs:enable
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
	 * @return bool Return true if conflict exsit.
	 * @since 1.0.0
	 */
	private function get_conflict(): bool {
		$trans    = get_transient( Rewrite::TRANS_NAME );
		$conflict = true;

		// @codeCoverageIgnoreStart
		if ( $trans ) {
			if ( is_array( $trans ) && ! isset( $trans['conflict'] ) ) {
				$conflict = $trans['conflict'];
			}
		}
		// @codeCoverageIgnoreEnd
		return $conflict;
	}
}
