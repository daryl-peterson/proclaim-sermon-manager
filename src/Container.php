<?php
/**
 * Service container.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Exceptions\NotfoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;

/**
 * Service container.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Container implements ContainerInterface {

	/**
	 * Services array
	 *
	 * @var array
	 */
	private array $services = array();

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
	 * @throws ContainerExceptionInterface Error while retrieving the entry.
	 *
	 * @return mixed Entry.
	 */
	public function get( $id ) {
		$item = $this->resolve( $id );
		if ( ! ( $item instanceof ReflectionClass ) ) {
			return $item;
		}
		return $this->get_instance( $item );
	}


	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * Returns false otherwise.
	 *
	 * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
	 * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @return bool
	 */
	public function has( $id ): bool {
		try {
			$item = $this->resolve( $id );
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			return false;
			// @codeCoverageIgnoreEnd
		}
		if ( $item instanceof ReflectionClass ) {
			return $item->isInstantiable();
		}
		return isset( $item );
	}

	/**
	 * Set container item.
	 *
	 * @param string $key   Container key name.
	 * @param mixed  $value  Value to user.
	 * @return Container
	 *
	 * @since 1.0.0
	 */
	public function set( string $key, mixed $value ): Container {
		$this->services[ $key ] = $value;
		return $this;
	}

	/**
	 * Get item from container.
	 *
	 * @param string $id Item name to resovle.
	 * @return mixed null|object|ReflectionClass
	 * @throws NotFoundException If not found.
	 *
	 * @since 1.0.0
	 */
	private function resolve( string $id ): mixed {
		$error = false;
		try {
			$name = $id;
			if ( isset( $this->services[ $id ] ) ) {
				$name = $this->services[ $id ];
				if ( is_callable( $name ) ) {
					return $name();
				}
			}

			$result = new ReflectionClass( $name );
			return $result;
		} catch ( \ReflectionException $th ) {
			$error = true;
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			$error = true;
			// @codeCoverageIgnoreEnd
		}

		throw new NotFoundException(
			esc_html( $th->getMessage() ),
			(int) $th->getCode()
		);
	}

	/**
	 * Get object instance.
	 *
	 * @param ReflectionClass $item Reflectionclass with the name of the object to initiate.
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	private function get_instance( ReflectionClass $item ): mixed {
		$constructor = $item->getConstructor();

		// @codeCoverageIgnoreStart
		if ( $item->hasMethod( 'init' ) ) {
			return call_user_func( $item->name . '::init' );
		}
		// @codeCoverageIgnoreEnd

		if ( $item->hasMethod( 'get_instance' ) ) {
			return call_user_func( $item->name . '::get_instance' );
		}

		if ( is_null( $constructor ) || $constructor->getNumberOfRequiredParameters() === 0 ) {
			return $item->newInstance();
		}
		$params = array();
		foreach ( $constructor->getParameters() as $param ) {
			$type = $param->getType();
			if ( isset( $type ) ) {
				$params[] = $this->get( $type->getName() );
			}
		}
		return $item->newInstanceArgs( $params );
	}
}
