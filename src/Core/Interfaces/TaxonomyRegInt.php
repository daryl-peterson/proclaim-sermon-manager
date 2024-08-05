<?php
/**
 * Taxonomy registration interface.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Interfaces;

use DRPSermonManager\Exceptions\PluginException;

/**
 * Taxonomy registration interface.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface TaxonomyRegInt {

	/**
	 * Add taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @throws PluginException Throws exception if not exist.
	 */
	public function add(): void;

	/**
	 * Remove taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @throws PluginException Throws exception if exist.
	 */
	public function remove(): void;

	/**
	 * Check if taxonomy exist.
	 *
	 * @since 1.0.0
	 */
	public function exist(): bool;

	/**
	 * Get WP_Error message.
	 *
	 * @param \WP_Error $error WP Error.
	 * @return string Error message
	 *
	 * @since 1.0.0
	 */
	public function get_wp_error_message( \WP_Error $error ): string;
}
