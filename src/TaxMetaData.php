<?php
/**
 * Taxonomy meta data class.
 *
 * @package     DRPPSM\TaxMetaData
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Term;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Taxonomy meta data class.
 *
 * @package     DRPPSM\TaxMetaData
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxMetaData {

	/**
	 * Term.
	 *
	 * @var null|WP_Term
	 * @since 1.0.0
	 */
	public ?WP_Term $term;

	/**
	 * Image id.
	 *
	 * @var null|int
	 * @since 1.0.0
	 */
	public ?int $image_id;

	/**
	 * Image url.
	 *
	 * @var null|string
	 * @since 1.0.0
	 */
	public ?string $image;

	/**
	 * Date.
	 *
	 * @var null|int
	 * @since 1.0.0
	 */
	public ?int $date;

	/**
	 * Link.
	 *
	 * @var null|string
	 * @since 1.0.0
	 */
	public ?string $link;

	/**
	 * Initialize object.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct( int|WP_Term $term_id ) {
		$this->term     = null;
		$this->image_id = null;
		$this->image    = null;
		$this->date     = null;
		$this->link     = null;

		if ( is_int( $term_id ) ) {
			$term = get_term_by( 'term_id', $term_id );
		} else {
			$term = $term_id;
		}

		// Can't find term.
		if ( ! $term || ! $term instanceof WP_Term ) {
			return;
		}

		$this->term = $term;
		$this->link = get_term_link( $this->term );
		$this->set_meta();
	}

	/**
	 * Serialize object.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function __serialize(): array {
		return array(
			'term'     => $this->term,
			'image_id' => $this->image_id,
			'image'    => $this->image,
			'date'     => $this->date,
			'link'     => $this->link,
		);
	}

	/**
	 * Unserialize object.
	 *
	 * @param array $data
	 * @return void
	 * @since 1.0.0
	 */
	public function __unserialize( array $data ): void {
		$this->term     = $data['term'];
		$this->image_id = $data['image_id'];
		$this->image    = $data['image'];
		$this->date     = $data['date'];
		$this->link     = $data['link'];
	}

	/**
	 * Check if term has image.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function has_image(): bool {
		return isset( $this->image_id );
	}

	/**
	 * Check if term has date.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function has_date(): bool {
		return isset( $this->date );
	}

	/**
	 * Set meta properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function set_meta() {
		$meta     = get_term_meta( $this->term->term_id );
		$taxonomy = $this->term->taxonomy;
		$term_id  = $this->term->term_id;

		// Can't find meta.
		if ( ! isset( $meta ) || ! is_array( $meta ) || 0 === count( $meta ) ) {
			$jobs = SchedulerJobs::get_instance();
			$jobs->add( $taxonomy, $term_id );
			return;
		}

		$suffix = array(
			"{$taxonomy}_date",
			"{$taxonomy}_image_id",
			"{$taxonomy}_image",
		);

		$suffix_map = array(
			"{$taxonomy}_date"     => 'date',
			"{$taxonomy}_image_id" => 'image_id',
			"{$taxonomy}_image"    => 'image',
		);

		foreach ( $meta as $key => $value ) {

			// @codeCoverageIgnoreStart
			if ( ! in_array( $key, $suffix ) ) {
				unset( $meta[ $key ] );
				continue;
			}
			// @codeCoverageIgnoreEnd

			$this->{$suffix_map[ $key ]} = maybe_unserialize( $value[0] );
		}
	}
}
