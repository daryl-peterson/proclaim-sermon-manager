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

get_header();

Templates::get_partial( 'content-sermon-wrapper-start' );
Templates::get_partial( 'content-sermon-filtering' );

if ( have_posts() ) :

	echo apply_filters( 'archive-wpfc_sermon-before-sermons', '' );

	while ( have_posts() ) :
		the_post();
		Templates::sermon_excerpt();
	endwhile;

	echo apply_filters( 'archive-wpfc_sermon-after-sermons', '' );

	echo '<div class="sm-pagination ast-pagination">';
	// sm_pagination();
	echo '</div>';
else :
	echo __( 'Sorry, but there aren\'t any posts matching your query.' );
endif;

Templates::get_partial( 'content-sermon-wrapper-end' );
get_footer();
