<?php
/**
 * Post type registration interface.
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
 * Post type registration interface.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface PostTypeSetupInt extends Initable, Registrable {

	/**
	 * Add post types and taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function add(): void;

	/**
	 * Remove post types and taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function remove(): void;

	/**
	 * Get list of post types.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type_list(): array;

	/**
	 * Get post type.
	 * - If post type does not exist throw exception.
	 *
	 * @since 1.0.0
	 * @param string $post_type Post type name.
	 * @throws PluginException Throws exception.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type( string $post_type ): PostTypeRegInt;

	/**
	 * Get post type taxonomies.
	 *
	 * @param string $post_type Post type name.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type_taxonomies( string $post_type ): ?array;

	/**
	 * Flush rewrite rules soft.
	 *
	 * @return void
	 */
	public function flush(): void;
}
