<?php
/**
 * Sermon sorting template.
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) or exit;

$template = str_replace( '.php', '', basename( __FILE__ ) );
$failure  = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';
if ( ! isset( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	echo $failure;
	return;
}

// phpcs:ignore
extract( $args );


$requirements = array(
	'action',
	'filters',
	'visibility_mapping',
	'args',
);
$hide_values  = array( 'yes', 'hide', 1, '1', true, 'on' );


// Check if requirements are met.
foreach ( $requirements as $required_variable ) {
	if ( ! isset( $$required_variable ) ) {
		echo $failure;
		Logger::error( 'Requirements not met : ' . $required_variable );
		return;
	}
}

// No point in continuing if these are set.
if ( $args['visibility'] === 'none' || is_tax_filtering_disabled( $args ) ) {
	return;
}

global $wp_query;
$vars = $wp_query->query_vars;
Logger::debug( array( 'QUERY VARS' => $vars ) );

?>

<div id="<?php echo $args['id']; ?>" class="<?php echo $args['classes']; ?>">
<?php

foreach ( $filters as $filter ) {

	if ( ! isset( $filter['taxonomy'] ) ) {
		continue;
	}

	if ( is_tax_hidden( $args, $filter['taxonomy'] ) ) {
		continue;
	}


	$taxonomy   = $filter['taxonomy'];
	$class_name = $filter['className'];
	$action     = $args['action'];
	$title      = $filter['title'];
	$disabled   = ! empty( $args[ $filter['taxonomy'] ] ) && 'disable' === $args['visibility'] ? 'disabled' : '';
	$options    = get_term_dropdown( $taxonomy, '' );

	$html = <<<HTML
	<div class="$class_name" style="display: inline-block">
		<form action="$action" method="get">
			<select
				id="$taxonomy"
				name="$taxonomy"
				title="$title"
				onchange="if(this.options[this.selectedIndex].value !== ''){return this.form.submit()}else{window.location = window.location.href.split('?')[0];}"
				autocomplete="off"
				$disabled>

				<option value="">$title</option>
				$options
			</select>
		</form>
	</div>
	HTML;
	echo $html;
}
?>
</div>