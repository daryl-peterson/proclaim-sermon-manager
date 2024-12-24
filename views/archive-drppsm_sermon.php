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

get_header(); ?>

<?php Templates::get_partial( 'content-sermon-wrapper-start' ); ?>

<?php
Templates::get_partial( 'content-sermon-filtering' );

if ( have_posts() ) :

	echo apply_filters( 'archive-wpfc_sermon-before-sermons', '' );

	while ( have_posts() ) :
		the_post();
		// wpfc_sermon_excerpt_v2(); // You can edit the content of this function in `partials/content-sermon-archive.php`.
	endwhile;

	echo apply_filters( 'archive-wpfc_sermon-after-sermons', '' );

	echo '<div class="sm-pagination ast-pagination">';
	// sm_pagination();
	echo '</div>';
else :
	echo __( 'Sorry, but there aren\'t any posts matching your query.' );
endif;
?>

<?php Templates::get_partial( 'content-sermon-wrapper-end' ); ?>

<?php
get_footer();
