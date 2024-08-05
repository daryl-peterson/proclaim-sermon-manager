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

use DRPSermonManager\Exceptions\NotFoundException;
use DRPSermonManager\Logging\Logger;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

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
		} catch ( NotFoundException $e ) {
			return false;
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
	 * @return void
	 */
	public function set( string $key, mixed $value ) {
		$this->services[ $key ] = $value;
		return $this;
	}

	/**
	 * Get item from container
	 *
	 * @param string $id Item name to resovle
	 * @return mixed null|object|ReflectionClass
	 *
	 * @since 1.0.0
	 */
	private function resolve( string $id ): mixed {
		try {
			$name = $id;
			if ( isset( $this->services[ $id ] ) ) {
				$name = $this->services[ $id ];
				if ( is_callable( $name ) ) {
					return $name();
				}
			}
			return ( new ReflectionClass( $name ) );
		} catch ( ReflectionException $e ) {
			Logger::error( array( 'NOT FOUND ID' => $id ) );
			throw new NotFoundException( $e->getMessage(), $e->getCode(), $e );

		}
	}

	/**
	 * Get object instance.
	 *
	 * @param ReflectionClass $item Reflectionclass with the name of the object to initiate.
	 * @return void
	 */
	private function get_instance( ReflectionClass $item ) {
		$constructor = $item->getConstructor();

		if ( $item->hasMethod( 'init' ) ) {
			return call_user_func( $item->name . '::init' );
		}

		if ( $item->hasMethod( 'get_instance' ) ) {
			return call_user_func( $item->name . '::get_instance' );
		}

		if ( is_null( $constructor ) || $constructor->getNumberOfRequiredParameters() == 0 ) {
			return $item->newInstance();
		}
		$params = array();
		foreach ( $constructor->getParameters() as $param ) {
			if ( $type = $param->getType() ) {
				$params[] = $this->get( $type->getName() );
			}
		}
		return $item->newInstanceArgs( $params );
	}
}
