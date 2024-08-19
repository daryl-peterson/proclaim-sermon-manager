<?php
/**
 * Taxonomy image attaching.
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
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logging\Logger;
use WP_Error;
use WP_Post;

/**
 * Taxonomy image attaching.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxonomyImage implements Executable, Registrable {

	/**
	 * Options interface.
	 *
	 * @var OptionsInt
	 */
	public OptionsInt $options;

	/**
	 * Sermon image class.
	 *
	 * @var SermonImage
	 */
	private SermonImage $sermon_image;

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
		$this->options      = options();
		$this->sermon_image = SermonImage::exec();
		$this->tax          = array(
			Tax::PREACHER,
			Tax::SERIES,
			Tax::TOPICS,
		);
		$this->image_suffix = '_image_id';

		foreach ( $this->tax as $taxonomy ) {
			$this->image_ids[] = $taxonomy . $this->image_suffix;
		}
	}

	/**
	 * Initialize and register callbacks.
	 *
	 * @return TaxonomyImage
	 * @since 1.0.0
	 */
	public static function exec(): TaxonomyImage {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register callbacks.
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
	 * @param mixed   $value
	 * @param integer $term_id Term ID;
	 * @param string  $meta_key Meta key.
	 * @param boolean $single Get single value.
	 * @param string  $meta_type Expected term.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_metadata( mixed $value, int $term_id, string $meta_key, bool $single, string $meta_type ): mixed {

		// If it's not what were looking for return orginal value.
		if ( ! isset( $meta_key ) || empty( $meta_key ) || ! in_array( $meta_key, $this->image_ids ) ) {
			return $value;
		}

		$meta_cache = wp_cache_get( $term_id, $meta_type . '_meta' );

		if ( ! $meta_cache ) {
			$meta_cache = update_meta_cache( $meta_type, array( $term_id ) );
			if ( isset( $meta_cache[ $term_id ] ) ) {
				$meta_cache = $meta_cache[ $term_id ];
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

		$option_key = $meta_key . '_' . $term_id;

		if ( isset( $result ) && ! empty( $result ) && $single ) {
			$this->options->set( $option_key, $result );
		}

		Logger::debug(
			array(
				'VALUE'     => $result,
				'TERM ID'   => $term_id,
				'META KEY'  => $meta_key,
				'SINGLE'    => $single,
				'META TYPE' => $meta_type,
			)
		);

		return $result;
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

		$sermons = $this->get_sermons_by_term( $taxonomy, $term_id );
		if ( ! isset( $sermons ) ) {
			return false;
		}

		$result = false;

		/**
		 * @var WP_Post $sermon
		 */
		foreach ( $sermons as $sermon ) {
			$result = $this->sermon_image->attach_image( $attachment, $sermon );

			if ( $result ) {
				break;
			}
		}
		Logger::debug(
			array(
				'TERM ID'    => $term_id,
				'META KEY'   => $meta_key,
				'META VALUE' => $meta_value,
				'RETURN'     => $result,
			)
		);
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

		$option_key = $meta_key . '_' . $term_id;
		$image_id   = $this->options->get( $option_key );
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

		$this->options->delete( $option_key );
		$result = $this->sermon_image->detach_image( $attachment, $sermon );

		Logger::debug(
			array(
				'TERM ID'    => $term_id,
				'META KEY'   => $meta_key,
				'META VALUE' => $meta_value,
				'RESULT'     => $result,
			)
		);
		return $result;
	}

	/**
	 * Get taxonomy from meta key.
	 *
	 * @param string $meta_key Meta key.
	 * @return string|null
	 * @since 1.0.0
	 */
	private function get_taxonomy( string $meta_key ): ?string {

		if ( ! isset( $meta_key ) || empty( $meta_key ) || ! in_array( $meta_key, $this->image_ids ) ) {
			return null;
		}

		if ( false === strpos( $meta_key, $this->image_suffix ) ) {
			return null;
		}

		$taxonomy = trim( str_replace( $this->image_suffix, '', $meta_key ) );
		if ( ! in_array( $taxonomy, $this->tax ) ) {
			return null;
		}

		Logger::debug( array( 'TAXONOMY' => $taxonomy ) );

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

		if ( $sermon instanceof WP_Error ) {
			return null;
		}

		if ( is_array( $sermon ) && isset( $sermon[0] ) ) {
			$sermon = $sermon[0];
		}

		if ( ( ! $sermon instanceof WP_Post ) || ( PT::SERMON !== $sermon->post_type ) ) {
			return null;
		}
		Logger::debug( $sermon );
		return $sermon;
	}

	/**
	 * Get sermons by term
	 *
	 * @param string  $taxonomy Taxonomy.
	 * @param integer $term_id Term id.
	 * @return array|null Sermons array or null.
	 * @since 1.0.0
	 */
	private function get_sermons_by_term( string $taxonomy, int $term_id ): ?array {
		$sermons = query_posts(
			array(
				'post_type'      => PT::SERMON,
				'showposts'      => -1,
				'posts_per_page' => 50,
				// 'fields'         => 'ids',  // Return array of ids.
				'tax_query'      => array(
					array(
						'taxonomy' => $taxonomy,
						'terms'    => $term_id,
						'field'    => 'term_id',
						'orderby'  => 'term_id',
						'order'    => 'asc',
					),
				),
			)
		);

		if ( ! is_array( $sermons ) ) {
			return null;
		}
		return $sermons;
	}

	/**
	 * Get attachment.
	 *
	 * @param int $image_id Post ID
	 * @return ?WP_Post Post attachment.
	 * @since 1.0.0
	 */
	private function get_attachment( int $image_id ): ?WP_Post {

		$attachment = get_post( $image_id );
		if ( $attachment instanceof WP_Error ) {
			return null;
		}

		if ( is_array( $attachment ) && isset( $attachment[0] ) ) {
			$attachment = $attachment[0];
		}

		if ( ! $attachment instanceof WP_Post ) {
			return null;
		}

		if ( PT::ATTACHEMENT !== $attachment->post_type ) {
			return null;
		}
		Logger::debug( $attachment );
		return $attachment;
	}
}
