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

defined( 'ABSPATH' ) || exit;

$template = str_replace( '.php', '', basename( __FILE__ ) );
$failure  = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';
if ( ! isset( $args ) || ! is_array( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	render_html( $failure );
	return;
}

$requirements = array(
	'action',
	'filters',
	'visibility_mapping',
	'args',
);
$hide_values  = array( 'yes', 'hide', 1, '1', true, 'on' );

foreach ( $requirements as $req ) {
	if ( ! key_exists( $req, $args ) ) {
		render_html( $failure );
		Logger::error( 'Requirements not met : ' . $required_variable );
		return;
	}
	${$req} = $args[ $req ];
}

// No point in continuing if these are set.
if ( 'none' === $args['visibility'] || is_tax_filtering_disabled( $args ) ) {
	return;
}

?>

<div id="<?php echo esc_html( $args['id'] ); ?>" class="<?php echo esc_html( $args['classes'] ); ?>">
<?php

foreach ( $filters as $filter ) {

	if ( ! isset( $filter['taxonomy'] ) ) {
		continue;
	}

	if ( is_tax_hidden( $args, $filter['taxonomy'] ) ) {
		continue;
	}


	$tax_var    = $filter['taxonomy'];
	$class_name = $filter['className'];
	$act        = $args['action'];
	$title_var  = $filter['title'];
	$disabled   = ! empty( $args[ $filter['taxonomy'] ] ) && 'disable' === $args['visibility'] ? 'disabled' : '';
	$options    = get_term_dropdown( $tax_var, '' );
	?>
	<div class="<?php echo esc_attr( $class_name ); ?>" style="display: inline-block">
		<form action="<?php echo esc_attr( $act ); ?>" method="get">
			<select
				id="<?php echo esc_attr( $tax_var ); ?>"
				name="<?php echo esc_attr( $tax_var ); ?>"
				title="<?php echo esc_attr( $title_var ); ?>"
				onchange="if(this.options[this.selectedIndex].value !== ''){return this.form.submit()}else{window.location = window.location.href.split('?')[0];}"
				autocomplete="off"
				<?php echo esc_attr( $disabled ); ?>>

				<option value=""><?php esc_html( $title_var ); ?></option>
				<?php render_html( $options ); ?>
			</select>
		</form>
	</div>
	<?php
}
?>
</div>