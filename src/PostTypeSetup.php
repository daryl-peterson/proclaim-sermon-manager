<?php
/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\PostTypeReg;
use DRPSermonManager\Constants\Filters;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\Tax;
use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Interfaces\TaxonomyRegInt;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\TaxonomyReg;

/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class PostTypeSetup implements PostTypeSetupInt {

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
	 * @since 1.0.0
	 */
	public function __construct() {
		$pt                        = PT::SERMON;
		$this->post_types[ $pt ]   = new PostTypeReg( PT::SERMON, 'post-type-sermon.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( Tax::BIBLE_BOOK, PT::SERMON, 'taxonomy-bible-book.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( Tax::PREACHER, PT::SERMON, 'taxonomy-preacher.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( Tax::SERIES, PT::SERMON, 'taxonomy-series.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( Tax::SERVICE_TYPE, PT::SERMON, 'taxonomy-service-type.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( Tax::TOPICS, PT::SERMON, 'taxonomy-topics.php' );
	}


	/**
	 * Register callbacks.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register(): void {
		add_action( 'init', array( $this, 'add' ) );
		add_action( Filters::FLUSH_REWRITE_RULES, array( $this, 'flush' ) );
	}

	/**
	 * Add post types and taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function add(): void {
		try {
			$list = $this->get_post_type_list();

			foreach ( $list as $post_type ) {
				/**
				 * Post type registation.
				 *
				 * @var PostTypeRegInt $obj
				 */
				$obj = $this->get_post_type( $post_type );
				$obj->add();
				$taxonomies = $this->get_post_type_taxonomies( $post_type );

				if ( ! isset( $taxonomies ) ) {
					// @codeCoverageIgnoreStart
					continue;
					// @codeCoverageIgnoreEnd
				}

				/**
				 * Taxonomy registration interface.
				 *
				 * @var TaxonomyRegInt $taxonomy
				 */
				foreach ( $taxonomies as $taxonomy ) {
					$taxonomy->add();
				}
			}

			do_action( Filters::AFTER_POST_SETUP );

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			throw new PluginException( $th->getMessage(), $th->getCode(), $th );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Remove post types and taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function remove(): void {
		try {
			$list = $this->get_post_type_list();
			Logger::debug( array( 'GET POST TYPE LIST' => $list ) );

			foreach ( $list as $post_type ) {
				/**
				 * Post type registration interface.
				 *
				 * @var PostTypeRegInt $obj
				 */
				$obj        = $this->get_post_type( $post_type );
				$taxonomies = $this->get_post_type_taxonomies( $post_type );

				if ( ! isset( $taxonomies ) ) {
					// @codeCoverageIgnoreStart
					$obj->remove();
					continue;
					// @codeCoverageIgnoreEnd
				}

				/**
				 * Taxonomy registration interface.
				 *
				 * @var TaxonomyRegInt $taxonomy
				 */
				foreach ( $taxonomies as $taxonomy ) {
					Logger::debug( array( 'TAXONOMY' => $taxonomy ) );
					$taxonomy->remove();
				}

				$obj->remove();
			}

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			throw new PluginException( $th->getMessage(), $th->getCode(), $th );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Flush rewrite rule
	 *
	 * @return void
	 */
	public function flush(): void {
		flush_rewrite_rules();
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
	 * @throws PluginException Throw exception if post type is not defined in array.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type( string $post_type ): PostTypeRegInt {
		if ( ! isset( $this->post_types[ $post_type ] ) ) {
			throw new PluginException( esc_html( "Invalid post type : $post_type" ) );
		}

		return $this->post_types[ $post_type ];
	}

	/**
	 * Get post type taxonomies.
	 *
	 * @param string $post_type Post type.
	 * @return array|null Array of taxonomies.
	 *
	 * @since 1.0.0
	 */
	public function get_post_type_taxonomies( string $post_type ): ?array {
		if ( ! isset( $this->taxonomies[ $post_type ] ) ) {
			return null;
		}

		return $this->taxonomies[ $post_type ];
	}
}
