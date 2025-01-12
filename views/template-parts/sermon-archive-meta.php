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

if ( ! isset( $args ) || ! is_array( $args ) ) {
	return;
}

// Check if requirements are met.
foreach ( $requirements as $req ) {
	if ( ! isset( $argsp[ $req ] ) ) {
		return;
	}
}

?>
<div class="drppsm-archive-meta-item">
	<div class="drppsm-archive-meta-prefix"><?php echo esc_html( $item_label['item_label'] ); ?></div>
	<div class="drppsm-archive-meta-text">
		<?php render_html( $args['item_value'] ); ?>
	</div>
</div>

