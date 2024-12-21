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

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
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
	 * List of post types / taxonomies to look for.
	 *
	 * @var array
	 */
	private array $list;

	/**
	 * List of conflicts or null.
	 *
	 * @var array|null
	 */
	private ?array $conflict;

	/**
	 * The key from $list that matched.
	 *
	 * @var string
	 */
	private ?string $matched;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		$permalinks = PermaLinks::exec()->get();
		$this->list = array_merge( Tax::LIST, array( PT::SERMON ) );
		/*
		$taxonomies = array();
		foreach ( Tax::LIST as $tax ) {
			$taxonomies[ $tax ] = $permalinks[ $tax ];
		}
		$this->list = array_merge( $taxonomies, array( PT::SERMON ) );

		Logger::debug( array( 'LIST' => $this->list ) );
		*/
		$this->conflict = $this->get_conflict();
	}

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
		Logger::debug( array( 'REGISTERING HOOKS' ) );
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
		global $wp;

		$term = get_queried_object();

		Logger::debug(
			array(
				'REQUEST' => $wp->request,
				'TERM'    => $term,
			)
		);

		if ( ! isset( $this->conflict ) ) {
			Logger::debug(
				array(
					'RESULT' => 'NO CONFLICTS',
					'QUERY'  => $query,
				)
			);
			return $query;
		}

		if ( ! $this->should_modify( $query ) ) {
			Logger::debug(
				array(
					'RESULT' => 'NOT OURS',
					'QUERY'  => $query,
				)
			);
			return $query;
		}

		$query_org = $query;
		if ( isset( $query['post_type'] ) ) {
			$query = $this->post_query( $query );
		} else {
			$query = $this->tax_query( $query );
		}

		/*
		Logger::debug(
			array(
				'QUERY ORG' => $query_org,
				'QUERY'     => $query,
			)
		);
		*/

		return $query;
	}

	/**
	 * Get transient to see if conflicts have been detected.
	 *
	 * @return bool Return true if conflict exsit.
	 * @since 1.0.0
	 */
	private function get_conflict(): ?array {
		$trans = get_transient( Rewrite::TRANS_NAME );

		if ( ! isset( $trans ) || ! $trans ) {
			return null;
		}
		return $trans;
	}

	/**
	 * Query posts.
	 *
	 * @param array $query Query vars.
	 * @return array
	 * @since 1.0.0
	 */
	private function post_query( array $query ): array {
		global $wpdb;

		$name = sanitize_text_field( $query['name'] );

		$sql = <<<EOT
			SELECT
				*
			FROM {$wpdb->prefix}posts
			WHERE post_name = %s
			LIMIT 1
		EOT;

		$sql = $wpdb->prepare(
			$sql,
			array(
				sanitize_text_field( $name ),
			)
		);

		$results = $wpdb->get_results( $sql );
		if ( is_array( $results ) ) {
			$results            = array_shift( $results );
			$query['post_type'] = $results->post_type;
		}

		if ( isset( $this->matched ) ) {
			unset( $query[ $this->matched ] );
		}

		return $query;
	}

	/**
	 * Get taxonomy info.
	 *
	 * @param array $query Orignal from override.
	 * @return array
	 * @since 1.0.0
	 */
	private function tax_query( array $query ): array {
		global $wpdb;
		if ( ! isset( $this->conflict ) || ! is_array( $this->conflict ) ) {
			return $query;
		}

		if ( ! isset( $this->matched ) ) {
			return $query;
		}

		$slug = sanitize_text_field( current( $query ) );

		$sql = <<<EOT
			SELECT
				*
			FROM {$wpdb->prefix}terms t
			LEFT JOIN {$wpdb->prefix}term_taxonomy tt
				ON t.term_id = tt.term_id
			WHERE t.slug = %s
		EOT;

		$sql = $wpdb->prepare(
			$sql,
			array(
				sanitize_text_field( $slug ),
			)
		);

		$results = $wpdb->get_results( $sql );
		Logger::debug(
			array(
				'RESULTS' => $results,
				'SQL'     => $sql,
			)
		);

		if ( is_array( $results ) && isset( $results[0] ) ) {
			$results = $results[0];
			unset( $query );
			$query[ $results->taxonomy ] = $results->slug;
		}
		return $query;
	}

	/**
	 * Check if we should modify query.
	 *
	 * @param array $query Original query array.
	 * @return boolean
	 * @since 1.0.0
	 */
	private function should_modify( array $query ): bool {
		global $wp;

		$term = get_queried_object();
		Logger::debug(
			array(
				'REQUEST' => $wp->request,
				'TERM'    => $term,
				'QUERY'   => $query,
			)
		);

		$match = false;
		foreach ( $this->list as $item ) {
			if ( key_exists( $item, $query ) ) {
				$match         = true;
				$this->matched = $item;
				break;
			}
		}
		return $match;
	}
}
