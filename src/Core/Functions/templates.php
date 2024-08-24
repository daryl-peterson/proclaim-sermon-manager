<?php
/**
 * Template functions.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Get partial template.
 * - This is a stub function to Templates class.
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
function get_partial( string $name, array $args = array() ): void {
	Templates::exec()->get_partial( $name, $args );
}
