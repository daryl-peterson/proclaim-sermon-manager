<?php
namespace DRPPSM;

use DRPPSM\Constants\Tax;

/**
 * Sermon wrapper start.
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

global $post;

$post_id    = 'post-' . get_the_ID();
$post_class = esc_attr( implode( ' ', get_post_class( '', $post ) ) );
$title      = the_title( '', '', false );

$image     = get_sermon_image_url( true, ImageSize::SERMON_WIDE, true );
$image_div = '';


Logger::debug( array( 'image' => $image ) );

if ( $image ) {
	$image_div = <<<HTML

	<div class="drppsm-sermon-single-image">
		<img class="drppsm-sermon-single-image-img" alt="$title" src="$image" style="width:100%;">
	</div>
HTML;

}

$preacher_label = get_option_general( Tax::PREACHER );


Logger::debug( array( 'PREACHER LABEL' => $preacher_label ) );
/*
if ( has_term( '', 'wpfc_preacher', $post->ID ) ) : ?>
	<div class="wpfc-sermon-single-meta-item wpfc-sermon-single-meta-preacher <?php echo \SermonManager::getOption( 'preacher_label', '' ) ? 'custom-label' : ''; ?>">
		<span class="wpfc-sermon-single-meta-prefix"><?php echo sm_get_taxonomy_field( 'wpfc_preacher', 'singular_name' ) . ':'; ?></span>
		<span class="wpfc-sermon-single-meta-text"><?php the_terms( $post->ID, 'wpfc_preacher' ); ?></span>
	</div>
}
*/

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


		</div>
	</div>
</article>
HTML;

echo $content;
