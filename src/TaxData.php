<?php
/**
 * Taxonomy data class.
 *
 * @package     DRPPSM\TaxBase
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Post;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Taxonomy data class.
 *
 * @package     DRPPSM\TaxBase
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxData {

	/**
	 * Post ID
	 *
	 * @var int
	 * @since 1.0.0
	 */
	public int $post_id;

	/**
	 * Post name
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $post_title;

	/**
	 * Taxonomy name
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $taxonomy;

	/**
	 * Taxonomy label
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $label_singular;

	/**
	 * Taxonomy plural label
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $label_plural;

	/**
	 * Terms
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public array $terms;

	/**
	 * Term names
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public array $links;

	/**
	 * Constructor
	 *
	 * @param WP_Post $post Post object.
	 * @param string  $taxonomy Taxonomy name.
	 * @since 1.0.0
	 */
	public function __construct( WP_Post $post, string $taxonomy ) {

		$this->post_id    = $post->ID;
		$this->post_title = $post->post_title;
		$this->taxonomy   = $taxonomy;

		$labels               = get_taxonomy( $taxonomy )->labels;
		$this->label_singular = $labels->singular_name;
		$this->label_plural   = $labels->name;

		$terms = get_the_terms( $post, $taxonomy );
		if ( ! $terms || is_wp_error( $terms ) ) {
			$this->terms = array();
		} else {
			$this->terms = $terms;
		}
	}

	/**
	 * Serialize the object
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function __serialize(): array {
		return array(
			'post_id'        => $this->post_id,
			'post_name'      => $this->post_title,
			'taxonomy'       => $this->taxonomy,
			'label_singular' => $this->label_singular,
			'label_plural'   => $this->label_plural,
			'terms'          => $this->terms,
		);
	}

	/**
	 * Unserialize the object
	 *
	 * @param array $data Data to unserialize.
	 * @return void
	 * @since 1.0.0
	 */
	public function __unserialize( array $data ): void {
		$this->post_id        = $data['post_id'];
		$this->post_title     = $data['post_name'];
		$this->taxonomy       = $data['taxonomy'];
		$this->label_singular = $data['label_singular'];
		$this->label_plural   = $data['label_plural'];
		$this->terms          = $data['terms'];
	}

	/**
	 * Convert the object to a string
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function __toString(): string {
		return $this->post_title . ' ' . $this->taxonomy;
	}

	/**
	 * Get the name of the term
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function name(): string {
		$names = array();

		/**
		 * @var WP_Term $term
		 */
		foreach ( $this->terms as $term ) {
			$names[] = $term->name;
		}
		$names = trim( implode( ', ', $names ) );

		// @codeCoverageIgnoreStart
		if ( substr( $names, -1 ) === ',' ) {
			$names = trim( substr( $names, 0, -1 ) );
		}
		// @codeCoverageIgnoreEnd

		return $names;
	}

	/**
	 * Get all links for terms.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function links(): array {
		$links = array();

		/**
		 * @var WP_Term $term
		 */
		foreach ( $this->terms as $term ) {
			$link = get_term_link( $term->term_id );
			if ( ! is_wp_error( $link ) ) {
				$links[] = $link;
			}
		}
		return $links;
	}

	/**
	 * Get the term IDs
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function ids(): array {
		$ids = array();

		/**
		 * @var WP_Term $term
		 */
		foreach ( $this->terms as $term ) {
			$ids[] = $term->term_id;
		}
		return $ids;
	}

	/**
	 * Get the terms array objects
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function terms(): array {
		return $this->terms;
	}

	/**
	 * Check if the object has terms.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function has_term(): bool {

		$terms  = $this->fix_terms();
		$result = null;

		// @codeCoverageIgnoreStart
		if ( count( $terms ) > 0 ) {
			$result = true;
		}

		if ( null === $result ) {
			$result = false;
		}
		// @codeCoverageIgnoreEnd

		return $result;
	}

	/**
	 * Get the number of terms
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function count(): int {
		$terms = $this->fix_terms();
		return count( $terms );
	}

	/**
	 * Fix terms array.
	 *
	 * - Remove none term.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function fix_terms() {
		$terms = $this->terms;

		foreach ( $terms as $key => $term ) {
			// @codeCoverageIgnoreStart
			if ( $term->slug === 'none' ) {
				unset( $terms[ $key ] );
				break;
			}
			// @codeCoverageIgnoreEnd
		}
		return $terms;
	}
}
