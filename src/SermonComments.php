<?php
/**
 * Sermon comments.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\PT;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Sermon comments.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonComments implements Registrable, Executable {

	/**
	 * Initialize and register.
	 *
	 * @return SermonComments
	 * @since 1.0.0
	 */
	public static function exec(): SermonComments {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register callbacks.
	 *
	 * @return null|bool Return true default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		$comments = get_setting( Settings::FIELD_COMMENTS );
		if ( $comments || has_filter( 'wp_insert_post_data', array( $this, 'default_comments_off' ) ) ) {
			return false;
		}
		add_filter( 'wp_insert_post_data', array( $this, 'default_comments_off' ) );
		return true;
	}

	/**
	 * Turn comments off for sermons.
	 *
	 * @param array $data Data to insert.
	 * @return array
	 * @since 1.0.0
	 */
	public function default_comments_off( array $data ): array {

		$comments = get_setting( 'comments' );

		if ( $comments ) {
			return $data;
		}

		if ( PT::SERMON === $data['post_type'] ) {
			$data['comment_status'] = 0;
		}

		return $data;
	}
}
