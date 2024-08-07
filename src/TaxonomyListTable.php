<?php
/**
 * Taxonomy list table.
 * - Adds images to specific taxonomy.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logging\Logger;

/**
 * Taxonomy list table.
 * - Adds images to specific taxonomy.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxonomyListTable implements Initable, Registrable {

	/**
	 * Table columns
	 *
	 * @var array
	 */
	private array $columns;

	/**
	 * Taxonomy list
	 *
	 * @var array
	 */
	private array $tax;

	/**
	 * Initialize object.
	 */
	protected function __construct() {
		$this->columns = array(
			'cb'                 => '<input type="checkbox" />',
			'drppsm-image'       => 'Image',
			'name'               => 'Name',
			'drppsm-description' => 'Description',
			'slug'               => 'Slug',
			'drppsm-count'       => 'Count',
		);
		$this->tax     = array(
			Tax::PREACHER,
			Tax::SERIES,
			Tax::TOPICS,
		);
	}

	/**
	 * Get initialized object.
	 *
	 * @return TaxonomyListTable
	 * @since 1.0.0
	 */
	public static function init(): TaxonomyListTable {
		return new self();
	}

	/**
	 * Register callbacks.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register(): void {

		if ( ! is_admin() && ! defined( 'PHPUNIT_TESTING' ) ) {
			return;
		}
		add_action( 'cmb2_admin_init', array( $this, 'cmb' ) );
		add_filter( 'list_table_primary_column', array( $this, 'list_table_primary_column' ), 10, 2 );

		foreach ( $this->tax as $taxonomy ) {
			add_filter( "{$taxonomy}_row_actions", array( $this, 'row_actions' ), 100, 2 );
			add_filter( "manage_edit-{$taxonomy}_sortable_columns", array( $this, 'set_sortable_columns' ) );
			add_filter( "manage_{$taxonomy}_custom_column", array( $this, 'set_column_content' ), 10, 3 );
			add_filter( "manage_edit-{$taxonomy}_columns", array( $this, 'set_columns' ), 10, 1 );
		}
	}

	/**
	 * CMB2 add image fields
	 *
	 * @return void
	 */
	public function cmb(): void {
		foreach ( $this->tax as $taxonomy ) {
			$this->add_image_field( $taxonomy );
		}
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
		return $this->columns;
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
		if ( in_array( $screen_id, $this->tax ) ) {
			return 'name';
		}

		return $default;
	}

	/**
	 * CMB2 add image field.
	 *
	 * @param string $taxonomy Image taxonomy.
	 * @return void
	 * @since 1.0.0
	 */
	public function add_image_field( string $taxonomy ): void {
		$prefix = $taxonomy . '_';

		/**
		 * Metabox to add fields to categories and tags
		 */
		$cmb_term = new_cmb2_box(
			array(
				'id'           => $prefix . 'edit',
				'title'        => esc_html__( 'Category Metabox', 'drppsm' ),
				'object_types' => array( 'term' ),
				'taxonomies'   => array( $taxonomy ),
			)
		);

		$cmb_term->add_field(
			array(
				'name' => esc_html__( 'Image', 'drppsm' ),
				'id'   => $prefix . 'image',
				'type' => 'file',
			)
		);
	}

	/**
	 * Set column content.
	 *
	 * @param mixed  $content Content.
	 * @param string $column_name Column name.
	 * @param int    $term_id Taxonomy term id.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function set_column_content( mixed $content, string $column_name, int $term_id ): mixed {
		try {
			$edit_link = get_edit_term_link( $term_id );
			switch ( $column_name ) {
				case 'drppsm-image':
					$content = $this->get_image( $term_id, $edit_link );
					break;
				case 'drppsm-description':
					$content = term_description( $term_id );
					$content = wp_trim_words( $content, 10 );
					break;
				case 'drppsm-count':
					$content = $this->get_term_count( $term_id );
					break;
				default:
					break;
			}

			return $content;
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
	 * Set sortable columns
	 *
	 * @param array $columns Columns array.
	 * @return array
	 * @since 1.0.0
	 */
	public function set_sortable_columns( array $columns ): array {
		return $columns;
	}


	public function row_actions( array $actions, \WP_Term $tag ): array {
		Logger::debug( array( $actions, $tag ) );

		if ( isset( $actions['view'] ) ) {
			unset( $actions['view'] );
		}

		return $actions;
	}

	/**
	 * Get term count.
	 *
	 * @param integer $term_id Taxonomy term id.
	 * @return int
	 * @since 1.0.0
	 */
	private function get_term_count( int $term_id ): int {
		$tax  = $this->get_tax_name();
		$term = get_term( $term_id, $tax );
		if ( ! $term instanceof \WP_Term ) {
			return 0;
		}
		return (int) $term->count;
	}

	/**
	 * Get image.
	 *
	 * @param int    $term_id   Taxonomy term id.
	 * @param string $edit_link Edit link.
	 * @return string
	 * @since 1.0.0
	 */
	private function get_image( int $term_id, string $edit_link ): string {
		$url = $this->get_image_url( $term_id );
		$img = '';
		if ( $url ) {
			$img = "<img src=\"$url\" alt=\"Image\">";
		}
		$html = <<<EOT
		<a href="$edit_link" title="Edit">
			<div class="drppsm-tax-thumb">
				$img
			</div>
		</a>
		EOT;
		return $html;
	}

	/**
	 * Get image url.
	 *
	 * @param integer $term_id Term ID
	 * @return string|null
	 * @since 1.0.0
	 */
	private function get_image_url( int $term_id ): ?string {

		$temp = wp_get_registered_image_subsizes();
		Logger::debug( $temp );

		$name     = $this->get_tax_name();
		$image_id = get_term_meta( $term_id, $name . '_image_id', true );
		if ( $image_id && ! empty( $image_id ) ) {
			return wp_get_attachment_image_url( $image_id, 'sermon_small' );
		}

		$url = get_term_meta( $term_id, $name . '_image', true );
		if ( $url && ! empty( $url ) ) {
			return $url;
		}

		$ds   = DIRECTORY_SEPARATOR;
		$url  = Helper::get_url();
		$url .= 'assets' . $ds . 'images' . $ds . 'blank-preacher-min.png';

		return $url;
	}

	/**
	 * Get taxonomy name
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function get_tax_name(): ?string {
		global $taxnow;
		return $taxnow;
	}
}
