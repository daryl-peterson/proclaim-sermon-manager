<?php
/**
 * Sermon list table.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\Filters;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\Tax;
use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;

/**
 * Sermon list table.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonListTable implements Initable, Registrable {

	/**
	 * Post type
	 *
	 * @var string
	 */
	private string $pt;

	/**
	 * Columns array.
	 *
	 * @var array
	 */
	private array $columns;

	public function __construct() {
		$this->pt                  = PT::SERMON;
		$this->columns['cb']       = '<input type="checkbox" />';
		$this->columns['title']    = __( 'Sermon Title', 'drpsermon' );
		$this->columns['preacher'] = TaxUtils::get_taxonomy_field( Tax::PREACHER, 'singular_name' );
		$this->columns['series']   = __( 'Sermon Series', 'drpsermon' );
		$this->columns['topics']   = __( 'Topics', 'drpsermon' );
		$this->columns['views']    = __( 'Views', 'drpsermon' );
		$this->columns['comments'] = __( 'Comments', 'drpsermon' );
		$this->columns['preached'] = __( 'Preached', 'drpsermon' );
		$this->columns['date']     = __( 'Published' );
	}

	public static function init(): SermonListTable {

		$result = new self();
		return $result;
	}

	public function register(): void {
		add_filter( "manage_edit-{$this->pt}_sortable_columns", array( $this, 'set_sortable_columns' ) );
		// add_filter( "manage_{$this->pt}_custom_column", array( $this, 'set_column_content' ), 10, 3 );
		add_action( "manage_{$this->pt}_posts_custom_column", array( $this, 'render_sermon_columns' ), 2 );
		add_filter( "manage_edit-{$this->pt}_columns", array( $this, 'set_columns' ), 10, 1 );
		add_filter( 'list_table_primary_column', array( $this, 'list_table_primary_column' ), 10, 2 );
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 100, 2 );
		add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );
		add_filter( 'request', array( $this, 'request_query' ) );
		add_filter( 'parse_query', array( $this, 'sermon_filters_query' ) );

		// do_action( 'after_sm_admin_post_types' );
	}


	public function render_sermon_columns( $column ) {
		global $post;

		try {
			if ( empty( $post->ID ) ) {
				return;
			}

			switch ( $column ) {
				case 'preacher':
					$data = get_the_term_list( $post->ID, Tax::PREACHER, '', ', ', '' );
					break;
				case 'series':
					$data = get_the_term_list( $post->ID, Tax::SERIES, '', ', ', '' );
					break;
				case 'topics':
					$data = get_the_term_list( $post->ID, Tax::TOPICS, '', ', ', '' );
					break;
				case 'views':
					$data = PostTypeUtils::get_view_count( array( 'post_id' => $post->ID ) );
					break;
				case 'preached':
					$unix_preached = DateUtils::get( 'U' );

					if ( time() - $unix_preached < DAY_IN_SECONDS ) {
						// translators: %s: The time. Such as "12 hours".
						$data = sprintf( __( '%s ago' ), human_time_diff( $unix_preached ) );
					} else {
						$data = date( 'Y/m/d', $unix_preached );
					}

					$data = '<abbr title="' . date( 'Y/m/d g:i:s a', $unix_preached ) . '">' . $data . '</abbr>';

					break;
				default:
					$data = '';
					break;
			}

			if ( $data instanceof \WP_Error ) {
				$data = __( 'Error' );
			}
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
		}

		echo $data;
	}

	/**
	 * Set columns for table.
	 *
	 * @param array $columns Table columns.
	 * @return array
	 *
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
	 * Set column content.
	 *
	 * @param mixed  $content Content.
	 * @param string $column_name Column name.
	 * @param int    $term_id
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function set_column_content( mixed $content, string $column_name, int $term_id ): mixed {

		switch ( $column_name ) {
			case 'drpsermon-tax-id':
				break;
			case 'drpsermon-image':
				break;
			default:
				break;
		}

		return $content;
	}


	/**
	 * Set sortable columns
	 *
	 * @param array $columns Columns array.
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function set_sortable_columns( array $columns ): array {

		$custom = array(
			'title'    => 'title',
			'preached' => 'preached',
			'views'    => 'views',
		);

		return wp_parse_args( $custom, $columns );
	}

	/**
	 * Set list table primary column
	 * Support for WordPress 4.3.
	 *
	 * @param string $default   Existing primary column.
	 * @param string $screen_id Current screen ID.
	 *
	 * @return string
	 */
	public function list_table_primary_column( string $default, string $screen_id ): string {
		if ( "edit-{$this->pt}" === $screen_id ) {
			return 'title';
		}

		return $default;
	}

	/**
	 * Set row actions for sermons
	 *
	 * @param array   $actions The existing actions.
	 * @param WP_Post $post    Sermon or other post instance.
	 *
	 * @return array
	 */
	public function row_actions( array $actions, \WP_Post $post ): array {
		if ( ! $this->post_type_match() ) {
			return $actions;
		}
		return array_merge( array( 'id' => 'ID: ' . $post->ID ), $actions );
	}

	/**
	 * Filters for post types.
	 */
	public function restrict_manage_posts() {
		if ( ! $this->post_type_match() ) {
			return;
		}

		$this->sermon_filters();
	}

	/**
	 * Filter the sermons in admin based on options
	 *
	 * @param mixed $query The query.
	 */
	public function sermon_filters_query( $query ) {

		if ( ! $this->post_type_match() ) {
			return;
		}

		if ( isset( $query->query_vars[ Tax::SERVICE_TYPE ] ) ) {
			$query->query_vars['tax_query'] = array(
				array(
					'taxonomy' => Tax::SERVICE_TYPE,
					'field'    => 'slug',
					'terms'    => $query->query_vars[ Tax::SERVICE_TYPE ],
				),
			);
		}
	}

	/**
	 * Filters and sorting handler.
	 *
	 * @param array $vars Current filtering arguments.
	 *
	 * @return array
	 */
	public function request_query( $vars ) {

		if ( ! $this->post_type_match() ) {
			return $vars;
		}

		if ( isset( $vars['orderby'] ) ) {

			// Sorting.
			switch ( $vars['orderby'] ) {
				case 'preached':
					$vars = array_merge(
						$vars,
						array(
							'meta_key'       => 'sermon_date',
							'orderby'        => 'meta_value_num',
							'meta_value_num' => time(),
							'meta_compare'   => '<=',
						)
					);
					break;

				case 'views':
					$vars = array_merge(
						$vars,
						array(
							'meta_key' => 'Views',
							'orderby'  => 'meta_value_num',
						)
					);
					break;
			}
		}

		if ( isset( $vars[ $this->pt ] ) && trim( $vars[ $this->pt ] ) === '' ) {
			unset( $vars[ $this->pt ] );

		}

		return $vars;
	}

	public function sermon_filters() {
		global $wp_query;

		$service_type = Tax::SERVICE_TYPE;

		// Type filtering.
		$terms = get_terms(
			array(
				'taxonomy'   => $service_type,
				'hide_empty' => false,
			)
		);

		$field   = TaxUtils::get_taxonomy_field( $service_type, 'singular_name' );
		$label   = wp_sprintf( __( 'Filter by %s', 'drpsermon' ), $field );
		$options = "<option value=\"\">$label</option>";

		foreach ( $terms as $term ) {
			$value  = trim( $term->slug );
			$option = "<option value=\"$value\" ";

			if ( isset( $wp_query->query[ $service_type ] ) ) {
				$option = selected( $term->slug, $wp_query->query[ $service_type ], false );
			}
			$option  .= '>';
			$option  .= ucfirst( $term->name );
			$option  .= '</option>';
			$options .= $option;
		}

		$output = <<<EOT
			<select name="$service_type" id="dropdown_$service_type">
				$options
			</select>
		EOT;
		echo apply_filters( Filters::SERMON_FILTER, $output );
	}

	private function post_type_match(): bool {
		global $typenow;
		if ( $this->pt === $typenow ) {
			return true;
		}
		return false;
	}
}
