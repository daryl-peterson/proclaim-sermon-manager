<?php
/**
 * Check if any rewrite conflicts exist.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\RewriteInt;

/**
 * Check if any rewrite conflicts exist.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Rewrite implements RewriteInt {

	/**
	 * Transient name.
	 */
	const TRANS_NAME = 'drppsm_rewrite_conflicts';

	const TRANS_TIMEOUT = DAY_IN_SECONDS;

	/**
	 * Initialize object and register callbacks.
	 *
	 * @return Rewrite
	 * @since 1.0.0
	 */
	public static function exec(): Rewrite {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register callbacks.
	 *
	 * @return boolean|null True if callbacks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( 'init', array( $this, 'find_conflicts' ) ) ) {
			return false;
		}
		add_action( 'init', array( $this, 'find_conflicts' ) );
		add_action( 'activate_plugin', array( $this, 'reset' ), 10, 2 );
		add_action( 'deactivate_plugin', array( $this, 'reset' ), 10, 2 );
		return true;
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
		delete_transient( self::TRANS_NAME );
	}

	/**
	 * Check if any conflicts exist.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function find_conflicts() {
		$trans = get_transient( self::TRANS_NAME );
		if ( $trans ) {
			return;
		}

		Logger::debug( 'HERE' );

		$rewrite = $this->get_slugs();
		$this->get_post_type_slugs( $rewrite );
		$this->get_taxonmy_slugs( $rewrite );
		$conflict = $this->has_conflicts( $rewrite );

		Logger::debug(
			array(
				'REWRITE'   => $rewrite,
				'CONFLICTS' => $conflict,
			)
		);

		set_transient(
			self::TRANS_NAME,
			array(
				'conflict' => $conflict,
				'rewrite'  => $rewrite,
				'time'     => time(),
			),
			self::TRANS_TIMEOUT
		);
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
		$slug               = $wp_post_types[ PT::SERMON ]->rewrite['slug'];
		$rewrite[ $slug ][] = PT::SERMON;
		foreach ( Tax::LIST as $type ) {
			$slug               = $wp_taxonomies[ $type ]->rewrite['slug'];
			$rewrite[ $slug ][] = $type;
		}
		return $rewrite;
	}

	/**
	 * Get slugs used by other post types.
	 *
	 * @param array $rewrite
	 * @return void
	 * @since 1.0.0
	 */
	private function get_post_type_slugs( array &$rewrite ): void {
		global $wp_post_types;
		foreach ( $wp_post_types as $type => $settings ) {
			if ( isset( $settings->rewrite ) && ! empty( $settings->rewrite ) ) {
				if ( ! is_array( $settings->rewrite ) ) {
					continue;
				}
				$slug = $settings->rewrite['slug'];
				if ( key_exists( $slug, $rewrite ) && ! in_array( $type, $rewrite[ $slug ] ) ) {
					$rewrite[ $slug ][] = $type;
				}
			}
		}
	}

	/**
	 * Get slugs used by other taxonomy.
	 *
	 * @param array $rewrite
	 * @return void
	 * @since 1.0.0
	 */
	private function get_taxonmy_slugs( array &$rewrite ): void {
		global $wp_taxonomies;
		foreach ( $wp_taxonomies as $type => $settings ) {
			if ( isset( $settings->rewrite ) && ! empty( $settings->rewrite ) ) {
				if ( ! is_array( $settings->rewrite ) ) {
					continue;
				}
				$slug = $settings->rewrite['slug'];
				if ( key_exists( $slug, $rewrite ) && ! in_array( $type, $rewrite[ $slug ] ) ) {
					$rewrite[ $slug ][] = $type;
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
		$conflict = false;
		foreach ( $rewrite as $types ) {
			if ( count( $types ) > 1 ) {
				$conflict = true;
				break;
			}
		}
		return $conflict;
	}

	/**
	 * Force check to run again.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function force() {
		delete_transient( self::TRANS_NAME );
	}
}
