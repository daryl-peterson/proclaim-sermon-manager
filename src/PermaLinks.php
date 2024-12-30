<?php
/**
 * Permalink singleton.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\PT;
use DRPPSM\Interfaces\PermaLinkInt;
use DRPPSM\Traits\SingletonTrait;

/**
 * Permalink singleton.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class PermaLinks implements PermaLinkInt {

	use SingletonTrait;

	/**
	 * Permalinks array.
	 *
	 * @var array
	 */
	private array $permalinks;

	/**
	 * Text domain
	 *
	 * @var TextDomain
	 */
	private TextDomain $text;

	protected function __construct() {
		$this->text = TextDomain::exec();
	}

	/**
	 * Get initialize object instance.
	 *
	 * @return PermaLinkInt
	 * @since 1.0.0
	 */
	public static function exec(): PermaLinkInt {
		return self::get_instance();
	}

	/**
	 * Register hooks.
	 *
	 * @todo Impliment.
	 * @return boolean
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		return true;
	}

	/**
	 * Get permalinks array.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function get(): array {
		$this->config();
		return $this->permalinks;
	}

	/**
	 * Fix permalink options
	 *
	 * @param string $slug Name of slug.
	 * @param string $default_value Default value if not found.
	 * @return string
	 * @since 1.0.0
	 */
	public static function fix_permalink( string $slug, string $default_value = '' ): string {
		// $value = OptGeneral::get( $slug, $default_value );
		$value = Settings::get( $slug, $default_value );
		$value = trim( sanitize_title( $value ) );
		return untrailingslashit( $value );
	}


	/**
	 * Get service type
	 *
	 * @return string
	 */
	private function get_service_type(): string {
		return self::fix_permalink(
			Settings::SERVICE_TYPE,
			_x( 'Service Type', 'slug', 'drppsm' )
		);
	}

	/**
	 * Get preacher
	 *
	 * @return string
	 */
	private function get_preacher(): string {
		return self::fix_permalink(
			Settings::PREACHER,
			_x( 'preacher', 'slug', 'drppsm' )
		);
	}

	/**
	 * Get sermon
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function get_sermon(): string {
		return self::fix_permalink(
			Settings::ARCHIVE_SLUG,
			_x( 'sermons', 'slug', 'drppsm' )
		);
	}

	/**
	 * Set configuration
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function config(): void {

		if ( isset( $this->permalinks ) && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}

		$action_key = Helper::get_key_name( 'PERMALINK_CONFIG' );
		if ( did_action( $action_key ) && ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}

		if ( did_action( 'admin_init' ) ) {
			$this->text->switch_to_site_locale();
		}

		$perm = array(
			Tax::PREACHER            => $this->get_preacher(),
			Tax::SERIES              => '',
			Tax::TOPICS              => '',
			Tax::BIBLE_BOOK          => '',
			Tax::SERVICE_TYPE        => $this->get_service_type(),
			PT::SERMON               => $this->get_sermon(),
			'use_verbose_page_rules' => false,
		);

		// Ensure rewrite slugs are set.
		$perm[ Tax::SERIES ] = empty( $perm[ Tax::SERIES ] ) ?
			_x( 'series', 'slug', 'drppsm' ) : $perm[ Tax::SERIES ];

		$perm[ Tax::TOPICS ] = empty( $perm[ Tax::TOPICS ] ) ?
			_x( 'topics', 'slug', 'drppsm' ) : $perm[ Tax::TOPICS ];

		$perm[ Tax::BIBLE_BOOK ] = empty( $perm[ Tax::BIBLE_BOOK ] ) ?
			_x( 'book', 'slug', 'drppsm' ) : $perm[ Tax::BIBLE_BOOK ];

		$perm[ PT::SERMON ] = empty( $perm[ PT::SERMON ] ) ?
			_x( 'sermons', 'slug', 'drppsm' ) : $perm[ PT::SERMON ];

		foreach ( $perm as $key => $value ) {
			$perm[ $key ] = untrailingslashit( $value );
		}

		// @todo fix
		$common = Settings::get( Settings::COMMON_BASE_SLUG );
		if ( $common ) {
			Logger::debug( array( 'COMMON BASE' => $common ) );
			foreach ( $perm as $name => &$permalink ) {
				if ( PT::SERMON === $name ) {
					continue;
				}

				$permalink = $perm[ PT::SERMON ] . '/' . $permalink;
			}
		}

		if ( did_action( 'admin_init' ) ) {
			// @codeCoverageIgnoreStart
			$this->text->restore_locale();
			// @codeCoverageIgnoreEnd
		}

		$hook = Helper::get_key_name( 'permalink_structure' );

		/*
		 * Allows to easily modify the slugs of sermons and taxonomies.
		 *
		 * @param array $perm Existing permalinks structure.
		 * @since 1.0.0
		 */
		$this->permalinks = apply_filters( $hook, $perm );
		do_action( $action_key );
	}
}
