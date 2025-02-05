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

use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logger;

use function DRPPSM\get_partial;

defined( 'ABSPATH' ) || exit;

/**
 * Tax base shortcode.
 *
 * @package     DRPPSM\ShortCodes\TaxShortcode
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxShortcode implements Registrable {

	/**
	 * Shortcode name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $sc;

	/**
	 * Image size.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $size;

	/**
	 * Register the shortcode.
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( shortcode_exists( $this->sc ) ) {
			Logger::debug( 'Shortcode already exists: ' . $this->sc );
			return false;
		}
		add_shortcode( $this->sc, array( $this, 'show' ) );
		return true;
	}

	/**
	 * Display the series.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 * @since 1.0.0
	 */
	public function show( array $atts ) {

		$qv_tax  = get_query_var( 'taxonomy' );
		$qv_term = get_query_var( $this->sc );
		$qv_play = get_query_var( 'play' );

		Logger::debug(
			array(
				'qv_tax'  => $qv_tax,
				'qv_term' => $qv_term,
				'qv_play' => $qv_play,
			)
		);

		$args = array(
			'display' => $this->sc,
			'size'    => $this->size,
		);
		Logger::debug(
			array(
				'args' => $args,
				'atts' => $atts,
			)
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
