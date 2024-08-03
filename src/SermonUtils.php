<?php
/**
 * Sermon utils.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\PT;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Sermon utils.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonUtils {

	/**
	 * Check if a post can be saved.
	 *
	 * @param integer  $post_id Post ID.
	 * @param \WP_Post $post WordPress Post.
	 * @return boolean True if the post may be saved, false if not.
	 */
	public static function is_savable( int $post_id, \WP_Post $post ) {
		if ( ! defined( 'PHPUNIT_TESTING' ) ) {
			$key = 'sermon-edit-' . $post_id;
			if ( ! isset( $_REQUEST[ $key ] ) ) {
				return false;
			}
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
			if ( ! wp_verify_nonce( $nonce, 'sermon-edit' ) ) {
				return false;
			}
		}

		if ( PT::SERMON !== $post->post_type ) {
			return false;
		}

		// Check current user permissions.
		$post_type = get_post_type_object( $post->post_type );

		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return false;
		}

		// Do not save the data if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		return true;
	}
}
