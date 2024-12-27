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
use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Get partial template.
 *
 * - `/wp-contents/themes/<theme_name>/partials/<partial_name>.php`
 * - `/wp-contents/themes/<theme_name>/template-parts/<partial_name>.php`
 * - `/wp-contents/themes/<theme_name>/<partial_name>.php`
 *
 * @param string $name File name.
 * @param array  $args Array of variables to pass to template.
 * @return void
 * @since 1.0.0
 */
function get_partial( string $name, array $args = array() ) {
	Templates::exec()->get_partial( $name, $args );
}

/**
 * Get sermon excerpt
 *
 * @param array $args
 * @return void
 * @since 1.0.0
 */
function sermon_excerpt( $args = array() ): void {
	Templates::exec()->sermon_excerpt( $args );
}

/**
 * Get sermon single.
 *
 * @param null|WP_Post $post_new Post object.
 * @return void
 * @since 1.0.0
 */
function sermon_single( ?WP_Post $post_new = null ): void {
	Templates::exec()->sermon_single( $post_new );
}

/**
 * Get sermon view count
 *
 * @param int  $post_id
 * @param bool $update
 * @return int
 * @since 1.0.0
 */
function get_sermon_view_count( int $post_id, bool $update = false ): int {
	$key   = 'Views';
	$count = get_post_meta( $post_id, $key, true );
	if ( $count == '' ) {
		$count = 1;
		delete_post_meta( $post_id, $key );
		add_post_meta( $post_id, $key, $count );
	}
	return $count;
}

/**
 * Build html option elements for select element.
 *
 * @param string $taxonomy
 * @param string $default
 * @return string
 * @since 1.0.0
 */
function get_term_dropdown( string $taxonomy, string $default = '' ): string {

	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'orderby'    => 'taxonomy',
			'order'      => 'ASC',
			'hide_empty' => false,
		)
	);

	$current_slug = get_query_var( $taxonomy ) ?: ( isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '' );
	$label        = Tax::get_label( $taxonomy );
	$html         = PHP_EOL;
	$html        .= "<option value=\"$label\">$label</option>" . PHP_EOL;

	foreach ( $terms as $term ) {
		$selected = ( ( '' === $default ? $current_slug === $term->slug : $default === $term->slug ) ? ' selected' : '' );
		$html    .= "<option value=\"$term->slug\"$selected>$term->name</option>" . PHP_EOL;

	}

	Logger::debug(
		array(
			'LABEL'        => $label,
			'TAXONOMY'     => $taxonomy,
			'TERMS'        => $terms,
			'CURRENT SLUG' => $current_slug,
			'HTML'         => $html,
		)
	);

	/**
	 * Allows you to filter the dropdown options (HTML).
	 *
	 * @var string $html         The existing HTML.
	 * @var array  $taxonomy     The taxonomy that is being used.
	 * @var string $default      The forced default value. See function PHPDoc.
	 * @var array  $terms        The array of terms, books will already be ordered.
	 * @var string $current_slug The term that is being requested.
	 *
	 * @since 1.0.0
	 */
	return apply_filters( 'drppsm_get_term_dropdown', $html, $taxonomy, $default, $terms, $current_slug );
}

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
	/*
	if ( is_string( $result ) ) {
		return $result;
	}
	*/

	if ( ! $image ) {
		$image = DRPPSM_URL . 'assets/images/spacer.png';
	}
	Logger::debug( array( 'IMAGE' => $image ) );
	return $image;
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

	if ( ! is_array( $terms ) ) {
		return $url;
	}

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

	return $url;
}
