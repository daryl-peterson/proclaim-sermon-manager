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
use DRPPSM\Logger;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use WP_User;

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

	public function getTestSermon(): ?\WP_Post {
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

	public function getTestPost(): \WP_Post {
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
	public function set_admin() {
		include_screen();

		$user = $this->get_admin_user();
		wp_set_current_user( $user->ID );
		set_current_screen( 'edit-post' );

		if ( ! defined( 'WP_ADMIN' ) ) {
			Logger::debug( 'Defining WP_ADMIN' );
			define( 'WP_ADMIN', true );
		}

		if ( ! is_admin() ) {
			Logger::debug( 'NOT ADMIN' );
		}
	}
}
