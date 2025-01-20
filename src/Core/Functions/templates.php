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

/**
 * Location template file.
 *
 * @param string $name Template name.
 * @param array  $args Argument array for locating templates.
 * @return null|string
 * @since 1.0.0
 */
function locate_partial( string $name, array $args = array() ): ?string {
	return Templates::exec()->locate_partial( $name, $args );
}

/**
 * Render already process html.
 *
 * @param string $html HTML string.
 * @return void
 * @since 1.0.0
 */
function render_html( string $html ): void {
	// phpcs:ignore
	echo $html;
}

/**
 * Get sermon excerpt
 *
 * @param array $args Arguments to pass to template.
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
 * @param array $args Arguments to pass to template.
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
 * @param array $args Arguments to pass to template.
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
 * @param array $args Array of argument to see if taxonomy filtering is disabled.
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
 * Get sermon view count.
 *
 * @param null|int $post_id The post id, if null will use get_the_ID().
 * @return null|int
 * @since 1.0.0
 */
function get_post_view_count( null|int $post_id = null ): int {

	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	if ( false === $post_id ) {
		return 0;
	}

	$key   = 'post_views_count';
	$count = get_post_meta( $post_id, $key, true );

	if ( '' === $count ) {
		$count = 1;
	}
	return $count;
}

/**
 * Set post view count.
 *
 * @return void
 * @since 1.0.0
 */
function set_post_view_count() {
	$key          = 'post_views_count';
	$post_id      = get_the_ID();
	$should_count = Settings::get( Settings::POST_VIEW_COUNT, true );

	if ( ! $should_count || false === $post_id ) {
		return;
	}

	$count = (int) get_post_meta( $post_id, $key, true );
	++$count;
	update_post_meta( $post_id, $key, $count );
}

/**
 * Get image for sermon.

 * @param string           $image_size The image size. Default: "post-thumbnail".
 * @param bool             $fallback If set to true, it will try to fallback to the secondary option. If series\
 *                         is primary, it will fallback to sermon image, else if sermon image is\
 *                         primary, it will fallback to series image - if they exist, of course.
 *
 * @param bool             $series_primary Set series image as primary. Default true.
 * @param null|int|WP_Post $post The post to get image url for.
 * @return null|string
 * @since 1.0.0
 */
function get_sermon_image_url( string $image_size = 'post-thumbnail', bool $fallback = true, bool $series_primary = true, null|int|WP_Post $post = null ): ?string {
	if ( null === $post ) {
		global $post;
	}

	if ( ! isset( $post ) ) {
		return null;
	}

	/**
	 * Allows to filter the override the image size.
	 *
	 * @param string $image_size   The image size. Default: "post-thumbnail".
	 * @param bool $fallback       If set to true, it will try to fallback to the secondary option. If series
	 *                             is primary, it will fallback to sermon image, else if sermon image is
	 *                             primary, it will fallback to series image - if they exist, of course.
	 * @param bool $series_primary Set series image as primary.
	 * @param int|WP_Post $post    The sermon object.
	 *
	 * @category filter
	 * @since 1.0.0
	 */
	$image_size = apply_filters( 'drppsmf_get_sermon_image_size', $image_size, $fallback, $series_primary, $post );

	// Get the sermon image.
	$sermon_image = get_the_post_thumbnail_url( $post, $image_size );

	// Get the series image.
	$series_image = get_series_image( $image_size );

	$image = null;

	// Assign the image, based on function parameters.
	if ( $series_primary && $series_image ) {
		$image = $series_image;
	} elseif ( $fallback && $sermon_image ) {
		$image = $sermon_image;
	}

	if ( ! $series_primary && $sermon_image ) {
		$image = $sermon_image;
	} elseif ( $fallback && $series_image ) {
		$image = $series_image;
	}

	// Use the image, or default image set in options, if nothing found.
	$default = Settings::get( Settings::DEFAULT_IMAGE );

	// Check if there is a default image and nothing else.
	if ( '' !== $default && ! $image ) {
		$image = $default;
	}

	/**
	 * Allows to filter the image URL.
	 *
	 * @param string       $image                The image URL.
	 * @param bool         $fallback             If set to true, it will try to fallback to the secondary option. If series
	 *                                           is primary, it will fallback to sermon image, else if sermon image is
	 *                                           primary, it will fallback to series image - if they exist, of course.
	 * @param bool         $series_primary Set series image as primary.
	 * @param WP_Post      $post                 The sermon object.
	 * @param string|array $image_size           The image size. Default: "post-thumbnail".
	 *
	 * @category filter
	 * @since 1.0.0
	 */
	$image = apply_filters( 'drppsm_get_sermon_image_url', $image, $fallback, $series_primary, $post, $image_size );

	return $image;
}

/**
 * Get series image
 *
 * @param string           $image_size The image size. Default: "post-thumbnail".
 * @param null|int|WP_Post $post The post to get image url for.
 * @return string|null
 * @since 1.0.0
 */
function get_series_image( string $image_size = 'post-thumbnail', null|int|WP_Post $post = null ): ?string {
	if ( null === $post ) {
		global $post;
	}

	if ( ! isset( $post ) ) {
		return null;
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

/**
 * Get the preach image for the current post.
 *
 * @param string $image_size The image size. Default: "post-thumbnail".
 * @return null|string
 */
function get_preacher_image( string $image_size = 'post-thumbnail' ): ?string {
	global $post;

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
 * @param string $taxonomy Taxonomy to get term dropdown for.
 * @param string $default_value The forced default value. See function PHPDoc.
 * @return string
 * @since 1.0.0
 */
function get_term_dropdown( string $taxonomy, string $default_value ): string {

	// Reset var.
	$html = "\n" . PHP_EOL;

	$terms = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false, // todo: add option to disable/enable this globally.
		)
	);

	if ( DRPPSM_TAX_BOOK === $taxonomy && ! Settings::get( Settings::BIBLE_BOOK_SORT, false ) ) {
		// Book order.
		$books = Bible::BOOKS;

		$ordered_terms   = array();
		$unordered_terms = array();

		// Assign every book a number.
		foreach ( $terms as $term ) {
			if ( array_search( $term->name, $books, true ) !== false ) {
				$ordered_terms[ array_search( $term->name, $books, true ) ] = $term;
			} else {
				$unordered_terms[] = $term;
			}
		}

		$terms = array_merge( $ordered_terms, $unordered_terms );
		sort( $terms );
	}

	$current_slug = get_query_var( $taxonomy );

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
	return apply_filters( 'drppsmf_get_term_dropdown', $html, $taxonomy, $default_value, $terms, $current_slug );
}
