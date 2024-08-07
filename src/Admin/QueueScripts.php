<?php
/**
 * Queue scritps / styles.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

use DRPPSM\Helper;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * Queue scritps / styles.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class QueueScripts implements Initable, Registrable {

	public static function init(): QueueScripts {
		return new self();
	}



	/**
	 * Register callbacks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register(): void {

		$hook = Helper::get_key_name( Helper::get_short_name( $this ) . '_' . __FUNCTION__ );
		if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'init_script_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load' ) );
			add_action( 'admin_footer', array( $this, 'footer' ) );
		}
		do_action( $hook );
	}

	/**
	 * Register styles / scripts
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init_script_styles() {
		// @codeCoverageIgnoreStart
		$file = Helper::get_url() . 'assets/css/drppsm-admin.css';
		wp_register_style( 'drppsm-admin-style', $file );

		$file = Helper::get_url() . 'assets/css/drppsm-icons.css';
		wp_register_style( 'drppsm-admin-icons', $file );

		// $file = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css';
		// wp_register_style('drppsm-jquery-ui-style', $file);

		$file = Helper::get_url() . 'assets/js/admin.js';
		wp_register_script( 'drppsm-admin-script', $file );
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Load registered scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function load(): void {
		if ( is_admin() ) {
			// @codeCoverageIgnoreStart
			wp_enqueue_style( 'drppsm-admin-style' );
			wp_enqueue_style( 'drppsm-admin-icons' );
			wp_enqueue_media();
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Load footer scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function footer() {
		if ( ! is_admin() ) {
			return;
		}
		// wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script( 'drppsm-admin-script' );
	}
}
