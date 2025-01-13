<?php
/**
 * Series image grid
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Meta;

defined( 'ABSPATH' ) || exit;

if ( ! did_action( 'get_header' ) ) {
	get_header();
}

$template     = str_replace( '.php', '', basename( __FILE__ ) );
$failure      = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';
$requirements = array(
	'terms',
	'image_size',
	'mkey',
);

if ( ! isset( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	render_html( $failure );
	return;
}

foreach ( $requirements as $req ) {
	if ( ! isset( $args[ $req ] ) ) {
		render_html( $failure );
		Logger::error( 'Requirements not met : ' . $req );
		return;
	}
}

get_partial( 'sc-wrapper-start' );
echo '<div id="drppsm-flex-grid">';

$count = 0;
foreach ( $args['terms'] as $item ) {

	$url  = null;
	$meta = get_term_meta( $item->term_id, Meta::SERIES_IMAGE_ID, true );


	if ( ! empty( $meta ) && false !== $meta ) {
		$url = wp_get_attachment_image_url( $meta, $args['image_size'] );
	}
	if ( ! $url ) {
		continue;
	}
	++$count;

	$grid = array(
		'term_id'    => $item->term_id,
		'term_name'  => $item->name,
		'term_tax'   => DRPPSM_TAX_SERIES,
		'image_size' => $args['image_size'],
		'url'        => $url,

	);
	get_partial( 'content-series-grid', $grid );
}
echo '</div>';
get_partial( 'sc-wrapper-end' );
