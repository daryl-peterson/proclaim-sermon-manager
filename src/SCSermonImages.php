<?php
/**
 * Shortcodes for sermon images.
 *
 * @package     DRPPSM\SCSermonImages
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use stdClass;
use WP_Term;

/**
 * Shortcodes for sermon images.
 *
 * @package     DRPPSM\SCSermonImages
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SCSermonImages extends SCBase implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Taxonomy transient map.
	 */
	public const TRANSIENT_MAP = array(
		DRPPSM_TAX_BOOK     => Transient::BOOK_INFO,
		DRPPSM_TAX_PREACHER => Transient::PREACHER_INFO,
		DRPPSM_TAX_SERIES   => Transient::SERIES_INFO,
		DRPPSM_TAX_TOPIC    => Transient::TOPIC_INFO,
	);

	/**
	 * Shortcode name.
	 *
	 * @var string
	 */
	private string $sc;

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
	 * Transient key.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $transient_key;

	/**
	 * Initialize object properties.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
		$this->sc = DRPPSM_SC_SERMON_IMAGES;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( shortcode_exists( $this->sc ) ) {
			return false;
		}
		add_shortcode( $this->sc, array( $this, 'show_images' ) );
		return true;
	}

	/**
	 * Show sermon,preacher images. ect.
	 *
	 * @param array $atts Shorcode attributes array.
	 * @return string
	 * @since 1.0.0
	 *
	 * #### Atts Parameters
	 * - **per_page** Define how many sermons to show per page. Overrides the WordPress setting.
	 * - **display** Series or preachers
	 * - **orderby** Order by name, id, count, slug, term_group, date, none. (name)
	 * - **hide_title** Hides title if set to "yes"
	 * - **show_description** Shows description if set to "yes"
	 * - **columns** Number of images per row.
	 */
	public function show_images( array $atts ): string {

		$atts = $this->fix_atts( $atts );
		$args = $this->get_default_args();
		$args = shortcode_atts( $args, $atts, $this->sc );

		$tax = $this->get_taxonomy_name( $args['display'] );
		if ( ! $tax ) {
			return '<strong>Error: Invalid "list" parameter.</strong><br> Possible values are: "series", "preachers", "topics" and "books".<br> You entered: "<em>' . $args['display'] . '</em>"';
		}

		$args['display'] = $tax;
		$args['post_id'] = get_the_ID();
		$this->set_term_data( $args );

		$output = '';
		if ( isset( $this->data ) && is_array( $this->data ) && count( $this->data ) > 0 ) {
			ob_start();
			get_partial(
				Templates::TaxImageList,
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
		return $output;
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
			'taxonomy'   => $args['display'],
			'hide_empty' => true,
			'number'     => $this->number,
			'offset'     => $this->offset,
		);

		if ( $args['orderby'] === 'date' ) {
			$tax_query = array(
				'hide_empty' => true,
				'number'     => $this->number,
				'offset'     => $this->offset,
				'meta_key'   => $args['display'] . '_date',
				'orderby'    => 'meta_value_num',
				'order'      => 'DESC',
			);
		}

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
		$this->paginate = null;

		$term_count = TaxUtils::get_term_count( $args['display'], true );
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
			'post_id' => $args['post_id'],
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
			'display'          => 'series',
			'order'            => 'DESC',
			'orderby'          => 'name',
			'size'             => ImageSize::SERMON_MEDIUM,
			'hide_title'       => false,
			'show_description' => false,
			'image_size'       => ImageSize::SERMON_MEDIUM,
			'columns'          => Settings::get( Settings::IMAGES_PER_ROW ),

			// Used in query as number
			'per_page'         => 12,
		);
	}
}
