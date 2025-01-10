<?php
// phpcs:ignoreFile
/**
 * Sermon archive content
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 */

namespace DRPPSM;

defined( 'ABSPATH' ) or exit;

$post_class   = esc_attr( implode( ' ', get_post_class( 'drppsm-archive-article', $post ) ) );
$tax_series   = DRPPSM_TAX_SERIES;
$tax_preacher = DRPPSM_TAX_PREACHER;
$tax_stype    = DRPPSM_TAX_SERVICE_TYPE;

?>

<div id="sermon-archive-id-<?php the_ID(); ?>" class="<?php echo $post_class; ?>">

	<div class="drppsm-archive-inner">

		<?php
		get_partial( 'sermon-archive-image', $args );
		?>

		<div class="drppsm-archive-main">
			<?php
			get_partial( 'sermon-archive-title' );

			// Get date meta.
			$args_date = array(
				'item_label' => __( 'Date', 'drppsm' ),
				'item_value' => get_the_date(),
			);
			get_partial( 'sermon-archive-meta', $args_date );

			// Get series meta.
			if ( has_term( '', $tax_series, $post->ID ) ) {
				$args_series = array(
					'item_label' => ucwords( Settings::get( Settings::SERIES ) ),
					'item_value' => get_the_term_list( $post->ID, $tax_series ),
				);

				get_partial( 'sermon-archive-meta', $args_series );
			}

			// Get preacher meta.
			if ( has_term( '', $tax_preacher, $post->ID ) ) {
				$args_preacher = array(
					'item_label' => ucwords( Settings::get( Settings::PREACHER ) ),
					'item_value' => get_the_term_list( $post->ID, $tax_preacher ),
				);

				get_partial( 'sermon-archive-meta', $args_preacher );
			}

			// Get service type meta.
			if ( has_term( '', $tax_stype, $post->ID ) ) {
				$args_stype = array(
					'item_label' => ucwords( Settings::get( Settings::SERVICE_TYPE ) ),
					'item_value' => get_the_term_list( $post->ID, $tax_stype ),
				);

				get_partial( 'sermon-archive-meta', $args_stype );
			}
			?>

		</div>
	</div>
</div>