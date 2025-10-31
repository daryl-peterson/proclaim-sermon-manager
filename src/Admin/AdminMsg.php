<?php
/**
 * Class description
 *
 * @package     DRPPSM\AdminMsg
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Class description
 *
 * @package     DRPPSM\AdminMsg
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminMsg {


	/**
	 * Singular label message.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function label_single(): string {
		return __( 'The label should be in the singular form.', 'drppsm' );
	}

	/**
	 * Plural label message.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function label_plural(): string {
		return __( 'The label should be in the plural form.', 'drppsm' );
	}

	/**
	 * Player name message.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function player_name(): string {
		return __( 'Audio & Video Player', 'drppsm' );
	}

	/**
	 * File not exist message.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function file_not_exist(): string {
		return __( 'File does not exist.', 'drppsm' );
	}

	/**
	 * Failed loading partial message.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function failed_partial(): string {
		return __( 'Failed loading partial file.', 'drppsm' );
	}

	/**
	 * Slug note message.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function slug_note(): string {
		return __( 'Note: This also changes the slugs.', 'drppsm' );
	}
}
