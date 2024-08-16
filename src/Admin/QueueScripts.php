<?php
/**
 * Queue scritps / styles.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

use DRPPSM\Helper;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logging\Logger;

/**
 * Queue scritps / styles.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class QueueScripts implements Initable, Registrable {


	/**
	 * Version
	 *
	 * @var string
	 */
	private string $ver;

	protected function __construct() {
		$this->ver = rand( 1, 999 );
	}

	/**
	 * Get initialize object.
	 *
	 * @return QueueScripts
	 * @since 1.0.0
	 */
	public static function init(): QueueScripts {
		return new self();
	}

	/**
	 * Register callbacks.
	 *
	 * @return null|bool Return true as default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		$hook = Helper::get_key_name( Helper::get_short_name( $this ) . '_' . __FUNCTION__ );
		if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return true;
			// @codeCoverageIgnoreEnd
		}

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'init_script_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load' ) );
			add_action( 'admin_footer', array( $this, 'footer' ) );
		}
		do_action( $hook );
		return true;
	}



	/**
	 * Register styles / scripts
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init_script_styles() {
		$url = Helper::get_url() . 'assets';

		// @codeCoverageIgnoreStart
		$file = $url . '/css/drppsm-admin.css';
		wp_register_style(
			'drppsm-admin-style',
			$file,
			array(),
			$this->ver
		);

		$file = $url . '/css/drppsm-icons.css';
		wp_register_style(
			'drppsm-admin-icons',
			$file,
			array(),
			$this->ver
		);

		$file = $url . '/js/admin.js';
		wp_register_script(
			'drppsm-admin-script',
			$file,
			array(),
			$this->ver,
			true
		);

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
		wp_enqueue_script( 'drppsm-admin-script' );
	}
}
