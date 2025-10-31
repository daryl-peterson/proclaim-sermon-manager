<?php
/**
 * Advanced settings.
 *
 * @package     DRPPSM\SPAdvanced
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

use CMB2;
use DRPPSM\Action;
use DRPPSM\Admin\AdminSettings;
use DRPPSM\Filter;
use DRPPSM\Settings;

/**
 * Advanced settings.
 *
 * @package     DRPPSM\SPAdvanced
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdvancedSettings extends BaseSettings {

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
		add_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( Filter::SETTINGS_REMOVE_SUBMENU, array( $this, 'set_menu' ) );
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
		$this->bible_book_load( $cmb );
		$this->bible_book_sort( $cmb );
		$this->post_view_count( $cmb );
		$this->cron_interval( $cmb );
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
				'id'         => Settings::BIBLE_BOOK_LOAD,
				'name'       => __( 'Load Books', 'drppsm' ),
				'type'       => 'checkbox',
				'after_row'  => $this->description( $desc ),
				'before_row' => function () {
					$this->add_section( __( 'Bible Settings', 'drppsm' ) );
				},
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

	/**
	 * Cron interval selection.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function cron_interval( CMB2 $cmb ) {
		$desc = __(
			'Interval in hours for cron jobs.',
			'drppsm'
		);
		$cmb->add_field(
			array(
				'id'        => Settings::CRON_INTERVAL,
				'name'      => __( 'Cron Interval', 'drppsm' ),
				'type'      => 'select',
				'options'   => array(
					'1'  => __( '1 HOUR', 'drppsm' ),
					'2'  => __( '2 HOURS', 'drppsm' ),
					'3'  => __( '3 HOURS', 'drppsm' ),
					'4'  => __( '4 HOURS', 'drppsm' ),
					'5'  => __( '5 HOURS', 'drppsm' ),
					'6'  => __( '6 HOURS', 'drppsm' ),
					'7'  => __( '7 HOURS', 'drppsm' ),
					'8'  => __( '8 HOURS', 'drppsm' ),
					'9'  => __( '9 HOURS', 'drppsm' ),
					'10' => __( '10 HOURS', 'drppsm' ),
					'11' => __( '11 HOURS', 'drppsm' ),
					'12' => __( '12 HOURS', 'drppsm' ),
					'13' => __( '13 HOURS', 'drppsm' ),
					'14' => __( '14 HOURS', 'drppsm' ),
					'15' => __( '15 HOURS', 'drppsm' ),
					'16' => __( '16 HOURS', 'drppsm' ),
					'17' => __( '17 HOURS', 'drppsm' ),
					'18' => __( '18 HOURS', 'drppsm' ),
					'19' => __( '19 HOURS', 'drppsm' ),
					'20' => __( '20 HOURS', 'drppsm' ),
					'21' => __( '21 HOURS', 'drppsm' ),
					'22' => __( '22 HOURS', 'drppsm' ),
					'23' => __( '23 HOURS', 'drppsm' ),
				),
				'after_row' => $this->description( $desc ),
			)
		);
	}
}
