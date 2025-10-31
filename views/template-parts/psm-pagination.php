<?php
/**
 * Pagination template.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

if ( ! isset( $args ) || ! is_array( $args ) ) {
	return;
}

if ( ! has_keys( array( 'current', 'total' ), $args ) ) {
	return;
}

if ( key_exists( 'disable_pagination', $args ) && 1 === $args['disable_pagination'] ) {
	return;
}

$paginate_vars = array(
	'format'   => '?page=%#%',
	'current'  => $args['current'],
	'total'    => $args['total'],
	'end_size' => 3,
);

$paginate_links = paginate_links( $paginate_vars );

if ( ! isset( $paginate_links ) ) {
	return;
}


?>

<div id="drppsm-pagination">
	<?php

	render_html( $paginate_links );

	// key variable.
	$paged_var = absint( get_query_var( 'page' ) );
	if ( ! $paged_var ) {
		$paged_var = 1;
	}

	if ( 1 === $paged_var && $args['total'] !== $paged_var ) {
		// render_html( ' <a class="next page-numbers" href="' . esc_html( get_permalink( $args['post_id'] ) ) . 'page/' . esc_html( $paged_var + 1 ) . '">Next &raquo;</a>' ); .
		$blah = '';
	}

	?>
</div>
