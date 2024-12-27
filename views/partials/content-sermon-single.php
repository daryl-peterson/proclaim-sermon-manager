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
$post_class = esc_attr( implode( ' ', get_post_class( 'drppsm-single-article', $post ) ) );

?>

<article id="post-<?php the_ID(); ?>" class="<?php echo $post_class; ?>">
	<div class="drppsm-single-inner">
		<?php
			get_partial( 'sermon-single-image' );
		?>
		<div class="drppsm-single-main">
			<?php
				get_partial( 'sermon-single-meta-title' );
				get_partial( 'sermon-single-meta-date' );
				get_partial( 'sermon-single-meta-series' );
				get_partial( 'sermon-single-meta-preacher' );
				get_partial( 'sermon-single-meta-views' );
			?>
		</div>
	</div>
</article>

