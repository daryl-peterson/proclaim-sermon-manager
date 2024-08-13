<?php
/**
 * Admin menu.
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Caps;
use DRPPSM\Constants\PT;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logging\Logger;

/**
 * Admin menu.
 *
 * @package
 * @category
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
	 * Register callbacks.
	 *
	 * @return boolean|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		// @codeCoverageIgnoreStart
		if ( ! is_admin() && ! defined( 'PHPUNIT_TESTING' ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		add_action( 'admin_menu', array( $this, 'fix_title' ), 100 );
		return true;
	}

	/**
	 * Change subment item tname to All Sermons.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function fix_title(): void {
		global $submenu;

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
