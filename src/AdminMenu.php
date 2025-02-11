<?php
/**
 * Admin menu.
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

use DRPPSM\Constants\Caps;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use WP_Exception;

/**
 * Admin menu.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminMenu implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Register hooks.
	 *
	 * @return boolean|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		// @codeCoverageIgnoreStart
		if ( ! is_admin() || has_action( 'admin_menu', array( $this, 'fix_title' ) ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		add_action( 'admin_menu', array( $this, 'fix_title' ), 100 );
		add_action( 'admin_menu', array( $this, 'add_import_export' ), 110 );
		add_action( 'admin_menu', array( $this, 'about' ), 111 );
		return true;
	}

	/**
	 * Change submenu item name to All Sermons.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function fix_title(): void {
		global $submenu;

		if ( ! isset( $submenu[ 'edit.php?post_type=' . DRPPSM_PT_SERMON ] ) ) {
			return;
		}

		foreach ( $submenu[ 'edit.php?post_type=' . DRPPSM_PT_SERMON ] as &$sermon_item ) {
			if ( 'edit.php?post_type=' . DRPPSM_PT_SERMON === $sermon_item[2] ) {
				$sermon_item[0] = __( 'All Sermons', 'drppsm' );
				return;
			}
		}
	}

	public function about(): void {
		add_submenu_page(
			'edit.php?post_type=' . DRPPSM_PT_SERMON,
			DRPPSM_TITLE . ' ' . __( 'About', 'drppsm' ),
			__( 'About', 'drppsm' ),
			Caps::MANAGE_SETTINGS,
			'psm-about',
			array( $this, 'load_about' )
		);
	}

	/**
	 * Add import / export submenu.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_import_export(): void {
		add_submenu_page(
			'edit.php?post_type=' . DRPPSM_PT_SERMON,
			DRPPSM_TITLE . ' ' . __( 'Import / Export', 'drppsm' ),
			__( 'Import/Export', 'drppsm' ),
			Caps::MANAGE_SETTINGS,
			'psm-import-export',
			array( $this, 'load_import_export' )
		);
	}

	/**
	 * Load import / export page.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function load_import_export() {
	}

	public function load_about() {
	}
}
