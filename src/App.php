<?php
/**
 * App service locator.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPPSM;

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
 *
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
	 * Initialize object.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		// @codeCoverageIgnoreStart
		$this->container = new Container();

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
	 *
	 * @since 1.0.0
	 */
	public static function init(): App {
		return self::get_instance();
	}

	/**
	 * Set container item.
	 *
	 * @param string $key Container key.
	 * @param mixed  $value Container value.
	 * @return App
	 *
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
		return PermaLinks::get_instance()->get();
	}

	/**
	 * Get plugin interface.
	 *
	 * @return PluginInt
	 */
	public function plugin(): PluginInt {
		return $this->get( PluginInt::class );
	}

	/**
	 * Get admin page.
	 */
	public static function getAdminPage(): AdminPage {
		return new AdminPage();
	}
}
