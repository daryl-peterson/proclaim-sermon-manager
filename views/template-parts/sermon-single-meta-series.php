<?php

/**
 * Sermon single series meta
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

if ( ! has_term( '', Tax::SERIES, $post->ID ) ) {
	return;
}

?>
<div class="drppsm-single-meta-item">
	<div class="drppsm-single-meta-prefix"><?php echo Tax::get_label( Tax::SERIES ); ?></div>
	<div class="drppsm-single-meta-text"><?php echo get_the_term_list( $post->ID, Tax::SERIES ); ?></div>
</div>
