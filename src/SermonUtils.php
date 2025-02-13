<?php
/**
 * Sermon utils.
 *
 * @package     DRPPSM\SermonUtils
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use WP_Post;
use WP_Term;

/**
 * Sermon utils.
 *
 * @package     DRPPSM\SermonUtils
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonUtils {

	/**
	 * Get latest sermons.
	 *
	 * @param int    $per_page Number of sermons to get.
	 * @param string $status   Post status.
	 * @param string $orderby  Order by.
	 * @return array
	 * @since 1.0.0
	 */
	public static function sermon_latest(
		string $status = 'publish',
		string $orderby = SermonMeta::DATE
	): ?WP_Post {

		$args = array(
			'post_type'      => DRPPSM_PT_SERMON,
			'posts_per_page' => 1,
			'post_status'    => $status,
			'order'          => 'DESC',
		);

		switch ( $orderby ) {
			case SermonMeta::DATE:
				$args['meta_key'] = SermonMeta::DATE;
				$args['orderby']  = 'meta_value_num';
				break;
			case 'post_date':
				$args['orderby'] = 'post_date';
				break;
			default:
				$args['meta_key'] = SermonMeta::DATE;
				$args['orderby']  = 'meta_value_num';
		}

		$result = get_posts( $args );
		if (
			is_wp_error( $result ) ||
			! is_array( $result ) ||
			count( $result ) === 0
		) {
			return null;
		}
		$result = array_shift( $result );
		return $result;
	}

	/**
	 * Get latest series.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function series_latest(): ?WP_Term {
		$tax  = DRPPSM_TAX_SERIES;
		$args = array(
			'hide_empty' => true,
			'meta_key'   => $tax . '_date',
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
			'number'     => 1,
		);

		$result = get_term_by( 'slug', 'none', $tax );
		if ( $result instanceof \WP_Term ) {
			$ids             = array( $result->term_id );
			$args['exclude'] = $ids;
		}

		$result = get_terms( $args );
		if (
			is_wp_error( $result ) ||
			! is_array( $result ) ||
			count( $result ) === 0
		) {
			return null;
		}
		$result = array_shift( $result );
		return $result;
	}
}
