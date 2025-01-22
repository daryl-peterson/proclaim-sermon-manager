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

use DRPPSM\Constants\Meta;
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

	/**
	 * Initialize object.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
		$this->sc_terms = DRPPSM_SC_TERMS;
	}

	/**
	 * Initialize object and preform hooks registration if needed.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): Executable {
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
		$atts = $this->fix_atts( $atts );

		// Default options.
		$defaults = array(
			'display' => 'series',
			'order'   => 'ASC',
			'orderby' => 'name',
		);

		// Join default and user options.
		$args = shortcode_atts( $defaults, $atts, DRPPSM_SC_TERMS );

		// Fix taxonomy.
		$args['display'] = $this->convert_taxonomy_name( $args['display'], true );

		$query_args = array(
			'taxonomy' => $args['display'],
			'orderby'  => $args['orderby'],
			'order'    => $args['order'],
		);

		// Get items.
		$terms = get_terms( $query_args );
		if ( is_wp_error( $terms ) ) {
			Logger::error(
				array(
					'ERROR' => $terms->get_error_message(),
					$terms->get_error_data(),
				)
			);
			return 'Shortcode Error';
		}

		if ( count( $terms ) > 0 ) {
			// Sort books by order.
			if ( DRPPSM_TAX_BOOK === $args['display'] && 'book' === $args['orderby'] ) {
				// Book order.
				$books = Bible::BOOKS;

				// Assign every book a number.
				foreach ( $terms as $term ) {
					$ordered_terms[ array_search( $term->name, $books, true ) ] = $term;
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
			return $list;
		} else {
			// If nothing has been found.
			return 'No ' . $this->convert_taxonomy_name( $args['display'], true ) . ' found.';
		}
	}
}
