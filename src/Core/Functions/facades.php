<?php

/**
 * Facade functions.
 *
 * @package     DRPSM/Functions/Facades
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

use DRPPSM\Templates;
use DRPPSM\Logger;

use function DRPPSM\sermon_excerpt;


function drppsm_extract( mixed $args ) {
	Logger::debug( array( 'ARGS' => $args ) );
	extract( $args, EXTR_OVERWRITE );
}

/**
 * Get partial template.
 *
 * - `/wp-contents/themes/<theme_name>/partials/<partial_name>.php`
 * - `/wp-contents/themes/<theme_name>/template-parts/<partial_name>.php`
 * - `/wp-contents/themes/<theme_name>/<partial_name>.php`
 *
 * @param string $name File name.
 * @param array  $args Array of variables to pass to template.
 * @return void
 * @since 1.0.0
 */
function drppsm_get_partial( string $name, array $args = array() ): void {
	Templates::exec()->get_partial( $name, $args );
}

/**
 * Get sermon excerpt
 *
 * @param array $args
 * @return void
 * @since 1.0.0
 */
function drppsm_sermon_excerpt( $args = array() ): void {
	sermon_excerpt( $args );
}
