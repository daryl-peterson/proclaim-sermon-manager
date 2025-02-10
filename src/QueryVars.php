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

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use WP_Query;

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
	use ExecutableTrait;

	/**
	 * Register hooks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_filter( 'request', array( $this, 'overwrite_request_vars' ) ) ) {
			return false;
		}

		add_filter( 'request', array( $this, 'overwrite_request_vars' ) );
		add_action( 'post_limits', array( $this, 'post_limits' ), 10, 2 );
		add_filter( 'query_vars', array( $this, 'add_query_vars' ), 10, 1 );
		return true;
	}


	/**
	 * Add query vars.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 * @since 1.0.0
	 */
	public function add_query_vars( array $vars ): array {
		$friendly = array_keys( DRPPSM_TAX_MAP );
		$vars     = array_merge( $vars, $friendly );
		$vars[]   = 'play';
		$vars[]   = 'player';
		return $vars;
	}

	/**
	 * Set post limits if query_type is set.
	 *
	 * #### Conditions
	 * - If Main query.
	 * - Not in admin.
	 * - Post type sermon archive.
	 *
	 * @param string   $limit Current limit string.
	 * @param WP_Query $query WP_Query object.
	 * @return string
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/hooks/post_limits/
	 */
	public function post_limits( string $limit, WP_Query $query ) {
		if ( ! $query->is_main_query() || is_admin() ) {
			return $limit;
		}

		if ( ! $query->is_post_type_archive( DRPPSM_PT_SERMON ) ) {
			return $limit;
		}

		if ( ! key_exists( 'query_type', $query->query ) ) {
			return $limit;
		}

		$qt = $query->query['query_type'];
		if ( in_array( $qt, array( 'drppsm_term', 'drppsm_tax', 'drppsm_post' ) ) ) {
			return 'LIMIT 1';
		}

		return $limit;
	}

	/**
	 * Overwrite request vars if needed.
	 *
	 * @param array $request Query array.
	 * @return array
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/hooks/request/
	 */
	public function overwrite_request_vars( array $request ): array {
		try {

			$request_org = $request;

			$result = $this->set_post_query( $request );
			if ( $result ) {
				Logger::debug(
					array(
						'FINAL REQUEST' => $result,
						'ORG REQUEST'   => $request_org,
					)
				);
				return $result;
			}

			// It's not for anything we are concerned with.
			if ( ! $this->is_concerned( $request ) ) {
				Logger::debug(
					array(
						'FINAL REQUEST' => $result,
						'ORG REQUEST'   => $request_org,
					)
				);
				return $request;
			}

			$changed = $this->set_tax_query( $request );
			if ( ! $changed ) {
				$this->set_term_query( $request );
			}
		} catch ( \Throwable $th ) {
			FatalError::set( $th );

		}
		Logger::debug(
			array(
				'FINAL REQUEST' => $request,
				'ORG REQUEST'   => $request_org,
			)
		);
		return $request;
	}

	/**
	 * Check if the request is for something we should look at.
	 *
	 * - Check for any of the taxonomies.
	 * - Check for the sermon post type.
	 *
	 * @param array $request Query array.
	 * @return bool Return true if we should look at the request, otherwise false.
	 * @since 1.0.0
	 */
	private function is_concerned( array $request ): bool {
		$tax = array_values( DRPPSM_TAX_MAP );
		foreach ( $tax as $tax_name ) {
			if ( key_exists( $tax_name, $request ) ) {
				return true;
			}
		}

		if ( key_exists( DRPPSM_PT_SERMON, $request ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Set tax term.
	 *
	 * #### Common Base slug for taxonomies
	 * - This allows for /sermons/series/{term} to series page.
	 * - This allows for /sermons/topics/{term} to topics page.
	 * - This allows for /sermons/books/{term} to books page.
	 *
	 * #### No Common Base slug for taxonomies
	 * - This allows for /series/{term} to series page.
	 * - This allows for /topics/{term} to topics page.
	 * - This allows for /books/{term} to books page.
	 *
	 * @param array $request Query array.
	 * @return void
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/hooks/request/
	 * @see https://developer.wordpress.org/reference/classes/wp_query/
	 */
	private function set_term_query( array &$request ) {

		$found = false;
		foreach ( DRPPSM_TAX_MAP as $tax_name ) {
			if ( key_exists( $tax_name, $request ) ) {
				$found = true;
				break;
			}
		}

		if ( ! $found ) {
			return;
		}

		$request_org = $request;

		$term = $request[ $tax_name ];

		$request = array(
			'post_type'  => DRPPSM_PT_SERMON,
			'taxonomy'   => $tax_name,
			$tax_name    => $term,
			'meta_key'   => SermonMeta::DATE,
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
			'query_type' => 'drppsm_term',
		);

		$qv_search = array(
			'page',
			'paged',
			'play',
			'player',
		);

		foreach ( $qv_search as $key ) {
			if ( key_exists( $key, $request_org ) ) {
				$request[ $key ] = $request_org[ $key ];
			}
		}

		return;
	}

	/**
	 * Set tax query.
	 *
	 * #### Common Base slug for taxonomies
	 * - This allows for /sermons/{series}/ to list series
	 * - This allows for /sermons/{topics}/ to list topics
	 * - This allows for /sermons/{books}/ to list books
	 *
	 * #### No Common Base slug for taxonomies
	 * - This allows for /{series}/ to list series
	 * - This allows for /{topics}/ to list topics
	 * - This allows for /{books}/ to list books
	 *
	 * @param array $request Query array.
	 * @return boolean Return true if tax query was set, otherwise false.
	 * @since 1.0.0
	 * @see https://developer.wordpress.org/reference/hooks/request/
	 * @see https://developer.wordpress.org/reference/classes/wp_query/
	 */
	private function set_tax_query( array &$request ): bool {
		if ( ! key_exists( DRPPSM_PT_SERMON, $request ) ) {
			return false;
		}

		$arg = $request[ DRPPSM_PT_SERMON ];
		$tax = TaxUtils::get_taxonomy_name( $arg );

		// It's not a taxonomy.
		if ( ! $tax || key_exists( $tax, $request ) ) {
			return false;
		}

		$request_org = $request;

		$request = array(
			'post_type'  => DRPPSM_PT_SERMON,
			'taxonomy'   => $tax,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'query_type' => 'drppsm_tax',
		);

		// Get a term to use.
		$terms = get_terms(
			array(
				'taxonomy'   => $tax,
				'fields'     => 'ids',
				'hide_empty' => true,
				'number'     => 1,
			)
		);

		// Add a tax query so we don't get 404.
		$request['tax_query'] = array(
			array(
				'taxonomy' => $tax,
				'field'    => 'id',
				'terms'    => array_values( $terms ),
			),
		);

		if ( key_exists( 'page', $request_org ) ) {
			$request['page'] = $request_org['page'];
		}

		return true;
	}

	/**
	 * Set post query.
	 *
	 * @param array $request Query array.
	 * @return array|false
	 * @since 1.0.0
	 */
	private function set_post_query( array $request ) {

		$compare = array(
			'post_type' => DRPPSM_PT_SERMON,
		);
		if ( isset( $request['page'] ) ) {
			$compare['page'] = $request['page'];
		}

		if ( $compare === $request ) {
			$compare['query_type'] = 'drppsm_post';
			return $compare;
		}

		return false;
	}
}
