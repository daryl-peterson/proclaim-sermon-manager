<?php

/**
 * Sermon archive series meta
 *
 * @package     DRPPSM/Views/Template-Parts
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

if ( ! has_term( '', Tax::PREACHER, $post->ID ) ) {
	return;
}

?>
<div class="drppsm-archive-meta-item drppsm-archive-meta-series">
	<div class="drppsm-archive-meta-prefix"><?php echo ucwords( Settings::get( Settings::PREACHER ) ); ?></div>
	<div class="drppsm-archive-meta-text"><?php echo get_the_term_list( $post->ID, Tax::PREACHER ); ?></div>
</div>


