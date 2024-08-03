<?php
/**
 * Post type registration interface.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Interfaces;

use DRPSermonManager\Exceptions\PluginException;

/**
 * Post type registration interface.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface PostTypeRegInt extends Initable {

	/**
	 * Add post type.
	 *
	 * @since 1.0.0
	 *
	 * @throws PluginException Throws exception on failure.
	 */
	public function add(): void;

	/**
	 * Remove post type.
	 *
	 * @since 1.0.0
	 *
	 * @throws PluginException Throws excepton on failure.
	 */
	public function remove(): void;

	/**
	 * Check if post type exist.
	 *
	 * @return bool True if post type exist, fail if not.
	 * @since 1.0.0
	 */
	public function exist(): bool;

	/**
	 * Get WP_Error message.
	 *
	 * @param \WP_Error $error WP Error.
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_wp_error_message( \WP_Error $error ): string;
}
