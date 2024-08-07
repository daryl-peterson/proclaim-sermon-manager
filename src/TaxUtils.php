<?php
/**
 * Taxonomy utilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\PT;

/**
 * Taxonomy utilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TaxUtils {

	/**
	 * Return registered taxonomies for post type.
	 *
	 * @param string $post_type Post type to get taxomomies for.
	 * @return array Taxonomy name array.
	 *
	 * @since 1.0.0
	 */
	public static function get_taxonomies( string $post_type = '' ): array {
		if ( empty( $post_type ) ) {
			$post_type = PT::SERMON;
		}

		return get_object_taxonomies( $post_type );
	}

	/**
	 * Get taxonomy field
	 *
	 * @param string|integer|\WP_Taxonomy $taxonomy Taxonomy.
	 * @param string                      $field_name Field name to get.
	 * @return string|null String if found, null if not.
	 */
	public static function get_taxonomy_field( string|int|\WP_Taxonomy $taxonomy, string $field_name ): ?string {
		$taxonomy = get_taxonomy( $taxonomy );

		if ( ! $taxonomy instanceof \WP_Taxonomy ) {
			return null;
		}

		if ( isset( $taxonomy->$field_name ) ) {
			return $taxonomy->$field_name;
		}

		if ( isset( $taxonomy->labels->$field_name ) ) {
			return $taxonomy->labels->$field_name;
		}

		// @codeCoverageIgnoreStart
		return null;
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Get term option for taxonomy.
	 *
	 * @param string $taxonomy Taxonomy name to get terms for.
	 * @return array Terms array.
	 */
	public static function get_term_options( $taxonomy = 'category' ): array {
		$args['taxonomy'] = $taxonomy;
		$taxonomy         = $args['taxonomy'];

		$args = array(
			'hide_empty' => false,
		);

		$terms = (array) get_terms(
			array(
				'taxonomy' => $taxonomy,
			) + $args
		);

		// Initialize an empty array.
		$term_options = array();
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$term_options[ $term->term_id ] = $term->name;
			}
		}

		return $term_options;
	}
}
