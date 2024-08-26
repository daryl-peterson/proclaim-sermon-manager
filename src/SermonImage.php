<?php

namespace DRPPSM;

/**
 * Class description
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 *
 *
 */
class SermonImage {

	/**
 * Returns sermon/series image URL.
 *
 * @param bool         $fallback             If set to true, it will try to fallback to the secondary option. If series
 *                                           is primary, it will fallback to sermon image, else if sermon image is
 *                                           primary, it will fallback to series image - if they exist, of course.
 * @param string|array $image_size           The image size. Defaults to "post-thumbnail".
 * @param bool         $series_image_primary Set series image as primary.
 * @param WP_Post      $post                 The sermon object, unless it's defined via global $post.
 *
 * @return string Image URL or empty string.
 *
 * @since 2.12.0
 */
public function get_sermon_image_url( $fallback = true, $image_size = 'post-thumbnail', $series_image_primary = false, $post = null ) {
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