<?php

/**
 * Sermon archive meta
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
	'meta_label',
	'meta_value',
);

if ( ! isset( $args ) ) {
	return;
}

extract( $args );

// Check if requirements are met.
foreach ( $requirements as $required_variable ) {
	if ( ! isset( $$required_variable ) ) {
		return;
	}
}

?>
<div class="drppsm-archive-meta-item">
	<div class="drppsm-archive-meta-prefix"><?php echo $meta_label; ?></div>
	<div class="drppsm-archive-meta-text">
		<?php echo $meta_value; ?>
	</div>
</div>

