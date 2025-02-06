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

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

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
	 * - crop true
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_SMALL = 'psm-sermon-small';

	/**
	 * Medium image size.
	 *
	 * - size 300x158
	 * - crop true
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_MEDIUM = 'psm-sermon-medium';

	/**
	 * Wide image size.
	 *
	 * - size 940x494
	 * - crop true
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_WIDE = 'psm-sermon-wide';

	/**
	 * Full image size.
	 *
	 * - size 1200x630
	 * - crop true
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const SERMON_FULL = 'psm-sermon-full';


	/**
	 * Preacher medium image size.
	 * - size 150x150
	 * - crop true
	 *
	 * @since 1.0.0
	 */
	public const TAX_MEDIUM = 'psm-preacher-medium';

	/**
	 * Preacher full image size.
	 * - size 300x300
	 * - crop true
	 *
	 * @since 1.0.0
	 */
	public const TAX_FULL = 'psm-preacher-full';

	/**
	 * Image sizes list.
	 */
	public const LIST = array(
		self::SERMON_SMALL,
		self::SERMON_MEDIUM,
		self::SERMON_WIDE,
		self::SERMON_FULL,
		self::TAX_MEDIUM,
		self::TAX_FULL,
	);

	public const SIZE_MAP = array(
		DRPPSM_PT_SERMON    => array(
			'small'  => self::SERMON_SMALL,
			'medium' => self::SERMON_MEDIUM,
			'wide'   => self::SERMON_WIDE,
			'full'   => self::SERMON_FULL,
		),

		DRPPSM_TAX_BOOK     => array(
			'medium' => self::TAX_MEDIUM,
			'full'   => self::TAX_FULL,
		),

		DRPPSM_TAX_PREACHER => array(
			'medium' => self::TAX_MEDIUM,
			'full'   => self::TAX_FULL,
		),

		DRPPSM_TAX_TOPIC    => array(
			'medium' => self::TAX_MEDIUM,
			'full'   => self::TAX_FULL,
		),

		DRPPSM_TAX_SERIES   => array(
			'medium' => self::SERMON_MEDIUM,
			'full'   => self::SERMON_FULL,
			'wide'   => self::SERMON_WIDE,
			'full'   => self::SERMON_FULL,
		),
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
			apply_filters( 'drppsm_image_size', self::TAX_MEDIUM ) => array(
				150,
				150,
				true,
			),
			apply_filters( 'drppsm_image_size', self::TAX_FULL )   => array(
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
	 * Get image size name.
	 *
	 * @param string $size Image size.
	 * @param string $taxonomy Taxonomy name.
	 * @return string
	 * @since 1.0.0
	 */
	public static function get_tax_image_size( string $size, string $taxonomy ): string {
		$tax_name = TaxUtils::get_taxonomy_name( $taxonomy );
		if ( ! $tax_name ) {
			return $size;
		}
		if ( isset( self::SIZE_MAP[ $tax_name ][ $size ] ) ) {
			$size = self::SIZE_MAP[ $tax_name ][ $size ];
		}

		/**
		 * Allows for the modification of the image size for a taxonomy.
		 *
		 * #### Friendly Taxonomy
		 * - book
		 * - preacher
		 * - series
		 * - topics
		 *
		 * #### Internal Taxonomy
		 * - drppsm_book
		 * - drppsm_preacher
		 * - drppsm_series
		 * - drppsm_topics
		 *
		 * @param string $size Image size.
		 * @param string $taxonomy Taxonomy name.
		 * @since 1.0.0
		 * @category filter
		 */
		$size = apply_filters( "get_($taxonomy)_image_size", $size, $taxonomy );

		return $size;
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

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
			// @codeCoverageIgnoreEnd
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
