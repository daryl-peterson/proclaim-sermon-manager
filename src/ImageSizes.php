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
	 * @return void
	 * @since 1.0.0
	 */
	public function add_image_sizes() {
		if ( function_exists( 'add_image_size' ) ) {
			add_image_size( 'sermon_small2', 75, 75, true );
			add_image_size( 'sermon_medium2', 300, 200, true );
			add_image_size( 'sermon_wide2', 940, 350, true );
		}
	}
}
