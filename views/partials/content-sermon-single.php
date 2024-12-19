<?php
/**
 * Sermon Single
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

global $post;

Logger::debug( array( 'POST' => $post ) );

$post_id    = 'post-' . get_the_ID();
$post_class = esc_attr( implode( ' ', get_post_class( '', $post ) ) );
$title      = the_title( '', '', false );

$inc               = DRPPSM_PATH . 'views/includes/';
$image_div         = require $inc . 'sermon-single-image.php';
$meta_preacher     = require $inc . 'sermon-single-meta-preacher.php';
$meta_series       = require $inc . 'sermon-single-meta-series.php';
$meta_service_type = require $inc . 'sermon-single-meta-service-type.php';

$content = <<<HTML
<article id="$post_id" class="$post_class">
	<div class="drppsm-sermon-single-inner">
		$image_div
	</div>
	<div class="drppsm-sermon-single-main">
		<div class="drppsm-sermon-single-header">


		</div>
		<h2 class="drppsm-sermon-single-title">$title</h2>
		<div class="drppsm-sermon-single-meta">
			$meta_preacher
			$meta_series
			$meta_service_type
		</div>
	</div>
</article>
HTML;

echo $content;
