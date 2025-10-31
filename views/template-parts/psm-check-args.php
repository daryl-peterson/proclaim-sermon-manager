<?php
/**
 * Check if args variable exists.
 *
 * - Checks required keys in args array.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

if ( ! isset( $template ) ) {
	return false;
}

$failure = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';
if ( ! isset( $args ) || ! is_array( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	render_html( $failure );
	return false;
}

if ( ! has_keys( $required, $args ) ) {
	render_html( $failure );
	return false;
}

return true;
