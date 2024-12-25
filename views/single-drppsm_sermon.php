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

get_header();
Templates::get_partial( 'sermon-wrapper-start' );

while ( have_posts() ) :
	global $post;
	the_post();



	if ( ! post_password_required( $post ) ) {
		Templates::sermon_single();
	} else {
		echo get_the_password_form( $post );
	}

	if ( comments_open() || get_comments_number() ) :
		if ( isset( $comments ) ) {
			comments_template();
		}
	endif;
endwhile;
Templates::get_partial( 'sermon-wrapper-end' );
get_footer();
