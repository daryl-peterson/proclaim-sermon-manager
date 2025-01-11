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
	 * Service container.
	 *
	 * @var Container
	 */
	private Container $container;

	/**
	 * Data storage array.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $data;

	/**
	 * Initialize object.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		// @codeCoverageIgnoreStart
		$this->container = new Container();
		$this->data      = array();

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
		$obj = self::get_instance();
		return $obj;
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
		return PermaLinks::exec()->get();
	}

	/**
	 * Get plugin interface.
	 *
	 * @return Plugin
	 * @since 1.0.0
	 */
	public function plugin(): Plugin {
		return $this->get( Plugin::class );
	}

	/**
	 * Get container interface.
	 *
	 * @return Container
	 * @since 1.0.0
	 */
	public function container(): Container {
		return $this->container;
	}

	/**
	 * Set app data.
	 *
	 * @param string $item Item name.
	 * @param mixed  $value Item value.
	 * @return void
	 * @since 1.0.0
	 */
	public function set_item( string $item, mixed $value ): void {
		// phpcs:ignore
		$this->data[ $item ] = $value;
	}

	/**
	 * Get set app data.
	 *
	 * @param string $item Item name.
	 * @param mixed  $default_value Default to return if it doesn't exist.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function get_item( string $item, mixed $default_value = null ): mixed {
		Logger::debug(
			array(
				'ITEM'    => $item,
				'DEFAULT' => $default_value,
			)
		);
		if ( ! key_exists( $item, $this->data ) ) {
			return $default_value;
		}

		return $this->data[ $item ];
	}
}
