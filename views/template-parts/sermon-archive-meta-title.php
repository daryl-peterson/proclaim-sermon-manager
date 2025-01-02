<?php

/**
 * Sermon archive series meta
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

?>

<div class="drppsm-archive-meta-item">
	<div class="drppsm-archive-title-prefix"></div>
	<div class="drppsm-archive-title-text">
		<a href="<?php the_permalink( $post->id ); ?>" class="drppsm-archive-post-title">
			<?php
			the_title( '', '' );
			?>

		</a>
	</div>
</div>






