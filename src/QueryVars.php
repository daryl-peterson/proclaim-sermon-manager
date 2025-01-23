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

use DRPPSM\Constants\Meta;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WPForms\Logger\Log;

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

			// $attachment = $this->fix_attachment( $query );

			if ( key_exists( DRPPSM_PT_SERMON, $query ) ) {
				$arg = $query[ DRPPSM_PT_SERMON ];

				$tax = TaxUtils::get_taxonomy_name( $arg );

				if ( $tax && ! key_exists( $tax, $query ) ) {
					Logger::debug( array( 'TAXONOMY' => $tax ) );
					$query = array(
						'post_type' => DRPPSM_PT_SERMON,
						'taxonomy'  => $tax,
						'orderby'   => 'name',
						'order'     => 'ASC',

					);

					$terms = get_terms(
						array(
							'taxonomy'   => $tax,
							'fields'     => 'ids',
							'hide_empty' => true,
							'number'     => 1,
						)
					);

					$query['tax_query'] = array(
						array(
							'taxonomy' => $tax,
							'field'    => 'id',
							'terms'    => array_values( $terms ),
						),
					);

					$orderby = Settings::get( Settings::ARCHIVE_ORDER_BY );
					if ( 'date_preached' === $orderby ) {
						$query['meta_query'] = array(
							'orderby'      => 'meta_value_num',
							'meta_key'     => Meta::DATE,
							'meta_value'   => time(),
							'meta_compare' => '<=',
						);
					}
				}
			} elseif ( key_exists( 'post_type', $query ) && DRPPSM_PT_SERMON === $query['post_type'] ) {
				$orderby = Settings::get( Settings::ARCHIVE_ORDER_BY );
				if ( 'date_preached' === $orderby ) {
					$query['meta_query'] = array(
						'orderby'      => 'meta_value_num',
						'meta_key'     => Meta::DATE,
						'meta_value'   => time(),
						'meta_compare' => '<=',
					);
				}
			}
		} catch ( \Throwable $th ) {
			FatalError::set( $th );

		}
		return $query;
	}

	/**
	 * Fix attachment if it's matches our permalinks.
	 *
	 * @param array $query Query arguments array.
	 * @return array
	 * @since 1.0.0
	 */
	private function fix_attachment( array &$query ): bool {
		global $wp;

		Logger::debug( array( 'QUERY' => $query ) );

		if ( ! key_exists( 'attachment', $query ) ) {
			return false;
		}

		$links = PermaLinks::get();
		Logger::debug( array( 'LINKS' => $links ) );
		$request = $wp->request;
		$term    = $query['attachment'];
		$request = trim( str_replace( '/' . $term, '', $request ) );
		$key     = array_search( $request, $links, true );
		Logger::debug(
			array(
				'REQUEST'    => $request,
				'TERM'       => $term,
				'KEY'        => $key,
				'PERMALINKS' => $links,
			)
		);
		if ( $key ) {
			$query = array(
				'post_type' => DRPPSM_PT_SERMON,
				'orderby'   => 'name',
				'order'     => 'ASC',
				'type'      => 'attachment',
				'tax_query' => array(
					array(
						'taxonomy' => $key,
						'field'    => 'name',
						'terms'    => $term,
					),
				),

			);
			return true;
		}

		return true;
	}
}
