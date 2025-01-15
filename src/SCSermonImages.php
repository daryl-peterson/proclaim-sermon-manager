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
use WP_Exception;
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
	 * Initialize and preform registration hooks if needed.
	 *
	 * @return SCSermonImages
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
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
	 * - **orderby** Order by name, id, count, slug, term_group, none. (name)
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
				Templates::ImageList,
				array(
					'list' => $this->data,
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

		$list = TaxQueries::get_terms_with_images(
			array(
				'taxonomy' => $args['display'],
				'order'    => $args['order'],
				'orderby'  => $args['orderby'],
				'number'   => $this->number,
				'offset'   => $this->offset,
			)
		);

		if ( ! $list ) {
			return;
		}

		$data     = array();
		$count    = 0;
		$meta_key = $args['display'] . '_image_id';

		/**
		 * @var WP_Term $item
		 */
		foreach ( $list as $item ) {

			$url  = null;
			$meta = get_term_meta( $item->term_id, $meta_key, true );

			if ( ! empty( $meta ) && false !== $meta ) {
				$url = wp_get_attachment_image_url( $meta, $args['size'] );
			}
			if ( ! $url ) {
				continue;
			}

			$data_temp = array(
				'term_id'          => $item->term_id,
				'term_name'        => $item->name,
				'term_tax'         => $item->taxonomy,
				'term_link'        => esc_url( get_term_link( $item, $item->taxonomy ) ),
				'term_description' => $item->description,
				'image_size'       => $args['size'],
				'image_url'        => $url,
				'columns'          => 'col' . $args['columns'],
				'count'            => $item->count,
				'preacher_label'   => get_taxonomy_field( DRPPSM_TAX_PREACHER, 'label' ),

			);

			$this->set_ext_data( $item->term_id, $data_temp );
			$data[] = $data_temp;

			++$count;
		}
		if ( 0 === $count ) {
			$this->data = null;
			return;
		}
		Logger::debug( $data );
		$this->data = $data;
	}

	/**
	 *
	 * @param int   $term_id
	 * @param array &$data
	 * @return void
	 * @since 1.0.0
	 */
	private function set_ext_data( int $term_id, array &$data ) {
		$key = 'drppsm_series_info_' . $term_id;

		$transient = get_transient( $key );
		if ( ! $transient ) {
			$data['preacher_cnt']   = 1;
			$data['preacher_names'] = '';
			$data['dates']          = '';
			return;
		}

		Logger::debug( array( 'TRANSIENT' => $transient ) );

		if ( isset( $transient->preacher ) ) {
			$data['preacher_cnt']   = $transient->preacher->cnt;
			$data['preacher_names'] = implode( ', ', $transient->preacher->names );
		}
		if ( $data['preacher_cnt'] === 0 ) {
			$data['preacher_cnt'] = 1;
		}

		if ( isset( $transient->dates ) ) {
			$dates = $transient->dates;

			if ( is_array( $dates ) && 0 !== count( $dates ) ) {
				$format = 'j F Y';
				asort( $dates );

				$cnt = count( $dates );

				Logger::debug( array( 'DATES' => $dates ) );
				$date_last = '';

				if ( 1 === $cnt ) {
					$date_first = wp_date( $format, $dates[0] );
					if ( ! $date_first ) {
						$date_first = '';
					}
				} elseif ( $cnt > 1 ) {
					$date_first = wp_date( $format, $dates[0] );

					$date_last = wp_date( $format, $dates[ $cnt - 1 ] );
					if ( ! $date_last ) {
						$date_last = '';
					} else {
						$date_last = ' - ' . $date_last;
					}

					$data['dates'] = $date_first . $date_last;
				}
				$data_temp['dates'] = $date_first . ' - ' . $date_last;

			}
		}
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

		$term_count = TaxQueries::get_term_count( $args['display'], true );
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
			'order'            => 'ASC',
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
