<?php
/**
 * Taxonomy info.
 *
 * @package     DRPPSM\TaxInfo
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Meta;
use WP_Term;

defined( 'ABSPATH' ) || exit;

/**
 * Taxonomy info.
 *
 * @package     DRPPSM\TaxInfo
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxInfo {

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $taxonomy;

	/**
	 * Term id.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $term_id;

	/**
	 * Term name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private ?WP_Term $term;

	/**
	 * Names array
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $names;

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
	 * Dates array
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $dates;

	/**
	 * Array of sermon post type.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private ?array $sermons;

	/**
	 * Array of taxonomies.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $taxonomies;

	/**
	 * TaxInfo constructor.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @since 1.0.0
	 */
	public function __construct( string $taxonomy, int $term_id, ?array $sermons = null ) {
		$this->taxonomy = $taxonomy;
		$this->term_id  = $term_id;

		$this->names      = array();
		$this->ids        = array();
		$this->links      = array();
		$this->dates      = array();
		$this->taxonomies = array();

		try {
			$terms = get_term( $term_id, $taxonomy );
			if ( is_wp_error( $terms ) ) {
				$this->term = null;
			} elseif ( is_array( $terms ) ) {
					$this->term = $terms[0];
			} else {
				$this->term = $terms;
			}

			if ( $sermons ) {
				$this->sermons = $sermons;
			} else {
				$this->sermons = null;
			}

			// @codeCoverageIgnoreStart
		} catch ( \Exception $e ) {
			$this->term    = null;
			$this->sermons = array();
			Logger::error(
				array(
					'MESSAGE' => $e->getMessage(),
					'TRACE'   => $e->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}

		Logger::debug( $this );
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function init() {

		$key = Timer::start( __FILE__, __FUNCTION__ );
		if ( ! $this->sermons ) {
			$this->sermons = TaxUtils::get_sermons_by_term( $this->taxonomy, $this->term_id, -1 );
		}
		foreach ( $this->sermons as $sermon ) {
			$this->names[] = $sermon->post_title;
			$this->add_id( $sermon->ID );

			$date = get_post_meta( $sermon->ID, Meta::DATE, true );
			if ( $date ) {
				$this->dates[] = $date;
			} else {
				$this->dates[] = strtotime( $sermon->post_date );
			}
		}
		Timer::stop( $key );
	}

	/**
	 * Refresh term info.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function refresh() {
		if ( ! $this->term ) {
			return;
		}
		$this->sermons = TaxUtils::get_sermons_by_term( $this->taxonomy, $this->term_id, -1 );
		$this->init();
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
	 * Get sermons.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function sermons(): ?array {
		return $this->sermons;
	}

	/**
	 * Get taxonomy.
	 *
	 * @return null|array
	 * @since 1.0.0
	 */
	public function topics(): ?array {
		if ( ! isset( $this->taxonomies[ DRPPSM_TAX_TOPICS ] ) ) {
			return null;
		}
		return $this->taxonomies[ DRPPSM_TAX_TOPICS ];
	}

	/**
	 * Add toxonomy to object.
	 *
	 * @param string $taxonomy
	 * @return void
	 * @since 1.0.0
	 */
	public function add_taxonomy( string $taxonomy ): void {

		try {
			$tax = array_values( DRPPSM_TAX_MAP );
			if ( ! in_array( $taxonomy, $tax, true ) ) {
				return;
			}

			foreach ( $this->sermons as $sermon ) {
				$terms = get_the_terms( $sermon->ID, $taxonomy );
				if ( is_wp_error( $terms ) || ! $terms ) {
					continue;
				}

				/**
				 * @var WP_Term $term
				 */
				foreach ( $terms as $term ) {
					$this->taxonomies[ $taxonomy ][ $term->name ] = new TaxInfo( $taxonomy, $term->term_id, $this->sermons );
					$this->taxonomies[ $taxonomy ][ $term->name ]->init();
				}
			}
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
		}
	}

	/**
	 * Get a taxonomy added by add_taxonomy method.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @return array|null
	 * @since 1.0.0
	 */
	public function get_taxonomy( string $taxonomy ): ?array {
		if ( ! isset( $this->taxonomies[ $taxonomy ] ) ) {
			return null;
		}
		return $this->taxonomies[ $taxonomy ];
	}

	/**
	 * Serialize magic method.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function __serialize(): array {
		return array(
			'taxonomy'   => $this->taxonomy,
			'term'       => $this->term,
			'term_id'    => $this->term_id,
			'names'      => $this->names,
			'ids'        => $this->ids,
			'links'      => $this->links,
			'taxonomies' => $this->taxonomies,
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
		$this->taxonomy   = $data['taxonomy'];
		$this->term_id    = $data['term_id'];
		$this->names      = $data['names'];
		$this->ids        = $data['ids'];
		$this->links      = $data['links'];
		$this->taxonomies = $data['taxonomies'];
	}

	/**
	 * To string magic method.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function __toString(): string {
		return 'Info : ' . $this->name();
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
		$link        = get_term_link( $id, $this->taxonomy );

		if ( ! is_wp_error( $link ) ) {
			$this->links[ $id ] = $link;
		}
	}
}
