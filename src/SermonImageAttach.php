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
use DRPPSM\Traits\ExecutableTrait;
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
	use ExecutableTrait;

	/**
	 * Post type.
	 *
	 * @var string
	 */
	private string $pt = DRPPSM_PT_SERMON;

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

			// unhook this function so it doesn't loop infinitely
			remove_action( 'save_post', array( $this, 'save_post' ) );

			$status = array();
			if ( $this->pt !== $post->post_type || defined( 'DRPSM_SAVING_IMAGES' ) ) {
				return $status;
			}

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

		// re-hook this function.
		add_action( 'save_post', array( $this, 'save_post' ), 50, 3 );
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
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
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

		$term = $this->get_series_term( $sermon );
		if ( ! $term instanceof WP_Term ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}

		$image_id = get_term_meta( $term->term_id, TaxMeta::SERIES_IMAGE_ID, true );
		if ( ! isset( $image_id ) || empty( $image_id ) ) {
			return false;
		}

		$attachment = get_post( (int) $image_id );
		if ( ! $attachment instanceof WP_Post ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
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

		if ( ! $this->is_valid_params( $attachment, $sermon ) ) {
			return false;
		}

		// Check if image is already attached.
		if (
			$attachment->post_parent === $sermon->ID ||
			0 !== $attachment->post_parent
		) {
			return true;
		}

		$attachment->post_parent = $sermon->ID;
		$args                    = array(
			'ID'          => $attachment->ID,
			'post_parent' => $sermon->ID,
		);

		$result = wp_update_post( $args, true, false );

		if ( is_wp_error( $result ) ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		} else {
			return true;
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

		if ( ! $this->is_valid_params( $attachment, $sermon ) ) {
			return false;
		}

		// Check if image is already detached.
		if ( 0 === $attachment->post_parent ) {
			return true;
		}

		$args = array(
			'ID'          => $attachment->ID,
			'post_parent' => 0,
		);

		$result = wp_update_post( $args, true, false );

		if ( is_wp_error( $result ) ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		} else {
			return true;
		}
	}

	/**
	 * Check if parameters are valid.
	 *
	 * @param WP_Post $attachment Attachment post object.
	 * @param WP_Post $sermon Sermon post object.
	 * @return boolean
	 * @since 1.0.0
	 */
	private function is_valid_params( WP_Post $attachment, WP_Post $sermon ): bool {
		if (
			'attachment' !== $attachment->post_type ||
			$this->pt !== $sermon->post_type
		) {
			return false;
		}
		return true;
	}

	/**
	 * Get series term.
	 *
	 * @param WP_Post $sermon Sermon post object.
	 * @return WP_Term|null
	 * @since 1.0.0
	 */
	private function get_series_term( WP_Post $sermon ): ?WP_Term {
		$term = get_the_terms( $sermon, DRPPSM_TAX_SERIES );
		if (
			is_wp_error( $term ) ||
			! is_array( $term ) ||
			0 === count( $term )
		) {
			// @codeCoverageIgnoreStart
			return null;
			// @codeCoverageIgnoreEnd
		}

		$term = array_shift( $term );
		return $term;
	}
}
