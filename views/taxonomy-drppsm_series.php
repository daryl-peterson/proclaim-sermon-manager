<?php
/**
 * Sermon series taxonomy template.
 *
 * @package     DRPPSM
 * @subpackage  Template
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

Logger::debug( array( 'ARGS' => $args ) );

if ( ! did_action( 'get_header' ) ) {
	// get_header();
}

get_partial( 'sermon-wrapper-start' );

$obj = new Series( array( 'display' => 'series' ) );

get_partial( 'sermon-wrapper-end' );

if ( ! did_action( 'get_footer' ) ) {
	get_footer();
}
