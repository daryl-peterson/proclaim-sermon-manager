<?php
/**
 * Shortcode base class.
 *
 * @package     NameSpace\ShortCodes\ShortCodeBase
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logger;

use function DRPPSM\unquote;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode base class.
 *
 * @package     NameSpace\ShortCodes\ShortCodeBase
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
abstract class ShortCode implements Registrable {
	/**
	 * Shortcode name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $sc;

	/**
	 * Taxomony name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $tax;

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
			return false;
		}
		add_shortcode( $this->sc, array( $this, 'show' ) );
		return true;
	}

	/**
	 * Display shortcode output.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 * @since 1.0.0
	 */
	abstract public function show( array $atts ): string;

	/**
	 * Fix the attributes / parameters.
	 *
	 * @param array $atts
	 * @return array
	 */
	public function fix_atts( array $atts ): array {
		foreach ( $atts as &$att ) {
			$att = unquote( $att );
		}
		return $atts;
	}
}
