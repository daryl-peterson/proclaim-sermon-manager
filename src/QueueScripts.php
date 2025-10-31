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

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

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
	use ExecutableTrait;

	/**
	 * Testing flag
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	public bool $testing = false;


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		include_pluggable();
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool Return true as default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( has_action( 'init', array( $this, 'register_scripts_styles' ) ) ) {
			return false;
		}
		add_action( 'init', array( $this, 'register_scripts_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );

		return true;
	}

	/**
	 * Queue style for frontend.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function enqueue_scripts(): void {

		wp_enqueue_style( 'drppsm-style' );
		wp_enqueue_style( 'drppsm-plyr-css' );
		wp_enqueue_style( 'drppsm-icons' );

		wp_enqueue_script( 'drppsm-plyr' );
		wp_enqueue_script( 'drppsm-plyr-loader' );
		wp_enqueue_script( 'drppsm-frontend' );

		$permalinks = PermaLinks::get();

		$data = array();
		foreach ( $permalinks as $tax => $url ) {
			$url          = get_site_url() . '/' . $url . '/';
			$data[ $tax ] = $url;
		}

		wp_localize_script(
			'drppsm-frontend',
			'drppsm_info',
			$data
		);
	}

	/**
	 * Register scripts and styles.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_scripts_styles(): void {
		$plyr_ver = '3.7.8';

		$in_footer = array( 'in_footer' => true );

		wp_register_script(
			'drppsm-plyr',
			DRPPSM_URL . 'assets/lib/plyr/plyr.polyfilled.js',
			array(),
			$plyr_ver,
			$in_footer
		);

		wp_register_script(
			'drppsm-plyr-loader',
			DRPPSM_URL . 'assets/js/plyr' . ( ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ? '' : '.min' ) . '.js',
			array( 'drppsm-plyr' ),
			DRPPSM_VER,
			$in_footer
		);

		wp_register_script(
			'drppsm-fb-video',
			DRPPSM_URL . 'assets/lib/facebook/fb-video.js',
			array(),
			DRPPSM_VER,
			$in_footer
		);

		wp_register_script(
			'drppsm-frontend',
			DRPPSM_URL . 'assets/js/frontend.js',
			array(),
			DRPPSM_VER,
			$in_footer
		);

		wp_register_script(
			'drppsm-admin-script',
			DRPPSM_URL . 'assets/js/admin' . ( ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) ? '' : '.min' ) . '.js',
			array(),
			DRPPSM_VER,
			$in_footer
		);

		wp_register_style(
			'drppsm-plyr-css',
			DRPPSM_URL . 'assets/lib/plyr/plyr.css',
			array(),
			$plyr_ver
		);

		wp_register_style(
			'drppsm-style',
			DRPPSM_URL . 'assets/css/drppsm-style.css',
			array(),
			DRPPSM_VER
		);

		wp_register_style(
			'drppsm-admin-style',
			DRPPSM_URL . 'assets/css/admin/drppsm-admin.css',
			array(),
			DRPPSM_VER
		);

		wp_register_style(
			'drppsm-admin-icons',
			DRPPSM_URL . 'assets/css/admin/drppsm-icons.css',
			array(),
			DRPPSM_VER
		);

		wp_register_style(
			'drppsm-icons',
			DRPPSM_URL . 'assets/css/icons/drppsm-general.css',
			array(),
			DRPPSM_VER
		);

		return;
	}


	/**
	 * Load registered scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts(): void {
		if ( ! $this->testing ) {
			if ( ! is_admin() ) {
				return;
			}
		}

		wp_enqueue_style( 'drppsm-admin-style' );
		wp_enqueue_style( 'drppsm-admin-icons' );

		wp_enqueue_media();
	}

	/**
	 * Load footer scripts.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function admin_footer() {
		if ( ! $this->testing ) {
			if ( ! is_admin() ) {
				return;
			}
		}

		wp_enqueue_script( 'drppsm-admin-script' );
	}
}
