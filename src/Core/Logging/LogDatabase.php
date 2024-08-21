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

use function DRPPSM\get_date;
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

	private string $table_name;
	private string $key_name;

	public function write( LogRecord $record ): bool {
		$result = false;
		try {
			global $wpdb;

			$blog_id          = get_current_blog_id();
			$this->key_name   = get_key_name( 'logs' );
			$this->table_name = $wpdb->prefix . $this->key_name;

			if ( ! $this->ready() ) {
				return false;
			}

			$wpdb->insert(
				$this->table_name,
				array(
					'blog_id'  => $blog_id,
					'dt'       => get_date( 'Y-m-d H:i:s.u', microtime( true ) ),
					'level'    => $record->level,
					'class'    => $record->class,
					'function' => $record->function,
					'line'     => $record->line,
					'file'     => $record->file,
					'context'  => $record->context,
				)
			);
			$result = true;
		} catch ( \Throwable $th ) {
			error_log(
				print_r(
					array(
						'MESSAGE' => $th->getMessage(),
						'TRACE'   => $th->getTrace(),
					),
					true
				)
			);
		}

		return $result;
	}

	private function ready() {

		$result = get_transient( $this->key_name );
		if ( $result ) {
			return true;
		}

		$table = table_exist( $this->table_name );
		if ( $table ) {
			set_transient( $this->key_name, true );
			return true;
		}
		return false;
	}

	private function microtime() {
		// return wp_date( 'Y-m-d H:i:s.U', time() );
		return date( 'Y-m-d H:i:s.U', microtime( true ) );
	}
}
