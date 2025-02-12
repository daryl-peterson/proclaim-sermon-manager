<?php
/**
 * Taxonomy image list.
 *
 * @package     DRPPSM\TaxImageList
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use stdClass;
use WP_Term;

/**
 * Taxonomy image list.
 *
 * @package     DRPPSM\TaxImageList
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
	 * Used in paginated queries, per_page
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $per_page;

	/**
	 * Template data.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private null|array $data;

	/**
	 * Query offset.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $offset;

	/**
	 * Query arguments.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $args;

	private ?TaxMeta $tax_meta;


	/**
	 * TaxImageList constructor.
	 *
	 * @param array $args Shortcode arguments.
	 * @since 1.0.0
	 */
	public function __construct( array $args = array() ) {

		if ( ! isset( $args['display'] ) ) {
			return;
		}

		$this->taxonomy = TaxUtils::get_taxonomy_name( $args['display'] );
		if ( ! $this->taxonomy ) {
			return;
		}
		$this->tax_meta = TaxMeta::exec();

		$defaults = $this->get_default_args();
		$args     = array_merge( $defaults, $args );
		Logger::debug( $args );

		$args['display'] = $this->taxonomy;
		$args['post_id'] = get_the_ID();
		$this->set_params( $args );
		$this->set_pagination();
		$this->set_term_data();
		$this->show_template( $args );
	}

	/**
	 * Show template.
	 *
	 * @param array $args
	 * @return void
	 * @since 1.0.0
	 */
	private function show_template( array $args ) {
		$output = '';

		$output .= TemplateFiles::start();

		if ( isset( $this->data ) && is_array( $this->data ) && count( $this->data ) > 0 ) {

			ob_start();
			echo sermon_sorting();
			get_partial(
				Template::TAX_IMAGE_LIST,
				array(
					'list'    => $this->data,
					'columns' => $args['columns'],
					'size'    => $args['size'],
				)
			);
			get_partial( Template::Pagination, $this->paginate );

			$output .= ob_get_clean();
		} else {
			ob_start();
			get_partial( 'no-posts' );
			$output .= ob_get_clean();
		}

		$output .= TemplateFiles::end();

		echo $output;
	}

	private function set_params( array $args ): void {
		$this->per_page = $args['per_page'];
		$this->args     = array(
			'hide_empty' => true,
			'meta_key'   => $this->taxonomy . '_date',
			'orderby'    => 'meta_value_num',
			'order'      => 'DESC',
		);
	}


	/**
	 * Set term data needed for template.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function set_term_data(): void {

		$this->data = null;

		if ( ! $this->paginate ) {
			return;
		}

		$page      = get_page_number();
		$tax       = $this->taxonomy;
		$trans_key = "{$tax}_imagelist_{$page}";
		$data      = Transient::get( $trans_key );
		if ( $data ) {
			Logger::debug( "Transient found : $trans_key" );
			$this->data = $data;
			return;
		} else {
			Logger::debug( "Transient not found : $trans_key" );
		}

		$args               = $this->args;
		$args['hide_empty'] = true;
		$args['number']     = $this->per_page;
		$args['offset']     = $this->offset;
		Logger::debug( $args );

		// @todo Add sorting by bible book

		$list = get_terms( $args );

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
		Transient::set( $trans_key, $data, Transient::TTL_12_HOURS );
	}

	/**
	 * Get meta data for term.
	 *
	 * @param WP_Term $item Term object.
	 * @return null|stdClass
	 * @since 1.0.0
	 */
	private function get_meta( WP_Term $item ): ?stdClass {
		if ( ! isset( $item->taxonomy ) ) {
			return null;
		}
		$meta = $this->tax_meta->get_taxonomy_meta( $item->taxonomy, $item->term_id );

		// $meta = apply_filters( "get_{$item->taxonomy}_meta_extd", $item->taxonomy, $item->term_id );
		if ( $meta ) {
			return $meta;
		}
		return null;
	}

	/**
	 * Set pagination data.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function set_pagination(): void {

		$this->paginate = null;

		$term_count = wp_count_terms(
			array(
				'taxonomy'   => $this->taxonomy,
				'hide_empty' => true,
			)
		);
		if ( 0 === $term_count ) {
			return;
		}

		// Calculate pagination
		$max_num_pages = ceil( $term_count / $this->per_page );
		$paged         = get_page_number();

		// Calculate term offset
		$offset = ( ( $paged - 1 ) * $this->per_page );

		// We can now get our terms and paginate it
		$this->offset = $offset;

		$this->paginate = array(
			'current' => $paged,
			'total'   => $max_num_pages,
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
			'per_page'   => Settings::get( Settings::SERMON_COUNT ),
		);
	}
}
