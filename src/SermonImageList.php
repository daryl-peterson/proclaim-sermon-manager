<?php
/**
 * Sermon Archive Class.
 *
 * @package     DRPPSM\SermonArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Post;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon Archive Class.
 *
 * @package     DRPPSM\SermonArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonImageList {

	/**
	 * Used in paginated queries, per_page
	 *
	 * @var int
	 * @since 1.0.0
	 */
	private int $per_page;

	/**
	 * Order.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $order;

	/**
	 * Order by.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $orderby;

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
	 * Query arguments.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $args;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->set_params();
		$this->set_pagination();
		$this->data = $this->get_post_data();

		$this->show_template();
	}

	/**
	 * Render the archive.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function show_template() {
		$output = '';

		$layout = Settings::get( Settings::SERMON_LAYOUT );

		if ( isset( $this->data ) && is_array( $this->data ) && count( $this->data ) > 0 ) {

			ob_start();
			get_partial(
				$layout,
				array(
					'list'    => $this->data,
					'columns' => Settings::get( Settings::IMAGES_PER_ROW ),
					'size'    => 'psm-sermon-medium',
				)
			);
			get_partial( Template::Pagination, $this->paginate );

			$output .= ob_get_clean();
		} else {
			ob_start();
			get_partial( 'no-posts' );
			$output .= ob_get_clean();
		}
		echo $output;
	}

	/**
	 * Set query parameters.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function set_params() {
		$this->per_page = Settings::get( Settings::SERMON_COUNT );
		$this->order    = Settings::get( Settings::ARCHIVE_ORDER );
		$this->orderby  = Settings::get( Settings::ARCHIVE_ORDER_BY );

		$args = array(
			'post_type'      => DRPPSM_PT_SERMON,
			'orderby'        => 'meta_value_num',
			'order'          => $this->order,
			'posts_per_page' => $this->per_page,
		);

		if ( $this->orderby === 'date_preached' ) {
			$args['meta_query'] = array(
				'orderby'      => 'meta_value_num',
				'meta_key'     => SermonMeta::DATE,
				'meta_value'   => time(),
				'meta_compare' => '<=',
			);
			$args['orderby']    = 'meta_value_num';
			$args['order']      = $this->order;
			$args['meta_key']   = SermonMeta::DATE;
		}
		$this->args = $args;
	}

	/**
	 * Get post data.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_post_data(): array {

		$pt        = DRPPSM_PT_SERMON;
		$page      = get_page_number();
		$trans_key = "{$pt}_imagelist_{$page}";
		$trans     = Transient::get( $trans_key );

		if ( $trans ) {
			Logger::debug( "Using transient : $trans_key" );
			return $trans;
		}

		// Set arguments from pagination.
		$this->args['number'] = $this->number;
		$this->args['offset'] = $this->offset;

		$post_data = get_posts( $this->args );
		$data      = array();

		/**
		 * @var WP_Post $post_item
		 */
		foreach ( $post_data as $post_item ) {
			$data[ $post_item->ID ] = new Sermon( $post_item );
		}
		Transient::set( $trans_key, $data, Transient::TTL_12_HOURS );
		return $data;
	}

	/**
	 * Set pagination data.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function set_pagination() {
		global $post;

		$this->paginate = null;

		$term_count = wp_count_posts( DRPPSM_PT_SERMON )->publish;
		if ( ! $term_count ) {
			return;
		}

		$tpp           = $this->per_page;
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
}
