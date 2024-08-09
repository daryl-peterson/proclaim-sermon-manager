<?php
/**
 * Sermon images.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * Sermon images.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ImageSizes implements Initable, Registrable {

	/**
	 * Set size constants.
	 */
	public const SERMON_SMALL  = 'sermon_small';
	public const SERMON_MEDIUM = 'sermon_medium';
	public const SERMON_WIDE   = 'sermon_wide';

	/**
	 * Size arrarrys.
	 *
	 * @var array
	 */
	protected array $sizes;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->sizes = array(
			self::SERMON_SMALL  => array(
				75,
				75,
				true,
			),
			self::SERMON_MEDIUM => array(
				300,
				200,
				true,
			),
			self::SERMON_WIDE   => array(
				940,
				350,
				true,
			),
		);
	}

	/**
	 * Get initalize object.
	 *
	 * @return ImageSizes
	 * @since 1.0.0
	 */
	public static function init(): ImageSizes {
		return new self();
	}

	/**
	 * Register callbacks.
	 *
	 * @return null|bool Return true default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes' ) );
		return true;
	}

	/**
	 * Add image sizes.
	 *
	 * @return bool True if successfull, otherwise false.
	 * @since 1.0.0
	 */
	public function add_image_sizes(): bool {
		$result = true;

		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\add_image_size' ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		foreach ( $this->sizes as $name => $settings ) {
			add_image_size( $name, ...$settings );
		}

		foreach ( $this->sizes as $name => $settings ) {
			$result = has_image_size( $name );

			// @codeCoverageIgnoreStart
			if ( ! $result ) {
				break;
			}
			// @codeCoverageIgnoreEnd
		}

		return $result;
	}
}
