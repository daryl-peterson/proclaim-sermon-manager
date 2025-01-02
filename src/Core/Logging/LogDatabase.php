<?php
/**
 * Write log record to database.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Logging;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Caps;
use DRPPSM\Constants\PT;
use wpdb;

use function DRPPSM\get_key_name;
use function DRPPSM\table_exist;

/**
 * Write log record to database.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class LogDatabase extends LogWritterAbs implements LogWritterInt {

	const SLUG = 'admin-drppsm-debug';

	/**
	 * Table name.
	 *
	 * @var string
	 */
	public string $table;

	/**
	 * Database.
	 *
	 * @var wpdb
	 */
	public wpdb $db;

	/**
	 * Prefixed key name. Used in transients.
	 *
	 * @var string
	 */
	public string $key_name;

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
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		global $wpdb;
		$this->db       = $wpdb;
		$this->key_name = get_key_name( 'logs' );
		$this->table    = $wpdb->prefix . $this->key_name;
		$this->limit    = 10;
	}

	/**
	 * Register hooks.
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
	public function add_menu(): void {

		add_submenu_page(
			'edit.php?post_type=' . PT::SERMON,
			__( 'Proclaim Debug', 'drppsm' ),
			__( 'Debug', 'drppsm' ),
			Caps::MANAGE_CATAGORIES,
			self::SLUG,
			array( $this, 'show' ),
		);
	}

	/**
	 * Truncate data.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function truncate(): void {
		$this->db->get_results( "DELETE FROM $this->table" );
		$this->db->query( 'ALTER TABLE ' . $this->table . ' AUTO_INCREMENT=1' );
	}

	/**
	 * Write log record.
	 *
	 * @param LogRecord $record Log record object.
	 * @return bool
	 * @since 1.0.0
	 */
	public function write( LogRecord $record ): bool {

		$result = false;
		try {
			$blog_id = get_current_blog_id();

			if ( ! $this->ready() ) {
				return false;
			}

			// phpcs:disable
			$this->db->insert(
				$this->table,
				array(
					'blog_id'  => $blog_id,
					'dt'       => wp_date( 'Y-m-d H:i:s.u', microtime( true ) ),
					'level'    => $record->level,
					'class'    => $record->class,
					'function' => $record->function,
					'line'     => $record->line,
					'file'     => $record->file,
					'context'  => $record->context,
				)
			);
			// phpcs:enable
			$result = true;
		} catch ( \Throwable $th ) {
			// phpcs:disable
			error_log(
				print_r(
					array(
						'MESSAGE' => $th->getMessage(),
						'TRACE'   => $th->getTrace(),
					),
					true
				)
			);
			// phpcs:enable
		}

		return $result;
	}

	/**
	 * Display debug log.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function show(): void {

		$this->purge();
		$this->set_totals();
		$data = $this->get_data();

		$url   = $this->get_url();
		$purge = esc_url( add_query_arg( 'purge', true, $url ) );
		$html  = <<<EOT
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

		echo $html; // phpcs:ignore
	}

	/**
	 * Purge records.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function purge(): void {

		$purge = filter_input( INPUT_GET, 'purge', FILTER_SANITIZE_NUMBER_INT );

		if ( isset( $purge ) && 0 !== $purge ) {
			$this->truncate();
		}
	}

	/**
	 * Set total for items/pages.
	 *
	 * @since 1.0.0
	 */
	private function set_totals() {
		$page       = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
		$items      = $this->db->get_var( "SELECT COUNT(id) FROM $this->table" );
		$this->page = max( 1, $page );

		/**
		 * Get pagination links
		 * - Filters are prefixed with drppsmf_
		 *
		 * @param integer $items Total records.
		 * @param integer $limit Per page.
		 * @param integer $page Page number.
		 * @return string
		 * @since 1.0.0
		 */
		$this->links = apply_filters( DRPPSMF_PAGINATION_GET, $items, $this->limit, $this->page, $this->get_url() );
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

	/**
	 * Get url
	 *
	 * @return string
	 * @since 1.0.0
	 */
	private function get_url(): string {
		$url = admin_url( 'edit.php?post_type=' . PT::SERMON . '&page=' . self::SLUG );

		$removable = array_merge( wp_removable_query_args(), array( 'purge' ) );
		return remove_query_arg( $removable, $url );
	}

	/**
	 * Check if the database is ready for logging.
	 *
	 * @return bool Return true if we are ready for logging, otherwise false.
	 * @since 1.0.0
	 */
	private function ready(): bool {

		$result = get_transient( $this->key_name );
		if ( $result ) {
			return true;
		}

		$table = table_exist( $this->table );
		if ( $table ) {
			set_transient( $this->key_name, true );
			return true;
		}
		return false;
	}
}
