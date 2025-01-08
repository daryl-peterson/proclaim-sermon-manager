<?php
/**
 * Settings constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

/**
 * Settings constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Settings {
	public const ARCHIVE_SLUG          = 'archive_slug';
	public const ARCHIVE_ORDER         = 'archive_order';
	public const ARCHIVE_ORDER_BY      = 'archive_order_by';
	public const ARCHIVE_DISABLE_IMAGE = 'archive_disable_image';

	public const BIBLE_BOOK      = 'book_label';
	public const BIBLE_BOOK_LOAD = 'bible_book_load';

	public const COMMENTS         = 'comments';
	public const COMMON_BASE_SLUG = 'common_base_slug';
	public const DATE_FORMAT      = 'date_format';
	public const DISABLE_CSS      = 'disable_css';
	public const MENU_ICON        = 'menu_icon';
	public const PLAYER           = 'player';
	public const PREACHER         = 'preacher_label';

	public const HIDE_BOOKS         = 'hide_books';
	public const HIDE_FILTERS       = 'hide_filters';
	public const HIDE_PREACHERS     = 'hide_preachers';
	public const HIDE_SERIES        = 'hide_series';
	public const HIDE_SERVICE_TYPES = 'hide_service_types';
	public const HIDE_TOPICS        = 'hide_topics';

	public const SERIES = 'series_label';

	public const SERMON_COUNT = 'sermon_count';
	public const SERVICE_TYPE = 'service_type_label';

	public const OPTION_KEY_DISPLAY  = 'drppsm_option_display';
	public const OPTION_KEY_GENERAL  = 'drppsm_option_general';
	public const OPTION_KEY_ADVANCED = 'drppsm_option_advanced';


	/**
	 * Option key map, used to map option to page.
	 *
	 * @since 1.0.0
	 */
	public const OPTION_KEY_MAP = array(
		self::ARCHIVE_SLUG          => self::OPTION_KEY_GENERAL,
		self::BIBLE_BOOK            => self::OPTION_KEY_GENERAL,
		self::COMMENTS              => self::OPTION_KEY_GENERAL,
		self::COMMON_BASE_SLUG      => self::OPTION_KEY_GENERAL,
		self::DATE_FORMAT           => self::OPTION_KEY_GENERAL,
		self::MENU_ICON             => self::OPTION_KEY_GENERAL,
		self::PLAYER                => self::OPTION_KEY_GENERAL,
		self::PREACHER              => self::OPTION_KEY_GENERAL,
		self::SERIES                => self::OPTION_KEY_GENERAL,
		self::SERMON_COUNT          => self::OPTION_KEY_GENERAL,
		self::SERVICE_TYPE          => self::OPTION_KEY_GENERAL,

		self::ARCHIVE_ORDER         => self::OPTION_KEY_DISPLAY,
		self::ARCHIVE_ORDER_BY      => self::OPTION_KEY_DISPLAY,
		self::ARCHIVE_DISABLE_IMAGE => self::OPTION_KEY_DISPLAY,


		self::BIBLE_BOOK_LOAD       => self::OPTION_KEY_DISPLAY,

		self::DISABLE_CSS           => self::OPTION_KEY_DISPLAY,

		self::HIDE_BOOKS            => self::OPTION_KEY_DISPLAY,
		self::HIDE_FILTERS          => self::OPTION_KEY_DISPLAY,
		self::HIDE_PREACHERS        => self::OPTION_KEY_DISPLAY,
		self::HIDE_SERIES           => self::OPTION_KEY_DISPLAY,
		self::HIDE_SERVICE_TYPES    => self::OPTION_KEY_DISPLAY,
		self::HIDE_TOPICS           => self::OPTION_KEY_DISPLAY,
	);




	private static array $option_default;

	/**
	 * Get options value
	 *
	 * @param string $key
	 * @param mixed  $default_value
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get( string $key, mixed $default_value = null ): mixed {
		$option_key = self::get_option_key( $key );

		if ( ! $option_key ) {
			return $default_value;
		}

		$options = \get_option( $option_key, array() );

		if ( ! is_array( $options ) || ! key_exists( $key, $options ) ) {
			return $default_value;
		}

		return $options[ $key ];
	}

	/**
	 * Get default value.
	 *
	 * @param string     $key
	 * @param mixed|null $default_value
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get_default( string $key, mixed $default_value = null ): mixed {
		self::init_defaults();
		$option_key = self::get_option_key( $key );

		if ( ! isset( self::$option_default[ $option_key ] ) ) {
			return $default_value;
		}

		if ( ! key_exists( $key, self::$option_default[ $option_key ] ) ) {
			return $default_value;
		}

		$result = self::$option_default[ $option_key ][ $key ];
		return self::$option_default[ $option_key ][ $key ];
	}

	/**
	 * Get defaults for an option page.
	 *
	 * @param string $option_key
	 * @return null|array
	 * @since 1.0.0
	 */
	public static function get_defaults( string $option_key ): ?array {
		self::init_defaults();

		if ( ! isset( self::$option_default[ $option_key ] ) ) {
			return null;
		}
		return self::$option_default[ $option_key ];
	}

	/**
	 * Set option value
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function set( string $key, mixed $value ): bool {
		$option_key = self::get_option_key( $key );

		if ( ! $option_key ) {
			return false;
		}

		$options = get_option( $option_key, false );
		if ( ! $options ) {
			$options         = array();
			$options[ $key ] = $value;
			return \add_option( $option_key, $options );
		}

		$options[ $key ] = $value;
		return \update_option( $option_key, $option_key );
	}

	/**
	 * Initialize private static variables.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private static function init_defaults() {
		if ( isset( self::$option_default ) ) {
			return;
		}
		self::$option_default = array(

			self::OPTION_KEY_GENERAL => array(
				self::ARCHIVE_SLUG     => 'Sermons',
				self::BIBLE_BOOK       => 'book',
				self::COMMENTS         => false,
				self::COMMON_BASE_SLUG => false,
				self::DATE_FORMAT      => 'mm/dd/YY',
				self::MENU_ICON        => 'dashicons-drppsm-holy-spirit',
				self::PLAYER           => 'plyr',
				self::PREACHER         => 'Preacher',
				self::SERIES           => 'Series',
				self::SERMON_COUNT     => 10,
				self::SERVICE_TYPE     => 'Service Type',
			),

			self::OPTION_KEY_DISPLAY => array(
				self::ARCHIVE_ORDER         => 'desc',
				self::ARCHIVE_ORDER_BY      => 'date_preached',
				self::ARCHIVE_DISABLE_IMAGE => false,
				self::BIBLE_BOOK_LOAD       => false,
				self::DISABLE_CSS           => false,

				self::HIDE_BOOKS            => false,
				self::HIDE_FILTERS          => false,
				self::HIDE_PREACHERS        => false,
				self::HIDE_SERIES           => false,
				self::HIDE_SERVICE_TYPES    => true,
				self::HIDE_TOPICS           => true,
			),

		);
	}

	/**
	 * Get primary option key based on search key.
	 *
	 * @param string $key
	 * @return null|string
	 * @since 1.0.0
	 */
	private static function get_option_key( string $key ): ?string {
		if ( ! key_exists( $key, self::OPTION_KEY_MAP ) ) {
			return null;
		}
		return self::OPTION_KEY_MAP[ $key ];
	}
}
