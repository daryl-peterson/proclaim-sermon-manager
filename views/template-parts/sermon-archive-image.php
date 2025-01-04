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
	$args['image_size'] = 'post-thumbnail';
}

$image = get_sermon_image_url( true, $args['image_size'], true );

?>

<div class="drppsm-archive-image">

	<?php if ( $image ) { ?>

	<a href="<?php the_permalink( $post->id ); ?>">
	<img class="drppsm-archive-image-img" alt="<?php the_title(); ?>" src="<?php echo $image; ?>">
	</a>

	<?php } ?>

</div>

