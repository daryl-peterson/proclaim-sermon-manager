<?php
/**
 * Taxonomy template.
 *
 * @package     DRPPSM
 * @subpackage  Template
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

$qv_tax = get_query_var( 'taxonomy' );

if ( ! did_action( 'get_header' ) ) {
	get_header();
}

$theme = wp_get_theme();
Logger::debug( array( 'THEME' => $theme ) );

get_partial( Template::WRAPPER_START );

echo do_shortcode( '[' . $qv_tax . ']' );

get_partial( Template::WRAPPER_END );

if ( ! did_action( 'get_footer' ) ) {
	get_footer();
}
