<?php
/**
 * Sermon list table.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Meta;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Sermon list table.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonListTable implements Executable, Registrable {

	/**
	 * Post type
	 *
	 * @var string
	 */
	private string $pt = DRPPSM_PT_SERMON;

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
	 * Columns array.
	 *
	 * @var array
	 */
	private array $columns;

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$preacher = Settings::get( Settings::PREACHER );

		$this->pt               = DRPPSM_PT_SERMON;
		$this->tax_preacher     = DRPPSM_TAX_PREACHER;
		$this->tax_series       = DRPPSM_TAX_SERIES;
		$this->tax_service_type = DRPPSM_TAX_SERVICE_TYPE;
		$this->tax_topics       = DRPPSM_TAX_TOPICS;

		$this->columns['cb']                  = '<input type="checkbox" />';
		$this->columns['title']               = __( 'Sermon Title', 'drppsm' );
		$this->columns[ $this->tax_preacher ] = $preacher;
		$this->columns[ $this->tax_series ]   = __( 'Sermon Series', 'drppsm' );
		$this->columns[ $this->tax_topics ]   = __( 'Topics', 'drppsm' );
		$this->columns['drppsm_views']        = __( 'Views', 'drppsm' );
		$this->columns['comments']            = __( 'Comments', 'drppsm' );
		$this->columns['drppsm_preached']     = __( 'Preached', 'drppsm' );
		$this->columns['date']                = __( 'Published' );
	}

	/**
	 * Initialize and register.
	 *
	 * @return SermonListTable
	 * @since 1.0.0
	 */
	public static function exec(): SermonListTable {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool Returns true default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		// @codeCoverageIgnoreStart
		if ( ! is_admin() && ! defined( 'PHPUNIT_TESTING' ) ) {
			return false;
		}
		// @codeCoverageIgnoreEnd

		add_filter( "manage_edit-{$this->pt}_sortable_columns", array( $this, 'set_sortable_columns' ) );
		add_action( "manage_{$this->pt}_posts_custom_column", array( $this, 'render_columns' ), 2 );
		add_filter( "manage_edit-{$this->pt}_columns", array( $this, 'set_columns' ), 10, 1 );
		add_filter( 'list_table_primary_column', array( $this, 'set_table_primary_column' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 100, 2 );
		add_filter( 'parse_query', array( $this, 'sermon_filters_query' ), 10, 1 );

		add_action( 'manage_posts_extra_tablenav', array( $this, 'extra_nav' ), 10, 1 );
		return true;
	}

	/**
	 * Add extra filters.
	 *
	 * @param string $which Top or bottom.
	 * @return void
	 * @since 1.0.0
	 */
	public function extra_nav( string $which ): void {

		if ( ! $this->post_type_match() || 'top' !== $which ) {
			return;
		}

		$url = get_admin_url() . 'edit.php?post_type=' . $this->pt;

		$filters = $this->sermon_filters();
		$html    = <<<EOT
			<input id="drppsm-custom-filters" name="drppsm-custom-filters" type="button" class="button toggle" value="More" data-item="#drppsm-filter">
			<input id="drppsm-filter-reset" name="drppsm-filter-reset" type="button" class="button" value="Reset" data-url="$url">
			<div id="drppsm-filter" class="drppsm-wrap display-none">
				<div id="drppsm-content" class="drppsm-content">
					$filters
				</div>
			</div>
		EOT;
		echo $html; // phpcs:ignore
	}

	/**
	 * Render columns.
	 *
	 * @param string $column Column name.
	 * @return void
	 * @since 1.0.0
	 */
	public function render_columns( $column ): void {
		global $post;

		try {
			if ( empty( $post->ID ) ) {
				return;
			}

			switch ( $column ) {
				case $this->tax_preacher:
					$data = get_the_term_list( $post->ID, $this->tax_preacher, '', ', ', '' );
					break;
				case $this->tax_series:
					$data = get_the_term_list( $post->ID, $this->tax_series, '', ', ', '' );
					break;
				case $this->tax_topics:
					$data = get_the_term_list( $post->ID, $this->tax_topics, '', ', ', '' );
					break;
				case 'drppsm_views':
					$data = PostTypeUtils::get_view_count( array( 'post_id' => $post->ID ) );
					break;
				case 'drppsm_preached':
					$unix_preached = DateUtils::get( 'U' );

					if ( time() - $unix_preached < DAY_IN_SECONDS ) {
						// translators: %s: The time. Such as "12 hours".
						$date = sprintf( __( '%s ago' ), human_time_diff( $unix_preached ) );
					} else {
						$date = gmdate( 'Y/m/d', $unix_preached );
					}

					$data = '<abbr title="' . gmdate( 'Y/m/d g:i:s a', $unix_preached ) . '">' . $date . '</abbr>';

					break;
				default:
					$data = '';
					break;
			}

			if ( $data instanceof \WP_Error ) {
				$data = __( 'Error' );
			}
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => wp_kses( $th->getMessage(), allowed_html() ),
					'TRACE'   => (array) $th->getTrace(),
				)
			);
			$data = __( 'Error' );
		}

		echo $data; // phpcs:ignore
	}

	/**
	 * Set columns for table.
	 *
	 * @param array $columns Table columns.
	 * @return array
	 * @since 1.0.0
	 */
	public function set_columns( array $columns ): array {
		if ( empty( $columns ) && ! is_array( $columns ) ) {
			$columns = array();
		}
		if ( isset( $columns['comments'] ) ) {
			$this->columns['comments'] = $columns['comments'];
		}
		return $this->columns + $columns;
	}

	/**
	 * Set sortable columns
	 *
	 * @param array $columns Columns array.
	 * @return array
	 * @since 1.0.0
	 */
	public function set_sortable_columns( array $columns ): array {

		$custom = array(
			'title'             => 'title',
			$this->tax_series   => $this->tax_series,
			$this->tax_preacher => $this->tax_preacher,
			Meta::DATE          => Meta::DATE,
			'drppsm_views'      => 'views',
		);

		return wp_parse_args( $custom, $columns );
	}

	/**
	 * Set list table primary column
	 * Support for WordPress 4.3.
	 *
	 * @param string $existing  Existing primary column.
	 * @param string $screen_id Current screen ID.
	 * @return string
	 * @since 1.0.0
	 */
	public function set_table_primary_column( string $existing, string $screen_id ): string {
		if ( "edit-{$this->pt}" === $screen_id ) {
			return 'title';
		}

		return $existing;
	}

	/**
	 * Set row actions for sermons
	 *
	 * @param array    $actions  The existing actions.
	 * @param \WP_Post $post    Sermon or other post instance.
	 * @return array
	 * @since 1.0.0
	 */
	public function row_actions( array $actions, \WP_Post $post ): array {
		if ( ! $this->post_type_match() ) {
			return $actions;
		}
		return array_merge( array( 'id' => 'ID: ' . $post->ID ), $actions );
	}

	/**
	 * Filter the sermons on service type.
	 *
	 * @param \WP_Query $query The query.
	 * @return void
	 * @since 1.0.0
	 */
	public function sermon_filters_query( \WP_Query &$query ): void {

		try {

			$qv = &$query->query_vars;

			if ( ! $this->post_type_match() ) {
				return;
			}

			if ( ! isset( $qv[ $this->tax_service_type ] ) || empty( $qv[ $this->tax_service_type ] ) ) {
				return;
			}

			// phpcs:disable
			$qv['tax_query'] = array(
				array(
					'taxonomy' => $this->tax_service_type,
					'field'    => 'slug',
					'terms'    => $query->query_vars[ $this->tax_service_type ],
				),
			);
			// phpcs:enable

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Filters and sorting handler.
	 *
	 * @param array $vars Current filtering arguments.
	 * @return array
	 * @since 1.0.0
	 */
	public function request_query( array $vars ) {

		try {
			if ( ! $this->post_type_match() ) {
				return $vars;
			}

			if ( isset( $vars['orderby'] ) ) {

				// Sorting.
				switch ( $vars['orderby'] ) {
					case 'preached':
						// phpcs:disable
						$vars = array_merge(
							$vars,
							array(
								'meta_key'       => 'sermon_date',
								'orderby'        => 'meta_value_num',
								'meta_value_num' => time(),
								'meta_compare'   => '<=',
							)
						);
						// phpcs:enable
						break;

					case 'views':
						// phpcs:disable
						$vars = array_merge(
							$vars,
							array(
								'meta_key' => 'Views',
								'orderby'  => 'meta_value_num',
							)
						);
						// phpcs:enable
						break;
				}
			}

			if ( isset( $vars[ $this->pt ] ) && trim( $vars[ $this->pt ] ) === '' ) {
				unset( $vars[ $this->pt ] );
			}

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th,
				)
			);
			// @codeCoverageIgnoreEnd
		}
		return $vars;
	}

	/**
	 * Create sermon filters.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function sermon_filters(): string {

		$output[] = $this->select_filter( $this->tax_service_type );
		$output[] = $this->select_filter( $this->tax_preacher );
		$output[] = $this->select_filter( $this->tax_series );

		$output = apply_filters( DRPPSMF_ADMIN_SERMON, $output );
		return implode( "\n", $output );
	}

	/**
	 * Create tax dropdown.
	 *
	 * @param string $tax Taxonomy name.
	 * @return string
	 * @since 1.0.0
	 */
	private function select_filter( string $tax ): string {
		global $wp_query;

		$terms = get_terms(
			array(
				'taxonomy'   => $tax,
				'hide_empty' => false,
			)
		);

		$field = TaxUtils::get_taxonomy_field( $tax, 'singular_name' );

		/* translators: %s: Filter by service type. */
		$label   = wp_sprintf( __( 'Filter by %s', 'drppsm' ), $field );
		$options = "<option value=\"\">$label</option>";

		foreach ( $terms as $term ) {
			$value = trim( $term->slug );

			$selected = '';
			if ( isset( $wp_query->query[ $tax ] ) ) {
				$selected = selected( $term->slug, $wp_query->query[ $tax ], false );
			}

			$option   = wp_sprintf( '<option value="%s"%s>%s</option>', $value, $selected, ucfirst( $term->name ) );
			$options .= $option;
		}

		$output = <<<EOT
			<select name="$tax" id="dropdown_$tax">
				$options
			</select>
		EOT;
		return $output;
	}

	/**
	 * Check if post type is a match.
	 *
	 * @return boolean True if post type matches.
	 * @since 1.0.0
	 */
	private function post_type_match(): bool {
		global $typenow;

		$result = false;
		if ( $this->pt === $typenow ) {
			$result = true;
		}
		return $result;
	}
}
