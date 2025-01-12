<?php
/**
 * Sermon pagination template.
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Error;

defined( 'ABSPATH' ) || exit;

if ( ! isset( $args['query'] ) || ! isset( $args['post_id'] ) ) {
	return;
}

if ( key_exists( 'disable_pagination', $args ) && 1 === $args['disable_pagination'] ) {
	return;
}


$add_args = array();

$paginate_vars = array(
	's',
	'p',
	'post_type',
	'page_id',
);

try {
	foreach ( $paginate_vars as $query_var_name ) {
		$query_var = get_query_var( $query_var_name );
		if ( $query_var ) {
			$add_args[ $query_var_name ] = $query_var;
		}
	}
	?>

<div id="drppsm-sermons-pagination">
	<?php
	echo paginate_links(
		array(
			'base'     => preg_replace( '/\/\?.*/', '', rtrim( get_permalink( $args['post_id'] ), '/' ) ) . '/%_%',
			'current'  => $args['query']->get( 'paged' ),
			'total'    => $args['query']->max_num_pages,
			'end_size' => 3,
			'add_args' => $add_args,
		)
	);

	// key variable.
	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;

	if ( $args['query']->max_num_pages != $paged && $paged == 1 ) {

		echo ' <a class="next page-numbers" href="' . get_permalink( $args['post_id'] ) . 'page/' . ( $paged + 1 ) . '">Next &raquo;</a>';

	}
	?>
</div>

	<?php
} catch ( \Throwable | WP_Error $th ) {
	Logger::error(
		array(
			'ERROR' => $th->getMessage(),
			'TRACE' => $th->getTrace(),
		)
	);
	return;
}