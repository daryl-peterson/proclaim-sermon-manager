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

use DRPPSM\Constants\Actions;
use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\OptionsInt;
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

	/**
	 * Get initialize object instance.
	 *
	 * @return PermaLinkInt
	 * @since 1.0.0
	 */
<<<<<<< HEAD
	public static function init(): PermaLinkInt {
=======
	public static function exec(): PermaLinkInt {
>>>>>>> 822b76c (Refactoring)
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

		/**
		 * Options interface.
		 *
<<<<<<< HEAD
		 * @var OptionsInt $opts
		 */
		$opts = App::init()->get( OptionsInt::class );
=======
		 * @var OptionsInt $opts Options interface.
		 */
		$opts = options();
>>>>>>> 822b76c (Refactoring)

		$perm = wp_parse_args(
			(array) $opts->get( 'permalinks', array() ),
			array(
				Tax::PREACHER            => get_slug(
					Settings::FIELD_PREACHER,
					_x(
						'preacher',
						'slug',
						'drppsm'
					)
				),
				Tax::SERIES              => '',
				Tax::TOPICS              => '',
				Tax::BIBLE_BOOK          => '',
				Tax::SERVICE_TYPE        => get_slug(
					Settings::FIELD_SERVICE_TYPE,
					_x(
						'service-type',
						'slug',
						'drppsm'
					)
				),
				PT::SERMON               => get_slug(
					Settings::FIELD_ARCHIVE_SLUG,
					_x(
						'sermons',
						'slug',
						'drppsm'
					)
				),
				'use_verbose_page_rules' => false,
			)
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
		if ( $opts->get( 'common_base_slug' ) ) {
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
