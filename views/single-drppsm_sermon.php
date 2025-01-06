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

// Make sure args is defined.
if ( ! isset( $args ) || ! is_array( $args ) ) {
	$args = array();
}

if ( ! did_action( 'get_header' ) ) {
	get_header();
}

get_partial( 'sermon-wrapper-start' );

while ( have_posts() ) {
	global $post;
	the_post();

	if ( ! post_password_required( $post ) ) {
		get_partial( 'content-sermon-single', $args );
	} else {
		echo get_the_password_form( $post );
	}

	if ( comments_open() || get_comments_number() ) {
		if ( isset( $comments ) ) {
			comments_template();
		}
	}
}

get_partial( 'sermon-wrapper-end' );

if ( ! did_action( 'get_footer' ) ) {
	get_footer();
}
