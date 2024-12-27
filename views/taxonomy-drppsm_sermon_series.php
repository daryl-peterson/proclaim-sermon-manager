<?php

/**
 * Sermon taxonomy series
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

get_header();
?>

<?php get_partial( 'sermon-wrapper-start' ); ?>

<?php
// echo render_wpfc_sorting();

if ( have_posts() ) :

	echo apply_filters( 'taxonomy-wpfc_sermon_series-before-sermons', '' );

	while ( have_posts() ) :
		the_post();
		// wpfc_sermon_excerpt_v2();
	endwhile;

	echo apply_filters( 'taxonomy-wpfc_sermon_series-after-sermons', '' );

	echo '<div class="sm-pagination ast-pagination">';
	// sm_pagination();
	echo '</div>';
else :
	echo __( 'Sorry, but there are no posts matching your query.' );
endif;
?>

<?php get_partial( 'sermon-wrapper-end' ); ?>

<?php
get_footer();
