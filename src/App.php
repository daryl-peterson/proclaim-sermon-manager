<?php
/**
 * App service locator.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\Initable;
use DRPSermonManager\Interfaces\PluginInt;
use DRPSermonManager\Traits\SingletonTrait;

/**
 * App service locator.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class App implements Initable {

	use SingletonTrait;

	private Container $container;


	protected function __construct() {
		$this->container = new Container();

		$config = Helper::get_config( 'app-config.php' );

		foreach ( $config as $key => $value ) {
			$this->container->set( $key, $value );
		}
	}

	public static function init(): App {
		return self::get_instance();
	}

	public function set( string $key, mixed $value ) {
		$this->container->set( $key, $value );
		return $this;
	}

	public function get( string $id ): ?object {
		return $this->container->get( $id );
	}

	public function has( string $id ) {
		return $this->container->has( $id );
	}

	public function permalinks(): array {
		return PermaLinks::get_instance()->get();
	}

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
