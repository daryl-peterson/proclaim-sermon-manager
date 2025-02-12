<?php
/**
 * File template utils.
 *
 * @package     DRPPSM\ShortCodes\TaxBlock
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Template;
use function DRPPSM\get_partial;

/**
 * Tax file utils.
 *
 * - Get header and start wrapper for file template.
 * - Get footer and end wrapper for file template.
 *
 * @package     DRPPSM\ShortCodes\TaxBlock
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class FileTemplate {

	/**
	 * Get header and start wrapper for file template.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function start(): string {
		$block  = wp_is_block_theme();
		$output = '';

		if ( ! $block ) {
			ob_start();
			if ( ! did_action( 'get_header' ) ) {
				get_header();
			}
			get_partial( Template::WRAPPER_START );
			$output .= ob_get_clean();
		}
		return $output;
	}

	/**
	 * Get footer and end wrapper for file template.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function end(): string {
		$block  = wp_is_block_theme();
		$output = '';

		if ( ! $block ) {
			ob_start();
			get_partial( Template::WRAPPER_END );
			if ( ! did_action( 'get_footer' ) ) {
				get_footer();
			}
			$output .= ob_get_clean();
		}
		return $output;
	}
}
