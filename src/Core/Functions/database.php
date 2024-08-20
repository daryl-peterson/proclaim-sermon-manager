<?php
/**
 * Database functions.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Check if a table exist.
 *
 * @param string $table_name Table name.
 * @return bool Return true if the table exist, otherwise false.
 * @since 1.0.0
 */
function table_exist( string $table_name ): bool {
	global $wpdb;

	$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

	if ( $wpdb->get_var( $query ) === $table_name ) {
		return true;
	}
	return false;
}
