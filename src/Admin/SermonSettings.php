<?php
/**
 * Sermon Settings.
 *
 * @package     DRPPSM\Admin\SermonSettings
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

use CMB2;
use DRPPSM\Action;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use DRPPSM\Admin\AdminSettings;
use DRPPSM\Filter;
use DRPPSM\Logger;
use DRPPSM\Settings;
use DRPPSM\Transient;

/**
 * Sermon Settings.
 *
 * @package     DRPPSM\Admin\SermonSettings
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonSettings extends BaseSettings implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * CMB2 Object.
	 *
	 * @var CMB2
	 * @since 1.0.0
	 */
	public static CMB2 $cmb;

	/**
	 * Key used in storing options.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $option_key;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->option_key = Settings::OPTION_KEY_SERMONS;

		parent::__construct();
	}

	/**
	 * Register hooks.
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		$object_type = 'options-page';
		$id          = $this->option_key;

		if ( ! is_admin() || has_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) ) ) {
			return false;
		}

		add_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( Filter::SETTINGS_REMOVE_SUBMENU, array( $this, 'set_menu' ) );
		add_action( "cmb2_save_{$object_type}_fields_{$id}", array( $this, 'check' ), 10, 3 );
		return true;
	}

	/**
	 * Register metaboxes.
	 *
	 * @param callable $display_cb Display callback.
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
			'id'           => Settings::OPTION_KEY_SERMONS,
			'title'        => $title,
			'menu_title'   => $menu_title,
			'object_types' => array( 'options-page' ),
			'option_key'   => Settings::OPTION_KEY_SERMONS,
			'parent_slug'  => AdminSettings::SLUG,
			'tab_group'    => AdminSettings::TAB_GROUP,
			'tab_title'    => 'Sermons',
			'display_cb'   => $display_cb,
		);

		$cmb = new_cmb2_box( $args );
		$this->date_format( $cmb );
		$this->sermon_count( $cmb );
		$this->archive_order_by( $cmb );
		$this->archive_order( $cmb );
		$this->sermon_layout( $cmb );
		$this->common_base_slug( $cmb );
		$this->sermon_single( $cmb );
		$this->sermon_plural( $cmb );
		self::$cmb = $cmb;
	}

	/**
	 * Check if settings changed.
	 *
	 * @param string     $object_id Object ID.
	 * @param null|array $updated List of fields that were updated.
	 * @param CMB2       $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	public function check( string $object_id, null|array $updated, CMB2 $cmb ) {

		$flush_check = array(
			Settings::SERMON_SINGULAR,
			Settings::SERMON_PLURAL,
			Settings::COMMON_BASE_SLUG,

		);
		$trans_check = array(
			Settings::SERMON_COUNT,
			Settings::ARCHIVE_ORDER_BY,
			Settings::ARCHIVE_ORDER,
		);

		$delete = false;
		foreach ( $trans_check as $value ) {
			if ( in_array( $value, $updated, true ) ) {
				Logger::debug( 'Deleting all transients' );
				Transient::delete_all();
				$delete = true;
				break;
			}
		}

		foreach ( $flush_check as $value ) {
			if ( in_array( $value, $updated, true ) ) {
				flush_rewrite_rules( true );
				if ( ! $delete ) {
					Transient::delete_all();
				}
				break;
			}
		}
	}

	/**
	 * Add common base slug.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function common_base_slug( CMB2 $cmb ): void {

		$desc  = __( 'If this option is checked, the taxonomies would also be under the slug set above.', 'drppsm' );
		$desc .= $this->dot();
		$s1    = '<code>' . __( '/sermons/series/jesus', 'drppsm' ) . '</code>';
		$s2    = '<code>' . __( '/sermons/preacher/mark', 'drppsm' ) . '</code>';

		$desc .= wp_sprintf(
			// translators: %1$s Example series path, effectively <code>/sermons/series/jesus</code>.
			// translators: %2$s Example preacher path, effectively <code>/sermons/preacher/mark</code>.
			__( 'For example, by default, series named “Jesus” would be under %1$s, preacher “Mark” would be under %2$s, and so on.', 'drppsm' ),
			$s1,
			$s2
		);

		$cmb->add_field(
			array(
				'id'        => Settings::COMMON_BASE_SLUG,
				'name'      => __( 'Common Base Slug', 'drppsm' ),
				'type'      => 'checkbox',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add date format field.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function date_format( CMB2 $cmb ): void {
		$desc = __( 'Used only in admin area, when creating a new Sermon', 'drppsm' );
		$cmb->add_field(
			array(
				'id'         => Settings::DATE_FORMAT,
				'name'       => __( 'Date Format', 'drppsm' ),
				'type'       => 'select',
				'options'    => array(
					'F j, Y, g:i A' => 'Febuary 15, 1971, 5:00 AM',
					'F j, Y'        => 'Febuary 15, 1971',
					'M j, Y'        => 'Feb 15, 1971',
					'm/d/Y'         => '02/15/1971',
					'Y/m/d'         => '1971/02/15',
					'Y-m-d'         => '1971-02-15',
				),
				'after_row'  => $this->description( $desc ),
				'before_row' => function () {
					$this->add_section( __( 'Sermon Settings', 'drppsm' ) );
				},

			)
		);
	}

	/**
	 * Add archive order by.
	 *
	 * @param CMB2 $cmb CMB2 object.
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
				'after_row'        => $this->description( $desc ),
				'before_row'       => function () {
					$this->add_section( __( 'Archive Settings', 'drppsm' ) );
				},
			)
		);
	}

	/**
	 * Add archive order.
	 *
	 * @param CMB2 $cmb CMB2 object.
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
				'after_row'        => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add sermon layout field.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function sermon_layout( CMB2 $cmb ): void {
		$desc = __( 'How sermon archive pages will be displayed.', 'drppsm' );
		$cmb->add_field(
			array(
				'id'        => Settings::SERMON_LAYOUT,
				'name'      => __( 'Layout', 'drppsm' ),
				'type'      => 'select',
				'options'   => Settings::SERMON_LAYOUT_OPTS,
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add sermon count field.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function sermon_count( CMB2 $cmb ): void {
		$desc = __( 'Affects only the default number, other settings will override it', 'drppsm' );
		$cmb->add_field(
			array(
				'id'         => Settings::SERMON_COUNT,
				'name'       => __( 'Per Page', 'drppsm' ),
				'type'       => 'text',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
				),
				'after_row'  => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add sermon single label.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function sermon_single( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/sermon/mark', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/lecture/mark', 'drppsm' ) . '</code>';

		$desc = AdminMsg::label_single() . '<br>';

		$desc .= wp_sprintf(
			// translators: %1$s Default sermon slug/path. Effectively <code>/sermon/mark</code>.
			// translators: %2$s Example lecture slug/path. Effectively <code>/lecture/mark</code>.
			__( 'Changing "Sermon" to "Lecture" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . AdminMsg::slug_note();

		$cmb->add_field(
			array(
				'id'         => Settings::SERMON_SINGULAR,
				'name'       => __( 'Singular Label', 'drppsm' ),
				'type'       => 'text',
				'after_row'  => $this->description( $desc ),
				'before_row' => function () {
					$this->add_section( __( 'Sermon Labels', 'drppsm' ) );
				},
			)
		);
	}

	/**
	 * Add sermon plural label.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function sermon_plural( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/sermons/', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/lectures/', 'drppsm' ) . '</code>';

		$desc  = AdminMsg::label_plural() . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default series slug/path. Effectively <code>/sermons/</code>.
			// translators: %2$s Example listings slug/path. Effectively <code>/lectures/</code>.
			__( 'Changing "Sermons" to "Lectures" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . AdminMsg::slug_note();

		$cmb->add_field(
			array(
				'id'        => Settings::SERMON_PLURAL,
				'name'      => __( 'Plural Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}
}
