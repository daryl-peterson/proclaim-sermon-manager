<?php
/**
 * Display settings.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */


namespace DRPPSM;

use CMB2;
use DRPPSM\Constants\Actions;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Display settings.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SettingsDisplay extends SettingsBase implements Executable, Registrable {

	public string $option_key = Settings::OPTION_KEY_DISPLAY;

	/**
	 * Initailize and register hooks.
	 *
	 * @return SettingsDisplay
	 * @since 1.0.0
	 */
	public static function exec(): SettingsDisplay {
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
			'tab_title'    => 'Display',
			'display_cb'   => $display_cb,
		);

		$cmb = new_cmb2_box( $args );
		$this->add_seperator( $cmb, __( 'Display Settings', 'drppsm' ) );
		$this->add_default_image( $cmb );
		$this->disable_css( $cmb );
		$this->add_seperator( $cmb, __( 'Archive Settings', 'drppsm' ) );

		$this->archive_order_by( $cmb );
		$this->archive_order( $cmb );
		$this->bible_books( $cmb );

		$this->add_seperator( $cmb, __( 'Sermon Filtering / Sorting', 'drppsm' ) );
		$this->hide_bible( $cmb );
		$this->hide_preacher( $cmb );
		$this->hide_series( $cmb );
		$this->hide_service_type( $cmb );
		$this->hide_topic( $cmb );
	}


	/**
	 * Add sermon default image field.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_default_image( CMB2 $cmb ): void {
		$desc = __(
			'Sets the default sermon image that would show up if there is no sermon or series image set.',
			'drppsm'
		);

		$cmb->add_field(
			array(
				'name'      => esc_html__( 'Default Image', 'drppsm' ),
				'id'        => 'sermon_default_image',
				'type'      => 'file',
				'after_row' => $this->description( $desc ),
			)
		);
	}


	private function bible_books( CMB2 $cmb ) {
		$cmb->add_field(
			array(
				'id'      => Settings::BIBLE_BOOK_LOAD,
				'name'    => __( 'Load Bible Books', 'drppsm' ),
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::BIBLE_BOOK_LOAD ),
			)
		);
	}

	/**
	 * Add archive order by.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function archive_order_by( CMB2 $cmb ): void {
		$desc  = __( 'Changes the way sermons are ordered by default.', 'drppsm' ) . ' ';
		$desc .= __( 'Affects the RSS feed and shown date as well. Default "Date Preached".', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::ARCHIVE_ORDER_BY,
				'name'             => __( 'Order sermons by', 'drppsm' ),
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'date_preached' => 'Date Preached',
					'date'          => 'Date Published',
					'title'         => 'Title',
					'ID'            => 'ID',
					'random'        => 'Random',
				),
				'default'          => Settings::get_default( Settings::ARCHIVE_ORDER_BY ),
				'after_row'        => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add archive order.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function archive_order( CMB2 $cmb ): void {
		$desc = __( 'Related to the setting above. Default descending.', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::ARCHIVE_ORDER,
				'name'             => __( 'Order direction', 'drppsm' ),
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'desc' => 'Descending',
					'asc'  => 'Ascending',
				),
				'default'          => Settings::get_default( Settings::ARCHIVE_ORDER ),
				'after_row'        => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add disable css.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function disable_css( CMB2 $cmb ): void {
		$desc = __( 'Disable Proclaim Sermon Manager\'s CSS.', 'drppsm' );
		$cmb->add_field(
			array(
				'id'        => Settings::DISABLE_CSS,
				'name'      => __( 'Sermon styles', 'drppsm' ),
				'type'      => 'checkbox',
				'default'   => Settings::get_default( Settings::DISABLE_CSS ),
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add archive filtering.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_filtering( CMB2 $cmb ) {
		$cmb->add_field(
			array(
				'id'      => Settings::HIDE_FILTERING,
				'name'    => __( 'Hide Filtering', 'drppsm' ),
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::HIDE_FILTERING, false ),
			)
		);
	}

	/**
	 * Add bible book archive filtering.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_bible( CMB2 $cmb ): void {

		$cmb->add_field(
			array(
				'id'      => Settings::HIDE_BIBLE_BOOK,
				'name'    => __( 'Hide Bible', 'drppsm' ),
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::HIDE_BIBLE_BOOK ),
			)
		);
	}

	/**
	 * Add preacher sorting.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_preacher( CMB2 $cmb ): void {
		$label = __( 'Hide', 'drppsm' ) . Settings::get( Settings::PREACHER );

		$cmb->add_field(
			array(
				'id'      => Settings::HIDE_PREACHER,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::HIDE_PREACHER ),
			)
		);
	}

	/**
	 * Add series sorting.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_series( CMB2 $cmb ): void {
		$label = __( 'Hide Series', 'drppsm' );

		$cmb->add_field(
			array(
				'id'      => Settings::HIDE_SERIES,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::HIDE_SERIES ),
			)
		);
	}

	/**
	 * Add service type sorting.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_service_type( CMB2 $cmb ): void {
		$label = __( 'Hide Service Type', 'drppsm' );

		$cmb->add_field(
			array(
				'id'      => Settings::HIDE_SERVICE_TYPE,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::HIDE_SERVICE_TYPE ),
			)
		);
	}

	/**
	 * Add topic sorting.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_topic( CMB2 $cmb ): void {
		$label = __( 'Hide Topic', 'drppsm' );

		$cmb->add_field(
			array(
				'id'      => Settings::HIDE_TOPICS,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::HIDE_TOPICS ),
			)
		);
	}
}
