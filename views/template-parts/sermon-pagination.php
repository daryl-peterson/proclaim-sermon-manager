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

defined( 'ABSPATH' ) || exit;

Logger::debug( $args );

if ( ! isset( $args['query'] ) || ! isset( $args['post_id'] ) ) {
	Logger::debug( 'HERE 1' );
	return;
}

if ( key_exists( 'disable_pagination', $args ) && 1 === $args['disable_pagination'] ) {
	Logger::debug( 'HERE 2' );
	return;
}


$add_args = array();

$paginate_vars = array(
	's',
	'p',
	'post_type',
	'page_id',
);

foreach ( $paginate_vars as $query_var_name ) {
	$query_var = get_query_var( $query_var_name );
	if ( $query_var ) {
		$add_args[ $query_var_name ] = $query_var;
	}
}
Logger::debug( 'HERE 3' );
?>

<div id="drppsm-sermons-pagination">
	<?php
	render_html(
		paginate_links(
			array(
				'base'     => preg_replace( '/\/\?.*/', '', rtrim( get_permalink( $args['post_id'] ), '/' ) ) . '/%_%',
				'current'  => $args['query']->get( 'paged' ),
				'total'    => $args['query']->max_num_pages,
				'end_size' => 3,
				'add_args' => $add_args,
			)
		)
	);

	// key variable.
	$paged_var = absint( get_query_var( 'paged' ) );
	if ( ! $paged_var ) {
		$paged_var = 1;
	}

	if ( 1 === $paged_var && $args['query']->max_num_pages !== $paged_var ) {
		render_html( ' <a class="next page-numbers" href="' . esc_html( get_permalink( $args['post_id'] ) ) . 'page/' . esc_html( $paged_var + 1 ) . '">Next &raquo;</a>' );
	}
	?>
</div>
