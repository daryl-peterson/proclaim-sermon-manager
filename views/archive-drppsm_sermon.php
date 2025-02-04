<?php
/**
 * Sermon archive template.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

/**
 * Make sure array exist. Other functions will need it.
 */
if ( ! isset( $args ) ) {
	$args = array();
}

if ( wp_is_block_theme() ) {
	get_partial( 'header' );
} elseif ( ! did_action( 'get_header' ) ) {
	get_header();
}

get_partial( 'sermon-wrapper-start' );
echo sermon_sorting();

if ( have_posts() ) {
	new SermonImageList();
	wp_reset_postdata();
} else {
	get_partial( 'no-posts' );
}

get_partial( 'sermon-wrapper-end' );



if ( wp_is_block_theme() ) {
	get_footer();
} elseif ( ! did_action( 'get_footer' ) ) {
	get_footer();
}
