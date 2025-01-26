<?php
/**
 * Taxonomy list table.
 * - Adds images to specific taxonomy.
 *
 * @package     DRPPSM\TaxListTable
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
use WP_Term;

/**
 * Taxonomy list table.
 * - Adds images to specific taxonomy.
 *
 * @package     DRPPSM\TaxListTable
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxListTable implements Executable, Registrable {
	use ExecutableTrait;

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
	 * @since 1.0.0
	 */
	private array $tax;

	/**
	 * Bible taxonomy.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $tax_bible;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->tax_bible = DRPPSM_TAX_BOOK;

		$this->columns = array(
			'cb'           => '<input type="checkbox" />',
			'drppsm-image' => 'Image',
			'name'         => 'Name',
			'slug'         => 'Slug',
			'posts'        => 'Count',
		);
		$this->tax     = array_values( DRPPSM_TAX_MAP );
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool Return true is default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( ! is_admin() || has_action( 'cmb2_admin_init', array( $this, 'cmb' ) ) ) {
			return false;
		}

		add_action( 'cmb2_admin_init', array( $this, 'cmb' ) );
		add_filter( 'list_table_primary_column', array( $this, 'list_table_primary_column' ), 10, 2 );

		foreach ( $this->tax as $taxonomy ) {
			add_filter( "manage_edit-{$taxonomy}_sortable_columns", array( $this, 'set_sortable_columns' ) );
			add_filter( "manage_{$taxonomy}_custom_column", array( $this, 'set_column_content' ), 10, 3 );
			add_filter( "manage_edit-{$taxonomy}_columns", array( $this, 'set_columns' ), 10, 1 );

		}

		return true;
	}

	/**
	 * CMB2 add image fields
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function cmb(): void {
		foreach ( $this->tax as $taxonomy ) {
			if ( $taxonomy !== $this->tax_bible ) {
				$this->add_image_field( $taxonomy );
			}
		}
	}

	/**
	 * Set columns for table.
	 *
	 * @param array $columns Table columns.
	 * @return array
	 * @since 1.0.0
	 */
	public function set_columns( array $columns ): array {
		unset( $columns );
		if ( $this->get_tax_name() === $this->tax_bible ) {
			unset( $this->columns['drppsm-image'] );
		}
		return $this->columns;
	}

	/**
	 * Set list table primary column
	 * Support for WordPress 4.3.
	 *
	 * @param string $existing   Existing primary column.
	 * @param string $screen_id Current screen ID.
	 * @return string
	 * @since 1.0.0
	 */
	public function list_table_primary_column( string $existing, string $screen_id ): string {
		if ( in_array( $screen_id, $this->tax, true ) ) {
			return 'name';
		}

		return $existing;
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
				'name'         => esc_html__( 'Image', 'drppsm' ),
				'id'           => $prefix . 'image',
				'type'         => 'file',
				'preview_size' => array( 150, 150 ),
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
	public function set_column_content(
		mixed $content,
		string $column_name,
		int $term_id
	): mixed {
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
		return $content;
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

	/**
	 * Remove view from row actions.
	 *
	 * @param array   $actions Existing actions.
	 * @param WP_Term $tag Term.
	 * @return array
	 */
	public function row_actions( array $actions, WP_Term $tag ): array {
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
		if ( ! $term instanceof WP_Term ) {
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
	 * @param integer $term_id Term ID.
	 * @return string|null
	 * @since 1.0.0
	 *
	 * @todo Fix so if image size is not right return null.
	 */
	private function get_image_url( int $term_id ): ?string {

		$name     = $this->get_tax_name();
		$image_id = get_term_meta( $term_id, $name . '_image_id', true );
		if ( $image_id && ! empty( $image_id ) ) {
			return wp_get_attachment_image_url( $image_id, 'thumbnail' );
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
