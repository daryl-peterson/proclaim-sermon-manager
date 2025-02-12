<?php
/**
 * Tax base shortcode.
 *
 * @package     DRPPSM\ShortCodes\TaxShortcode
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\Registrable;
use DRPPSM\TaxArchive;
use DRPPSM\TaxImageList;

use function DRPPSM\get_partial;

/**
 * Tax base shortcode.
 *
 * @package     DRPPSM\ShortCodes\TaxShortcode
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
abstract class TaxShortcode extends ShortCode implements Registrable {

	/**
	 * Display the tax listing or term archive.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 * @since 1.0.0
	 */
	public function show( array $atts ): string {

		$atts    = $this->fix_atts( $atts );
		$qv_tax  = get_query_var( 'taxonomy' );
		$qv_term = get_query_var( $this->sc );

		$defaults = array(
			'display' => $this->sc,
			'size'    => $this->size,
		);

		$args = shortcode_atts(
			$defaults,
			$atts,
			$this->sc
		);

		ob_start();
		if ( empty( $qv_term ) ) {
			new TaxImageList( $args );
		} elseif ( have_posts() ) {
			new TaxArchive( $qv_tax, $qv_term );
			wp_reset_postdata();
		} else {
			get_partial( 'no-posts' );
		}
		$result = ob_get_clean();
		return $result;
	}
}
