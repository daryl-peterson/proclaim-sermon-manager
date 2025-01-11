<?php
/**
 * Sermon archive meta
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$requirements = array(
	'item_label',
	'item_value',
);

if ( ! isset( $args ) ) {
	return;
}

// phpcs:ignore
extract( $args );

// Check if requirements are met.
foreach ( $requirements as $required_variable ) {
	if ( ! isset( $$required_variable ) ) {
		return;
	}
}

?>
<div class="drppsm-archive-meta-item">
	<div class="drppsm-archive-meta-prefix"><?php echo esc_html( $item_label ); ?></div>
	<div class="drppsm-archive-meta-text">
		<?php render_html( $item_value ); ?>
	</div>
</div>

