<?php
/**
 * Load classes so hooks are registered.
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
use DRPPSM\Interfaces\Runable;

/**
 * Load classes so hooks are registered.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Loader implements Executable, Runable {

	/**
	 * List of classes to load.
	 *
	 * @var array
	 */
	private array $classes;

	/**
	 * Initialize and register hooks.
	 *
	 * @return Loader
	 * @since 1.0.0
	 */
	public static function exec(): Loader {
		$obj = new self();
		$obj->run();
		return $obj;
	}

	/**
	 * Load classes.
	 *
	 * @return boolean|null Return true if classes were loaded, otherwise false.
	 * @since 1.0.0
	 */
	public function run(): bool {
		try {
			if ( did_action( Action::AFTER_INIT ) && ! ( defined( DRPPSM_TESTING ) ) ) {
				// @codeCoverageIgnoreStart
				return false;
				// @codeCoverageIgnoreEnd

			}
			$this->classes = app()->container()->keys();
			foreach ( $this->classes as $class ) {
				if ( Plugin::class === $class ) {
					continue;
				}
				app()->get( $class );
			}
			do_action( Action::AFTER_INIT );

		} catch ( \Throwable $th ) {
			FatalError::set( $th );
		}

		return true;
	}
}
