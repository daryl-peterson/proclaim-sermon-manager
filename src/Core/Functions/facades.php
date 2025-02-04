<?php
/**
 * Facade functions.
 *
 * @package     DRPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

use function DRPPSM\sermon_excerpt;


/**
 * Get sermon excerpt
 *
 * @param array $args Argument to pass to templates.
 * @return void
 * @since 1.0.0
 */
function drppsm_sermon_excerpt( $args = array() ): void {
	sermon_excerpt( $args );
}
