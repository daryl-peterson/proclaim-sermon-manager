<?php
/**
 * Debug page.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Caps;
use DRPPSM\Constants\PT;
use DRPPSM\DB\Tables;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use wpdb;

/**
 * Debug page.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class AdminDebug implements Executable, Registrable {

	/**
	 * Total records.
	 *
	 * @var integer
	 */
	private int $total_items;

	/**
	 * Total pages.
	 *
	 * @var integer
	 */
	private int $total_pages;

	/**
	 * Records per page.
	 *
	 * @var integer
	 */
	private int $per_page;

	/**
	 * Used for sql.
	 *
	 * @var integer
	 */
	private int $limit;

	/**
	 * Current page
	 *
	 * @var integer
	 */
	private int $page;

	/**
	 * Pagination links.
	 *
	 * @var mixed
	 */
	private mixed $links;

	/**
	 * Table name.
	 *
	 * @var string
	 */
	private string $table;

	/**
	 * Database.
	 *
	 * @var wpdb
	 */
	private wpdb $db;


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		global $wpdb;
		$this->db          = $wpdb;
		$this->per_page    = 20;
		$this->limit       = $this->per_page;
		$this->page        = 1;
		$this->total_items = 0;
		$this->total_pages = 1;
		$this->table       = get_table_name( 'logs' );
	}

	/**
	 * Initialize and register callbacks.
	 *
	 * @return AdminDebug
	 * @since 1.0.0
	 */
	public static function exec(): AdminDebug {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register callbacks.
	 *
	 * @return boolean|null True if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( 'admin_menu', array( $this, 'add_menu' ) ) ) {
			return false;
		}

		add_action( 'admin_menu', array( $this, 'add_menu' ) );

		return true;
	}

	/**
	 * Add sub menu for debug.
	 *
	 * @return void
	 */
	public function add_menu() {

		add_submenu_page(
			'edit.php?post_type=' . PT::SERMON,
			__( 'Proclaim Debug', 'drppsm' ),
			__( 'Debug', 'drppsm' ),
			Caps::MANAGE_CATAGORIES,
			'admin-drppsm-debug',
			array( $this, 'show_debug' ),
		);
	}

	/**
	 * Display debug log.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function show_debug() {
		$this->purge();
		$this->set_totals();
		$data = $this->get_data();

		$removable_query_args = wp_removable_query_args();
		$url                  = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$url                  = remove_query_arg( $removable_query_args, $url );
		$purge                = esc_url( add_query_arg( 'purge', true, $url ) );
		$html                 = <<<EOT

		<div class="wrap">
			<div id="drppsm">

				<h3>
					Debugging
				</h3>
				<div class="tablenav top">
					<div class="alignleft">
						<a class="button" href="$purge">Purge</a>
					</div>

					$this->links
				</div>
				<section class="bg-debug">
					$data
				</section>

				<div class="tablenav bottom">
					$this->links
				</div>
			</div>
		</div>
		EOT;

		echo $html;
	}

	/**
	 * Purge records.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function purge() {

		$purge = filter_input( INPUT_GET, 'purge', FILTER_SANITIZE_NUMBER_INT );
		if ( isset( $purge ) && $purge !== 0 ) {
			$results = $this->db->get_results( "DELETE FROM $this->table" );
			$this->db->query( 'ALTER TABLE ' . $this->table . ' AUTO_INCREMENT=1' );
		}
	}

	/**
	 * Set total for items/pages.
	 *
	 * @since 1.0.0
	 */
	private function set_totals() {
		$page              = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
		$result            = $this->db->get_var( "SELECT COUNT(id) FROM $this->table" );
		$this->total_items = $result;
		$this->total_pages = ( ( $this->total_items % $this->limit ) == 0 ) ? $this->total_items / $this->limit : floor( $this->total_items / $this->limit ) + 1;
		$this->page        = max( 1, $page );

		$this->links = $this->pagination( $this->total_items, $this->total_pages, $this->page );
	}

	/**
	 * Get pagination links
	 *
	 * @param integer $total_items Total records.
	 * @param integer $total_pages Total pages.
	 * @param integer $current Current page.
	 * @return string
	 * @static
	 */
	private function pagination( int $total_items, int $total_pages, int $current ): string {

		$output = '<span class="displaying-num">' . sprintf(
			/* translators: %s: Number of items. */
			_n( '%s item', '%s items', $total_items ),
			number_format_i18n( $total_items )
		) . '</span>';

		$removable_query_args   = wp_removable_query_args();
		$removable_query_args[] = 'purge';
		$current_url            = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$current_url            = remove_query_arg( $removable_query_args, $current_url );

		$page_links         = array();
		$total_pages_before = '<span class="paging-input">';
		$total_pages_after  = '</span></span>';
		$disable_first      = false;
		$disable_last       = false;
		$disable_prev       = false;
		$disable_next       = false;

		if ( 1 === $current ) {
			$disable_first = true;
			$disable_prev  = true;
		}
		if ( $total_pages === $current ) {
			$disable_last = true;
			$disable_next = true;
		}

		if ( $disable_first ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='first-page button' href='%s'>" .
					"<span class='screen-reader-text'>%s</span>" .
					"<span aria-hidden='true'>%s</span>" .
				'</a>',
				esc_url( remove_query_arg( 'paged', $current_url ) ),
				/* translators: Hidden accessibility text. */
				__( 'First page' ),
				'&laquo;'
			);
		}

		if ( $disable_prev ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='prev-page button' href='%s'>" .
					"<span class='screen-reader-text'>%s</span>" .
					"<span aria-hidden='true'>%s</span>" .
				'</a>',
				esc_url( add_query_arg( 'paged', max( 1, $current - 1 ), $current_url ) ),
				/* translators: Hidden accessibility text. */
				__( 'Previous page' ),
				'&lsaquo;'
			);
		}

			$html_current_page = sprintf(
				'<label for="current-page-selector" class="screen-reader-text">%s</label>' .
				"<input class='current-page' id='current-page-selector' type='text'
					name='paged' value='%s' size='%d' aria-describedby='table-paging' />" .
				"<span class='tablenav-paging-text'>",
				/* translators: Hidden accessibility text. */
				__( 'Current Page' ),
				$current,
				strlen( $total_pages )
			);

		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );

		$page_links[] = $total_pages_before . sprintf(
			/* translators: 1: Current page, 2: Total pages. */
			_x( '%1$s of %2$s', 'paging' ),
			$html_current_page,
			$html_total_pages
		) . $total_pages_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='next-page button' href='%s'>" .
					"<span class='screen-reader-text'>%s</span>" .
					"<span aria-hidden='true'>%s</span>" .
				'</a>',
				esc_url( add_query_arg( 'paged', min( $total_pages, $current + 1 ), $current_url ) ),
				/* translators: Hidden accessibility text. */
				__( 'Next page' ),
				'&rsaquo;'
			);
		}

		if ( $disable_last ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='last-page button' href='%s'>" .
					"<span class='screen-reader-text'>%s</span>" .
					"<span aria-hidden='true'>%s</span>" .
				'</a>',
				esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
				/* translators: Hidden accessibility text. */
				__( 'Last page' ),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		if ( ! empty( $infinite_scroll ) ) {
			$pagination_links_class .= ' hide-if-js';
		}
		$output .= "\n<span class='$pagination_links_class'>" . implode( "\n", $page_links ) . '</span>';

		if ( $total_pages ) {
			$page_class = $total_pages < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		$pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

		return $pagination;
	}

	/**
	 * Get debug log.
	 *
	 * @return string HTML markup for debug log.
	 * @since 1.0.0
	 */
	private function get_data(): string {
		$offset  = $this->page - 1;
		$blog_id = get_current_blog_id();
		$sql     = $this->db->prepare(
			"SELECT * FROM $this->table WHERE blog_id=%d ORDER BY dt ASC LIMIT %d OFFSET %d",
			array( $blog_id, $this->limit, $offset )
		);

		$results = $this->db->get_results( $sql );

		$html = '';

		foreach ( $results as $key => $value ) {
			$context = "\n" . trim( $value->context );
			$html   .= <<<EOT
				<article class="row">
					<div class="col-12">
						<span class="label">Date</span>
						$value->dt
					</div>
					<div class="col-12">
						<span class="label">Level</span>
						$value->level
					</div>

					<div class="col-12">
						<span class="label">Class</span>
						$value->class
					</div>


					<div class="col-12">
						<span class="label">Function</span>
						$value->function
					</div>


					<div class="col-12">
						<span class="label">Line</span>
						$value->line
					</div>

					<div class="col-12">
						<span class="label">context</span>
					</div>
					<div class="col-12">
						<div class="code clearfix">
							$context
						</div>
					</div>
				</article>
			EOT;
		}

		return $html;
	}
}
