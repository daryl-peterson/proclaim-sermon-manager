<?php
/**
 * Database update Major 1 Minior 0 Patch 0.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\DB;

use DRPPSM\Exceptions\PluginException;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Runable;
use DRPPSM\Logger;
use stdClass;

use function DRPPSM\get_table_name;
use function DRPPSM\table_exist;

/**
 * Database update Major 1 Minior 0 Patch 0.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class M001M000P000 implements Initable, Runable {

	/**
	 * Get initialized object.
	 *
	 * @return M001M000P000
	 */
	public static function init(): M001M000P000 {
		return new self();
	}

	/**
	 * Run functions to create table.
	 *
	 * @return boolean True if table was created, otherwise false.
	 * @since 1.0.0
	 */
	public function run(): bool {

		$result = false;
		try {
			$obj = $this->get_table( 'logs' );
			maybe_create_table( $obj->table, $obj->def );
			$result = table_exist( $obj->table );
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
		}
		return $result;
	}

	/**
	 * Get table definition.
	 *
	 * @param string $table Unprefixed table name.
	 * @return object
	 * @throws PluginException Throws exception if definition not found.
	 * @since 1.0.0
	 */
	private function get_table( string $table ): object {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		switch ( $table ) {
			case 'logs':
				$obj             = new stdClass();
				$table           = get_table_name( 'logs' );
				$charset_collate = $wpdb->get_charset_collate();

				$def        = <<<EOT
				CREATE TABLE $table (
					id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					blog_id bigint(20) unsigned DEFAULT 1,
					dt DATETIME(6),
					level varchar(20) DEFAULT NULL,
					class varchar(100) DEFAULT NULL,
					function varchar(100) DEFAULT NULL,
					line int(11) DEFAULT NULL,
					file varchar(100) DEFAULT NULL,
					context longblob DEFAULT NULL,
					PRIMARY KEY (id),
					KEY SECONDARY (blog_id,dt,level,class,function,line)
				) $charset_collate
				EOT;
				$obj->table = $table;
				$obj->def   = $def;
				break;

			default:
				// code...
				break;
		}

		if ( ! isset( $obj ) ) {
			throw new PluginException( 'Table definition not found' );
		}

		return $obj;
	}
}

$obj = M001M000P000::init();
return $obj->run();
