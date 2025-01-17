<?php
/**
 * Taxonomy image attaching.
 *
 * @package     DRPPSM\TaxonomyImageAttach
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Error;
use WP_Post;

/**
 * Taxonomy image attaching.
 *
 * @package     DRPPSM\TaxonomyImageAttach
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxonomyImageAttach implements Executable, Registrable {

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

	private string $preacher_suffix;

	private array $preacher_ids;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->sermon_image    = SermonImageAttach::exec();
		$this->tax             = array(
			DRPPSM_TAX_PREACHER,
			DRPPSM_TAX_SERIES,
			DRPPSM_TAX_TOPICS,
		);
		$this->image_suffix    = '_image_id';
		$this->preacher_suffix = '_preachers';

		foreach ( $this->tax as $taxonomy ) {
			$this->image_ids[]    = $taxonomy . $this->image_suffix;
			$this->preacher_ids[] = $taxonomy . '_preachers';
		}
	}

	/**
	 * Initialize and register hooks.
	 *
	 * @return TaxonomyImageAttach
	 * @since 1.0.0
	 */
	public static function exec(): TaxonomyImageAttach {
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
		int $object_id,
		string $meta_key,
		bool $single,
		string $meta_type
	): mixed {

		if ( ! isset( $meta_key ) || empty( $meta_key ) ) {
			return $value;
		}

		if ( in_array( $meta_key, $this->image_ids, true ) ) {
			return $this->get_image_meta( $object_id, $meta_key, $single, $meta_type );
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
	public function add_meta( int $term_id, string $meta_key, mixed $meta_value ): bool {

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
	public function update_meta( int $meta_id, int $term_id, string $meta_key, mixed $meta_value ) {

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
	public function delete_meta( mixed $meta_ids, int $term_id, string $meta_key, mixed $meta_value ) {

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
	private function attach( int $term_id, string $meta_key, mixed $meta_value ): bool {

		$taxonomy = $this->get_taxonomy( $meta_key );
		if ( ! isset( $taxonomy ) ) {
			return false;
		}

		$attachment = $this->get_attachment( $meta_value );
		if ( ! isset( $attachment ) ) {
			return false;
		}

		$sermons = TaxUtils::get_sermons_by_term( $taxonomy, $term_id );
		if ( ! isset( $sermons ) ) {
			return false;
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
	private function detach( int $term_id, string $meta_key, mixed $meta_value ): bool {

		$taxonomy = $this->get_taxonomy( $meta_key );
		if ( ! isset( $taxonomy ) ) {
			return false;
		}

		$option_key = $meta_key;
		$options    = get_option( $option_key, null );
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$image_id = null;
		if ( is_array( $options ) && key_exists( $term_id, $options ) ) {
			$image_id = $options[ $term_id ];
		}

		if ( ! isset( $image_id ) ) {
			return false;
		}

		$attachment = $this->get_attachment( $image_id );
		if ( ! isset( $attachment ) ) {
			return false;
		}

		if ( 0 === $attachment->post_parent ) {
			return true;
		}

		$sermon = $this->get_sermon( $attachment->post_parent );
		if ( ! isset( $sermon ) ) {
			return false;
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

		if ( ! isset( $meta_key ) || empty( $meta_key ) || ! in_array( $meta_key, $this->image_ids, true ) ) {
			return null;
		}

		if ( false === strpos( $meta_key, $this->image_suffix ) ) {
			return null;
		}

		$taxonomy = trim( str_replace( array( $this->image_suffix, $this->preacher_suffix ), '', $meta_key ) );
		if ( ! in_array( $taxonomy, $this->tax, true ) ) {
			return null;
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
		$sermon = get_post( $sermon_id );

		if ( $this->is_error( $sermon ) ) {
			return null;
		}

		if ( is_array( $sermon ) ) {
			$sermon = array_shift( $sermon );
		}

		if ( ( ! $sermon instanceof WP_Post ) || ( $this->pt !== $sermon->post_type ) ) {
			return null;
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

		$attachment = get_post( $image_id );
		if ( $this->is_error( $attachment ) ) {
			return null;
		}

		if ( is_array( $attachment ) && isset( $attachment[0] ) ) {
			$attachment = $attachment[0];
		}

		if ( ! $attachment instanceof WP_Post ) {
			return null;
		}

		if ( 'attachment' !== $attachment->post_type ) {
			return null;
		}
		return $attachment;
	}

	/**
	 * Check if value is WP_Error.
	 *
	 * @param mixed $value Value to check.
	 * @return bool
	 * @since 1.0.0
	 */
	private function is_error( mixed $value ): bool {
		return $value instanceof WP_Error;
	}

	private function get_image_meta(
		int $object_id,
		string $meta_key,
		bool $single,
		string $meta_type
	) {
		$meta_cache = wp_cache_get( $object_id, $meta_type . '_meta' );

		if ( ! $meta_cache ) {
			$meta_cache = update_meta_cache( $meta_type, array( $object_id ) );
			if ( isset( $meta_cache[ $object_id ] ) ) {
				$meta_cache = $meta_cache[ $object_id ];
			} else {
				$meta_cache = null;
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
			$options = array();
		}

		if ( isset( $result ) && ! empty( $result ) && $single ) {
			$options[ $object_id ] = $result;
			update_option( $option_key, $options );
		}

		return $result;
	}
}
