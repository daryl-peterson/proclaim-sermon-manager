<?php
/**
 * Taxonomy image list.
 *
 * @package     DRPPSM\TaxList
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use stdClass;
use WP_Term;

defined( 'ABSPATH' ) || exit;


/**
 * Taxonomy image list.
 *
 * @package     DRPPSM\TaxList
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxImageList {

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $taxonomy;

	/**
	 * Pagination arguments.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private null|array $paginate;

	/**
	 * Template data.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private null|array $data;

	/**
	 * Used in paginated queries, per_page
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $number;

	/**
	 * Query offset.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $offset;


	/**
	 * TaxImageList constructor.
	 *
	 * @param array $args Shortcode arguments.
	 * @since 1.0.0
	 */
	public function __construct( array $args = array() ) {
		Logger::debug( $args );
		$defaults = $this->get_default_args();
		$args     = array_merge( $defaults, $args );
		Logger::debug( $args );
		$this->set_term_data( $args );

		$this->taxonomy = TaxUtils::get_taxonomy_name( $args['display'] );
		if ( ! $this->taxonomy ) {
			return;
		}

		$args['display'] = $this->taxonomy;
		$args['post_id'] = get_the_ID();
		$this->set_term_data( $args );

		$output = '';
		if ( isset( $this->data ) && is_array( $this->data ) && count( $this->data ) > 0 ) {

			ob_start();

			get_partial(
				Templates::TAX_IMAGE_LIST,
				array(
					'list'    => $this->data,
					'columns' => $args['columns'],
					'size'    => $args['size'],
				)
			);
			get_partial( Templates::Pagination, $this->paginate );

			$output .= ob_get_clean();
		} else {
			ob_start();
			get_partial( 'no-posts' );
			$output .= ob_get_clean();
		}
		echo $output;
	}

	/**
	 * Set term data needed for template.
	 *
	 * @param array $args Shortcode arguments.
	 * @return void
	 * @since 1.0.0
	 */
	private function set_term_data( array $args ): void {

		$this->data     = null;
		$this->paginate = null;

		$this->set_pagination( $args );
		if ( ! $this->paginate ) {
			return;
		}

		$tax_query = array(
			'hide_empty' => true,
			'number'     => $this->number,
			'offset'     => $this->offset,
			'meta_key'   => $args['display'] . '_date',
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
		);

		$list = get_terms( $tax_query );

		if ( ! $list ) {
			return;
		}

		$data  = array();
		$count = 0;

		/**
		 * @var WP_Term $item
		 */
		foreach ( $list as $item ) {

			$meta = $this->get_meta( $item );
			if ( ! $meta ) {
				continue;
			}

			$data[] = $meta;
			++$count;
		}
		if ( 0 === $count ) {
			$this->data = null;
			return;
		}
		$this->data = $data;
	}


	/**
	 * Get meta data for term.
	 *
	 * @param WP_Term $item Term object.
	 * @return null|stdClass
	 * @since 1.0.0
	 */
	private function get_meta( WP_Term $item ): ?stdClass {

		$meta = apply_filters( "get_{$item->taxonomy}_meta_extd", $item->taxonomy, $item->term_id );
		if ( $meta ) {
			return $meta;
		}
		return null;
	}

	/**
	 * Set pagination data.
	 *
	 * @param array $args Shortcode arguments.
	 * @return void
	 * @since 1.0.0
	 */
	private function set_pagination( array $args ): void {
		global $post;

		$this->paginate = null;

		$term_count = TaxUtils::get_term_count( DRPPSM_TAX_SERIES, true );
		if ( ! $term_count ) {
			return;
		}

		$tpp           = $args['per_page'];
		$max_num_pages = ceil( $term_count / $tpp );
		$paged         = get_page_number();

		// Calculate term offset
		$offset = ( ( $paged - 1 ) * $tpp );

		// We can now get our terms and paginate it
		$this->number = $tpp;
		$this->offset = $offset;

		$this->paginate = array(

			'current' => $paged,
			'total'   => $max_num_pages,
			'post_id' => $post->ID,
		);
	}

	/**
	 * Get default arguments.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_default_args(): array {
		return array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'size'       => ImageSize::SERMON_MEDIUM,
			'hide_title' => false,
			'image_size' => ImageSize::SERMON_MEDIUM,
			'columns'    => Settings::get( Settings::IMAGES_PER_ROW ),
			'per_page'   => 6,
		);
	}
}
