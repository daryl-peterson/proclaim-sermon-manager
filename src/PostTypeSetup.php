<?php
/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Abstracts\PostTypeSetupAbs;
use DRPSermonManager\Constants\ACTIONS;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Interfaces\TaxonomyRegInt;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\PostType\SermonReg;
use DRPSermonManager\Taxonomy\BibleBookReg;
use DRPSermonManager\Taxonomy\PreacherReg;
use DRPSermonManager\Taxonomy\SeriesReg;
use DRPSermonManager\Taxonomy\ServiceTypeReg;
use DRPSermonManager\Taxonomy\TopicsReg;

/**
 * Register post types and taxonomies.
 * - Stub - calls other object methods.
 * - Used so other object don't have to register callbacks.
 *
 * @since       1.0.0
 */
class PostTypeSetup extends PostTypeSetupAbs implements PostTypeSetupInt {

	/**
	 * Initialize object.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$pt                        = PT::SERMON;
		$this->post_types[ $pt ]   = SermonReg::init();
		$this->taxonomies[ $pt ][] = PreacherReg::init();
		$this->taxonomies[ $pt ][] = SeriesReg::init();
		$this->taxonomies[ $pt ][] = TopicsReg::init();
		$this->taxonomies[ $pt ][] = BibleBookReg::init();
		$this->taxonomies[ $pt ][] = ServiceTypeReg::init();
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
		add_action( ACTIONS::FLUSH_REWRITE_RULES, array( $this, 'flush' ) );
	}

	/**
	 * Add post types and taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function add(): void {
		try {
			$list = $this->get_post_type_list();
			Logger::debug( array( 'GET POST TYPE LIST' => $list ) );

			foreach ( $list as $post_type ) {
				/**
				 * Post type registation.
				 *
				 * @var PostTypeRegInt $obj
				 */
				$obj = $this->get_post_type( $post_type );
				Logger::debug( array( 'OBJ' => $obj ) );
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

			do_action( 'drpsermon_after_post_setup' );

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
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
			FatalError::set( $th );
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
}
