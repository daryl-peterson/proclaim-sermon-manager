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
	public const SERMON_SMALL  = 'proclaim_small';
	public const SERMON_MEDIUM = 'proclaim_medium';
	public const SERMON_WIDE   = 'proclaim_wide';

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
				false,
			),
			self::SERMON_MEDIUM => array(
				300,
				200,
				false,
			),
			self::SERMON_WIDE   => array(
				940,
				350,
				false,
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
		$timer     = Timer::get_instance();
		$timer_key = $timer->start( __FUNCTION__, __FILE__ );
		$result    = true;

		// @codeCoverageIgnoreStart
		if ( ! function_exists( '\add_image_size' ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		foreach ( $this->sizes as $name => $settings ) {
			add_image_size( $name, ...$settings );
		}

		Logger::debug( $this->get_all_image_sizes() );

		foreach ( $this->sizes as $name => $settings ) {
			$result = has_image_size( $name );

			// @codeCoverageIgnoreStart
			if ( ! $result ) {
				break;
			}
			// @codeCoverageIgnoreEnd
		}
		$timer->stop( $timer_key );
		return $result;
	}

	public function get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = get_intermediate_image_sizes();

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
			$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
			$image_sizes[ $size ]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
		}

		if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		return $image_sizes;
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
