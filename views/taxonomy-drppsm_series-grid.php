<?php
/**
 * Series image grid
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

if ( ! did_action( 'get_header' ) ) {
	get_header();
}

$template     = str_replace( '.php', '', basename( __FILE__ ) );
$failure      = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';
$requirements = array(
	'terms',
	'image_size',
);

if ( ! isset( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	echo $failure;
	return;
}

// phpcs:ignore
extract( $args );
get_partial( 'sc-wrapper-start' );

// Check if requirements are met.
foreach ( $requirements as $required_variable ) {
	if ( ! isset( $$required_variable ) ) {
		echo $failure;
		Logger::error( 'Requirements not met : ' . $required_variable );
		return;
	}
}

foreach ( $terms as $term ) {
	$grid = array(
		'term'     => $term,
		'taxonomy' => DRPPSM_TAX_SERIES,
	);
	get_partial( 'content-series-grid', $grid );
}

get_partial( 'sc-wrapper-end' );
