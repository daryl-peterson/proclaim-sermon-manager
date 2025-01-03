<?php

/**
 * Sermon archive series meta
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$tax_service_type = DRPPSM_TAX_SERVICE_TYPE;

if ( ! has_term( '', $tax_service_type, $post->ID ) ) {
	return;
}

?>

<div class="drppsm-archive-meta-item">
	<div class="drppsm-archive-meta-prefix"><?php echo ucwords( Settings::get( Settings::SERVICE_TYPE ) ); ?></div>
	<div class="drppsm-archive-meta-text"><?php echo get_the_term_list( $post->ID, $tax_service_type ); ?></div>
</div>
