<?php
/**
 * Sermon preacher taxonomy.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$qv_tax  = get_query_var( 'taxonomy' );
$qv_term = get_query_var( DRPPSM_TAX_PREACHER );


if ( ! did_action( 'get_header' ) ) {
	get_header();
}

get_partial( 'sermon-wrapper-start' );

if ( empty( $qv_term ) ) {
	new TaxImageList(
		array(
			'display' => $qv_tax,
			'size'    => get_tax_image_size( 'full', 'preacher' ),
		)
	);
} elseif ( have_posts() ) {
	get_partial( 'content-sermon-filtering' );

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
