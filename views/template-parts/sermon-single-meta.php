<?php
/**
 * Sermon single meta
 *
 * @package     DRPPSM/Views/Partials
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

if ( ! isset( $args ) || ! is_array( $args ) ) {
	return;
}

// Check if requirements are met.
foreach ( $requirements as $req ) {
	if ( ! isset( $args[ $req ] ) ) {
		return;
	}
}

?>
<div class="drppsm-single-meta-item">
	<div class="drppsm-single-meta-prefix"><?php echo esc_html( $args['item_label'] ); ?></div>
	<div class="drppsm-single-meta-text">
		<?php echo $args['item_value']; ?>
	</div>
</div>
