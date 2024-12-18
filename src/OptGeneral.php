<?php
/**
 * General settings.
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
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * General settings.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class OptGeneral implements Initable, Registrable {

	public const OPTION_KEY = 'drppsm_options';

	const DEFAULTS = array(
		Settings::FIELD_MENU_ICON        => Settings::DEFAULT_MENU_ICON,
		Settings::FIELD_DATE_FORMAT      => Settings::DEFAULT_DATE_FORMAT,
		Settings::FIELD_SERMON_COUNT     => Settings::DEFAULT_SERMON_COUNT,
		Settings::FIELD_ARCHIVE_SLUG     => Settings::DEFAULT_ARCHIVE_SLUG,
		Settings::FIELD_COMMON_BASE_SLUG => Settings::DEFAULT_COMMON_BASE_SLUG,
		Settings::FIELD_PREACHER         => Settings::DEFAULT_PREACHER,
	);

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->set_defaults();
		// $this->app_settings();
	}

	/**
	 * Get initialize object.
	 *
	 * @return OptGeneral
	 * @since 1.0.0
	 */
	public static function init(): OptGeneral {
		return new self();
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( ! is_admin() || has_action( Actions::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) ) ) {
			return false;
		}

		add_action( Actions::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( DRPPSM_FLTR_SETTINGS_MM, array( $this, 'set_menu' ) );

		$object_type = 'options-page';
		$id          = self::OPTION_KEY;
		add_action( "cmb2_{$object_type}_process_fields_{$id}", array( $this, 'pre_proccess' ), 10, 2 );

		return true;
	}

	/**
	 * Set this menu as the main.
	 *
	 * @param string $menu Main menu.
	 * @return string
	 * @since 1.0.0
	 */
	public function set_menu( string $menu ): string {
		return self::OPTION_KEY;
	}

	/**
	 * Not used yet.
	 *
	 * @param mixed $obj Some variable.
	 * @param mixed $object_id Some object id.
	 * @return void
	 * @todo impliment this.
	 */
	public function pre_proccess( mixed $obj, mixed $object_id ) {
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
			'id'           => self::OPTION_KEY,
			'title'        => $title,
			'menu_title'   => $menu_title,
			'object_types' => array( 'options-page' ),
			'option_key'   => self::OPTION_KEY,
			'parent_slug'  => AdminSettings::SLUG,
			'tab_group'    => AdminSettings::TAB_GROUP,
			'tab_title'    => 'General',

		);

		// 'tab_group' property is supported in > 2.4.0.
		if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
			$args['display_cb'] = $display_cb;
		}

		$cmb = new_cmb2_box( $args );

		$this->add_menu_icon( $cmb );
		$this->add_sermon_comments( $cmb );
		$this->add_date_format( $cmb );
		$this->add_sermon_count( $cmb );
		$this->add_archive( $cmb );
		$this->add_common_base_slug( $cmb );
		$this->add_preacher( $cmb );
		$this->add_service_type( $cmb );
	}

	/**
	 * Set defaults.
	 *
	 * @return bool True if defaults were set, otherwise false.
	 * @since 1.0.0
	 */
	public function set_defaults(): bool {
		$transient_key = get_key_name( self::OPTION_KEY . '_init' );
		$transient     = get_transient( $transient_key );

		if ( ! empty( $transient ) || ( $transient ) ) {
			return false;
		}

		$option_int = options();
		$options    = (array) $option_int->get( self::OPTION_KEY, array() );

		foreach ( self::DEFAULTS as $key => $value ) {
			if ( ! key_exists( $key, $options ) ) {
				$options[ $key ] = $value;
			}
		}
		$option_int->set( self::OPTION_KEY, $options );
		set_transient( $transient_key, true );
		return true;
	}

	/**
	 * Add menu icon.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_menu_icon( CMB2 $cmb ): void {
		$desc = __( 'Allows for changing the admin menu icon.', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::FIELD_MENU_ICON,
				'name'             => __( 'Menu Icon', 'drppsm' ),
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'dashicons-drppsm-bible'       => __( 'Bible', 'drppsm' ),
					'dashicons-drppsm-bible-alt'   => __( 'Bible Alt', 'drppsm' ),
					'dashicons-drppsm-church'      => __( 'Church', 'drppsm' ),
					'dashicons-drppsm-church-alt'  => __( 'Church', 'drppsm' ),
					'dashicons-drppsm-cross'       => __( 'Cross', 'drppsm' ),
					'dashicons-drppsm-alt'         => __( 'Cross Alt', 'drppsm' ),
					'dashicons-drppsm-fish'        => __( 'Fish', 'drppsm' ),
					'dashicons-drppsm-fish-alt'    => __( 'Fish Alt', 'drppsm' ),
					'dashicons-drppsm-megaphone'   => __( 'Megaphone', 'drppsm' ),
					'dashicons-drppsm-pulpit'      => __( 'Pulpit', 'drppsm' ),
					'dashicons-drppsm-pulpit-alt'  => __( 'Pulpit Alt', 'drppsm' ),
					'dashicons-drppsm-sermon'      => __( 'Sermon', 'drppsm' ),
					'dashicons-drppsm-sermon-inv'  => __( 'Sermon Alt', 'drppsm' ),
					'dashicons-drppsm-holy-spirit' => __( 'Holy Spirit', 'drppsm' ),
				),
				'default'          => Settings::DEFAULT_MENU_ICON,
				'after_row'        => $this->description( $desc ),
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
	private function add_date_format( CMB2 $cmb ): void {
		$desc = __( 'Used only in admin area, when creating a new Sermon', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::FIELD_DATE_FORMAT,
				'name'             => __( 'Sermon Date Format', 'drppsm' ),
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'mm/dd/YY' => 'mm/dd/YY',
					'dd/mm/YY' => 'dd/mm/YY',
					'YY/mm/dd' => 'YY/mm/dd',
					'YY/dd/mm' => 'YY/dd/mm',
				),
				'default'          => Settings::DEFAULT_DATE_FORMAT,
				'after_row'        => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add sermon comments field.
	 *
	 * @param CMB2 $cmb  CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_sermon_comments( CMB2 $cmb ): void {
		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_COMMENTS,
				'name'    => __( 'Allow Comments', 'drppsm' ),
				'type'    => 'checkbox',
				'default' => Settings::DEFAULT_COMMENTS,
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
	private function add_sermon_count( CMB2 $cmb ): void {
		$desc = __( 'Affects only the default number, other settings will override it', 'drppsm' );
		$cmb->add_field(
			array(
				'id'         => Settings::FIELD_SERMON_COUNT,
				'name'       => __( 'Sermons Per Page', 'drppsm' ),
				'type'       => 'text',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
				),
				'default'    => Settings::DEFAULT_SERMON_COUNT,
				'after_row'  => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add archive field
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_archive( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/sermons', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/sermons/jesus', 'drppsm' ) . '</code>';

		$desc  = __( 'This setting determines the page where sermons will be found, including each sermon.', 'drppsm' );
		$desc .= $this->dot();

		$desc .= wp_sprintf(
			// translators: %1$s Default archive path, effectively <code>/sermons</code>.
			// translators: %2$s Example single sermon path, effectively <code>/sermons/jesus</code>.
			__(
				'By default, all sermons will be under %1$s, and a single sermon with slug of “jesus” will be under %2$s.',
				'drppsm'
			),
			$s1,
			$s2
		);
		$desc .= $this->dot() . __( 'However, this does not apply if "pretty permalinks" are not enabled.', 'drppsm' );

		$cmb->add_field(
			array(
				'id'        => Settings::FIELD_ARCHIVE_SLUG,
				'name'      => __( 'Archive Page Slug', 'drppsm' ),
				'type'      => 'text',
				'default'   => Settings::DEFAULT_ARCHIVE_SLUG,
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add common base slug.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_common_base_slug( CMB2 $cmb ): void {

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
				'id'        => Settings::FIELD_COMMON_BASE_SLUG,
				'name'      => __( 'Common Base Slug', 'drppsm' ),
				'type'      => 'checkbox',
				'default'   => Settings::DEFAULT_COMMON_BASE_SLUG,
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add preacher field.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_preacher( CMB2 $cmb ): void {
		$s1    = '<code>' . __( '/preacher/mark', 'drppsm' ) . '</code>';
		$s2    = '<code>' . __( '/reverend/mark', 'drppsm' ) . '</code>';
		$desc  = DRPPSM_MSG_LABEL_SINGLE;
		$desc .= $this->dot() . __( 'You have the option to change the default value of "Preacher" to anything you prefer.' );
		$desc .= $this->dot();

		$desc .= wp_sprintf(
			// translators: %1$s Default preacher slug/path. Effectively <code>/preacher/mark</code>.
			// translators: %2$s Example reverend slug/path. Effectively <code>/reverend/mark</code>.
			__( 'Changing "Preacher" to "Reverend" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= $this->dot() . __( 'Note: This also changes the slugs.' );

		/**
		 * Preacher label.
		 */
		$cmb->add_field(
			array(
				'id'        => Settings::FIELD_PREACHER,
				'name'      => __( 'Preacher Label', 'drppsm' ),
				'type'      => 'text',
				'default'   => Settings::DEFAULT_PREACHER,
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add service type.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_service_type( CMB2 $cmb ): void {
		$desc  = __( 'The label should be in singular form.', 'drppsm' );
		$desc .= $this->dot() . __( 'You can change the default value of "Service Type" to anything you prefer.', 'drppsm' );
		$desc .= $this->dot();

		$s1 = '<code>' . __( '/service-type/morning', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/congregation/monring', 'drppsm' ) . '</code>';

		$desc .= wp_sprintf(
				// translators: %1$s Default slug/path. Effectively <code>/service-type/morning</code>.
				// translators: %2$s Example changed slug/path. Effectively <code>/congregation/morning</code>.
			__( 'Changing "Service Type" to "Congregation" would result in %1$s becomimg %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= $this->dot() . __( 'Note that this also changes the slugs.', 'drppsm' );

		$cmb->add_field(
			array(
				'id'        => Settings::FIELD_SERVICE_TYPE,
				'name'      => __( 'Service Type Label', 'drppsm' ),
				'type'      => 'text',
				'default'   => Settings::DEFAULT_SERVICE_TYPE,
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Move description to a new line.
	 *
	 * @param string $desc Description.
	 * @return string
	 * @since 1.0.0
	 */
	private function description( string $desc ): string {
		return '<div class="description">' . $desc . '</div>';
	}

	/**
	 * Create spacing between new lines.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function dot(): string {
		return '<span class="spacer"></span>';
	}

	/**
	 * Set app settings.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function app_settings() {
		$option_int = options();
		$options    = (array) $option_int->get( self::OPTION_KEY, array() );

		app()->set_setting( $options );
	}
}
