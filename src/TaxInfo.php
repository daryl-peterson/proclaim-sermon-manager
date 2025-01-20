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

use WP_Post;
use WP_Term;

defined( 'ABSPATH' ) || exit;


/**
 * Taxonomy info for associated sermons.
 *
 * - books
 * - preachers
 * - series
 * - topics
 *
 * @package     DRPPSM\TaxInfo
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxInfo {

	/**
	 * Ids array.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $ids;

	/**
	 * Links array.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $links;

	/**
	 * Names array.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $names;

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
	 * Term object.
	 *
	 * @var WP_Term
	 * @since 1.0.0
	 */
	private ?WP_Term $term;

	/**
	 * Time.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	public int $time;

	/**
	 * Sermon info.
	 *
	 * @var SermonsInfo
	 * @since 1.0.0
	 */
	private ?SermonsInfo $sermons;

	/**
	 * Taxonomy list.
	 *
	 * @var array
	 * @since
	 */
	private array $list;

	/**
	 * Pointer for taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $pointer;

	/**
	 * TaxInfo constructor.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @since 1.0.0
	 */
	public function __construct( string $taxonomy, int $term_id ) {

		$this->time     = time();
		$this->taxonomy = $taxonomy;
		$this->term_id  = $term_id;
		$this->ids      = array();
		$this->links    = array();
		$this->names    = array();
		$this->pointer  = DRPPSM_TAX_SERIES;

		try {
			$this->set_term( $term_id, $taxonomy );
			$this->init();

			Logger::debug( $this );

			// @codeCoverageIgnoreStart
		} catch ( \Exception $e ) {
			Logger::error(
				array(
					'MESSAGE' => $e->getMessage(),
					'TRACE'   => $e->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Serialize magic method.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function __serialize(): array {
		return array(
			'ids'      => $this->ids,
			'links'    => $this->links,
			'names'    => $this->names,
			'object'   => $this->term,
			'taxonomy' => $this->taxonomy,
			'term_id'  => $this->term_id,
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
		$this->ids      = $data['ids'];
		$this->links    = $data['links'];
		$this->names    = $data['names'];
		$this->term     = $data['object'];
		$this->taxonomy = $data['taxonomy'];
		$this->term_id  = $data['term_id'];
	}

	/**
	 * To string magic method.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function __toString(): string {
		$msg  = "Term : $this->term->name ";
		$msg .= 'Sermons : ' . $this->sermons->count();
		$msg .= 'Books : ' . $this->books()->count();
		$msg .= 'Series : ' . $this->series()->count();
		$msg .= 'Topics : ' . $this->topics()->count();
		return $msg;
	}

	/**
	 * Get summary.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function summary(): array {
		return array(
			'term'     => $this->term->name,
			'term_id'  => $this->term_id,
			'taxonomy' => $this->taxonomy,
			'sermons'  => $this->sermons->count(),
			'books'    => $this->books()->count(),
			'series'   => $this->series()->count(),
			'topics'   => $this->topics()->count(),
		);
	}

	/**
	 * Switch to books taxonomy.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public function books() {
		$this->pointer = DRPPSM_TAX_BOOK;
		return $this;
	}

	/**
	 * Switch to series taxonomy.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public function series() {
		$this->pointer = DRPPSM_TAX_SERIES;
		return $this;
	}

	/**
	 * Switch to topics taxonomy.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public function topics() {
		$this->pointer = DRPPSM_TAX_TOPIC;
		return $this;
	}

	/**
	 * Switch to preachers taxonomy.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public function preachers() {
		$this->pointer = DRPPSM_TAX_PREACHER;
		return $this;
	}

	/**
	 * Get names array or string.
	 *
	 * @param bool        $array True to return array, false to return string.
	 * @param null|string $taxonomy Optional taxonomy name.
	 * @return array|string
	 * @since 1.0.0
	 */
	public function names(
		bool $array = true,
		?string $taxonomy = null
	): array|string {

		if ( ! isset( $taxonomy ) ) {
			$taxonomy = $this->pointer;
		}

		if ( ! isset( $this->names[ $taxonomy ] ) ) {
			return array();
		}

		if ( $array ) {
			return array_values( $this->names[ $taxonomy ] );
		}

		$names = $this->names[ $taxonomy ];
		asort( $names );

		return implode( ', ', $names );
	}

	/**
	 * Get ids array.
	 *
	 * @param null|string $taxonomy Optional taxonomy name.
	 * @return array
	 * @since 1.0.0
	 */
	public function ids( ?string $taxonomy = null ): array {
		if ( ! isset( $taxonomy ) ) {
			$taxonomy = $this->pointer;
		}

		if ( ! isset( $this->ids[ $taxonomy ] ) ) {
			return array();
		}

		return $this->ids[ $taxonomy ];
	}

	/**
	 * Get links array.
	 *
	 * @param null|string $taxonomy Optional taxonomy name.
	 * @return array
	 * @since 1.0.0
	 */
	public function links( ?string $taxonomy = null ): array {
		if ( ! isset( $taxonomy ) ) {
			$taxonomy = $this->pointer;
		}

		if ( ! isset( $this->links[ $taxonomy ] ) ) {
			return array();
		}

		return $this->links[ $taxonomy ];
	}

	/**
	 * Get term link.
	 *
	 * @param int         $id Term id.
	 * @param null|string $taxonomy Optional taxonomy name.
	 * @return string|null
	 * @since 1.0.0
	 */
	public function link( int $id, ?string $taxonomy = null ): ?string {
		if ( ! isset( $taxonomy ) ) {
			$taxonomy = $this->pointer;
		}

		if ( ! isset( $this->links[ $taxonomy ][ $id ] ) ) {
			return null;
		}

		return $this->links[ $taxonomy ][ $id ];
	}

	/**
	 * Get taxonomy count.
	 *
	 * @param null|string $taxonomy Optional taxonomy name.
	 * @return int
	 * @since 1.0.0
	 */
	public function count( ?string $taxonomy = null ): int {
		if ( ! isset( $taxonomy ) ) {
			$taxonomy = $this->pointer;
		}

		if ( ! isset( $this->ids[ $taxonomy ] ) ) {
			return 0;
		}

		return count( $this->ids[ $taxonomy ] );
	}

	/**
	 * Get term image.
	 *
	 * @param string $size
	 * @return null|string
	 */
	public function image( string $size = ImageSize::SERMON_MEDIUM ): ?string {

		$term = $this->term;
		if ( ! isset( $term ) || $term->has_image === false ) {
			return null;
		}

		if ( is_array( $term->images ) && isset( $term->images[ $size ] ) ) {
			return $term->images[ $size ];
		}
	}

	/**
	 * Get term object.
	 *
	 * @return WP_Term
	 * @since 1.0.0
	 */
	public function term() {
		return $this->term;
	}

	/**
	 * Get taxonomy label.
	 *
	 * @return string|null
	 * @since 1.0.0
	 */
	public function label(): ?string {
		return get_taxonomy_field( $this->pointer, 'label' );
	}

	/**
	 * Refresh object.
	 *
	 * @return void
	 */
	public function refresh() {
		$this->ids     = array();
		$this->links   = array();
		$this->names   = array();
		$this->pointer = $this->taxonomy;
		$this->init();
	}

	/**
	 * Initialize.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function init() {

		$sermons = TaxUtils::get_sermons_by_term(
			$this->taxonomy,
			$this->term_id,
			-1
		);

		$this->sermons = new SermonsInfo( $sermons );
		foreach ( $sermons as $sermon ) {
			$this->set_terms( $sermon );
		}
	}

	/**
	 * Set term info.
	 *
	 * @param WP_Post $sermon Sermon post.
	 * @return void
	 * @since 1.0.0
	 */
	private function set_terms( WP_Post $sermon ): void {
		$this->list = array_values( DRPPSM_TAX_MAP );
		foreach ( $this->list as $tax ) {
			$terms = wp_get_post_terms( $sermon->ID, $tax );

			if ( is_wp_error( $terms ) || ! is_array( $terms ) || count( $terms ) === 0 ) {
				continue;
			}

			$term = $terms[0];
			$tid  = $term->term_id;

			if ( ! isset( $this->ids[ $tax ] ) ) {
				$this->ids[ $tax ] = array();
			}

			if ( in_array( $tid, $this->ids[ $tax ], true ) ) {
				continue;
			}

			$this->ids[ $tax ][]         = $tid;
			$this->names[ $tax ][ $tid ] = $term->name;
			$this->links[ $tax ][ $tid ] = $this->get_term_link( $tid, $tax );
		}
	}

	/**
	 * Set object.
	 *
	 * @param int    $term_id Term id.
	 * @param string $taxonomy Taxonomy name.
	 * @return void
	 * @since 1.0.0
	 */
	private function set_term( int $term_id, string $taxonomy ): void {

		// @return WP_Term|array|WP_Error|null
		$term = get_term( $term_id, $taxonomy );

		if ( is_wp_error( $term ) || ! isset( $term ) ) {
			return;
		}

		if ( is_array( $term ) && count( $term ) !== 0 ) {
			$obj = $term[0];
		} elseif ( is_a( $term, 'WP_Term' ) ) {
			$obj = $term;
		}

		if ( ! isset( $obj ) ) {
			return;
		}

		$obj->link = $this->get_term_link( $term_id, $taxonomy );
		$this->set_images( $obj );
		$this->term_cleanup( $obj );
		$this->term = $obj;
	}

	/**
	 * Set term images.
	 *
	 * @param WP_Term $term Term object.
	 * @return void
	 * @since 1.0.0
	 */
	private function set_images( WP_Term $term ): void {
		$sizes    = get_intermediate_image_sizes();
		$meta_key = $this->taxonomy . '_image_id';
		$images   = array();
		$meta     = get_term_meta( $term->term_id, $meta_key, true );
		if ( empty( $meta ) || false === $meta ) {
			$term->has_image = false;
			$term->images    = array();
		}

		foreach ( $sizes as $size ) {

			$image = wp_get_attachment_image_url( $meta, $size );
			if ( ! $image ) {
				continue;
			}
			$images[ $size ] = $image;
		}
		if ( count( $images ) > 0 ) {
			$term->has_image = true;
			$term->images    = $images;
			return;
		}
	}

	/**
	 * Term cleanup, remove unwanted properties.
	 *
	 * @param WP_Term $term Term object.
	 * @return void
	 * @since 1.0.0
	 */
	private function term_cleanup( WP_Term &$term ): void {
		unset( $term->filter );
		unset( $term->parent );
		unset( $term->term_group );
		unset( $term->term_order );
	}

	/**
	 * Get term link.
	 *
	 * @param int    $term_id Term id.
	 * @param string $taxonomy Taxonomy name.
	 * @return string|null
	 * @since 1.0.0
	 */
	private function get_term_link( int $term_id, string $taxonomy ): ?string {
		$link = get_term_link( $term_id, $taxonomy );
		if ( is_wp_error( $link ) ) {
			return null;
		}
		return $link;
	}
}
