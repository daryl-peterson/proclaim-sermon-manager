<?php
/**
 * Settings constants.
 *
 * @package     DRPPSM\Settings
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Traits\SingletonTrait;
use Exception;

/**
 * Settings constants.
 *
 * @package     DRPPSM\Settings
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Settings {
	use SingletonTrait;


	/**
	 * Option key for display tab of settings page.
	 *
	 * @since 1.0.0
	 */
	public const OPTION_KEY_DISPLAY = 'drppsm_option_display';

	/**
	 * Option key for general tab of settings page.
	 *
	 * @since 1.0.0
	 */
	public const OPTION_KEY_GENERAL = 'drppsm_option_general';

	/**
	 * Option key for advanced tab of settings page.
	 *
	 * @since 1.0.0
	 */
	public const OPTION_KEY_ADVANCED = 'drppsm_option_advanced';

	/**
	 * Option key for sermons.
	 *
	 * @since 1.0.0
	 */
	public const OPTION_KEY_SERMONS = 'drppsm_option_sermons';


	/**
	 * Option key for series.
	 *
	 * @since 1.0.0
	 */
	public const OPTION_KEY_SERIES = 'drppsm_option_series';

	/**
	 * Default image key.
	 *
	 * @since 1.0.0
	 */
	public const DEFAULT_IMAGE = 'default_image';



	/**
	 * Bible book label key.
	 *
	 * @since 1.0.0
	 */
	public const BIBLE_BOOK = 'book_label';

	/**
	 * Bible book load key.
	 * - Used to force reloading bible books.
	 *
	 * @since 1.0.0
	 */
	public const BIBLE_BOOK_LOAD = 'bible_book_load';

	/**
	 * Bible book sort key.
	 *
	 * @since 1.0.0
	 */
	public const BIBLE_BOOK_SORT = 'bible_book_sort';

	/**
	 * Cron interval key expressed in hours.
	 *
	 * - How often to run the cron job.
	 *
	 * @since 1.0.0
	 */
	public const CRON_INTERVAL = 'cron_interval';

	/**
	 * Enable / disable comments key.
	 *
	 * @since 1.0.0
	 */
	public const COMMENTS = 'comments';

	/**
	 * Common base slug key.
	 *
	 * @since 1.0.0
	 */
	public const COMMON_BASE_SLUG = 'common_base_slug';

	public const DATE_FORMAT = 'date_format';
	public const DISABLE_CSS = 'disable_css';
	public const MENU_ICON   = 'menu_icon';
	public const PLAYER      = 'player';


	/**
	 * Hide all filtering.
	 *
	 * @since 1.0.0
	 */
	public const HIDE_FILTERS = 'hide_filters';

	/**
	 * Hide from filtering.
	 *
	 * @since 1.0.0
	 */
	public const HIDE_BOOKS = 'hide_books';

	/**
	 * Hide preachers from filtering.
	 *
	 * @since 1.0.0
	 */
	public const HIDE_PREACHERS = 'hide_preachers';

	/**
	 * Hide series from filtering.
	 *
	 * @since 1.0.0
	 */
	public const HIDE_SERIES = 'hide_series';

	/**
	 * Hide service types from filtering.
	 *
	 * @since 1.0.0
	 */
	public const HIDE_SERVICE_TYPES = 'hide_service_types';

	/**
	 * Hide topics from filtering.
	 *
	 * @since 1.0.0
	 */
	public const HIDE_TOPICS = 'hide_topics';

	/**
	 * Preacher singular label key.
	 *
	 * @since 1.0.0
	 */
	public const PREACHER_SINGULAR = 'preacher_singular';

	/**
	 * Preacher plural label key.
	 *
	 * @since 1.0.0
	 */
	public const PREACHER_PLURAL = 'preacher_plural';

	/**
	 * Series singular label key.
	 *
	 * @since 1.0.0
	 */
	public const SERIES_SINGULAR = 'series_singular';

	/**
	 * Series plural label key.
	 *
	 * @since 1.0.0
	 */
	public const SERIES_PLURAL = 'series_plural';

	// ------------------------------------------------------------------------
	// SERMON SETTINGS.
	// ------------------------------------------------------------------------

	/**
	 * Sermon count key.
	 *
	 * - Number of sermons to display on archive page.
	 *
	 * @since 1.0.0
	 */
	public const SERMON_COUNT = 'sermon_count';

	/**
	 * Sermon singular label key.
	 *
	 * @since 1.0.0
	 */
	public const SERMON_SINGULAR = 'sermon_singular';

	/**
	 * Sermon plural label key.
	 *
	 * @since 1.0.0
	 */
	public const SERMON_PLURAL = 'sermon_plural';

	/**
	 * Sermon layout key.
	 *
	 * @since 1.0.0
	 */
	public const SERMON_LAYOUT = 'sermon_layout';

	/**
	 * Sermon archive order key.
	 *
	 * @since 1.0.0
	 */
	public const ARCHIVE_ORDER = 'archive_order';

	/**
	 * Sermon archive order by key.
	 *
	 * @since 1.0.0
	 */
	public const ARCHIVE_ORDER_BY = 'archive_order_by';

	/**
	 * Disable sermon images.
	 *
	 * @since 1.0.0
	 */
	public const ARCHIVE_DISABLE_IMAGE = 'archive_disable_image';

	// ------------------------------------------------------------------------
	// SERVICE TYPE SETTINGS.
	// ------------------------------------------------------------------------


	/**
	 * Service type singular label key.
	 *
	 * @since 1.0.0
	 */
	public const SERVICE_TYPE_SINGULAR = 'service_type_singular';

	/**
	 * Service type plural label key.
	 *
	 * @since 1.0.0
	 */
	public const SERVICE_TYPE_PLURAL = 'service_type_plural';

	/**
	 * Post view count key.
	 *
	 * @since 1.0.0
	 */
	public const POST_VIEW_COUNT = 'post_view_count';

	/**
	 * Images per row key. Used in the sermon images shortcode.
	 *
	 * @since 1.0.0
	 */
	public const IMAGES_PER_ROW = 'images_per_row';



	/**
	 * Option keys used to loop through options and set defaults.
	 *
	 * @since 1.0.0
	 */
	private const OPTION_KEYS = array(
		self::OPTION_KEY_GENERAL,
		self::OPTION_KEY_DISPLAY,
		self::OPTION_KEY_ADVANCED,
		self::OPTION_KEY_SERMONS,
		self::OPTION_KEY_SERIES,
	);

	/**
	 * Option key map, used to map option to page.
	 *
	 * @since 1.0.0
	 */
	private const OPTION_KEY_MAP = array(

		self::BIBLE_BOOK            => self::OPTION_KEY_GENERAL,
		self::COMMENTS              => self::OPTION_KEY_GENERAL,


		self::DEFAULT_IMAGE         => self::OPTION_KEY_GENERAL,
		self::MENU_ICON             => self::OPTION_KEY_GENERAL,
		self::PLAYER                => self::OPTION_KEY_GENERAL,

		// Preacher settings.
		self::PREACHER_SINGULAR     => self::OPTION_KEY_GENERAL,
		self::PREACHER_PLURAL       => self::OPTION_KEY_GENERAL,

		// Series settings.
		self::SERIES_SINGULAR       => self::OPTION_KEY_GENERAL,
		self::SERIES_PLURAL         => self::OPTION_KEY_GENERAL,

		// Service type settings.
		self::SERVICE_TYPE_SINGULAR => self::OPTION_KEY_GENERAL,
		self::SERVICE_TYPE_PLURAL   => self::OPTION_KEY_GENERAL,






		// Filter settings.
		self::HIDE_BOOKS            => self::OPTION_KEY_DISPLAY,
		self::HIDE_FILTERS          => self::OPTION_KEY_DISPLAY,
		self::HIDE_PREACHERS        => self::OPTION_KEY_DISPLAY,
		self::HIDE_SERIES           => self::OPTION_KEY_DISPLAY,
		self::HIDE_SERVICE_TYPES    => self::OPTION_KEY_DISPLAY,
		self::HIDE_TOPICS           => self::OPTION_KEY_DISPLAY,

		self::DISABLE_CSS           => self::OPTION_KEY_DISPLAY,
		self::IMAGES_PER_ROW        => self::OPTION_KEY_DISPLAY,

		self::BIBLE_BOOK_LOAD       => self::OPTION_KEY_ADVANCED,
		self::BIBLE_BOOK_SORT       => self::OPTION_KEY_ADVANCED,
		self::POST_VIEW_COUNT       => self::OPTION_KEY_ADVANCED,
		self::CRON_INTERVAL         => self::OPTION_KEY_ADVANCED,

		// Sermon settings.
		self::DATE_FORMAT           => self::OPTION_KEY_SERMONS,
		self::COMMON_BASE_SLUG      => self::OPTION_KEY_SERMONS,
		self::ARCHIVE_ORDER         => self::OPTION_KEY_SERMONS,
		self::ARCHIVE_ORDER_BY      => self::OPTION_KEY_SERMONS,
		self::ARCHIVE_DISABLE_IMAGE => self::OPTION_KEY_SERMONS,
		self::SERMON_COUNT          => self::OPTION_KEY_SERMONS,
		self::SERMON_SINGULAR       => self::OPTION_KEY_SERMONS,
		self::SERMON_PLURAL         => self::OPTION_KEY_SERMONS,
		self::SERMON_LAYOUT         => self::OPTION_KEY_SERMONS,

	);

	/**
	 * Used to store default option values.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private static array $option_default;

	/**
	 * Used to store option values.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private static array $options;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		self::init_defaults();
		if ( ! isset( self::$options ) ) {
			foreach ( self::OPTION_KEYS as $key_name ) {
				$result = \get_option( $key_name, false );
				if ( $result ) {
					self::$options[ $key_name ] = $result;
				} else {
					self::$options[ $key_name ] = array();
				}
			}
		}
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

			self::OPTION_KEY_GENERAL  => array(
				self::BIBLE_BOOK            => 'book',
				self::COMMENTS              => false,

				self::DEFAULT_IMAGE         => '',
				self::MENU_ICON             => 'dashicons-drppsm-holy-spirit',
				self::PLAYER                => 'plyr',

				// ------------------------------------------------------------
				// PREACHER SETTINGS
				// ------------------------------------------------------------

				/* translators: preacher singular */
				self::PREACHER_SINGULAR     => __( 'Preacher', 'drppsm' ),

				/* translators: preacher plural */
				self::PREACHER_PLURAL       => __( 'Preachers', 'drppsm' ),

				// ------------------------------------------------------------
				// SERIES SETTINGS
				// ------------------------------------------------------------

				/* translators: series singular */
				self::SERIES_SINGULAR       => __( 'Series', 'drppsm' ),

				/* translators: series plural */
				self::SERIES_PLURAL         => __( 'Series', 'drppsm' ),

				// --------------------------------------------------------------
				// SERVICE TYPE SETTINGS
				// --------------------------------------------------------------

				/* translators: service type singular */
				self::SERVICE_TYPE_SINGULAR => __( 'Service Type', 'drppsm' ),

				/* translators: service type plural */
				self::SERVICE_TYPE_PLURAL   => __( 'Service Types', 'drppsm' ),
			),

			self::OPTION_KEY_DISPLAY  => array(
				self::DISABLE_CSS        => false,
				self::HIDE_BOOKS         => false,
				self::HIDE_FILTERS       => false,
				self::HIDE_PREACHERS     => false,
				self::HIDE_SERIES        => false,
				self::HIDE_SERVICE_TYPES => false,
				self::HIDE_TOPICS        => false,
				self::IMAGES_PER_ROW     => 3,

			),
			self::OPTION_KEY_ADVANCED => array(
				self::BIBLE_BOOK_LOAD => true,
				self::BIBLE_BOOK_SORT => false,
				self::POST_VIEW_COUNT => false,
				self::CRON_INTERVAL   => 2,

			),

			self::OPTION_KEY_SERIES   => array(),

			self::OPTION_KEY_SERMONS  => array(
				self::ARCHIVE_ORDER         => 'desc',
				self::ARCHIVE_ORDER_BY      => 'date_preached',
				self::ARCHIVE_DISABLE_IMAGE => false,
				self::COMMON_BASE_SLUG      => false,
				self::DATE_FORMAT           => 'F j, Y',
				self::SERMON_COUNT          => 10,

				/* translators: sermon singular */
				self::SERMON_SINGULAR       => __( 'Sermon', 'drppsm' ),

				/* translators: sermon plural */
				self::SERMON_PLURAL         => __( 'Sermons', 'drppsm' ),

				self::SERMON_LAYOUT         => 'grid',
			),

		);
	}


	/**
	 * Get options value.
	 *
	 * @param string $key Options key.
	 * @param mixed  $default_value Default value to return if not found.
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get( string $key, mixed $default_value = null ): mixed {
		self::get_instance();
		$option_key = self::get_option_key( $key );

		if ( ! $option_key ) {
			return $default_value;
		}

		if ( ! isset( self::$options[ $option_key ] ) ) {
			$result = \get_option( $option_key, false );
			if ( $result ) {
				self::$options[ $option_key ] = $result;
			} else {
				self::$options[ $option_key ] = array();
			}
		}

		if ( ! is_array( self::$options[ $option_key ] ) || ! key_exists( $key, self::$options[ $option_key ] ) ) {
			return $default_value;
		}
		return self::$options[ $option_key ][ $key ];
	}

	/**
	 * Get default value.
	 *
	 * @param string     $key Options key.
	 * @param mixed|null $default_value Default value to return if not found.
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get_default( string $key, mixed $default_value = null ): mixed {
		self::get_instance();

		$option_key = self::get_option_key( $key );

		if ( ! isset( self::$option_default[ $option_key ] ) ) {
			return $default_value;
		}

		if ( ! key_exists( $key, self::$option_default[ $option_key ] ) ) {
			return $default_value;
		}

		return self::$option_default[ $option_key ][ $key ];
	}

	/**
	 * Get defaults for an option page.
	 *
	 * @param string $option_key Options key.
	 * @return null|array
	 * @since 1.0.0
	 */
	public static function get_defaults( string $option_key ): ?array {
		self::get_instance();

		if ( ! isset( self::$option_default[ $option_key ] ) ) {
			return null;
		}
		return self::$option_default[ $option_key ];
	}

	/**
	 * Set option value
	 *
	 * @param string $key Options key.
	 * @param mixed  $value Option value.
	 * @return boolean
	 * @throws Exception Throws exception when option is not found.
	 * @since 1.0.0
	 */
	public static function set( string $key, mixed $value ): bool {
		self::get_instance();

		try {
			$option_key = self::get_option_key( $key );
			if ( ! $option_key ) {
				throw new Exception( 'Option key not found : ' . $key );
			}

			if ( ! isset( self::$options[ $option_key ] ) ) {
				self::$options[ $option_key ] = \get_option( $option_key, array() );
			}
			if ( ! is_array( self::$options[ $option_key ] ) ) {
				self::$options[ $option_key ] = array();

			}
			self::$options[ $option_key ][ $key ] = $value;
			return \update_option( $option_key, self::$options[ $option_key ] );
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'ERROR' => $th->getMessage(),
					'TRACE' => $th->getTrace(),
				)
			);
		}
		return false;
	}

	/**
	 * Set default options.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function set_defaults() {
		self::get_instance();
		foreach ( self::OPTION_KEYS as $option_key ) {
			$result = \get_option( $option_key, false );
			if ( ! $result ) {
				$defaults                     = self::get_defaults( $option_key );
				self::$options[ $option_key ] = $defaults;
				update_option( $option_key, self::$options[ $option_key ] );
			}
		}
	}

	/**
	 * Get primary option key based on search key.
	 *
	 * @param string $key Options key.
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
