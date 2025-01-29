<?php
/**
 * Display settings.
 *
 * @package     DRPPSM\SettingsDisplay
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use CMB2;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Display settings.
 *
 * @package     DRPPSM\SettingsDisplay
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SPDisplay extends SPBase implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Key used in storing options.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $option_key = Settings::OPTION_KEY_DISPLAY;

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
			'tab_title'    => 'Display',
			'display_cb'   => $display_cb,
		);

		$cmb = new_cmb2_box( $args );
		$this->default_image( $cmb );
		$this->disable_css( $cmb );

		// Filtering
		$this->hide_filtering( $cmb );
		$this->hide_bible( $cmb );
		$this->hide_preachers( $cmb );
		$this->hide_series( $cmb );
		$this->hide_service_types( $cmb );
		$this->hide_topics( $cmb );

		$this->images_per_row( $cmb );
	}

	/**
	 * Add the images per row field.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function images_per_row( CMB2 $cmb ): void {
		$desc = __( 'For series, preacher image lists images per row.', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::IMAGES_PER_ROW,
				'name'             => __( 'Images per row', 'drppsm' ),
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'1' => 'One',
					'2' => 'Two',
					'3' => 'Three',
					'4' => 'Four',
				),
				'after_row'        => $this->description( $desc ),
				'before_row'       => function () {
					$this->add_section( __( 'Image Lists', 'drppsm' ) );
				},
			)
		);
	}

	/**
	 * Add sermon default image field.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function default_image( CMB2 $cmb ): void {
		$desc = __(
			'Sets the default sermon image that would show up if there is no sermon or series image set.',
			'drppsm'
		);

		$cmb->add_field(
			array(
				'name'       => __( 'Default Image', 'drppsm' ),
				'id'         => Settings::DEFAULT_IMAGE,
				'type'       => 'file',
				'after_row'  => $this->description( $desc ),
				'before_row' => function () {
					$this->add_section( __( 'Display Settings', 'drppsm' ) );
				},
			)
		);
	}

	/**
	 * Add disable css.
	 *
	 * @param CMB2 $cmb CMB2 object.
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
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add archive filtering.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_filtering( CMB2 $cmb ) {

		$cmb->add_field(
			array(
				'id'         => Settings::HIDE_FILTERS,
				'name'       => __( 'Hide Filtering', 'drppsm' ),
				'type'       => 'checkbox',
				'before_row' => function () {
					$this->add_section( __( 'Sermon Filtering / Sorting', 'drppsm' ) );
				},
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
				'id'   => Settings::HIDE_BOOKS,
				'name' => __( 'Hide Bible', 'drppsm' ),
				'type' => 'checkbox',
			)
		);
	}

	/**
	 * Add preacher sorting.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_preachers( CMB2 $cmb ): void {
		/* translators: %s preacher */
		$label = wp_sprintf( 'Hide %s', get_taxonomy_field( DRPPSM_TAX_PREACHER, 'label' ) );

		$cmb->add_field(
			array(
				'id'   => Settings::HIDE_PREACHERS,
				'name' => $label,
				'type' => 'checkbox',
			)
		);
	}

	/**
	 * Add series sorting.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_series( CMB2 $cmb ): void {
		/* translators: %s series */
		$label = wp_sprintf( 'Hide %s', get_taxonomy_field( DRPPSM_TAX_SERIES, 'label' ) );

		$cmb->add_field(
			array(
				'id'   => Settings::HIDE_SERIES,
				'name' => $label,
				'type' => 'checkbox',
			)
		);
	}

	/**
	 * Add service type sorting.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_service_types( CMB2 $cmb ): void {

		/* translators: %s service types */
		$label = wp_sprintf( 'Hide %s', get_taxonomy_field( DRPPSM_TAX_SERVICE_TYPE, 'label' ) );

		$cmb->add_field(
			array(
				'id'   => Settings::HIDE_SERVICE_TYPES,
				'name' => $label,
				'type' => 'checkbox',
			)
		);
	}

	/**
	 * Add topic sorting.
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function hide_topics( CMB2 $cmb ): void {

		/* translators: %s topics */
		$label = wp_sprintf( 'Hide %s', get_taxonomy_field( DRPPSM_TAX_TOPIC, 'label' ) );

		$cmb->add_field(
			array(
				'id'   => Settings::HIDE_TOPICS,
				'name' => $label,
				'type' => 'checkbox',
			)
		);
	}
}
