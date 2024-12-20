<?php
/**
 * Template functions.
 *
 * @package     DRPSM/Functions/Templates
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Meta;
use DRPPSM\Constants\Tax;
use WP_Post;

defined( 'ABSPATH' ) || exit;


/**
 * Get sermon image
 *
 * @param bool             $fallback
 * @param string           $image_size
 * @param bool             $series_image_primary
 * @param null|int|WP_Post $post
 * @return null|string
 * @since 1.0.0
 */
function get_sermon_image_url( bool $fallback = true, string $image_size = 'post-thumbnail', bool $series_image_primary = false, null|int|WP_Post $post = null ): ?string {
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
	 * @since 1.0.0
	 */
	$image_size = apply_filters( 'get_sermon_image_url_image_size', $image_size, $fallback, $series_image_primary, $post );

	// Get the sermon image.
	$sermon_image = get_the_post_thumbnail_url( $post, $image_size ) ?: null;
	$series_image = null;

	$args = array(
		'post_id'    => $post->ID,
		'image_size' => $image_size,
	);

	// Get the series image.
	$series_image = get_series_image( $post, $image_size );
	Logger::debug( array( 'SERIES' => $series_image ) );

	// Assign the image, based on function parameters.
	if ( $series_image_primary ) {
		$image = $series_image ?: ( $fallback ? $sermon_image : null );
	} else {
		$image = $sermon_image ?: ( $fallback ? $series_image : null );
	}

	// Use the image, or default image set in options, if nothing found.
	// $image = $image ?: \SermonManager::getOption( 'default_image' );

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
	$result = apply_filters( 'get_sermon_image_url', $image, $fallback, $series_image_primary, $post, $image_size );
	if ( is_string( $result ) ) {
		return $result;
	}
	return null;
}

function get_sermon_image( null|WP_Post $post = null, string $image_size = 'post-thumbnail' ) {
	if ( null === $post ) {
		global $post;

	}

	$image_size = apply_filters( DRPPSM_FLTR_SERMON_IMAGE_SIZE, $image_size, $post );

	$sermon_image = get_the_post_thumbnail_url( $post, $image_size ) ?: null;
	$series_image = get_series_image( $post );
}


function get_series_image( null|int|WP_Post $post = null, string $image_size = 'post-thumbnail' ): ?string {
	if ( null === $post ) {
		global $post;
	}

	$terms = get_the_terms( $post, Tax::SERIES );
	$url   = null;

	foreach ( $terms as $term ) {
		$meta = get_term_meta( $term->term_id, Meta::SERIES_IMAGE_ID, true );
		$url  = null;
		if ( ! empty( $meta ) && false !== $meta ) {
			$url = wp_get_attachment_image_url( $meta, $image_size );
		}
		if ( $url ) {
			break;
		}
	}

	Logger::debug(
		array(
			'URL'   => $url,
			'TERMS' => $terms,
		)
	);
	return $url;
}
