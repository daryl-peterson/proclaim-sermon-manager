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
use WP_Error;
use WP_Post;
use WP_Query;
use WP_Term;
use WP_User;

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


	/**
	 * BaseTest constructor.
	 *
	 * @param string $name
	 */
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
			$post      = array_shift( $posts );
			$args['p'] = $post->ID;

			$args['posts_per_page'] = 1;
		}

		$posts = $wp_query->query( $args );
		return $posts;
	}

	/**
	 * Get series term.
	 *
	 * @return ?WP_Term
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
	 * Get a series with images.
	 *
	 * @return ?WP_Term
	 * @since 1.0.0
	 */
	public function get_series_with_images(): ?WP_Term {

		$tax = DRPPSM_TAX_SERIES;

		$args  = array(
			'taxonomy'   => $tax,
			'hide_empty' => false,
			'number'     => 1,
			'meta_query' => array(
				array(
					'key'     => "{$tax}_image_id",
					'value'   => '',
					'compare' => '!=',
				),
			),
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
	public function get_term( string $taxonomy ): ?WP_Term {
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
	 * @return WP_Post
	 * @since 1.0.0
	 */
	public function get_post( string $type = DRPPSM_PT_SERMON ): ?WP_Post {
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
		if ( ! $post instanceof WP_Post ) {
			return null;
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

		$this->inc();

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
		$this->inc();

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
		$this->inc();

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

	/**
	 * Include needed admin files.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function inc() {
		$this->inc_admin_plugin();
		$this->inc_admin_template();
		$this->inc_pluggable();
		$this->inc_dashboard();
		$this->inc_screen();
	}

	/**
	 * Include plugin actions functions from wp-admin/includes/plugin.php.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function inc_admin_plugin() {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\is_plugin_active' ) ) {
			$file = ABSPATH . 'wp-admin/includes/plugin.php';

			require_once $file;
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Include metabox / template functions from wp-admin/includes/template.php.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function inc_admin_template() {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\remove_meta_box' ) ) {
			$file = ABSPATH . 'wp-admin/includes/template.php';
			require_once $file;
		}
		// @codeCoverageIgnoreEnd
	}


	/**
	 * Include plugable file if wp_rand function not loaded.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function inc_pluggable(): void {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\wp_rand' ) ) {
			$file = ABSPATH . 'wp-includes/pluggable.php';
			require_once $file;
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Include dashboard functions.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function inc_dashboard() {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\wp_dashboard' ) ) {
			require_once ABSPATH . 'wp-admin/includes/dashboard.php';
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Include admin screen functions.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function inc_screen() {
		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\get_current_screen' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';

			$file = ABSPATH . 'wp-admin/includes/screen.php';
			require_once $file;
		}
		// @codeCoverageIgnoreEnd
	}
}
