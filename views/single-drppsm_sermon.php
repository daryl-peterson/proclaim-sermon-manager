<?php
/**
 * Single sermon template.
 *
 * @package     DRPPSM/Views
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Tax;

get_header();
get_partial( 'content-sermon-wrapper-start' );

while ( have_posts() ) :
	global $post;
	the_post();

	get_series_image( $post->ID );

	if ( ! post_password_required( $post ) ) {
		// wpfc_sermon_single_v2();
	} else {
		echo get_the_password_form( $post );
	}

	if ( comments_open() || get_comments_number() ) :
		if ( isset( $comments ) ) {
			comments_template();
		}
	endif;
endwhile;

get_partial( 'content-sermon-wrapper-end' );
get_footer();
