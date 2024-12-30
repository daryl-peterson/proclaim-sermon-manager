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
class OptDisplay extends OptBase implements Executable, Registrable {

	public string $option_key = Settings::OPTION_KEY_DISPLAY;


	/**
	 * Initailize and register hooks.
	 *
	 * @return OptDisplay
	 * @since 1.0.0
	 */
	public static function exec(): OptDisplay {
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
		add_filter( DRPPSM_FLTR_SETTINGS_RSM, array( $this, 'set_menu' ) );
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

		$cmb  = new_cmb2_box( $args );
		$desc = __( 'If an option is unchecked, archive sorting / filtering for that item is hidden.', 'drppsm' );

		$this->add_seperator( $cmb, __( 'Display Settings', 'drppsm' ) );
		$this->add_html( $cmb, "<h4>$desc</h4>" );
		$this->add_bible_book_sort( $cmb );
		$this->add_preacher_sort( $cmb );
		$this->add_series_sort( $cmb );
		$this->add_service_type( $cmb );
		$this->add_topic_sort( $cmb );
	}


	/**
	 * Add common base slug.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_bible_book_sort( CMB2 $cmb ): void {

		$cmb->add_field(
			array(
				'id'      => Settings::BIBLE_BOOK_SORT,
				'name'    => __( 'Bible Book', 'drppsm' ),
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::BIBLE_BOOK_SORT ),
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
	private function add_preacher_sort( CMB2 $cmb ): void {
		$label = Settings::get( Settings::PREACHER );

		$cmb->add_field(
			array(
				'id'      => Settings::PREACHER_SORT,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::PREACHER_SORT ),
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
	private function add_series_sort( CMB2 $cmb ): void {
		$label = __( 'Series', 'drppsm' );

		$cmb->add_field(
			array(
				'id'      => Settings::SERIES_SORT,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::SERIES_SORT ),
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
	private function add_service_type( CMB2 $cmb ): void {
		$label = __( 'Service Type', 'drppsm' );

		$cmb->add_field(
			array(
				'id'      => Settings::SERVICE_TYPE_SORT,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::SERVICE_TYPE_SORT ),
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
	private function add_topic_sort( CMB2 $cmb ): void {
		$label = __( 'Topic', 'drppsm' );
		$desc  = __( 'If this option is unchecked, archive sorting for topics is hidden', 'drppsm' );

		$cmb->add_field(
			array(
				'id'      => Settings::TOPICS_SORT,
				'name'    => $label,
				'type'    => 'checkbox',
				'default' => Settings::get_default( Settings::TOPICS_SORT ),
			)
		);
	}
}
