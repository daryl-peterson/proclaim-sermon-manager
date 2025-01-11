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

		$this->list     = array_merge( array_values( DRPPSM_TAX_MAP ), array( DRPPSM_PT_SERMON ) );
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
		if ( has_filter( 'request', array( $this, 'overwrite_query_vars' ) ) ) {
			return false;
		}
		add_filter( 'request', array( $this, 'overwrite_query_vars' ) );
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
		try {
			$query_org = $query;
			$query     = $this->fix_attachment( $query );

			if ( key_exists( DRPPSM_PT_SERMON, $query ) ) {
				$arg = $query[ DRPPSM_PT_SERMON ];

				switch ( $arg ) {
					case 'series':
						$query = array(
							'taxonomy' => DRPPSM_TAX_SERIES,
							'term'     => '',
						);
						break;
					default:
						// code
						break;
				}
			}
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
			$query = $query_org;
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

		$term          = get_queried_object();
		$common        = Settings::get( Settings::COMMON_BASE_SLUG, false );
		$this->matched = null;

		$match = false;
		foreach ( $this->list as $item ) {
			if ( key_exists( $item, $query ) ) {
				$match         = true;
				$this->matched = $item;
				break;
			}
		}

		Logger::debug(
			array(
				'COMMON BASE' => $common,
				'REQUEST'     => $wp->request,
				'TERM'        => $term,
				'QUERY'       => $query,
				'RETURN'      => $match,
			)
		);
		return $match;
	}

	/**
	 * Fix attachment if it's matches our permalinks.
	 *
	 * @param array $query Query arguments array.
	 * @return array
	 * @since 1.0.0
	 */
	private function fix_attachment( array $query ): array {
		global $wp;

		if ( ! key_exists( 'attachment', $query ) ) {
			return $query;
		}

		$links   = PermaLinks::exec()->get();
		$request = $wp->request;
		$term    = $query['attachment'];
		$request = trim( str_replace( '/' . $term, '', $request ) );
		$key     = array_search( $request, $links );
		Logger::debug(
			array(
				'REQUEST'    => $request,
				'TERM'       => $term,
				'KEY'        => $key,
				'PERMALINKS' => $links,
			)
		);
		if ( $key ) {
			unset( $query['attachment'] );
			$query[ $key ] = $term;
		}

		return $query;
	}

	/**
	 * Fig taxonomy settings.
	 *
	 * @param array $query Query arguments array.
	 * @return array
	 * @since 1.0.0
	 */
	private function fix_taxonomy( array $query ): array {

		if ( key_exists( DRPPSM_PT_SERMON, $query ) ) {
			$term = get_term_by( 'slug', $query[ DRPPSM_PT_SERMON ] );
			Logger::debug( array( 'TERM' => $term ) );
			$query['taxonomy'] = DRPPSM_TAX_SERVICE_TYPE;
			unset( $query['page'] );
			unset( $query['name'] );
		}

		Logger::debug( array( 'QUERY' => $query ) );
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

		if ( ! key_exists( 'name', $query ) ) {
			return $query;
		}

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

		$results   = $wpdb->get_results( $sql );
		$query_org = $query;

		if ( is_array( $results ) ) {
			$results            = array_shift( $results );
			$query['post_type'] = $results->post_type;
		}

		if ( isset( $this->matched ) ) {
			unset( $query[ $this->matched ] );
		}

		Logger::debug(
			array(
				'RESULTS'   => $results,
				'SQL'       => $sql,
				'QUERY ORG' => $query_org,
				'QUERY'     => $query,
			)
		);

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
			WHERE t.slug = %s and tt.term_id is not null
		EOT;

		$sql = $wpdb->prepare(
			$sql,
			array(
				sanitize_text_field( $slug ),
			)
		);

		$results = $wpdb->get_results( $sql );

		if ( is_array( $results ) && isset( $results[0] ) ) {
			$results   = $results[0];
			$query_org = $query;
			unset( $query );
			$query[ $results->taxonomy ] = $results->slug;
		}

		Logger::debug(
			array(
				'RESULTS'   => $results,
				'SQL'       => $sql,
				'QUERY ORG' => $query_org,
				'QUERY'     => $query,
			)
		);
		return $query;
	}
}
