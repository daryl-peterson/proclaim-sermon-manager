<?php
/**
 * Shortcodes for terms.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Bible;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use WP_Error;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcodes for terms.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SCTerms extends SCBase implements Executable, Registrable {
	/**
	 * Terms short code.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc_terms;


	protected function __construct() {
		parent::__construct();
		$this->sc_terms = DRPPSM_SC_TERMS;
	}

	public static function exec(): Executable {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {

		if ( shortcode_exists( $this->sc_terms ) ) {
			return false;
		}
		add_shortcode( $this->sc_terms, array( $this, 'show_term_list' ) );

		return true;
	}

	/**
	 * Display simple unordered term list.
	 *
	 * @param array $atts Attribute list.
	 * @return string
	 * @since 1.0.0
	 *
	 * #### Atts Parameters
	 * - **display** : Options "series", "preachers", "topics", "books", "serives_types".
	 * - **order** : Options "DESC" for descending; "ASC" for ascending.
	 * - **orderby** : Options "name" (default), "id", "count", "slug", "term_group", "none"
	 *
	 * ```
	 * // An example using all three options.
	 * [list_sermons display="preachers" order="DESC" orderby="id"]
	 * ```
	 */
	public function show_term_list( array $atts ): string {
		$timer     = Timer::get_instance();
		$timer_key = $timer->start( __FUNCTION__, __FILE__ );
		$atts      = $this->fix_atts( $atts );

		// Default options.
		$defaults = array(
			'display' => 'series',
			'order'   => 'ASC',
			'orderby' => 'name',
		);

		// Join default and user options.
		$args = shortcode_atts( $defaults, $atts, DRPPSM_SC_TERMS );

		// Fix taxonomy
		$args['display'] = $this->convert_taxonomy_name( $args['display'], true );

		$query_args = array(
			'taxonomy' => $args['display'],
			'orderby'  => $args['orderby'],
			'order'    => $args['order'],
		);

		if ( 'date' === $query_args['orderby'] ) {
			$query_args['orderby']        = 'meta_value_num';
			$query_args['meta_key']       = 'sermon_date';
			$query_args['meta_compare']   = '<=';
			$query_args['meta_value_num'] = time();
		}

		// Get items.
		$terms = get_terms( $query_args );

		if ( $terms instanceof WP_Error ) {
			Logger::error(
				array(
					'ERROR' => $terms->get_error_message(),
					$terms->get_error_data(),
				)
			);
			$timer->stop( $timer_key );
			return 'Shortcode Error';
		}

		if ( count( $terms ) > 0 ) {
			// Sort books by order.
			if ( DRPPSM_TAX_BIBLE === $args['display'] && 'book' === $args['orderby'] ) {
				// Book order.
				$books = Bible::BOOKS;

				// Assign every book a number.
				foreach ( $terms as $term ) {
					$ordered_terms[ array_search( $term->name, $books ) ] = $term;
				}

				// Order the numbers (books).
				ksort( $ordered_terms );
				$terms = $ordered_terms;
			}

			$list = '<ul id="list-sermons">';
			foreach ( $terms as $term ) {
				$list .= '<li><a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '" title="' . $term->name . '">' . $term->name . '</a></li>';
			}
			$list .= '</ul>';
			$timer->stop( $timer_key );
			return $list;
		} else {
			$timer->stop( $timer_key );
			// If nothing has been found.
			return 'No ' . $this->convert_taxonomy_name( $args['display'], true ) . ' found.';
		}
	}
}
