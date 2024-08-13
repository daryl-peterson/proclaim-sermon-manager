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

use DRPPSM\Constants\Actions;
use DRPPSM\Constants\Filters;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

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
				'id'         => Settings::DEFAULT_SERMON_COUNT,
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

		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_ARCHIVE_SLUG,
				'name'    => __( 'Archive Page Slug', 'drppsm' ),
				'desc'    => '
							This controls the page where sermons will be located, which includes single sermons.
							For example, by default, all sermons would be located under /sermons, and a single sermon with slug “god” would be under /sermons/god.
							Does not apply if "pretty permalinks" are not turned on.',

				'type'    => 'text',
				'default' => Settings::DEFAULT_ARCHIVE_SLUG,
			)
		);

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

		$desc = __(
			'Put the label in singular form.
			It will change the default Preacher to anything you wish.
			("Pastor", for example). Note: it will also change the slugs.
			For example, /preacher/mark would become /pastor/mark.',
			'drppsm'
		);

		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_PREACHER_LABEL,
				'name'    => __( 'Preacher Label', 'drppsm' ),
				'desc'    => $desc,
				'type'    => 'text',
				'default' => Settings::DEFAULT_PREACHER_LABEL,
			)
		);

		$cmb->add_field(
			array(
				'id'      => Settings::FIELD_SERVICE_TYPE_LABEL,
				'name'    => __( 'Service Type Label', 'drppsm' ),
				'desc'    => '',
				'type'    => 'text',
				'default' => Settings::DEFAULT_SERVICE_TYPE_LABEL,
			)
		);
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
