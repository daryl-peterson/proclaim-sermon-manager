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
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

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

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 1 );

		add_action( 'admin_menu', array( $this, 'fix_title' ), 100 );
		return true;
	}

	public function nav( array $items, mixed $object, array $args ) {
		Logger::debug(
			array(
				'ITEMS'  => $items,
				'OBJECT' => $object,
				'ARGS'   => $args,
			)
		);
		return $items;
	}

	public function admin_menu() {
		return;
		Logger::debug( 'HERE NOW !' );

		$top_slug = 'proclaim-sermon-manager';
		add_menu_page(
			__( 'Proclaim Sermon Manager', 'drppsm' ),
			__( 'Dashboard', 'drppsm' ),
			Caps::MANAGE_SETTINGS,
			$top_slug,
			array( $this, 'hs_admin_page_contents' ),
			app()->get_setting( 'menu_icon' ),
			5
		);
		return;

		add_submenu_page(
			$top_slug,
			__( 'Proclaim Sermons', 'drppsm' ),
			__( 'All Sermons', 'drppsm' ),
			Caps::MANAGE_SETTINGS,
			'edit.php?post_type=' . PT::SERMON,
		);

		// edit-tags.php?taxonomy=drppsm_bible&post_type=drppsm_sermon
		add_submenu_page(
			$top_slug,
			__( 'Bible Books', 'drppsm' ),
			__( 'Books', 'drppsm' ),
			Caps::MANAGE_CATAGORIES,
			'edit-tags.php?taxonomy=' . Tax::BIBLE_BOOK . '&post_type=' . PT::SERMON,
		);

		$preacher = get_setting( Tax::PREACHER, __( 'Preacher', 'drppsm' ) );
		add_submenu_page(
			$top_slug,
			$preacher,
			$preacher . 's',
			Caps::MANAGE_CATAGORIES,
			'edit-tags.php?taxonomy=' . Tax::PREACHER . '&post_type=' . PT::SERMON,
		);
	}

	function hs_admin_page_contents() {
		?>
		<h1>
			<?php esc_html_e( 'Welcome to my custom admin page.', 'my-plugin-textdomain' ); ?>
		</h1>
		<?php
	}

	/**
	 * Change subment item tname to All Sermons.
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

		/*
		$dashboard = array(
			'Dashboard',
			'manage_options',
			'admin.php?page=proclaim-sermon-manager',
		);
		array_unshift( $submenu[ 'edit.php?post_type=' . PT::SERMON ], $dashboard );


		Logger::debug( array( $GLOBALS['menu'], $GLOBALS['submenu'] ) );
		*/

		foreach ( $submenu[ 'edit.php?post_type=' . PT::SERMON ] as &$sermon_item ) {
			if ( 'edit.php?post_type=' . PT::SERMON === $sermon_item[2] ) {
				$sermon_item[0] = __( 'All Sermons', 'drppsm' );
				return;
			}
		}
	}
}
