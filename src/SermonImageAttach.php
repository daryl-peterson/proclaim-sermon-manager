<?php
/**
 * Sermon Images.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Meta;
use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Error;
use WP_Post;
use WP_Term;

/**
 * Sermon Images.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonImageAttach implements Executable, Registrable {

	/**
	 * Initialize and register hooks.
	 *
	 * @return SermonImageAttach
	 *
	 * @since 1.0.0
	 */
	public static function exec(): SermonImageAttach {

		$obj = new self();
		$obj->register();

		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( is_admin() && ! has_action( 'save_post', array( $this, 'save_post' ) ) ) {
			add_action( 'save_post', array( $this, 'save_post' ), 50, 3 );
			return false;
		}

		return true;
	}

	/**
	 * Save post images.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Post.
	 * @param bool    $update Udate flag.
	 * @return array
	 * @since 1.0.0
	 */
	public function save_post( int $post_id, WP_Post $post, bool $update ): array {
		try {

			$status = array();
			if ( PT::SERMON !== $post->post_type || defined( 'DRPSM_SAVING_IMAGES' ) ) {
				return $status;
			}

			define( 'DRPSM_SAVING_IMAGES', true );

			$status['thumb']  = $this->attach_thumb( $post );
			$status['series'] = $this->attach_series( $post );

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}
		Logger::debug( $status );

		return $status;
	}

	/**
	 * Attach thumbnails.
	 *
	 * @param WP_Post $sermon Post.
	 * @return bool True if image was attached, otherwise false.
	 * @since 1.0.0
	 */
	public function attach_thumb( WP_Post $sermon ): bool {
		if ( PT::SERMON !== $sermon->post_type ) {
			return false;
		}

		$thumb = get_post_meta( $sermon->ID, '_thumbnail_id', true );
		if ( ! isset( $thumb ) || empty( $thumb ) ) {
			return false;
		}

		$attachment = get_post( $thumb );
		if ( ! $attachment instanceof WP_Post ) {
			return false;
		}

		return $this->attach_image( $attachment, $sermon );
	}

	/**
	 * Attach series image.
	 *
	 * @param WP_Post $sermon Post.
	 * @return bool True if images was attached, otherwise false.
	 * @since 1.0.0
	 */
	public function attach_series( WP_Post $sermon ): bool {
		if ( PT::SERMON !== $sermon->post_type ) {
			return false;
		}

		$term = get_the_terms( $sermon, Tax::SERIES );
		if ( ! is_array( $term ) ) {
			return false;
		}

		$term = array_shift( $term );
		if ( ! $term instanceof WP_Term ) {
			return false;
		}

		$series_id = get_term_meta( $term->term_id, Meta::SERIES_IMAGE_ID, true );
		if ( ! isset( $series_id ) || empty( $series_id ) ) {
			return false;
		}

		$attachment = get_post( (int) $series_id );
		if ( ! $attachment instanceof WP_Post ) {
			return false;
		}

		return $this->attach_image( $attachment, $sermon );
	}

	/**
	 * Attach image.
	 *
	 * @param WP_Post $attachment Post object.
	 * @param WP_Post $sermon Post object.
	 * @return bool If Post parent id equals $parent_id true, otherwise false.
	 * @since 1.0.0
	 *
	 */
	public function attach_image( WP_Post $attachment, WP_Post $sermon ): bool {


		if ( PT::ATTACHEMENT !== $attachment->post_type
			|| PT::SERMON !== $sermon->post_type
			|| defined( 'DRPSM_ATTACHING_IMAGE'
		) ) {
			return false;
		}

		define( 'DRPSM_ATTACHING_IMAGE', true );

		if ( $attachment->post_parent === $sermon->ID ) {
			return true;
		}

		if ( 0 === $attachment->post_parent ) {
			$attachment->post_parent = $sermon->ID;
			$result                  = wp_update_post( $attachment );

			if ( $result instanceof WP_Error ) {
				return false;
			} else {
				return true;
			}
		}

		return false;
	}

	/**
	 * Detach image from post.
	 *
	 * @param WP_Post $attachment Attachement post object.
	 * @param WP_Post $sermon Sermon post object.
	 * @return boolean
	 * @since 1.0.0
	 */
	public function detach_image( WP_Post $attachment, WP_Post $sermon ): bool {
		if ( PT::ATTACHEMENT !== $attachment->post_type || PT::SERMON !== $sermon->post_type || defined( 'DRPSM_ATTACHING_IMAGE' ) ) {
			return false;
		}

		if ( 0 === $attachment->post_parent ) {
			return true;
		}

		$attachment->post_parent = 0;
		$result                  = wp_update_post( $attachment );

		if ( $result instanceof WP_Error ) {
			return false;
		} else {
			return true;
		}
	}

	public function get_sermon_image_url($fallback = true, $image_size = 'post-thumbnail', $series_image_primary = false, $post = null){
		if ( null === $post ) {
			global $post;
		}

		/**
		 * Allows to filter the image size.
		 *
		 * @param string|array $image_size           The image size. Default: "post-thumbnail".
		 * @param bool         $fallback             If set to true, it will try to fallback to the secondary option. If series
		 *                                           is primary, it will fallback to sermon image, else if sermon image is
		 *                                           primary, it will fallback to series image - if they exist, of course.
		 * @param bool         $series_image_primary Set series image as primary.
		 * @param WP_Post      $post                 The sermon object.
		 *
		 * @since 2.13.0
		 */
		$image_size = apply_filters( 'get_sermon_image_url_image_size', $image_size, $fallback, $series_image_primary, $post );

		// Get the sermon image.
		$sermon_image = get_the_post_thumbnail_url( $post, $image_size ) ?: null;
		$series_image = null;

		// Get the series image.
		foreach (
			apply_filters( 'sermon-images-get-the-terms', '', array( // phpcs:ignore
				'post_id'    => $post->ID,
				'image_size' => $image_size,
			) ) as $term
		) {
			if ( isset( $term->image_id ) && 0 !== $term->image_id ) {
				$series_image = wp_get_attachment_image_url( $term->image_id, $image_size );

				if ( $series_image ) {
					break;
				}
			}
		}

		// Assign the image, based on function parameters.
		if ( $series_image_primary ) {
			$image = $series_image ?: ( $fallback ? $sermon_image : null );
		} else {
			$image = $sermon_image ?: ( $fallback ? $series_image : null );
		}

		// Use the image, or default image set in options, if nothing found.
		$image = $image ?: \SermonManager::getOption( 'default_image' );

		/**
		 * Allows to filter the image URL.
		 *
		 * @param string       $image                The image URL.
		 * @param bool         $fallback             If set to true, it will try to fallback to the secondary option. If series
		 *                                           is primary, it will fallback to sermon image, else if sermon image is
		 *                                           primary, it will fallback to series image - if they exist, of course.
		 * @param bool         $series_image_primary Set series image as primary.
		 * @param WP_Post      $post                 The sermon object.
		 * @param string|array $image_size           The image size. Default: "post-thumbnail".
		 *
		 * @since 2.13.0
		 * @since 2.15.2 - Added missing $image_size argument, and re-labelled $image to correct description.
		 */
		return apply_filters( 'get_sermon_image_url', $image, $fallback, $series_image_primary, $post, $image_size );

	}
}
