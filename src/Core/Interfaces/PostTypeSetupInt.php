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
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
interface PostTypeSetupInt extends Registrable {

	/**
	 * Add post types and taxonomy.
	 *
	 * @return array Post and taxonomies registered.
	 * @since 1.0.0
	 */
	public function add(): array;

	/**
	 * Remove post types and taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function remove(): array;

	/**
	 * Get list of post types.
	 *
	 * @return array Post and taxonomies deregistered.
	 * @since 1.0.0
	 */
	public function get_post_type_list(): array;

	/**
	 * Get post type from setup array.
	 * - If post type does not exist throw exception.
	 *
	 * @param string $post_type Post type name.
	 * @throws PluginException Throws exception if type does not exist in setup array.
	 * @since 1.0.0
	 */
	public function get_post_type( string $post_type ): PostTypeRegInt;

	/**
	 * Get post type taxonomies.
	 *
	 * @param string $post_type Post type name.
	 * @return null|array Taxony array of TaxonomyRegInt.
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
