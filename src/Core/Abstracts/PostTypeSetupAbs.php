<?php
/**
 * Post type setup abstract.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Abstracts;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Logging\Logger;

/**
 * Post type setup abstract.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
abstract class PostTypeSetupAbs implements PostTypeSetupInt {

	/**
	 * Taxonomies indexed on post type.
	 *
	 * @var array
	 */
	protected array $taxonomies;

	/**
	 * Post types
	 *
	 * @var array
	 */
	protected array $post_types;

	/**
	 * Initialize object.
	 *
	 * @return PostTypeSetupInt Post type setup interface.
	 */
	public static function init(): PostTypeSetupInt {
		return new static();
	}

	/**
	 * Get post type list.
	 *
	 * @return array Post types array.
	 */
	public function get_post_type_list(): array {
		return array_keys( $this->post_types );
	}

	/**
	 * Get post type from array.
	 *
	 * @param string $post_type Post type.
	 * @return PostTypeRegInt Post type registration interface.
	 *
	 * @throws PluginException Throw exception if post type is not define in array.
	 */
	public function get_post_type( string $post_type ): PostTypeRegInt {
		if ( ! isset( $this->post_types[ $post_type ] ) ) {
			throw new PluginException( "Invalid post type : $post_type" );
		}

		return $this->post_types[ $post_type ];
	}

	/**
	 * Get post type taxonomies.
	 *
	 * @param string $post_type Post type.
	 * @return array|null Array of taxonomies.
	 */
	public function get_post_type_taxonomies( string $post_type ): ?array {
		if ( ! isset( $this->taxonomies[ $post_type ] ) ) {
			return null;
		}

		return $this->taxonomies[ $post_type ];
	}
}
