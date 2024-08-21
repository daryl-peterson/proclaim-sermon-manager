<?php
/**
 * Database updates.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\DB;

use DRPPSM\Interfaces\DbInt;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Logger;

use const DRPPSM\PLUGIN_VER;

/**
 * Get options interface.
 *
 * @package     Proclaim Sermon Manager
 */
use function DRPPSM\options;

/**
 * Database updates.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Db implements DbInt {

	/**
	 * Options interface.
	 *
	 * @var OptionsInt
	 */
	private OptionsInt $options;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->options = options();
	}

	/**
	 * Get database interface.
	 *
	 * @return DbInt
	 * @since 1.0.0
	 */
	public static function exec(): DbInt {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register callbacks.
	 *
	 * @return boolean|null
	 */
	public function register(): ?bool {
		if ( has_action( 'plugins_loaded', array( $this, 'version_check' ) ) ) {
			return false;
		}
		add_action( 'plugins_loaded', array( $this, 'version_check' ) );
		return true;
	}

	/**
	 * Run update in file.
	 *
	 * @return boolean
	 * @since 1.0.0
	 */
	public function run(): bool {

		try {
			$result = false;
			$file   = $this->version_to_file();
			if ( file_exists( $file ) ) {
				$result = include $file;
			}
		} catch ( \Throwable $th ) {
			Logger::error( $th->getMessage() );
			$result = false;
		}

		if ( $result ) {
			$this->options->set( 'plugin_ver', PLUGIN_VER );
		}

		return $result;
	}

	/**
	 * Check if database is the same version as plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function version_check(): void {
		if ( PLUGIN_VER !== $this->options->get( 'plugin_ver' ) ) {
			$this->run();
		}
	}

	/**
	 * Get file name from plugin version.
	 *
	 * @return string|null Return file name if it exist.
	 * @since 1.0.0
	 */
	private function version_to_file(): ?string {
		$parts = explode( '.', PLUGIN_VER );
		if ( 3 !== count( $parts ) ) {
			return null;
		}

		foreach ( $parts as $key => $item ) {
			$parts[ $key ] = substr( '000' . $parts[ $key ], -3 );
		}

		$ds   = DIRECTORY_SEPARATOR;
		$cn   = 'M' . $parts[0] . 'M' . $parts[1] . 'P' . $parts[2];
		$file = __DIR__ . $ds . $cn . '.php';
		if ( ! file_exists( $file ) ) {
			$file = null;
		}

		return $file;
	}
}
