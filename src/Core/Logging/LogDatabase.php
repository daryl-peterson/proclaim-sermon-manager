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

use stdClass;
use wpdb;

defined( 'ABSPATH' ) || exit;

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

	/**
	 * Table name.
	 *
	 * @var string
	 */
	public string $table;

	/**
	 * Prefixed key name. Used in transients.
	 *
	 * @var string
	 */
	public string $key_name;

	/**
	 * Database.
	 *
	 * @var wpdb
	 */
	public wpdb $db;

	/**
	 * Initialize object properties.
	 */
	public function __construct() {
		global $wpdb;
		$this->db       = $wpdb;
		$this->key_name = get_key_name( 'logs' );
		$this->table    = $wpdb->prefix . $this->key_name;
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
