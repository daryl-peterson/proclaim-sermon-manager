<?php
/**
 * Check if any rewrite conflicts exist.
 *
 * @package     DRPPSM\Rewrite
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Traits\ExecutableTrait;

/**
 * Check if any rewrite conflicts exist.
 *
 * @package     DRPPSM\Rewrite
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Rewrite {
	use ExecutableTrait;

	/**
	 * Transients.
	 */
	const TRANS_NAME    = 'drppsm_rewrite_conflicts';
	const TRANS_TIMEOUT = DAY_IN_SECONDS;

	/**
	 * Register hooks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( Action::REWRITE_FLUSH, array( $this, 'flush' ) ) ) {
			return false;
		}

		add_action( 'init', array( $this, 'find_conflicts' ) );
		add_action( 'activate_plugin', array( $this, 'reset' ), 10, 2 );
		add_action( 'deactivate_plugin', array( $this, 'reset' ), 10, 2 );
		add_action( Action::REWRITE_FLUSH, array( $this, 'flush' ) );
		return true;
	}

	/**
	 * Flush rewrite rules
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function flush(): void {
		flush_rewrite_rules( false );
		delete_transient( self::TRANS_NAME );
		Logger::debug( 'FLUSHED REWRITE RULES' );
	}

	/**
	 * A plugin has been activated/deactivated force check.
	 *
	 * @param string  $plugin Plugin name.
	 * @param boolean $network_wide Network flag.
	 * @return void
	 * @since 1.0.0
	 */
	public function reset( string $plugin, bool $network_wide ) {
		if ( $plugin || $network_wide ) {
			delete_transient( self::TRANS_NAME );
		}
	}

	/**
	 * Check if any conflicts exist.
	 *
	 * @return bool True if conflicts exist, otherwise false.
	 * @since 1.0.0
	 *
	 * @todo Use to display admin notice of conflicts.
	 */
	public function find_conflicts(): bool {
		if ( FatalError::exist() ) {
			// @codeCoverageIgnoreStart
			return true;
			// @codeCoverageIgnoreEnd
		}

		$trans = Transient::get( self::TRANS_NAME );
		if ( $trans ) {
			if ( key_exists( 'conflict', $trans ) && is_bool( $trans['conflict'] ) ) {
				return $trans['conflict'];
			}
		}

		$rewrite = $this->get_slugs();
		$this->get_post_type_slugs( $rewrite );
		$this->get_taxonmy_slugs( $rewrite );
		$conflict = $this->has_conflicts( $rewrite );

		$info = array(
			'conflict' => $conflict,
			'rewrite'  => $rewrite,
			'time'     => time(),
		);

		Transient::set( self::TRANS_NAME, $info, self::TRANS_TIMEOUT );
		return $conflict;
	}

	/**
	 * Get slugs used by this plugin.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_slugs(): array {
		global $wp_post_types, $wp_taxonomies;

		$rewrite            = array();
		$slug               = $wp_post_types[ DRPPSM_PT_SERMON ]->rewrite['slug'];
		$rewrite[ $slug ][] = DRPPSM_PT_SERMON;
		$tax                = array_values( DRPPSM_TAX_MAP );

		foreach ( $tax as $type ) {

			// Skip if not registered.
			// @codeCoverageIgnoreStart
			if ( ! isset( $wp_taxonomies[ $type ] ) ) {
				continue;
			}
			// @codeCoverageIgnoreEnd

			$slug               = $wp_taxonomies[ $type ]->rewrite['slug'];
			$rewrite[ $slug ][] = $type;
		}
		return $rewrite;
	}

	/**
	 * Get slugs used by other post types.
	 *
	 * @param array $rewrite Array of slugs.
	 * @return void
	 * @since 1.0.0
	 */
	private function get_post_type_slugs( array &$rewrite ): void {
		global $wp_post_types;

		foreach ( $wp_post_types as $type => $settings ) {
			if ( isset( $settings->rewrite ) && ! empty( $settings->rewrite ) ) {
				if ( ! is_array( $settings->rewrite ) ) {
					// @codeCoverageIgnoreStart
					continue;
					// @codeCoverageIgnoreEnd
				}

				$slug = $settings->rewrite['slug'];
				if ( key_exists( $slug, $rewrite ) && ! in_array( $type, $rewrite[ $slug ], true ) ) {
					// @codeCoverageIgnoreStart
					$rewrite[ $slug ][] = $type;
					// @codeCoverageIgnoreEnd
				}
			}
		}
	}

	/**
	 * Get slugs used by other taxonomy.
	 *
	 * @param array $rewrite Array of slugs.
	 * @return void
	 * @since 1.0.0
	 */
	private function get_taxonmy_slugs( array &$rewrite ): void {
		global $wp_taxonomies;
		foreach ( $wp_taxonomies as $type => $settings ) {
			if ( isset( $settings->rewrite ) && ! empty( $settings->rewrite ) ) {

				if ( ! is_array( $settings->rewrite ) ) {
					// @codeCoverageIgnoreStart
					continue;
					// @codeCoverageIgnoreEnd
				}

				$slug = $settings->rewrite['slug'];
				if ( key_exists( $slug, $rewrite ) && ! in_array( $type, $rewrite[ $slug ], true ) ) {
					// @codeCoverageIgnoreStart
					$rewrite[ $slug ][] = $type;
					// @codeCoverageIgnoreEnd
				}
			}
		}
	}

	/**
	 * Check array for any conflicts.
	 *
	 * @param array $rewrite List of slugs.
	 * @return boolean
	 * @since 1.0.0
	 */
	private function has_conflicts( array $rewrite ): bool {
		foreach ( $rewrite as $types ) {
			if ( count( $types ) > 1 ) {
				// @codeCoverageIgnoreStart
				return true;
				// @codeCoverageIgnoreEnd
			}
		}
		return false;
	}
}
