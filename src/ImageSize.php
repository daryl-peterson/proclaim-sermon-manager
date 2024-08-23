<?php
/**
 * Image sizes.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\ImageSizeInt;

/**
 * Image sizes.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ImageSize implements ImageSizeInt {

	/**
	 * Image sizes.
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
	 * Initialize and register hooks.
	 *
	 * @return ImageSizeInt
	 * @since 1.0.0
	 */
	public static function exec(): ImageSizeInt {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool Return true default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( 'after_setup_theme', array( $this, 'run' ) ) ) {
			return false;
		}
		add_action( 'after_setup_theme', array( $this, 'run' ) );
		return true;
	}

	/**
	 * Add image sizes.
	 *
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function run(): bool {
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

	/**
	 * Get sizes to define.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get_sizes(): array {
		return $this->sizes;
	}
}
