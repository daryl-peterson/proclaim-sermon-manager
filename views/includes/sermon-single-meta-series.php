<?php

/**
 * Sermon Single Meta Series
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Tax;

if ( ! has_term( '', Tax::SERIES, $post->ID ) ) {
	return '';
}

$series       = get_the_term_list( $post->ID, Tax::SERIES );
$label_series = __( 'Series', 'drppsm' );
$meta_series  = <<<HTML
	<div class="drppsm-sermon-single-meta-item drppsm-sermon-single-meta-series">
		<span class="drppsm-sermon-single-meta-prefix">$label_series:</span>
		<span class="drppsm-sermon-single-meta-text">$series</span>
	</div>
HTML;

return $meta_series;
