<?php
/**
 * Admin settings.
 *
 * @package     DRPPSM\Admin\AdminSettings
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

use CMB2_Boxes;
use CMB2_Options_Hookup;
use DRPPSM\Action;
use DRPPSM\Filter;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logger;
use DRPPSM\Settings;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Admin settings.
 *
 * @package     DRPPSM\AdminSettings
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminSettings implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Menu slug.
	 */
	const SLUG = 'edit.php?post_type=' . DRPPSM_PT_SERMON;

	/**
	 * Tab group.
	 */
	const TAB_GROUP = DRPSM_KEY_PREFIX . '_options';

	/**
	 * Register hooks
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( ! is_admin() || has_action( 'cmb2_admin_init', array( $this, 'register_metaboxes' ) ) ) {
			return false;
		}
		Settings::set_defaults();

		$object_type = 'options-page';

		add_action( 'cmb2_admin_init', array( $this, 'register_metaboxes' ) );
		add_filter( 'submenu_file', array( $this, 'remove_submenus' ) );
		// add_action( "cmb2_save_{$object_type}_fields", array( $this, 'after_save' ), 10, 4 );

		GeneralSettings::exec();
		DisplaySettings::exec();
		SermonSettings::exec();
		AdvancedSettings::exec();

		return true;
	}

	/**
	 * Register metabox
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_metaboxes() {
		$cb = array( $this, 'display_with_tabs' );
		do_action( Action::SETTINGS_REGISTER_FORM, $cb );
	}



	public function after_save( mixed $object_id, mixed $cmb_id, mixed $updated, mixed $cmb ) {
		Logger::debug(
			array(
				'object_id' => $object_id,
				'cmb_id'    => $cmb_id,
				'updated'   => $updated,
				'cmb'       => $cmb,
			)
		);
	}

	/**
	 * Display
	 *
	 * @param CMB2_Options_Hookup $cmb_options CMB2 Options hookup.
	 * @return void
	 * @since 1.0.0
	 */
	public function display_with_tabs( CMB2_Options_Hookup $cmb_options ) {

		$title   = __( 'Proclaim Sermon Manager Settings', 'drppsm' );
		$action  = esc_url( admin_url( 'admin-post.php' ), false );
		$form_id = $cmb_options->cmb->cmb_id;
		$enc     = 'multipart/form-data';
		$attr    = esc_attr( $cmb_options->option_key );

		$nav    = $this->get_nav( $cmb_options );
		$inputs = $this->get_inputs( $cmb_options );
		$button = $this->get_button( $cmb_options );

		$html = <<<EOT

			<div id="drppsm" class="wrap">

				<h1 class="wp-heading-inline">
					$title
				</h1>
				<div class="settings-main">
					<div class="settings-content">
						<div class="inside">
							<h2 class="nav-tab-wrapper">
								$nav
							</h2>
							<form class="cmb-form" action="$action" method="POST" id="$form_id" enctype="$enc" encoding="$enc">
							<input type="hidden" name="action" value="$attr">
							$inputs
							$button
							</form>
						</div>
					</div>
					<div class="settings-side">

					</div>
				</div>
			</div>
		EOT;
		echo $html; //phpcs:ignore
	}

	/**
	 * Gets navigation tabs array for CMB2 options pages which share the given
	 * display_cb param.
	 *
	 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
	 * @return array String array of tabs.
	 * @since 1.0.0
	 */
	public function get_options_page_tabs( CMB2_Options_Hookup $cmb_options ): array {
		$tab_group = $cmb_options->cmb->prop( 'tab_group' );
		$tabs      = array();

		foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
			if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
				$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
					? $cmb->prop( 'tab_title' )
					: $cmb->prop( 'title' );
			}
		}

		return $tabs;
	}

	/**
	 * Remove submenus for settings menu and change selected menu.
	 *
	 * @param ?string $submenu_file Sub menu to remove.
	 * @return ?string
	 * @since 1.0.0
	 */
	public function remove_submenus( ?string $submenu_file ): ?string {

		global $plugin_page;

		/**
		 * Get list of submenus to remove.
		 *
		 * @param array $hidden Array of submenus to remove.
		 * @return mixed
		 */
		$hidden = apply_filters( Filter::SETTINGS_REMOVE_SUBMENU, array() );

		// Select another submenu item to highlight (optional).
		if ( $plugin_page && in_array( $plugin_page, $hidden, true ) ) {
			$submenu_file = Settings::OPTION_KEY_GENERAL;
		}

		// Hide the submenus.
		foreach ( $hidden as $submenu ) {
			remove_submenu_page( self::SLUG, $submenu );
		}

		return $submenu_file;
	}

	/**
	 * Get tab navigation.
	 *
	 * @param CMB2_Options_Hookup $cmb_options CMB options.
	 * @return string
	 * @since 1.0.0
	 */
	private function get_nav( CMB2_Options_Hookup $cmb_options ): string {
		$tabs = $this->get_options_page_tabs( $cmb_options );
		$nav  = '';

		foreach ( $tabs as $option_key => $tab_title ) {
			$class = 'nav-tab';
			$page  = '';
			// phpcs:disable
			if ( isset( $_REQUEST['page'] ) && ! empty( $_REQUEST['page'] ) ) {
				$page = sanitize_text_field( wp_unslash( $_REQUEST['page'] ) );
			}
			// phpcs:enable

			if ( $option_key === $page ) {
				$class = 'nav-tab nav-tab-active';
			}
			$url       = menu_page_url( $option_key, false );
			$tab_title = wp_kses_post( $tab_title );
			$nav      .= "<a class=\"$class\" href=\"$url\">$tab_title</a>\n";
		}
		return $nav;
	}

	/**
	 * Get inputs for.
	 *
	 * @param CMB2_Options_Hookup $cmb_options CMB options.
	 * @return string
	 * @since 1.0.0
	 */
	private function get_inputs( CMB2_Options_Hookup $cmb_options ): string {
		ob_start();
		$cmb_options->options_page_metabox();
		return ob_get_clean();
	}

	/**
	 * Create save button.
	 *
	 * @param CMB2_Options_Hookup $cmb_options CMB options.
	 * @return string
	 * @since 1.0.0
	 */
	private function get_button( CMB2_Options_Hookup $cmb_options ): string {
		ob_start();
		submit_button( esc_attr( $cmb_options->cmb->prop( 'save_button' ) ), 'primary', 'submit-cmb' );
		return ob_get_clean();
	}
}
