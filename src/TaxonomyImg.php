<?php
/**
 * Taxonomy Image.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\Tax;
use DRPSermonManager\Interfaces\Registrable;
use DRPSermonManager\Logging\Logger;

/**
 * Taxonomy Image.
 *
 * @package     Proclain Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxonomyImg implements Registrable {

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
	public function __construct() {
		$this->columns = array(
			'cb'                    => '<input type="checkbox" />',
			'drpsermon-tax-id'      => 'ID',
			'drpsermon-image'       => 'Image',
			'name'                  => 'Name',
			'drpsermon-description' => 'Description',
			'slug'                  => 'Slug',
			'count'                 => 'Count',
		);
		$this->tax     = array(
			Tax::PREACHER,
			Tax::SERIES,
			Tax::TOPICS,
		);
	}

	/**
	 * Register callbacks.
	 *
	 * @return void
	 */
	public function register(): void {

		if ( ! is_admin() && ! defined( 'PHPUNIT_TESTING' ) ) {
			return;
		}
		add_action( 'cmb2_admin_init', array( $this, 'cmb' ) );

		foreach ( $this->tax as $taxonomy ) {
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
	 * CMB2 add image field.
	 *
	 * @param string $taxonomy Image taxonomy.
	 * @return void
	 */
	public function add_image_field( string $taxonomy ): void {
		$prefix = $taxonomy . '_';

		/**
		 * Metabox to add fields to categories and tags
		 */
		$cmb_term = new_cmb2_box(
			array(
				'id'           => $prefix . 'edit',
				// Doesn't output for term boxes.
				'title'        => esc_html__( 'Category Metabox', 'drpsermon' ),
				// Tells CMB2 to use term_meta vs post_meta.
				'object_types' => array( 'term' ),
				// Tells CMB2 which taxonomies should have these fields.
				'taxonomies'   => array( $taxonomy ),
			)
		);

		$cmb_term->add_field(
			array(
				'name' => esc_html__( 'Image', 'drpsermon' ),
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
	 * @param int    $term_id
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function set_column_content( mixed $content, string $column_name, int $term_id ): mixed {
		$name = $this->get_taxonomy_name();

		switch ( $column_name ) {
			case 'drpsermon-tax-id':
				$link = get_edit_term_link( $term_id );
				Logger::debug( array( 'LINK' => $link ) );
				$content = "<a href=\"$link\" title=\"Edit\">$term_id</a>";
				break;
			case 'drpsermon-image':
				$img = get_term_meta( $term_id, $name . '_image', true );

				$html    = <<<EOT

					<img src="$img" style="height:75px;width:75px">
				EOT;
				$content = $html;
				break;
			case 'drpsermon-description':
				global $taxonomy;
				$content = term_description( $term_id, $taxonomy );
				$content = wp_trim_words( $content, 10 );
				break;
			case 'count':
				if ( isset( $content ) ) {
					$content = 0;
				}
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
		$columns['drpsermon-tax-id']      = 'id';
		$columns['drpsermon-description'] = 'descriptioin';
		$columns['count']                 = 'count';
		Logger::debug( array( 'SORTABLE' => $columns ) );
		return $columns;
	}

	/**
	 * Get taxonomy name
	 *
	 * @return string
	 *
	 * @since 1.0.0
	 */
	private function get_taxonomy_name(): string {
		$name   = '';
		$screen = get_current_screen();
		$tax    = get_taxonomy( $screen->taxonomy );
		if ( isset( $tax ) ) {
			$name = $tax->name;
		}
		return $name;
	}
}
