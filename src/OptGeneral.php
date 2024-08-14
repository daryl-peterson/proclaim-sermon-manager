<?php
/**
 * General settings.
 *
 * @package
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use CMB2;
use DRPPSM\Constants\Actions;
use DRPPSM\Constants\Filters;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logging\Logger;

/**
 * General settings.
 *
 * @package
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
		$this->app_settings();
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
	 * Register callbacks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_action( Actions::REGISTER_SETTINGS_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( Filters::OPTIONS_MAIN_MENU, array( $this, 'set_menu' ) );

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


	public function pre_proccess( mixed $obj, mixed $object_id ) {
		Logger::debug(
			array(
				'OBJ'    => $obj,
				'OBJ ID' => $object_id,
			)
		);
	}

	public function register_metaboxes( callable $display_cb ) {

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

		$cmb->add_field(
			array(
				'id'               => Settings::FIELD_MENU_ICON,
				'name'             => __( 'Menu Icon', 'drppsm' ),
				'desc'             => '',
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
			)
		);

		$cmb->add_field(
			array(
				'id'               => Settings::FIELD_DATE_FORMAT,
				'name'             => __( 'Menu Icon', 'drppsm' ),
				'desc'             => '',
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'mm/dd/YY' => 'mm/dd/YY',
					'dd/mm/YY' => 'dd/mm/YY',
					'YY/mm/dd' => 'YY/mm/dd',
					'YY/dd/mm' => 'YY/dd/mm',
				),
				'default'          => Settings::DEFAULT_DATE_FORMAT,
			)
		);
		$cmb->add_field(
			array(
				'id'         => Settings::FIELD_SERMON_COUNT,
				'name'       => __( 'Sermons Per Page', 'drppsm' ),
				'desc'       => __( 'Affects only the default number', 'drppsm' ),
				'type'       => 'text',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
				),
				'default'    => Settings::DEFAULT_SERMON_COUNT,
			)
		);

		$this->add_archive( $cmb );

		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_COMMON_BASE_SLUG,
				'name'    => __( 'Common Base Slug', 'drppsm' ),
				'desc'    => '
					If this option is checked, the taxonomies would also be under the slug set above.
					For example, by default, series named “James” would be under /sermons/series/james,
					preacher “Paul” would be under /sermons/preacher/paul, and so on.',
				'type'    => 'checkbox',
				'default' => Settings::DEFAULT_COMMON_BASE_SLUG,
			)
		);

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

		$option_int = get_options_int();
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
	 * Add archive field
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_archive( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/sermons', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/sermons/jesus', 'drppsm' ) . '</code>';

		$desc = __( 'This controls the page where sermons will be located, which includes single sermons.', 'drppsm' );

		$desc .= wp_sprintf(
			// translators: %1$s Default archive path, effectively <code>/sermons</code>.
			// translators: %2$s Example single sermon path, effectively <code>/sermons/jesus</code>.
			__(
				'By default all sermons would be located under %1$s, and a single sermon with slug “jesus” would be under %2$s.',
				'drppsm'
			),
			$s1,
			$s2
		);

		$desc .= __( 'Does not apply if "pretty permalinks" are not turned on.', 'drppsm' );

		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_ARCHIVE_SLUG,
				'name'    => __( 'Archive Page Slug', 'drppsm' ),
				'desc'    => $desc,
				'type'    => 'text',
				'default' => Settings::DEFAULT_ARCHIVE_SLUG,
			)
		);
	}

	/**
	 * Add preacher field.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_preacher( CMB2 $cmb ): void {
		$s1    = '<code>' . __( '/preacher/mark', 'drppsm' ) . '</code>';
		$s2    = '<code>' . __( '/reverend/mark', 'drppsm' ) . '</code>';
		$desc  = __( 'Label in singular form. You change the default Preacher to anything you like.', 'drppsm' );
		$desc .= __( '"Reverend", for example). Note: This also change the slugs.', 'drppsm' );

		$desc .= wp_sprintf(
			// translators: %1$s Default preacher slug/path. Effectively <code>/preacher/mark</code>.
			// translators: %2$s Example reverend slug/path. Effectively <code>/speaker/mark</code>.
			__( 'For example, %1$s would become %2$s.', 'drppsm' ),
			$s1,
			$s2
		);

		/**
		 * Preacher label.
		 */
		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_PREACHER,
				'name'    => __( 'Preacher Label', 'drppsm' ),
				'desc'    => $desc,
				'type'    => 'text',
				'default' => Settings::DEFAULT_PREACHER,
			)
		);
	}

	/**
	 * Add service type.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_service_type( CMB2 $cmb ): void {
		$desc  = __( 'Put the label in singular form. ', 'drppsm' );
		$desc .= __( 'You change the default Service Type label to anything you wish.', 'drppsm' );
		$desc .= __( '("Congregation", for example).	Note: it will also change the slugs.', 'drppsm' );

		$s1 = '<code>' . __( '/service-type/morning', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/congregation/monring', 'drppsm' ) . '</code>';

		$desc .= wp_sprintf(
				// translators: %1$s Default slug/path. Effectively <code>/service-type/morning</code>.
				// translators: %2$s Example changed slug/path. Effectively <code>/congregation/morning</code>.
			__( 'Note: it will also change the slugs. For example, %1$s would become %2$s.', 'drppsm' ),
			$s1,
			$s2
		);

		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_SERVICE_TYPE,
				'name'    => __( 'Service Type Label', 'drppsm' ),
				'desc'    => $desc,
				'type'    => 'text',
				'default' => Settings::DEFAULT_SERVICE_TYPE,
			)
		);
	}

	/**
	 * Set app settings.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function app_settings() {
		$option_int = get_options_int();
		$options    = (array) $option_int->get( self::OPTION_KEY, array() );

		app()->set_setting( $options );
	}
}
