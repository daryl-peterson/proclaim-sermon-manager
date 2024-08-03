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
class PostTypeUtils {

	/**
	 * Check if a post can be saved.
	 *
	 * @param integer  $post_id Post ID.
	 * @param \WP_Post $post WordPress Post.
	 * @return boolean True if the post may be saved, false if not.
	 */
	public static function is_savable( int $post_id, \WP_Post $post ) {
		if ( ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			$key = 'sermon-edit-' . $post_id;
			if ( ! isset( $_REQUEST[ $key ] ) ) {
				return false;
			}
			$nonce = sanitize_text_field( wp_unslash( $_REQUEST[ $key ] ) );
			if ( ! wp_verify_nonce( $nonce, 'sermon-edit' ) ) {
				return false;
			}
			// @codeCoverageIgnoreEnd
		}

		if ( PT::SERMON !== $post->post_type ) {
			return false;
		}

		// Check current user permissions.
		$post_type = get_post_type_object( $post->post_type );

		// @codeCoverageIgnoreStart
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		// Do not save the data if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		return true;
	}

	public static function get_current_post_type(): ?array {
		global $post, $typenow, $current_screen;

		// we have a post so we can just get the post type from that
		if ( $post && $post->post_type ) {
			return $post->post_type;

			// check the global $typenow - set in admin.php
		} elseif ( $typenow ) {
			return $typenow;

			// check the global $current_screen object - set in sceen.php
		} elseif ( $current_screen && $current_screen->post_type ) {
			return $current_screen->post_type;

			// lastly check the post_type querystring
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			return sanitize_key( $_REQUEST['post_type'] );
		}

		// we do not know the post type!
		return null;
	}
}
