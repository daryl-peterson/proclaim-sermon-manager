<?php
/**
 * Sermon Single Image
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;


$image     = get_sermon_image_url( true, ImageSize::SERMON_WIDE, true );
$image_div = '';

if ( ! $image ) {
	return '';
}



$image_div = <<<HTML
	<div class="drppsm-sermon-single-image">
		<img class="drppsm-sermon-single-image-img" alt="$title" src="$image" style="width:100%;">
	</div>
HTML;

return $image_div;
