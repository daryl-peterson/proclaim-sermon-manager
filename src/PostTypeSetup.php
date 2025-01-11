<?php
/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register hooks.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Actions;
use DRPPSM\PostTypeReg;
use DRPPSM\Exceptions\PluginException;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\TaxonomyReg;

/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register hooks.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class PostTypeSetup implements Executable, Registrable {

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
	protected function __construct() {
		$pt                        = DRPPSM_PT_SERMON;
		$this->post_types[ $pt ]   = new PostTypeReg( DRPPSM_PT_SERMON, 'post-type-sermon.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( DRPPSM_TAX_BIBLE, DRPPSM_PT_SERMON, 'taxonomy-bible-book.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( DRPPSM_TAX_PREACHER, DRPPSM_PT_SERMON, 'taxonomy-preacher.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( DRPPSM_TAX_SERIES, DRPPSM_PT_SERMON, 'taxonomy-series.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( DRPPSM_TAX_SERVICE_TYPE, DRPPSM_PT_SERMON, 'taxonomy-service-type.php' );
		$this->taxonomies[ $pt ][] = new TaxonomyReg( DRPPSM_TAX_TOPICS, DRPPSM_PT_SERMON, 'taxonomy-topics.php' );
	}

	/**
	 * Initialize and register.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool Return true if hooks were set, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( has_action( 'init', array( $this, 'add' ) ) ) {
			return false;
		}

		add_action( 'init', array( $this, 'add' ) );
		return true;
	}

	/**
	 * Add post types and taxonomy.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function add(): ?array {
		try {

			$list   = $this->get_post_type_list();
			$status = array();

			foreach ( $list as $post_type ) {
				$obj = $this->get_post_type( $post_type );
				$obj->add();
				$taxonomies = (array) $this->get_post_type_taxonomies( $post_type );

				$status[ $post_type ]['status'] = $obj->exist();

				/**
				 * Taxonomy registration interface.
				 *
				 * @var TaxonomyReg $taxonomy
				 */
				foreach ( $taxonomies as $taxonomy ) {
					$taxonomy->add();
					$status[ $post_type ]['taxonomies'][ $taxonomy->get_name() ] = $taxonomy->exist();
				}
			}

			do_action( Actions::AFTER_POST_SETUP );
			return $status;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
		}
		return null;
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

				$obj        = $this->get_post_type( $post_type );
				$taxonomies = (array) $this->get_post_type_taxonomies( $post_type );
				$obj->remove();
				$status[ $post_type ]['status'] = $obj->exist();

				/**
				 * Taxonomy registration interface.
				 *
				 * @var TaxonomyReg $taxonomy
				 */
				foreach ( $taxonomies as $taxonomy ) {
					$taxonomy->remove();
					$status[ $post_type ]['taxonomies'][ $taxonomy->get_name() ] = $taxonomy->exist();
				}
			}

			return $status;
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'ERROR' => $th->getMessage(),
					'TRACE' => $th->getTrace(),
				)
			);
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
		do_action( DRPPSMA_FLUSH_REWRITE );
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
	public function get_post_type( string $post_type ): PostTypeReg {
		if ( ! is_array( $this->post_types ) || ! isset( $this->post_types[ $post_type ] ) ) {
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

		$result = $this->taxonomies[ $post_type ];
		return $result;
	}
}
