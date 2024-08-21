<?php

namespace DRPPSM\DB;

use function DRPPSM\get_key_name;

/**
 * Class description
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Tables {

	const LOGS = 'logs';

	public static function get_table_name( string $table ): string {
		global $wpdb;

		return $wpdb->base_prefix . get_key_name( $table );
	}
}
