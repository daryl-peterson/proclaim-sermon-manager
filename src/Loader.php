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

use DRPPSM\Constants\Actions;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Interfaces\Registrable;
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
		if ( did_action( Actions::AFTER_INIT ) ) {
			return false;

		}
		$this->classes = app()->container()->keys();
		foreach ( $this->classes as $class ) {
			if ( PluginInt::class === $class ) {
				continue;
			}
			Logger::debug( "LOADING $class" );
			app()->get( $class );
		}
		do_action( Actions::AFTER_INIT );

		return true;
	}
}
