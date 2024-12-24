<?php

/**
 * Sermon Single Meta Preacher
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

$label_preacher = Tax::get_label( Tax::PREACHER );
$preacher       = get_the_term_list( $post->ID, Tax::PREACHER );
$meta_preacher  = '';

if ( has_term( '', Tax::PREACHER, $post->ID ) ) {
	$meta_preacher = <<<HTML
	<div class="drppsm-single-meta-item drppsm-single-meta-preacher">
		<span class="drppsm-sermon-single-meta-prefix">$label_preacher</span>
		<span class="drppsm-sermon-single-meta-text">$preacher</span>
	</div>
HTML;
}

return $meta_preacher;
