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

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Helper;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Queue scritps / styles.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class QueueScripts implements Registrable, Executable {

	/**
	 * Version
	 *
	 * @var string
	 */
	private string $ver;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		include_pluggable();
		$this->ver = \wp_rand( 1, 999 );
	}

	/**
	 * Initialize and register hooks.
	 *
	 * @return QueueScripts
	 * @since 1.0.0
	 */
	public static function exec(): QueueScripts {
		$obj = new static();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool Return true as default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( is_admin() && ! has_action( 'admin_init', array( $this, 'init_script_styles' ) ) ) {
			add_action( 'admin_init', array( $this, 'init_script_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load' ) );
			add_action( 'admin_footer', array( $this, 'footer' ) );
		}

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

		$file = $url . '/css/drppsm-admin.min.css';
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

		$file = $url . '/js/admin.min.js';
		wp_register_script(
			'drppsm-admin-script',
			$file,
			array(),
			$this->ver,
			true
		);
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
