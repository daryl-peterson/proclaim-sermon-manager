<?php

namespace DRPPSM\Tests;

use DRPPSM\App;
use DRPPSM\Constants\PT;
use PHPUnit\Framework\TestCase;

/**
 * Base test case.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
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

	public function getAdminUser(): \WP_User {
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
			'post_type'   => PT::SERMON,
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
}
