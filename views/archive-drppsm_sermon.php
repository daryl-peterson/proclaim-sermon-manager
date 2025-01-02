<?php // phpcs:ignore
/**
 * Sermon archive template.
 *
 * @package     DRPPSM/Views/
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
get_partial( 'content-sermon-filtering' );

if ( have_posts() ) :

	// echo apply_filters( 'archive-wpfc_sermon-before-sermons', '' );

	while ( have_posts() ) :
		the_post();
		sermon_excerpt();
	endwhile;

	// echo apply_filters( 'archive-wpfc_sermon-after-sermons', '' );

	echo '<div class="sm-pagination ast-pagination">';
	// sm_pagination();
	echo '</div>';
else :
	echo __( 'Sorry, but there aren\'t any posts matching your query.' );
endif;

get_partial( 'sermon-wrapper-end' );

if ( ! did_action( 'get_footer' ) ) {
	get_footer();
}
