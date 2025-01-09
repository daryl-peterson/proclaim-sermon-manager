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

defined( 'ABSPATH' ) || exit;

/**
 * Check if any rewrite conflicts exist.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Rewrite {

	/**
	 * Transients.
	 */
	const TRANS_NAME    = 'drppsm_rewrite_conflicts';
	const TRANS_TIMEOUT = DAY_IN_SECONDS;

	/**
	 * Initialize object and register hooks.
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
	 * Register hooks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( 'init', array( $this, 'find_conflicts' ) ) ) {
			return false;
		}
		add_action( 'init', array( $this, 'find_conflicts' ) );
		add_action( 'activate_plugin', array( $this, 'reset' ), 10, 2 );
		add_action( 'deactivate_plugin', array( $this, 'reset' ), 10, 2 );
		add_action( DRPPSMA_FLUSH_REWRITE, array( $this, 'flush' ) );
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
		delete_transient( self::TRANS_NAME );
	}

	/**
	 * Check if any conflicts exist.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function find_conflicts(): void {
		$trans = get_transient( self::TRANS_NAME );
		if ( $trans ) {
			return;
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

		set_transient(
			self::TRANS_NAME,
			$info,
			self::TRANS_TIMEOUT
		);

		Logger::debug( $info );
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

			if ( ! isset( $wp_taxonomies[ $type ] ) ) {
				continue;
			}
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
					continue;
				}
				$slug = $settings->rewrite['slug'];
				if ( key_exists( $slug, $rewrite ) && ! in_array( $type, $rewrite[ $slug ], true ) ) {
					$rewrite[ $slug ][] = $type;
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
					continue;
				}
				$slug = $settings->rewrite['slug'];
				if ( key_exists( $slug, $rewrite ) && ! in_array( $type, $rewrite[ $slug ], true ) ) {
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
}
