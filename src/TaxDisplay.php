<?php
/**
 * Tax display base class.
 *
 * @package     DRPPSM\TaxDisplay
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Tax display base class.
 *
 * @package     DRPPSM\TaxDisplay
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
abstract class TaxDisplay {

	/**
	 * Taxonomy name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected ?string $taxonomy = null;

	/**
	 * Pagination arguments.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected null|array $paginate = null;

	/**
	 * Used in paginated queries, per_page
	 *
	 * @var int
	 * @since 1.0.0
	 */
	protected int $per_page;

	/**
	 * Query offset.
	 *
	 * @var int
	 * @since 1.0.0
	 */
	protected int $offset;

	/**
	 * Order.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $order;

	/**
	 * Order by.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	protected string $orderby;

	/**
	 * Template data.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected null|array $data = null;

	/**
	 * Query arguments.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected array $args;

	/**
	 * Get record count
	 *
	 * @return int
	 * @since 1.0.0
	 */
	abstract public function get_count(): int;

	/**
	 * Set data needed for template.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	abstract protected function set_data(): void;

	/**
	 * Validate arguments.
	 *
	 * @param array $args
	 * @return bool
	 * @since 1.0.0
	 */
	abstract protected function is_args_valid( array $args ): bool;

	/**
	 * Set pagination data.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function set_pagination(): void {
		global $post;

		$this->paginate = null;

		$term_count = $this->get_count();

		// Calculate pagination
		$max_num_pages = ceil( $term_count / $this->per_page );
		$paged         = get_page_number();

		// Calculate term offset
		$offset = ( ( $paged - 1 ) * $this->per_page );

		// We can now get our terms and paginate it
		$this->offset = $offset;

		$this->paginate = array(
			'current' => $paged,
			'total'   => $max_num_pages,
		);

		if ( isset( $post->ID ) ) {
			$this->paginate['post_id'] = $post->ID;
		}
	}

	/**
	 * Show template.
	 *
	 * @param array $args
	 * @return void
	 * @since 1.0.0
	 */
	protected function show_template( string $template, array $args ) {
		$output = '';

		$output .= TemplateFiles::start();

		if ( isset( $this->data ) && is_array( $this->data ) && count( $this->data ) > 0 ) {

			ob_start();
			$sorting = sermon_sorting();
			echo esc_html( $sorting );

			get_partial( $template, $args );
			get_partial( Template::Pagination, $this->paginate );

			$output .= ob_get_clean();
		} else {
			// @codeCoverageIgnoreStart
			ob_start();
			get_partial( 'no-posts' );
			$output .= ob_get_clean();
			// @codeCoverageIgnoreEnd
		}

		$output .= TemplateFiles::end();

		echo $output;
	}
}
