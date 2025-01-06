<?php
/**
 * Sermon sorting.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use WP_Exception;

defined( 'ABSPATH' ) || exit;


/**
 * Sermon sorting.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonSorting implements Executable {
	/**
	 * Bible taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_bible;

	/**
	 * Preacher taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_preacher;

	/**
	 * Series taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_series;


	/**
	 * Service type taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_service_type;


	/**
	 * Topics taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_topics;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->tax_bible        = DRPPSM_TAX_BIBLE;
		$this->tax_preacher     = DRPPSM_TAX_PREACHER;
		$this->tax_series       = DRPPSM_TAX_SERIES;
		$this->tax_service_type = DRPPSM_TAX_SERVICE_TYPE;
		$this->tax_topics       = DRPPSM_TAX_TOPICS;
	}

	public static function exec(): self {
		$obj = new self();
		return $obj;
	}

	/**
	 * Render sermon sorting / filtering.
	 *
	 * @param array $args  Array of arguments.
	 * @return null|string Return null on error string on success.
	 * @since 1.0.0
	 */
	public function render_sorting( array $args = array() ): ?string {

		try {
			/**
			 * Allows for filters HTML field data attributes.
			 *
			 * @param array $args
			 * @return array
			 *
			 * @category filter
			 * @since 1.0.0
			 */
			$filters = apply_filters( 'drppsmf_sorting_filters_styles', $this->get_filter_data_atts() );

			/**
			 * Allows for filting taxonomy sorting / filtering visibility.
			 * This overrides any settings in the admin area.
			 *
			 * @param array $visibility
			 * @return array
			 *
			 * @category filter
			 * @since 1.0.0
			 */
			$visibility_mapping = apply_filters( 'drppsmf_sorting_visibility_mapping', $this->get_visibility_mapping() );

			// Save orig args for filters.
			$orig_args = $args;

			$default = $this->get_defaults();
			$args    = $args + $default;

			// Populate the action field for the form.
			$this->set_action( $args );

			/**
			 * Allows to filter filtering args.
			 *
			 * @param array  $args               The args.
			 * @param array  $orig_args          The unmodified args.
			 * @param string $action             The form URL.
			 * @param array  $filters            Filters HTML form data. i.e. no idea.
			 * @param array  $visibility_mapping Taxonomy slug -> args parameter name
			 * @return array
			 *
			 * @category filter
			 * @since 1.0.0
			 */
			$args = apply_filters( 'drppsmf_sorting_args', $args, $orig_args, $filters, $visibility_mapping );

			$hide_filters = $args['hide_filters'];

			/**
			 * Allows to skip rendering of filtering completely.
			 *
			 * @param bool   $hide_filters       True to show, false to hide. Default as it is defined in settings.
			 * @param array  $args               The args.
			 * @param array  $orig_args          The unmodified args.
			 * @param string $action             The form URL.
			 * @param array  $filters            Filters HTML form data. i.e. no idea.
			 * @param array  $visibility_mapping Taxonomy slug -> args parameter name
			 *
			 * @category filter
			 * @since 1.0.0
			 */
			$do_filter = apply_filters( 'drppsmf_do_sorting', $hide_filters, $args, $orig_args, $filters, $visibility_mapping );

			$content = '';
			if ( $do_filter ) {
				$content = get_partial(
					'content-sermon-filtering',
					array(
						'filters'            => $filters,
						'visibility_mapping' => $visibility_mapping,
						'args'               => $args,
					)
				);
			}

			/**
			 * Allows to filter the output of filter rendering.
			 *
			 * @param string $content            The original content.
			 * @param array  $args               The args.
			 * @param array  $orig_args          The unmodified args.
			 * @param string $action             The form URL.
			 * @param array  $filters            Filters HTML form data. i.e. no idea.
			 * @param array  $visibility_mapping Taxonomy slug -> args parameter name
			 *
			 * @category filter
			 * @since 1.0.0
			 */
			return apply_filters( 'drppsmf_sorting_output', $content, $args, $orig_args, $filters, $visibility_mapping );

		} catch ( \Throwable | WP_Exception $th ) {
			Logger::error(
				array(
					'ERROR' => $th->getMessage(),
					$th->getTrace(),
				)
			);
			return null;
		}
	}

	/**
	 * Get filter data attributes.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_filter_data_atts(): array {
		return array(
			array(
				'className' => 'drppsm-sort-preacher',
				'taxonomy'  => $this->tax_preacher,
				'title'     => get_taxonomy_field( $this->tax_preacher, 'singular_name' ),
			),
			array(
				'className' => 'drppsm-sort-series',
				'taxonomy'  => $this->tax_series,
				'title'     => __( 'Series', 'drppsm' ),
			),
			array(
				'className' => 'drppsm-sort-topics',
				'taxonomy'  => $this->tax_topics,
				'title'     => __( 'Topic', 'drppsm' ),
			),
			array(
				'className' => 'drppsm-sort-book',
				'taxonomy'  => $this->tax_bible,
				'title'     => __( 'Book', 'drppsm' ),
			),
			array(
				'className' => 'drppsm-sort-stype',
				'taxonomy'  => $this->tax_service_type,
				'title'     => get_taxonomy_field( $this->tax_service_type, 'singular_name' ),
			),
		);
	}

	/**
	 * Get defaults.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_defaults(): array {
		return array(
			'id'                  => 'drppsm_sermon_sorting',
			'classes'             => '',
			'series_filter'       => '',
			'service_type_filter' => '',
			'series'              => '',
			'preachers'           => '',
			'topics'              => '',
			'books'               => '',
			'visibility'          => 'suggest',
			'hide_filtering'      => Settings::get( Settings::HIDE_FILTERING, false ),
			'hide_book'           => Settings::get( Settings::HIDE_BIBLE_BOOK, true ),
			'hide_preacher'       => Settings::get( Settings::HIDE_PREACHER, false ),
			'hide_series'         => Settings::get( Settings::HIDE_SERIES, false ),
			'hide_service_type'   => Settings::get( Settings::HIDE_SERVICE_TYPE, true ),
			'hide_topics'         => Settings::get( Settings::HIDE_TOPICS, true ),

			'hide_dates'          => '',
			// 'hide_filters'        => ! SermonManager::getOption( 'hide_filters' ),
			'action'              => 'none',
		);
	}

	/**
	 * Get visibility mapping.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function get_visibility_mapping(): array {
		return array(
			$this->tax_topics       => 'hide_topics',
			$this->tax_series       => 'hide_series',
			$this->tax_preacher     => 'hide_preachers',
			$this->tax_bible        => 'hide_books',
			$this->tax_service_type => 'hide_service_types',
			'drppsm_dates'          => 'hide_dates',
		);
	}

	/**
	 * Set form action.
	 *
	 * @param array &$args
	 * @return void
	 */
	private function set_action( array &$args ): void {
		switch ( $args['action'] ) {
			case 'home':
				$args['action'] = get_home_url();
				break;
			case 'site':
				$args['action'] = get_site_url();
				break;
			case 'none':
			default:
				if ( get_query_var( 'paged' ) === 0 ) {
					$args['action'] = '';
				} else {
					$args['action'] = str_replace( parse_url( get_pagenum_link(), PHP_URL_QUERY ), '', get_pagenum_link() );
				}
				break;
		}
	}
}
