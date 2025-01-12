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

if ( ! isset( $args['image_size'] ) ) {
	$args['image_size'] = ImageSize::SERMON_WIDE;
}

$image = get_sermon_image_url( $args['image_size'] );

?>

<div class="drppsm-single-image">
	<?php if ( $image ) { ?>

	<a href="<?php the_permalink( $post->id ); ?>">
	<img class="drppsm-single-image-img" alt="<?php the_title(); ?>" src="<?php echo esc_html( $image ); ?>">
	</a>

	<?php } ?>
</div>