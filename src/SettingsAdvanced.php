<?php
/**
 * Advanced settings.
 *
 * @package     DRPPSM\SettingsAdvanced
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use CMB2;
use DRPPSM\Constants\Actions;

/**
 * Advanced settings.
 *
 * @package     DRPPSM\SettingsAdvanced
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SettingsAdvanced extends SettingsBase {

	/**
	 * Key used in storing options.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $option_key = Settings::OPTION_KEY_ADVANCED;

	/**
	 * Initailize and register hooks.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_action( Actions::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( DRPPSMF_SETTINGS_RSM, array( $this, 'set_menu' ) );
		return true;
	}

	/**
	 * Register metaboxes.
	 *
	 * @param callable $display_cb Display callback.
	 * @return void
	 * @since 1.0.0
	 */
	public function register_metaboxes( callable $display_cb ) {
		$title = 'Proclaim ' . __( 'Sermon Manager Settings', 'drppsm' );

		/**
		 * Registers main options page menu item and form.
		 */
		$args = array(
			'id'           => $this->option_key,
			'title'        => $title,
			'object_types' => array( 'options-page' ),
			'option_key'   => $this->option_key,
			'parent_slug'  => AdminSettings::SLUG,
			'tab_group'    => AdminSettings::TAB_GROUP,
			'tab_title'    => 'Advanced',
			'display_cb'   => $display_cb,
		);

		$cmb = new_cmb2_box( $args );
		$this->add_seperator( $cmb, __( 'Bible Settings', 'drppsm' ) );
		$this->bible_book_load( $cmb );
		$this->bible_book_sort( $cmb );
		$this->post_view_count( $cmb );
	}


	/**
	 * Add checkbox to enable reloading of bible books.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function bible_book_load( CMB2 $cmb ) {
		$desc = __(
			'Select this to reload books',
			'drppsm'
		);
		$cmb->add_field(
			array(
				'id'        => Settings::BIBLE_BOOK_LOAD,
				'name'      => __( 'Load Books', 'drppsm' ),
				'type'      => 'checkbox',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add checkbox to enable sorting in biblical order.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function bible_book_sort( CMB2 $cmb ) {
		$desc = __(
			'Alphabetical rather than biblical order. Default unchecked.',
			'drppsm'
		);
		$cmb->add_field(
			array(
				'id'        => Settings::BIBLE_BOOK_SORT,
				'name'      => __( 'Sort Books Alphabetical', 'drppsm' ),
				'type'      => 'checkbox',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Count views if user is logged in.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function post_view_count( CMB2 $cmb ) {
		$desc = __(
			'Disable this option if you do not want to count sermon views for editors and admins.',
			'drppsm'
		);
		$cmb->add_field(
			array(
				'id'        => Settings::POST_VIEW_COUNT,
				'name'      => __( 'View Count', 'drppsm' ),
				'type'      => 'checkbox',
				'after_row' => $this->description( $desc ),
			)
		);
	}
}
