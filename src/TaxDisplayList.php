<?php
/**
 * Display taxonomy list.
 *
 * @package     DRPPSM\TaxDisplayList
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
 * Display taxonomy list.
 *
 * @package     DRPPSM\TaxDisplayList
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxDisplayList extends TaxDisplay {


	/**
	 * Taxonomy meta.
	 *
	 * @var null|TaxMeta
	 * @since 1.0.0
	 */
	private ?TaxMeta $tax_meta;


	/**
	 * TaxImageList constructor.
	 *
	 * @param array $args Shortcode arguments.
	 * @since 1.0.0
	 */
	public function __construct( array $args = array() ) {

		if ( ! $this->is_args_valid( $args ) ) {
			return;
		}

		$this->tax_meta = TaxMeta::exec();

		$defaults = $this->get_default_args();
		$args     = array_merge( $defaults, $args );

		$args['display'] = $this->taxonomy;
		$args['post_id'] = get_the_ID();
		$this->set_params( $args );
		$this->set_pagination();
		$this->set_data();

		$params = array(
			'list'    => $this->data,
			'columns' => $args['columns'],
			'size'    => $args['size'],
		);

		$this->show_template( Template::TAX_IMAGE_LIST, $params );
	}

	/**
	 * Get record count
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public function get_count(): int {
		$term_count = wp_count_terms(
			array(
				'taxonomy'   => $this->taxonomy,
				'hide_empty' => true,
			)
		);
		if ( is_wp_error( $term_count ) ) {
			return 0;
		}
		return absint( $term_count );
	}

	/**
	 * Validate arguments.
	 *
	 * @param array $args
	 * @return bool
	 * @since 1.0.0
	 */
	protected function is_args_valid( array $args ): bool {
		if ( ! isset( $args['display'] ) ) {
			return false;
		}

		$this->taxonomy = TaxUtils::get_taxonomy_name( $args['display'] );
		if ( ! $this->taxonomy ) {
			return false;
		}

		return true;
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

	/**
	 * Set query parameters.
	 *
	 * @param array $args
	 * @return void
	 * @since 1.0.0
	 */
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
	protected function set_data(): void {

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
}
