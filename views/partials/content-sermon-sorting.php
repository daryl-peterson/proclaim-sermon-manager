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

$failure = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . str_replace( '.php', '', basename( __FILE__ ) ) . '</i>" loaded incorrectly.</p>';
if ( ! isset( $args ) ) {
	echo $failure;
	return;
}

Logger::debug( array( 'ARGS' => $args ) );
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
		return;
	}
}

// No point in continuing if these are set.
if ( $args['visibility'] === 'none' || is_tax_filtering_disabled( $args ) ) {
	return;
}


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
	Logger::debug( $options );

	/*
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
	// echo $html;
	*/
	?>
	<div class="<?php echo $filter['className']; ?>" style="display: inline-block">
		<form action="<?php echo $args['action']; ?>" method="get">
			<select name="<?php echo $filter['taxonomy']; ?>"
					title="<?php echo $filter['title']; ?>"
					id="<?php echo $filter['taxonomy']; ?>"
					onchange="if(this.options[this.selectedIndex].value !== ''){return this.form.submit()}else{window.location = window.location.href.split('?')[0];}"
					autocomplete="off"
				<?php echo ! empty( $args[ $filter['taxonomy'] ] ) && 'disable' === $args['visibility'] ? 'disabled' : ''; ?>>
				<option value=""><?php echo $filter['title']; ?></option>

			</select>
			<?php $series = explode( ',', $args['series_filter'] ); ?>
			<?php if ( isset( $args['series_filter'] ) && '' !== $args['series_filter'] && $series ) : ?>
				<?php if ( $series > 1 ) : ?>
					<?php foreach ( $series as $item ) : ?>
						<input type="hidden" name="wpfc_sermon_series[]" value="<?php echo esc_attr( trim( $item ) ); ?>">
					<?php endforeach; ?>
				<?php else : ?>
					<input type="hidden" name="wpfc_sermon_series" value="<?php echo esc_attr( $series[0] ); ?>">
				<?php endif; ?>
			<?php endif; ?>
			<?php $service_types = explode( ',', $args['service_type_filter'] ); ?>
			<?php if ( isset( $args['service_type_filter'] ) && '' !== $args['service_type_filter'] && $service_types ) : ?>
				<?php if ( $service_types > 1 ) : ?>
					<?php foreach ( $service_types as $service_type ) : ?>
						<input type="hidden" name="wpfc_service_type[]" value="<?php echo esc_attr( trim( $service_type ) ); ?>">
					<?php endforeach; ?>
				<?php else : ?>
					<input type="hidden" name="wpfc_service_type" value="<?php echo esc_attr( $service_types[0] ); ?>">
				<?php endif; ?>
			<?php endif; ?>
			<noscript>
				<div><input type="submit" value="Submit"/></div>
			</noscript>
		</form>
	</div>

<?php } ?>
</div>