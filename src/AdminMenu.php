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
<<<<<<< HEAD
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\Initable;
=======
use DRPPSM\Interfaces\Executable;
>>>>>>> 822b76c (Refactoring)
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
<<<<<<< HEAD
class AdminMenu implements Initable, Registrable {

	/**
	 * Get initialize object.
=======
class AdminMenu implements Executable, Registrable {

	/**
	 * Initialize and register.
>>>>>>> 822b76c (Refactoring)
	 *
	 * @return AdminMenu
	 * @since 1.0.0
	 */
<<<<<<< HEAD
	public static function init(): AdminMenu {
		return new self();
=======
	public static function exec(): AdminMenu {
		$obj = new self();
		$obj->register();
		return $obj;
>>>>>>> 822b76c (Refactoring)
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
