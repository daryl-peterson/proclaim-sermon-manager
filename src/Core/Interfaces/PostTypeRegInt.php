<?php
/**
 * Post type registration interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

use DRPPSM\Exceptions\PluginException;

/**
 * Post type registration interface.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface PostTypeRegInt {

	/**
	 * Add post type.
	 *
	 * @since 1.0.0
	 * @throws PluginException Throws exception on failure.
	 */
	public function add(): void;

	/**
	 * Remove post type.
	 *
	 * @since 1.0.0
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
}
