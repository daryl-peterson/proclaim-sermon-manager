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

use DRPPSM\Constants\Bible;
use DRPPSM\Constants\Meta;
use WP_Exception;
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

function locate_partial( string $name, array $args = array() ) {
	return Templates::exec()->locate_partial( $name, $args );
}

/**
 * Get sermon excerpt
 *
 * @param array $args
 * @return void
 * @since 1.0.0
 */
function sermon_excerpt( array $args = array() ): void {
	if ( ! isset( $args['image_size'] ) ) {
		$args['image_size'] = ImageSize::SERMON_MEDIUM;
	}

	// Get the partial.
	get_partial( 'content-sermon-archive', $args );
}

/**
 * Sermon single.
 *
 * @param array $args
 * @return void
 * @since 1.0.0
 */
function sermon_single( array $args = array() ): void {

	// Get the partial.
	get_partial( 'content-sermon-single', $args );
}

/**
 * Sermon shorting.
 *
 * @param array $args
 * @since 1.0.0
 */
function sermon_sorting( array $args = array() ): string {
	return SermonSorting::render_sorting( $args );
}

/**
 * Get visibility settings
 *
 * @return array
 * @since 1.0.0
 */
function get_visibility_settings(): array {
	$visibility = array(
		Settings::HIDE_TOPICS,
		Settings::HIDE_SERIES,
		Settings::HIDE_PREACHERS,
		Settings::HIDE_BOOKS,
		Settings::HIDE_SERVICE_TYPES,
		Settings::HIDE_FILTERS,
	);

	$result = array();
	foreach ( $visibility as $option ) {
		$result[ $option ] = filter_var( Settings::get( $option ), FILTER_VALIDATE_BOOLEAN );
	}
	return $result;
}

/**
 * Check if filters is shown for taxonomy.
 *
 * @param array  $args Arguments array.
 * @param string $taxonomy Taxonomy.
 * @return boolean Returns true if filtering for taxonomy is hidden.
 * @since 1.0.0
 */
function is_tax_hidden( array $args, string $taxonomy ): bool {
	$visibility_mapping = DRPPSM_TAX_VISIBILITY_MAP;

	if ( empty( $taxonomy ) ) {
		return true;
	}

	if ( ! key_exists( $taxonomy, $visibility_mapping ) ) {
		return true;
	}

	$map_field = $visibility_mapping[ $taxonomy ];
	if ( ! key_exists( $map_field, $args ) ) {
		return true;
	}

	return filter_var( $args[ $map_field ], FILTER_VALIDATE_BOOLEAN );
}

/**
 * Check if filtering is disabled for taxonomies.
 *
 * @param array $args
 * @return bool
 * @since 1.0.0
 */
function is_tax_filtering_disabled( array $args ): bool {

	if ( ! key_exists( 'hide_filters', $args ) ) {
		return false;
	}

	return filter_var( $args['hide_filters'], FILTER_VALIDATE_BOOLEAN );
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

	if ( empty( $image_size ) ) {
		$image_size = 'post-thumbnail';
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

	return $image;
}


function get_series_image( null|int|WP_Post $post = null, string $image_size = 'post-thumbnail' ): ?string {
	if ( null === $post ) {
		global $post;
	}

	$terms = get_the_terms( $post, DRPPSM_TAX_SERIES );
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


function get_preacher_image( null|int|WP_Post $post = null, string $image_size = 'post-thumbnail' ) {
	if ( null === $post ) {
		global $post;
	}

	$terms = get_the_terms( $post, DRPPSM_TAX_PREACHER );
	$url   = null;

	if ( ! is_array( $terms ) ) {
		return $url;
	}

	foreach ( $terms as $term ) {
		$meta = get_term_meta( $term->term_id, Meta::PREACHER_IMAGE_ID, true );
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


/**
 * Get term dropdown.
 *
 * @param string $taxonomy
 * @param string $default
 * @return string
 * @since 1.0.0
 */
function get_term_dropdown( string $taxonomy, string $default ): string {

	// Reset var.
	$html = "\n" . PHP_EOL;

	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false, // todo: add option to disable/enable this globally.
		)
	);

	if ( DRPPSM_TAX_BIBLE === $taxonomy && Settings::get( Settings::BIBLE_BOOK_SORT, true ) ) {
		// Book order.
		$books = Bible::BOOKS;

		$ordered_terms   = array();
		$unordered_terms = array();

		// Assign every book a number.
		foreach ( $terms as $term ) {
			if ( array_search( $term->name, $books ) !== false ) {
				$ordered_terms[ array_search( $term->name, $books ) ] = $term;
			} else {
				$unordered_terms[] = $term;
			}
		}

		// Order the numbers (books).
		// ksort( $ordered_terms );

		$terms = array_merge( $ordered_terms, $unordered_terms );
		sort( $terms );
	}

	$current_slug = get_query_var( $taxonomy ) ?: ( isset( $_GET[ $taxonomy ] ) ? $_GET[ $taxonomy ] : '' );

	foreach ( $terms as $term ) {

		if ( $current_slug === $term->slug ) {
			$html .= "<option value=\"$term->slug\" selected>$term->name</option>" . PHP_EOL;
		} else {
			$html .= "<option value=\"$term->slug\">$term->name</option>" . PHP_EOL;
		}
	}

	/**
	 * Allows you to filter the dropdown options (HTML).
	 *
	 * @var string $html         The existing HTML.
	 * @var array  $taxonomy     The taxonomy that is being used.
	 * @var string $default      The forced default value. See function PHPDoc.
	 * @var array  $terms        The array of terms, books will already be ordered.
	 * @var string $current_slug The term that is being requested.
	 *
	 * @category filter
	 * @since 1.0.0
	 */
	return apply_filters( 'drppsmf_get_term_dropdown', $html, $taxonomy, $default, $terms, $current_slug );
}
