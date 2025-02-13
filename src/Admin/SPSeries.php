<?php
/**
 * Series Settings.
 *
 * @package     DRPPSM\SPSeries
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

use DRPPSM\Action;
use DRPPSM\Filter;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Settings;
use DRPPSM\Traits\ExecutableTrait;
use DRPPSM\SPBase;

/**
 * Series Settings.
 *
 * @package     DRPPSM\SPSeries
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SPSeries extends SPBase implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Key used in storing options.
	 *
	 * @var string
	 */
	public string $option_key;


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
		$this->option_key = Settings::OPTION_KEY_SERIES;
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( ! is_admin() || has_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) ) ) {
			return false;
		}

		add_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( Filter::SETTINGS_REMOVE_SUBMENU, array( $this, 'set_menu' ) );
		return true;
	}
	/**
	 * Register metaboxes.
	 *
	 * @param callable $display_cb Callback to display on form.
	 * @return void
	 * @since 1.0.0
	 */
	public function register_metaboxes( callable $display_cb ): void {

		$menu_title = __( 'Settings', 'drppsm' );
		$title      = 'Proclaim ' . __( 'Sermon Manager Settings', 'drppsm' );

		/**
		 * Registers main options page menu item and form.
		 */
		$args = array(
			'id'           => $this->option_key,
			'title'        => $title,
			'menu_title'   => $menu_title,
			'object_types' => array( 'options-page' ),
			'option_key'   => $this->option_key,
			'parent_slug'  => AdminSettings::SLUG,
			'tab_group'    => AdminSettings::TAB_GROUP,
			'tab_title'    => __( 'Series', 'drppsm' ),
			'display_cb'   => $display_cb,
		);

		$cmb = new_cmb2_box( $args );
	}
}
