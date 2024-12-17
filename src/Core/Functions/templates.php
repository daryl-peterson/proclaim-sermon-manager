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
 * Get partial template.
 * - This is a stub function to Templates class.
 *
 * `/wp-contents/themes/<theme_name>/partials/<partial_name>.php`\
 * `/wp-contents/themes/<theme_name>/template-parts/<partial_name>.php`\
 * `/wp-contents/themes/<theme_name>/<partial_name>.php`
 *
 * @param string $name File name.
 * @param array  $args Array of variables to pass to template.
 * @return void
 * @since 1.0.0
 */
function get_partial( string $name, array $args = array() ): void {
	Templates::exec()->get_partial( $name, $args );
}

/**
 * Get partial template.
 * - This is a stub function to Templates class.
 *
 * `/wp-contents/themes/<theme_name>/partials/<partial_name>.php`\
 * `/wp-contents/themes/<theme_name>/template-parts/<partial_name>.php`\
 * `/wp-contents/themes/<theme_name>/<partial_name>.php`
 *
 * @param string $name Piece name.
 * @param array  $args Array of variables to pass to filters.
 * @return void
 * @since 1.0.0
 */
function get_template_piece( string $name, array $args = array() ) {
	Templates::exec()->get_template_piece( $name, $args );
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
