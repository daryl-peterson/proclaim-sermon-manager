<?php
/**
 * Taxonomy image attaching.
 *
 * @package     DRPPSM\TaxImageAttach
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use WP_Post;

/**
 * Taxonomy image attaching.
 *
 * @package     DRPPSM\TaxImageAttach
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxImageAttach implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Post type.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $pt = DRPPSM_PT_SERMON;

	/**
	 * Sermon image class.
	 *
	 * @var SermonImageAttach
	 */
	private SermonImageAttach $sermon_image;

	/**
	 * Taxonomy list
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $tax;

	/**
	 * Image id list.
	 *
	 * @var array
	 */
	private array $image_ids;

	/**
	 * Image suffix.
	 *
	 * @var string
	 */
	private string $image_suffix;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->sermon_image = SermonImageAttach::exec();
		$this->tax          = array(
			DRPPSM_TAX_PREACHER,
			DRPPSM_TAX_SERIES,
			DRPPSM_TAX_TOPIC,
		);
		$this->image_suffix = '_image_id';

		foreach ( $this->tax as $taxonomy ) {
			$this->image_ids[] = $taxonomy . $this->image_suffix;
		}
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
	 * @since 1.0.0
	 *
	 * @see https://developer.wordpress.org/reference/hooks/add_meta_type_meta/
	 * @see https://developer.wordpress.org/reference/hooks/get_meta_type_metadata/
	 * @see https://developer.wordpress.org/reference/hooks/update_meta_type_meta/
	 * @see https://developer.wordpress.org/reference/hooks/delete_meta_type_meta/
	 */
	public function register(): ?bool {
		$meta_type = 'term';
		if ( ! is_admin() || has_action( "get_{$meta_type}_metadata", array( $this, 'get_metadata' ) ) ) {
			return false;
		}
		add_filter( "get_{$meta_type}_metadata", array( $this, 'get_metadata' ), 10, 5 );
		add_action( "add_{$meta_type}_meta", array( $this, 'add_meta' ), 10, 3 );
		add_action( "update_{$meta_type}_meta", array( $this, 'update_meta' ), 10, 4 );
		add_action( "delete_{$meta_type}_meta", array( $this, 'delete_meta' ), 10, 4 );
		return true;
	}

	/**
	 * Get meta data.
	 *
	 * @param mixed   $value The value to return, either a single metadata value\
	 *                or an array of values depending on the value of $single. Default null.
	 * @param integer $term_id ID of the object metadata is for.
	 * @param string  $meta_key Metadata key.
	 * @param boolean $single Whether to return only the first value of the specified $meta_key.
	 * @param string  $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term',\
	 *                'user', or any other object type with an associated meta table.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_metadata(
		mixed $value,
		int $term_id,
		string $meta_key,
		bool $single,
		string $meta_type
	): mixed {

		if ( ! isset( $meta_key ) || empty( $meta_key ) ) {
			return $value;
		}

		if ( in_array( $meta_key, $this->image_ids, true ) ) {
			return $this->get_image_meta( $term_id, $meta_key, $single, $meta_type );
		}

		return $value;
	}

	/**
	 * Add meta data for term.
	 *
	 * @param int    $term_id Term ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta value.
	 * @return bool
	 * @since 1.0.0
	 */
	public function add_meta(
		int $term_id,
		string $meta_key,
		mixed $meta_value
	): bool {
		return $this->attach( $term_id, $meta_key, $meta_value );
	}

	/**
	 * Update meta data for term.
	 *
	 * @param int    $meta_id Meta ID.
	 * @param int    $term_id Term ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta value.
	 * @return bool
	 * @since 1.0.0
	 */
	public function update_meta(
		int $meta_id,
		int $term_id,
		string $meta_key,
		mixed $meta_value
	): bool {
		$this->detach( $term_id, $meta_key, $meta_value );
		return $this->attach( $term_id, $meta_key, $meta_value );
	}

	/**
	 * Delete meta for term.
	 *
	 * @param mixed  $meta_ids Unused.
	 * @param int    $term_id Term ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta Value.
	 * @return bool
	 * @since 1.0.0
	 */
	public function delete_meta(
		mixed $meta_ids,
		int $term_id,
		string $meta_key,
		mixed $meta_value
	): bool {
		Logger::debug(
			array(
				'META_IDS'   => $meta_ids,
				'TERM_ID'    => $term_id,
				'META_KEY'   => $meta_key,
				'META_VALUE' => $meta_value,
			)
		);
		return $this->detach( $term_id, $meta_key, $meta_value );
	}

	/**
	 * Attach term image to first sermon that matches.
	 *
	 * @param int    $term_id Term ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta Value.
	 * @return bool
	 * @since 1.0.0
	 */
	private function attach(
		int $term_id,
		string $meta_key,
		mixed $meta_value
	): bool {

		Logger::debug(
			array(
				'TERM_ID'    => $term_id,
				'META_KEY'   => $meta_key,
				'META_VALUE' => $meta_value,
			)
		);

		$taxonomy = $this->get_taxonomy( $meta_key );
		if ( ! isset( $taxonomy ) ) {
			return false;
		}

		$attachment = $this->get_attachment( $meta_value );
		if ( ! isset( $attachment ) ) {
			return false;
		}

		$sermons = TaxUtils::get_sermons_by_term( $taxonomy, $term_id, 10 );
		if ( ! isset( $sermons ) ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}

		$result = false;

		/**
		 * Sermon posts array.
		 *
		 * @var WP_Post $sermon Sermon post object.
		 */
		foreach ( $sermons as $sermon ) {
			$result = $this->sermon_image->attach_image( $attachment, $sermon );

			if ( $result ) {
				break;
			}
		}
		return $result;
	}

	/**
	 * Detach term meta from sermon.
	 *
	 * @param int    $term_id Term ID.
	 * @param string $meta_key Meta key.
	 * @param mixed  $meta_value Meta Value.
	 * @return bool
	 * @since 1.0.0
	 */
	private function detach(
		int $term_id,
		string $meta_key,
		mixed $meta_value
	): bool {
		Logger::debug(
			array(
				'TERM_ID'    => $term_id,
				'META_KEY'   => $meta_key,
				'META_VALUE' => $meta_value,
			)
		);

		$taxonomy = $this->get_taxonomy( $meta_key );
		if ( ! isset( $taxonomy ) ) {
			return false;
		}

		$option_key = $meta_key;
		$options    = get_option( $option_key, null );
		if ( ! is_array( $options ) ) {
			// @codeCoverageIgnoreStart
			$options = array();
			// @codeCoverageIgnoreEnd
		}

		$image_id = null;
		if ( is_array( $options ) && key_exists( $term_id, $options ) ) {
			$image_id = $options[ $term_id ];
		}

		if ( ! isset( $image_id ) ) {
			return false;
		}

		$attachment = $this->get_attachment( $image_id );

		// This should never happen.
		if ( ! isset( $attachment ) ||
			! $attachment instanceof WP_Post ||
			0 === $attachment->post_parent
		) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}

		$sermon = $this->get_sermon( $attachment->post_parent );

		if ( ! isset( $sermon ) ) {
			// @codeCoverageIgnoreStart
			return false;
			// @codeCoverageIgnoreEnd
		}

		// delete_option( $option_key );
		unset( $options[ $term_id ] );
		update_option( $option_key, $options );
		$result = $this->sermon_image->detach_image( $attachment, $sermon );
		return $result;
	}

	/**
	 * Get taxonomy from meta key.
	 *
	 * @param string $meta_key Meta key.
	 * @return string|null String if taxonomy is found, otherwise null.
	 * @since 1.0.0
	 */
	private function get_taxonomy( string $meta_key ): ?string {

		if (
			! isset( $meta_key ) ||
			empty( $meta_key ) ||
			! in_array( $meta_key, $this->image_ids, true )
		) {
			return null;
		}

		// This should never happen.
		if ( false === strpos( $meta_key, $this->image_suffix ) ) {
			// @codeCoverageIgnoreStart
			return null;
			// @codeCoverageIgnoreEnd
		}

		$taxonomy = trim( str_replace( $this->image_suffix, '', $meta_key ) );

		// This should never happen. But if we add taxonomies in the future, we can catch it.
		if ( ! in_array( $taxonomy, $this->tax, true ) ) {
			// @codeCoverageIgnoreStart
			return null;
			// @codeCoverageIgnoreEnd
		}

		return $taxonomy;
	}

	/**
	 * Get sermon.
	 *
	 * @param int $sermon_id Sermon ID.
	 * @return null|WP_Post Sermon or null.
	 * @since 1.0.0
	 */
	private function get_sermon( int $sermon_id ): ?WP_Post {

		$args = array(
			'post_type'      => $this->pt,
			'post__in'       => array( $sermon_id ),
			'posts_per_page' => 1,
		);

		$sermon = get_posts( $args );

		if (
			is_wp_error( $sermon ) ||
			! is_array( $sermon ) ||
			0 === count( $sermon )
		) {
			return null;
		}

		if ( is_array( $sermon ) ) {
			$sermon = array_shift( $sermon );
		}
		return $sermon;
	}

	/**
	 * Get attachment.
	 *
	 * @param int $image_id Post ID.
	 * @return null|WP_Post Post attachment.
	 * @since 1.0.0
	 */
	private function get_attachment( int $image_id ): ?WP_Post {

		$args = array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post__in'       => array( $image_id ),
			'posts_per_page' => 1,
		);

		$attachment = get_posts( $args );

		if (
			is_wp_error( $attachment ) ||
			! is_array( $attachment ) ||
			0 === count( $attachment )
		) {
			return null;
		}

		if ( is_array( $attachment ) ) {
			$attachment = array_shift( $attachment );
		}

		return $attachment;
	}

	/**
	 * Get image meta.
	 *
	 * @param int    $term_id Term object ID.
	 * @param string $meta_key Meta key.
	 * @param bool   $single Single.
	 * @param string $meta_type Meta type.
	 * @return mixed
	 * @since 1.0.0
	 */
	private function get_image_meta(
		int $term_id,
		string $meta_key,
		bool $single,
		string $meta_type
	) {
		Logger::debug(
			array(
				'TERM_ID'   => $term_id,
				'META_KEY'  => $meta_key,
				'SINGLE'    => $single,
				'META_TYPE' => $meta_type,
			)
		);
		$meta_cache = wp_cache_get( $term_id, $meta_type . '_meta' );

		if ( ! $meta_cache ) {
			$meta_cache = update_meta_cache( $meta_type, array( $term_id ) );
			if ( isset( $meta_cache[ $term_id ] ) ) {
				$meta_cache = $meta_cache[ $term_id ];
			} else {
				// @codeCoverageIgnoreStart
				$meta_cache = null;
				// @codeCoverageIgnoreEnd
			}
		}

		if ( ! $meta_key ) {
			return $meta_cache;
		}

		$result = null;
		if ( isset( $meta_cache[ $meta_key ] ) ) {
			if ( $single ) {
				$result = maybe_unserialize( $meta_cache[ $meta_key ][0] );
			} else {
				$result = array_map( 'maybe_unserialize', $meta_cache[ $meta_key ] );
			}
		}

		$option_key = $meta_key;
		$options    = get_option( $option_key, array() );
		if ( ! is_array( $options ) ) {
			// @codeCoverageIgnoreStart
			$options = array();
			// @codeCoverageIgnoreEnd
		}

		if ( isset( $result ) && ! empty( $result ) && $single ) {
			$options[ $term_id ] = $result;
			update_option( $option_key, $options );
		}

		return $result;
	}
}
