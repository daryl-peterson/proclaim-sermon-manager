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

$post_class = esc_attr( implode( ' ', get_post_class( 'drppsm-single-article', $post ) ) );

$args['image_size'] = 'full';
$tax_series         = DRPPSM_TAX_SERIES;
$tax_preacher       = DRPPSM_TAX_PREACHER;
$tax_stype          = DRPPSM_TAX_SERVICE_TYPE;

?>

<article id="sermon-single-id-<?php the_ID(); ?>" class="<?php echo $post_class; ?>">
	<div class="drppsm-single-inner">
		<?php
		get_partial( 'sermon-single-image', $args );
		?>
		<div class="drppsm-single-main">

			<?php
			// Get date meta.
			$args_date = array(
				'meta_label' => __( 'Date', 'drppsm' ),
				'meta_value' => get_the_date(),
			);
			get_partial( 'sermon-single-meta', $args_date );

			// Get series meta.
			if ( has_term( '', $tax_series, $post->ID ) ) {
				$args_series = array(
					'meta_label' => ucwords( Settings::get( Settings::SERIES ) ),
					'meta_value' => get_the_term_list( $post->ID, $tax_series ),
				);

				get_partial( 'sermon-single-meta', $args_series );
			}

			// Get preacher meta.
			if ( has_term( '', $tax_preacher, $post->ID ) ) {
				$args_preacher = array(
					'meta_label' => ucwords( Settings::get( Settings::PREACHER ) ),
					'meta_value' => get_the_term_list( $post->ID, $tax_preacher ),
				);

				get_partial( 'sermon-single-meta', $args_preacher );
			}

			// Get service type meta.
			if ( has_term( '', $tax_stype, $post->ID ) ) {
				$args_stype = array(
					'meta_label' => ucwords( Settings::get( Settings::SERVICE_TYPE ) ),
					'meta_value' => get_the_term_list( $post->ID, $tax_stype ),
				);

				get_partial( 'sermon-single-meta', $args_stype );
			}

			$args_views = array(
				'meta_label' => __( 'Views', 'drppsm' ),
				'meta_value' => get_sermon_view_count( (int) $post->ID ),
			);
			get_partial( 'sermon-single-meta', $args_views );

			?>

		</div>
	</div>
</article>

