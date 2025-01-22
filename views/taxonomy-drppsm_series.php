<?php
/**
 * Sermon series taxonomy template.
 *
 * @package     DRPPSM
 * @subpackage  Template
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

if ( ! did_action( 'get_header' ) ) {
	get_header();
}

get_partial( 'sermon-wrapper-start' );
global $wp_query;

$archive = is_archive();
$qv      = get_query_var( 'taxonomy' );
Logger::debug(
	array(
		'QV' => $qv,
		$wp_query->query,
	)
);

$query = $wp_query->query;
if ( isset( $query['taxonomy'] ) ) {
	$obj = new TaxList( array( 'display' => 'series' ) );
} elseif ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		sermon_excerpt();
	}
		wp_reset_postdata();
} else {
	get_partial( 'no-posts' );
}


get_partial( 'sermon-wrapper-end' );

if ( ! did_action( 'get_footer' ) ) {
	get_footer();
}
