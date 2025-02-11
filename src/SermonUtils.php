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

use WP_Query;

defined( 'ABSPATH' ) || exit;

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

	public static function get_latest(
		int $per_page = 1,
		string $status = 'publish',
		string $orderby = SermonMeta::DATE
	): array {

		$args = array(
			'post_type'      => DRPPSM_PT_SERMON,
			'posts_per_page' => $per_page,
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

		$query  = new WP_Query( $args );
		$result = $query->posts;
		Logger::debug( array( 'RESULT' => $result ) );
		return $result;
	}
}
