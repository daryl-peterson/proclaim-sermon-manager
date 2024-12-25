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
$image = get_sermon_image_url( true, ImageSize::SERMON_MEDIUM, true );

?>

<div class="drppsm-single-image">
	<?php if ( $image ) { ?>
		<img class="drppsm-single-image-img" alt="<?php the_title(); ?>" src="<?php echo $image; ?>">
	<?php } ?>
</div>