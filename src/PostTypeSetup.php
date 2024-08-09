<?php
/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\PostTypeReg;
use DRPPSM\Constants\Filters;
use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
use DRPPSM\Exceptions\PluginException;
use DRPPSM\Interfaces\PostTypeRegInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Interfaces\TaxonomyRegInt;
use DRPPSM\TaxonomyReg;

/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
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
	 * @return null|bool Return true default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_action( 'init', array( $this, 'add' ) );
		add_action( Filters::FLUSH_REWRITE_RULES, array( $this, 'flush' ) );
		return true;
	}

	/**
	 * Add post types and taxonomy.
	 *
	 * @return array
	 * @throws PluginException If error ooccures add post type or taxonomy.
	 * @since 1.0.0
	 */
	public function add(): array {
		try {
			$list   = $this->get_post_type_list();
			$status = array();

			foreach ( $list as $post_type ) {
				/**
				 * Post type registation.
				 *
				 * @var PostTypeRegInt $obj
				 */
				$obj = $this->get_post_type( $post_type );
				$obj->add();
				$taxonomies = (array) $this->get_post_type_taxonomies( $post_type );

				$status[ $post_type ]['status'] = $obj->exist();

				/**
				 * Taxonomy registration interface.
				 *
				 * @var TaxonomyRegInt $taxonomy
				 */
				foreach ( $taxonomies as $taxonomy ) {
					$taxonomy->add();
					$status[ $post_type ]['taxonomies'][ $taxonomy->get_name() ] = $taxonomy->exist();
				}
			}

			flush_rewrite_rules( false );
			do_action( Filters::AFTER_POST_SETUP );

			return $status;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			throw new PluginException( wp_kses( $th->getMessage(), allowed_html() ) );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Remove post types and taxonomy.
	 *
	 * @return array
	 * @throws PluginException If error ooccures remove post type or taxonomy.
	 * @since 1.0.0
	 */
	public function remove(): array {
		try {
			$list   = $this->get_post_type_list();
			$status = array();

			foreach ( $list as $post_type ) {
				/**
				 * Post type registration interface.
				 *
				 * @var PostTypeRegInt $obj
				 */
				$obj = $this->get_post_type( $post_type );

				$taxonomies = (array) $this->get_post_type_taxonomies( $post_type );
				$obj->remove();
				$status[ $post_type ]['status'] = $obj->exist();

				/**
				 * Taxonomy registration interface.
				 *
				 * @var TaxonomyRegInt $taxonomy
				 */
				foreach ( $taxonomies as $taxonomy ) {
					$taxonomy->remove();
					$status[ $post_type ]['taxonomies'][ $taxonomy->get_name() ] = $taxonomy->exist();
				}
			}

			return $status;
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			throw new PluginException( wp_kses( $th->getMessage(), allowed_html() ) );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Flush rewrite rule.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function flush(): void {
		flush_rewrite_rules( false );
	}

	/**
	 * Get post type list.
	 *
	 * @return array Post types array.
	 * @since 1.0.0
	 */
	public function get_post_type_list(): array {
		return array_keys( $this->post_types );
	}

	/**
	 * Get post type from setup array.
	 * - If post type does not exist throw exception.
	 *
	 * @param string $post_type Post type name.
	 * @throws PluginException Throws exception if type does not exist in setup array.
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
	 * @param string $post_type Post type name.
	 * @return null|array
	 * @since 1.0.0
	 */
	public function get_post_type_taxonomies( string $post_type ): ?array {
		if ( ! isset( $this->taxonomies[ $post_type ] ) ) {
			return null;
		}

		return $this->taxonomies[ $post_type ];
	}
}
