<?php

namespace DRPPSM;

use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class ImageUtils implements Initable, Registrable {

	public string $hook;

	protected function __construct() {
		$this->hook = Helper::get_key_name( 'IMAGEUTILS_REGISTER' );
	}

	public static function init(): ImageUtils {
		return new self();
	}

	public function register(): void {
		if ( ! did_action( $this->hook ) || defined( 'PHPUNIT_TESTING' ) ) {
			add_action( 'after_setup_theme', array( $this, 'addImageSizes' ) );
			do_action( $this->hook );
		}
	}

	/**
	 * Add image sizes.
	 */
	public function addImageSizes(): void {
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'sermon_small', 75, 75, true );
			add_image_size( 'sermon_medium', 300, 200, true );
			add_image_size( 'sermon_wide', 940, 350, true );
		}
	}
}
