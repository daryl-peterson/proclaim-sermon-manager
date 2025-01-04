<?php

/**
 * Sermon archive content
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) or exit;

$post_class = esc_attr( implode( ' ', get_post_class( 'drppsm-archive-article', $post ) ) );
?>

<div id="post-<?php the_ID(); ?>" class="<?php echo $post_class; ?>">

	<div class="drppsm-archive-inner">

<?php
get_partial( 'sermon-archive-image', $args );
?>

		<div class="drppsm-archive-main">

<?php
get_partial( 'sermon-archive-meta-title', $args );
get_partial( 'sermon-archive-meta-date', $args );
get_partial( 'sermon-archive-meta-series', $args );
get_partial( 'sermon-archive-meta-preacher', $args );
get_partial( 'sermon-archive-meta-service-type', $args );
?>

		</div>
	</div>
</div>