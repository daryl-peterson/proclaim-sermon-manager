<?php
/**
 * App service locator.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Traits\SingletonTrait;

/**
 * App service locator.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class App implements Initable {

	use SingletonTrait;

	/**
	 * Service container
	 *
	 * @var Container
	 */
	private Container $container;

	/**
	 * Settings array.
	 *
	 * @var array
	 */
	private array $settings;

	/**
	 * Initialize object.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		// @codeCoverageIgnoreStart
		$this->container = new Container();
		$this->settings  = array();

		$config = Helper::get_config( 'app-config.php' );

		foreach ( $config as $key => $value ) {
			$this->container->set( $key, $value );
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Get initialize object.
	 *
	 * @return App
	 * @since 1.0.0
	 */
	public static function init(): App {
<<<<<<< HEAD
		return self::get_instance();
=======
		$obj = self::get_instance();
		return $obj;
>>>>>>> 822b76c (Refactoring)
	}

	/**
	 * Set container item.
	 *
	 * @param string $key Container key.
	 * @param mixed  $value Container value.
	 * @return App
	 * @since 1.0.0
	 */
	public function set( string $key, mixed $value ): App {
		$this->container->set( $key, $value );
		return $this;
	}

	/**
	 * Get item from container.
	 *
	 * @param string $id Item name.
	 * @return object|null
	 *
	 * @since 1.0.0
	 */
	public function get( string $id ): ?object {
		return $this->container->get( $id );
	}

	/**
	 * Check if item exist in container.
	 *
	 * @param string $id Item name.
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function has( string $id ) {
		return $this->container->has( $id );
	}

	/**
	 * Get parmalinks array.
	 *
	 * @since 1.0.0
	 */
	public function permalinks(): array {
<<<<<<< HEAD
		return PermaLinks::get_instance()->get();
=======
		return PermaLinks::exec()->get();
>>>>>>> 822b76c (Refactoring)
	}

	/**
	 * Get plugin interface.
	 *
	 * @return PluginInt
	 * @since 1.0.0
	 */
	public function plugin(): PluginInt {
		return $this->get( PluginInt::class );
	}

	/**
<<<<<<< HEAD
=======
	 * Get container interface.
	 *
	 * @return Container
	 * @since 1.0.0
	 */
	public function container(): Container {
		return $this->container;
	}

	/**
>>>>>>> 822b76c (Refactoring)
	 * Set settings.
	 *
	 * @param array $settings Key value pairs.
	 * @return void
	 * @since 1.0.0
	 */
	public function set_setting( array $settings ): void {
		foreach ( $settings as $key => $value ) {
			$this->settings[ $key ] = $value;
		}
	}

	/**
	 * Get setting.
	 *
	 * @param string $key Setting name.
	 * @param mixed  $default_value Default to return if it doesn't exist.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_setting( string $key, mixed $default_value = null ): mixed {
		if ( ! isset( $this->settings[ $key ] ) ) {
			return $default_value;
		}
		return $this->settings[ $key ];
	}
}
