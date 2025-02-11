<?php
/**
 * Base test case.
 *
 * @package     DRPPSM\Tests\BaseTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\App;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use WP_Query;
use WP_Term;
use WP_User;

use function DRPPSM\include_admin_plugin;
use function DRPPSM\include_screen;

/**
 * Base test case.
 *
 * @package     DRPPSM\Tests\BaseTest
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class BaseTest extends TestCase {

	protected App $app;



	public function __construct( string $name ) {
		parent::__construct( $name );
		if ( ! defined( 'PHPUNIT_TESTING' ) ) {
			define( 'PHPUNIT_TESTING', true );
		}

		$this->app = App::init();
	}

	/**
	 * Set up the test.
	 *
	 * @since 1.0.0
	 */
	protected function setUp(): void {
		parent::setUp();
	}

	/**
	 * Tear down the test.
	 *
	 * @since 1.0.0
	 */
	protected function tearDown(): void {
		parent::tearDown();

		// unset( $GLOBALS['current_screen'] );
	}

	/**
	 * Get the first admin user.
	 *
	 * @return WP_User
	 */
	public function get_admin_user(): \WP_User {
		$args  = array(
			'role'    => 'administrator',
			'orderby' => 'user_nicename',
			'order'   => 'ASC',
		);
		$users = get_users( $args );

		return $users[0];
	}

	/**
	 * Get a sermon post.
	 *
	 * @return \WP_Post
	 * @since 1.0.0
	 */
	public function get_test_sermon(): ?\WP_Post {
		$args  = array(
			'numberposts' => 5,
			'post_type'   => DRPPSM_PT_SERMON,
			'order'       => 'DESC',
			'orderby'     => 'date',
		);
		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			if ( 'publish' !== $post->post_status ) {
				continue;
			}

			break;
		}

		return $post;
	}

	/**
	 * Get sermon archive.
	 *
	 * - Uses WP_Query to get the sermon post.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sermon_archive(): array {
		/**
		 * @var \WP_Query $wp_query
		 */
		global $wp_query;

		$args = $this->get_sermon_query_args();

		// Try and test for is_post_type_archive.
		$posts = $wp_query->query( $args );
		return $posts;
	}

	/**
	 * Get sermon single.
	 *
	 * - Uses WP_Query to get the sermon post.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sermon_single(): array {
		/**
		 * @var \WP_Query $wp_query
		 */
		global $wp_query;

		$args  = $this->get_sermon_query_args();
		$posts = $this->get_sermon_archive();

		// Try and test for is_singular.
		if ( is_array( $posts ) ) {
			$post                   = array_shift( $posts );
			$args['p']              = $post->ID;
			$args['posts_per_page'] = 1;
		}

		$posts = $wp_query->query( $args );
		return $posts;
	}

	/**
	 * Get series term.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_series(): ?WP_Term {

		$tax  = DRPPSM_TAX_SERIES;
		$args = array(
			'taxonomy'   => $tax,
			'hide_empty' => true,
			'number'     => 1,
		);

		$terms = get_terms( $args );
		if ( is_array( $terms ) ) {
			$term = array_shift( $terms );
			return $term;
		}

		return null;
	}

	/**
	 * Get a term for a taxonomy.
	 *
	 * @param string $taxonomy Taxonomy name.
	 *
	 * @return WP_Term
	 * @since 1.0.0
	 */
	public function get_term( string $taxonomy ) {
		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'number'     => 1,
		);

		$terms = get_terms( $args );
		if ( is_array( $terms ) ) {
			$term = array_shift( $terms );
			return $term;
		}
		return null;
	}

	/**
	 * Get a post.
	 *
	 * @return \WP_Post
	 * @since 1.0.0
	 */
	public function get_post( string $type = DRPPSM_PT_SERMON ): \WP_Post {
		$args  = array(
			'numberposts' => 5,
			'post_type'   => 'post',
			'order'       => 'DESC',
			'orderby'     => 'date',
		);
		$posts = get_posts( $args );

		foreach ( $posts as $post ) {
			if ( 'publish' !== $post->post_status ) {
				continue;
			}

			break;
		}

		return $post;
	}

	/**
	 * Plugin deactivate.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function plugin_deactivate() {

		include_admin_plugin();

		$plugin = DRPPSM_BASENAME;

		$ms = is_multisite();
		if ( $ms ) {
			deactivate_plugins( $plugin, true, true );
			return;
		} else {
			deactivate_plugins( $plugin, true );
		}
	}

	/**
	 * Plugin activate.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function plugin_activate() {
		include_admin_plugin();

		$plugin = DRPPSM_BASENAME;

		$ms = is_multisite();
		if ( $ms ) {
			activate_plugin( $plugin, '', true, true );
			return;
		} else {
			activate_plugin( $plugin, '', false, true );
		}
	}

	/**
	 * Get make object method accessible.
	 *
	 * @param mixed  $obj
	 * @param string $name
	 *
	 * @return \ReflectionMethod
	 * @since 1.0.0
	 */
	public function get_method( mixed $obj, $name ): ReflectionMethod {
		$class  = new ReflectionClass( $obj );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );
		return $method;
	}

	/**
	 * Set a property on an object that is private or protected.
	 *
	 * @param mixed  $obj
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function set_property( mixed $obj, $name, $value ): void {
		$class  = new ReflectionClass( $obj );
		$method = $class->getProperty( $name );
		$method->setAccessible( true );
		$method->setValue( $obj, $value );
	}

	/**
	 * Get a property on an object that is private or protected.
	 *
	 * @param mixed  $obj
	 * @param string $name
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_property( mixed $obj, $name ): mixed {
		$class  = new ReflectionClass( $obj );
		$method = $class->getProperty( $name );
		$method->setAccessible( true );
		return $method->getValue( $obj );
	}

	/**
	 * Set the current user to an admin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function set_admin( bool $state = true ) {
		include_screen();

		$user = $this->get_admin_user();
		wp_set_current_user( $user->ID );

		if ( $state ) {
			set_current_screen( 'edit-post' );
		} else {
			set_current_screen( 'front' );
		}

		if ( ! defined( 'WP_ADMIN' ) ) {
			define( 'WP_ADMIN', $state );
		}
	}

	/**
	 * Set the main query.
	 *
	 * @param WP_Query $query
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function set_main_query( WP_Query $query ) {
		global $wp_the_query;
		$wp_the_query = $query;
	}

	/**
	 * Get a page.
	 *
	 * @return \WP_Post
	 * @since 1.0.0
	 */
	public function get_page(): ?\WP_Post {

		/**
		 * @var \WP_Query $wp_query
		 */
		global $wp_query;

		$args = array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => 1,
		);

		$post = $wp_query->query( $args );
		if ( ! $post ) {
			return null;
		}

		if ( is_array( $post ) ) {
			return $post[0];
		}
		return $post;
	}

	/**
	 * Switch to a block theme.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function switch_to_block_theme() {
		/**
		 * @var \WP_Theme $theme_org
		 */
		$theme_org = wp_get_theme();

		if ( $theme_org->is_block_theme() ) {
			return;
		}

		$themes = array_keys( wp_get_themes() );

		foreach ( $themes as $theme_name ) {
			$theme    = wp_get_theme( $theme_name );
			$is_block = $theme->is_block_theme();
			if ( $is_block ) {
				break;
			}
		}

		if ( $is_block ) {
			update_option( 'drppsm_phpunit_theme', $theme_org->get_stylesheet() );
			switch_theme( $theme->get_stylesheet() );
		}
	}

	/**
	 * Swith to file theme.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function switch_to_file_theme() {
		/**
		 * @var \WP_Theme $theme_org
		 */
		$theme_org = wp_get_theme();

		if ( ! $theme_org->is_block_theme() ) {
			return;
		}

		$themes = array_keys( wp_get_themes() );

		foreach ( $themes as $theme_name ) {
			$theme    = wp_get_theme( $theme_name );
			$is_block = $theme->is_block_theme();
			if ( ! $is_block ) {
				break;
			}
		}

		if ( ! $is_block && ! $theme->is_block_theme() ) {
			update_option( 'drppsm_phpunit_theme', $theme_org->get_stylesheet() );
			switch_theme( $theme->get_stylesheet() );
		}
	}

	/**
	 * Get sermon query args.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sermon_query_args() {
		$args = array(
			'post_type'      => DRPPSM_PT_SERMON,
			'post_status'    => 'publish',
			'posts_per_page' => 5,
		);
		return $args;
	}
}
