<?php
/**
 * Sermon info.
 *
 * @package     DRPPSM\SermonInfo
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Meta;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon info.
 *
 * @package     DRPPSM\SermonInfo
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonsInfo {

	/**
	 * Dates array
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $dates;

	/**
	 * Ids array
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $ids;

	/**
	 * Links array
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $links;

	/**
	 * Names array
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $names;

	public function __construct( array $sermons = array() ) {
		$this->dates = array();
		$this->ids   = array();
		$this->links = array();
		$this->names = array();
		$this->init( $sermons );
	}

	/**
	 * Serialize magic method.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function __serialize(): array {
		return array(
			'ids'   => $this->ids,
			'links' => $this->links,
			'names' => $this->names,
			'dates' => $this->dates,
		);
	}

	/**
	 * Unserialize magic method.
	 *
	 * @param array $data Data.
	 * @return void
	 * @since 1.0.0
	 */
	public function __unserialize( array $data ): void {
		$this->ids   = $data['ids'];
		$this->links = $data['links'];
		$this->dates = $data['dates'];
		$this->names = $data['names'];
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init( array $sermons ) {

		/**
		 * @var WP_Post $sermon
		 */
		foreach ( $sermons as $sermon ) {
			$this->names[] = $sermon->post_title;
			$this->add_id( $sermon->ID );

			$date = get_post_meta( $sermon->ID, Meta::DATE, true );
			if ( $date ) {
				$this->dates[] = $date;
			} else {
				$this->dates[] = strtotime( $sermon->post_date );
			}
		}
	}

	/**
	 * Count.
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function count(): int {
		return count( $this->names );
	}

	/**
	 * Name.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function name(): string {
		if ( count( $this->names ) === 0 ) {
			return 'none';
		}
		return implode( ', ', $this->names );
	}

	/**
	 * Get names.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function names(): array {
		return $this->names;
	}

	/**
	 * Get ids.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ids(): array {
		return $this->ids;
	}

	/**
	 * Get links.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function links(): array {
		return $this->links;
	}

	/**
	 * Get link.
	 *
	 * @param int $id Id.
	 * @return string|null
	 * @since 1.0.0
	 */
	public function link( int $id ): ?string {
		if ( ! isset( $this->links[ $id ] ) ) {
			return null;
		}
		return $this->links[ $id ];
	}

	/**
	 * Get date.
	 *
	 * @param string $format Date format.
	 * @return null|string
	 * @since 1.0.0
	 */
	public function date( string $format = 'j F Y' ): ?string {
		$dates = $this->dates;

		if ( count( $this->dates ) === 0 ) {
			return null;
		}

		asort( $dates );

		$cnt       = count( $dates );
		$date_last = '';

		if ( 1 === $cnt ) {
			$first = wp_date( $format, $dates[0] );
			if ( ! $first ) {
				$first = null;
			}
			return $first;

		} elseif ( $cnt > 1 ) {
			$first = wp_date( $format, $dates[0] );
			if ( ! $first ) {
				return null;
			}

			$last = wp_date( $format, $dates[ $cnt - 1 ] );

			if ( ! $last ) {
				$last = '';
			} else {
				$last = ' - ' . $date_last;
			}

			return $first . $last;
		}
	}

	/**
	 * Get dates.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function dates(): array {
		return $this->dates;
	}

	/**
	 * Add id.
	 *
	 * @param int $id Id.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_id( int $id ): void {
		$this->ids[] = $id;
		$link        = get_permalink( $id );

		if ( ! is_wp_error( $link ) ) {
			$this->links[ $id ] = $link;
		}
	}
}
