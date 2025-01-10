<?php
/**
 * Sermon archive image.
 *
 * @package     DRPPSM
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

$image = get_sermon_image_url( true, $args['image_size'], true );

?>

<?php if ( $image ) { ?>
<div class="drppsm-archive-image">

	<a href="<?php the_permalink( $post->id ); ?>">
	<img class="drppsm-archive-image-img" alt="<?php the_title(); ?>" src="<?php echo parse_html_image( $image ); ?>">
	</a>

</div>
<?php } ?>
