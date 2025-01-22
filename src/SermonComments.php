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
	use Traits\ExecutableTrait;

	/**
	 * Register hooks.
	 *
	 * @return null|bool Return true default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_filter( 'wp_insert_post_data', array( $this, 'default_comments_off' ) ) ) {
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

		$comments = Settings::get( Settings::COMMENTS );

		if ( $comments ) {
			return $data;
		}

		if ( DRPPSM_PT_SERMON === $data['post_type'] ) {
			$data['comment_status'] = 0;
		}

		return $data;
	}
}
