<?php
/**
 * Pagination for pages.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Pagination for pages.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Pagination implements Executable, Registrable {

	/**
	 * Initialize and register settings.
	 *
	 * @return Pagination
	 * @since 1.0.0
	 */
	public static function exec(): Pagination {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Returns true if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_filter( DRPPSM_FLTR_PAGINATION_GET, array( $this, 'pagination' ) ) ) {
			return false;
		}
		add_filter( DRPPSM_FLTR_PAGINATION_GET, array( $this, 'pagination' ), 10, 4 );
		return true;
	}

	/**
	 * Get pagination links
	 *
	 * @param integer $items Total records.
	 * @param integer $limit Per page.
	 * @param integer $page Page number.
	 * @return string
	 * @since 1.0.0
	 */
	public function pagination( int $items, int $limit, int $page, string $url ): string {

		$page_cnt = ( ( $items % $limit ) === 0 ) ? $items / $limit : floor( $items / $limit ) + 1;
		$output   = '<span class="displaying-num">' . sprintf(
			/* translators: %s: Number of items. */
			_n( '%s item', '%s items', $items ),
			number_format_i18n( $items )
		) . '</span>';

		$page_links      = array();
		$page_cnt_before = '<span class="paging-input">';
		$page_cnt_after  = '</span></span>';
		$disable_first   = false;
		$disable_last    = false;
		$disable_prev    = false;
		$disable_next    = false;

		if ( 1 === $page ) {
			$disable_first = true;
			$disable_prev  = true;
		}
		if ( $page_cnt === $page ) {
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
				esc_url( remove_query_arg( 'paged', $url ) ),
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
				esc_url( add_query_arg( 'paged', max( 1, $page - 1 ), $url ) ),
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
			$page,
			strlen( $page_cnt )
		);

		$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $page_cnt ) );

		$page_links[] = $page_cnt_before . sprintf(
			/* translators: 1: Current page, 2: Total pages. */
			_x( '%1$s of %2$s', 'paging' ),
			$html_current_page,
			$html_total_pages
		) . $page_cnt_after;

		if ( $disable_next ) {
			$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		} else {
			$page_links[] = sprintf(
				"<a class='next-page button' href='%s'>" .
					"<span class='screen-reader-text'>%s</span>" .
					"<span aria-hidden='true'>%s</span>" .
				'</a>',
				esc_url( add_query_arg( 'paged', min( $page_cnt, $page + 1 ), $url ) ),
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
				esc_url( add_query_arg( 'paged', $page_cnt, $url ) ),
				/* translators: Hidden accessibility text. */
				__( 'Last page' ),
				'&raquo;'
			);
		}

		$pagination_links_class = 'pagination-links';
		$output                .= "\n<span class='$pagination_links_class'>" . implode( "\n", $page_links ) . '</span>';

		if ( $page_cnt ) {
			$page_class = $page_cnt < 2 ? ' one-page' : '';
		} else {
			$page_class = ' no-pages';
		}
		$pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

		return $pagination;
	}
}
