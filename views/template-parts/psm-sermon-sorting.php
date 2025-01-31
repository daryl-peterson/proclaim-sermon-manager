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
// onchange="if(this.options[this.selectedIndex].value !== ''){return this.form.submit()}else{window.location = window.location.href.split('?')[0];}"

$browse = __( 'Browse', 'drppsm' );

$sermons   = get_post_field( DRPPSM_PT_SERMON, 'label' );
$books     = get_taxonomy_field( DRPPSM_TAX_BOOK, 'label' );
$series    = get_taxonomy_field( DRPPSM_TAX_SERIES, 'label' );
$topics    = get_taxonomy_field( DRPPSM_TAX_TOPIC, 'label' );
$preachers = get_taxonomy_field( DRPPSM_TAX_PREACHER, 'label' );

?>


<div id="<?php echo esc_html( $args['id'] ); ?>" class="<?php echo esc_html( $args['classes'] ); ?>">
<div class="drppsm-browse" style="display: inline-block">
	<select
		id="drppsm_browse"
		name="drppsm_browse"
		title="Browse"
		autocomplete="off">
		<option value=""><?php echo esc_html( $browse ); ?></option>
		<option value="drppsm_bible"><?php echo esc_html( $books ); ?></option>
		<option value="drppsm_preacher"><?php echo esc_html( $preachers ); ?></option>
		<option value="drppsm_sermon"><?php echo esc_html( $sermons ); ?></option>
		<option value="drppsm_series"><?php echo esc_html( $series ); ?></option>
		<option value="drppsm_topics"><?php echo esc_html( $topics ); ?></option>
	</select>
</div>

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
		<select
			id="<?php echo esc_attr( $tax_var ); ?>"
			name="<?php echo esc_attr( $tax_var ); ?>"
			title="<?php echo esc_attr( $title_var ); ?>"

			autocomplete="off"
			<?php echo esc_attr( $disabled ); ?>>

			<option value=""><?php echo esc_html( $title_var ); ?></option>
			<?php render_html( $options ); ?>
		</select>
	</div>
	<?php
}
?>
</div>
