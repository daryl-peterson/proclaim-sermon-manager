<?php
/**
 * Sermon Images.
 *
 * @package     DRPPSM\SermonImageAttach
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Post;
use WP_Term;

/**
 * Sermon Images.
 *
 * @package     DRPPSM\SermonImageAttach
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonImageAttach implements Executable, Registrable {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	private string $pt = DRPPSM_PT_SERMON;

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
		if ( ! is_admin() || has_action( 'save_post', array( $this, 'save_post' ) ) ) {
			return false;
		}
		add_action( 'save_post', array( $this, 'save_post' ), 50, 3 );
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
	public function save_post(
		int $post_id,
		WP_Post $post,
		bool $update
	): array {
		try {

			$status = array();
			if ( $this->pt !== $post->post_type || defined( 'DRPSM_SAVING_IMAGES' ) ) {
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
		if ( $this->pt !== $sermon->post_type ) {
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
		if ( $this->pt !== $sermon->post_type ) {
			return false;
		}

		$term = get_the_terms( $sermon, DRPPSM_TAX_SERIES );
		if ( ! is_array( $term ) ) {
			return false;
		}

		$term = array_shift( $term );
		if ( ! $term instanceof WP_Term ) {
			return false;
		}

		$series_id = get_term_meta( $term->term_id, TaxMeta::SERIES_IMAGE_ID, true );
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
	 */
	public function attach_image(
		WP_Post $attachment,
		WP_Post $sermon
	): bool {

		if (
			'attachment' !== $attachment->post_type ||
			$this->pt !== $sermon->post_type ||
			defined( 'DRPSM_ATTACHING_IMAGE' )
		) {
			return false;
		}
		define( 'DRPSM_ATTACHING_IMAGE', true );

		if ( $attachment->post_parent === $sermon->ID ) {
			return true;
		}

		if ( 0 === $attachment->post_parent ) {
			$attachment->post_parent = $sermon->ID;

			$result = wp_update_post( $attachment );

			if ( is_wp_error( $result ) ) {
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
	public function detach_image(
		WP_Post $attachment,
		WP_Post $sermon
	): bool {
		if (
			'attachment' !== $attachment->post_type ||
			$this->pt !== $sermon->post_type ||
			defined( 'DRPSM_ATTACHING_IMAGE' )
		) {
			return false;
		}

		if ( 0 === $attachment->post_parent ) {
			return true;
		}

		$attachment->post_parent = 0;

		$result = wp_update_post( $attachment );

		if ( is_wp_error( $result ) ) {
			return false;
		} else {
			return true;
		}
	}
}
