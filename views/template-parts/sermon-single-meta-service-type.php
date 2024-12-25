<?php

/**
 * Sermon single meta service type
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

if ( ! has_term( '', Tax::SERVICE_TYPE, $post->ID ) ) {
	return '';
}

$label_service_type = Tax::get_label( Tax::SERVICE_TYPE );
$service_type       = get_the_term_list( $post->ID, Tax::SERVICE_TYPE );

$meta_service_type = <<<HTML
	<div class="drppsm-sermon-single-meta-item drppsm-sermon-single-meta-service">
		<span class="drppsm-sermon-single-meta-prefix">$label_service_type:</span>
		<span class="drppsm-sermon-single-meta-text">$service_type</span>
	</div>
HTML;

echo $meta_service_type;
