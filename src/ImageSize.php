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

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Image sizes.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ImageSize implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Small image size.
	 *
	 * - size 75x75
	 * - crop false
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_SMALL = 'psm-sermon-small';

	/**
	 * Medium image size.
	 *
	 * - size 300x158
	 * - crop false
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_MEDIUM = 'psm-sermon-medium';

	/**
	 * Wide image size.
	 *
	 * - size 940x494
	 * - crop false
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_WIDE = 'psm-sermon-wide';

	/**
	 * Full image size.
	 *
	 * - size 1200x630
	 * - crop false
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_FULL = 'psm-sermon-full';


	public const PREACHER_MEDIUM = 'psm-preacher-medium';
	public const PREACHER_FULL   = 'psm-preacher-full';



	/**
	 * Image sizes list.
	 */
	public const LIST = array(
		self::SERMON_SMALL,
		self::SERMON_MEDIUM,
		self::SERMON_WIDE,
		self::SERMON_FULL,

	);

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

			apply_filters( 'drppsm_image_size', self::SERMON_SMALL ) => array(
				75,
				75,
				true,
			),

			apply_filters( 'drppsm_image_size', self::SERMON_MEDIUM ) => array(
				300,
				158,
				true,
			),
			apply_filters( 'drppsm_image_size', self::SERMON_WIDE )   => array(
				940,
				494,
				true,
			),
			apply_filters( 'drppsm_image_size', self::SERMON_FULL )   => array(
				1200,
				630,
				true,
			),
			apply_filters( 'drppsm_image_size', self::PREACHER_MEDIUM ) => array(
				150,
				150,
				true,
			),
			apply_filters( 'drppsm_image_size', self::PREACHER_FULL )   => array(
				300,
				300,
				true,
			),
		);
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
		add_action( 'after_setup_theme', array( $this, 'run' ), 100, 1 );
		return true;
	}

	/**
	 * Add image sizes.
	 *
	 * @return bool True on success, otherwise false.
	 * @since 1.0.0
	 */
	public function run(): bool {
		$result = false;
		try {

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
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
		}
		return $result;
	}

	/**
	 * Get all image sizes.
	 *
	 * @return mixed|array
	 * @since 1.0.0
	 */
	public static function get_all_image_sizes() {
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
}
