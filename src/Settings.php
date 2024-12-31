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
	public const ARCHIVE_SLUG      = 'archive_slug';
	public const BIBLE_BOOK        = 'book_label';
	public const BIBLE_BOOK_SORT   = 'bible_sort';
	public const COMMENTS          = 'comments';
	public const COMMON_BASE_SLUG  = 'common_base_slug';
	public const DATE_FORMAT       = 'date_format';
	public const MENU_ICON         = 'menu_icon';
	public const PLAYER            = 'player';
	public const PREACHER          = 'preacher_label';
	public const PREACHER_SORT     = 'preacher_sort';
	public const SERIES            = 'series_label';
	public const SERIES_SORT       = 'series_sort';
	public const SERMON_COUNT      = 'sermon_count';
	public const SERVICE_TYPE      = 'service_type_label';
	public const SERVICE_TYPE_SORT = 'service_type_sort';
	public const TOPICS_SORT       = 'topics_sort';

	public const OPTION_KEY_DISPLAY = 'drppsm_option_display';
	public const OPTION_KEY_GENERAL = 'drppsm_options';



	public const OPTION_KEY_MAP = array(
		self::ARCHIVE_SLUG      => self::OPTION_KEY_GENERAL,
		self::BIBLE_BOOK        => self::OPTION_KEY_GENERAL,
		self::COMMENTS          => self::OPTION_KEY_GENERAL,
		self::COMMON_BASE_SLUG  => self::OPTION_KEY_GENERAL,
		self::DATE_FORMAT       => self::OPTION_KEY_GENERAL,
		self::MENU_ICON         => self::OPTION_KEY_GENERAL,
		self::PLAYER            => self::OPTION_KEY_GENERAL,
		self::PREACHER          => self::OPTION_KEY_GENERAL,
		self::SERIES            => self::OPTION_KEY_GENERAL,
		self::SERMON_COUNT      => self::OPTION_KEY_GENERAL,
		self::SERVICE_TYPE      => self::OPTION_KEY_GENERAL,
		self::BIBLE_BOOK_SORT   => self::OPTION_KEY_DISPLAY,
		self::PREACHER_SORT     => self::OPTION_KEY_DISPLAY,
		self::SERIES_SORT       => self::OPTION_KEY_DISPLAY,
		self::SERVICE_TYPE_SORT => self::OPTION_KEY_DISPLAY,
		self::TOPICS_SORT       => self::OPTION_KEY_DISPLAY,
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

		$options = get_option( $option_key, array() );
		if ( ! key_exists( $key, $options ) ) {
			return $default_value;
		}

		return $options[ $key ];
	}

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
				self::PLAYER           => 'Plyr',
				self::PREACHER         => __( 'Preacher', 'drppsm' ),
				self::SERIES           => 'Series',
				self::SERMON_COUNT     => 10,
				self::SERVICE_TYPE     => 'Service Type',
			),
			self::OPTION_KEY_DISPLAY => array(
				self::BIBLE_BOOK_SORT   => true,
				self::PREACHER_SORT     => true,
				self::SERIES_SORT       => true,
				self::SERVICE_TYPE_SORT => false,
				self::TOPICS_SORT       => true,
			),

		);
	}

	/**
	 * Undocumented function
	 *
	 * @param string $key
	 * @return string|null
	 */
	private static function get_option_key( string $key ): ?string {
		if ( ! key_exists( $key, self::OPTION_KEY_MAP ) ) {
			return null;
		}
		return self::OPTION_KEY_MAP[ $key ];
	}
}
