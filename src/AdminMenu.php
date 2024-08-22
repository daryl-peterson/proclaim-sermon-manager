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

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * Admin menu.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminMenu implements Initable, Registrable {

	/**
	 * Get initialize object.
	 *
	 * @return AdminMenu
	 * @since 1.0.0
	 */
	public static function init(): AdminMenu {
		return new self();
	}

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

		$GLOBALS['menu'];

		if ( ! isset( $submenu[ 'edit.php?post_type=' . PT::SERMON ] ) ) {
			return;
		}

		foreach ( $submenu[ 'edit.php?post_type=' . PT::SERMON ] as &$sermon_item ) {
			if ( 'edit.php?post_type=' . PT::SERMON === $sermon_item[2] ) {
				$sermon_item[0] = __( 'All Sermons', 'drppsm' );
				return;
			}
		}
	}
}
