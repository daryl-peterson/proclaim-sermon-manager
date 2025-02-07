<?php
/**
 * Service container.
 *
 * @package     DRPPSM\Container
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Exceptions\NotfoundException;
use DRPPSM\Interfaces\ContainerInt;
use ReflectionClass;

/**
 * Service container.
 *
 * @package     DRPPSM\Container
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Container implements ContainerInt {

	/**
	 * Services array
	 *
	 * @var array
	 */
	public array $services = array();

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param string $id Identifier of the entry to look for.
	 * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
	 * @throws ContainerExceptionInterface Error while retrieving the entry.
	 * @return mixed Entry.
	 * @since 1.0.0
	 */
	public function get( $id ) {
		$item = $this->resolve( $id );

		return $this->get_instance( $item );
	}

	/**
	 * Get item from container.
	 *
	 * @param string $id Item name to resovle.
	 * @return mixed null|object|ReflectionClass
	 * @throws NotFoundException If not found.
	 * @since 1.0.0
	 */
	private function resolve( string $id ): mixed {

		try {
			$name = $id;

			if ( isset( $this->services[ $id ] ) ) {
				$name = $this->services[ $id ];
			}

			$result = new ReflectionClass( $name );
			return $result;
		} catch ( \Throwable | \ReflectionException $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
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
	 * @since 1.0.0
	 */
	private function get_instance( ReflectionClass $item ): mixed {

		// @codeCoverageIgnoreStart
		if ( $item->hasMethod( 'exec' ) ) {
			return call_user_func( $item->name . '::exec' );
		}

		if ( $item->hasMethod( 'init' ) ) {
			return call_user_func( $item->name . '::init' );
		}

		if ( $item->hasMethod( 'get_instance' ) ) {
			return call_user_func( $item->name . '::get_instance' );
		}

		$constructor = $item->getConstructor();
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
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Returns true if the container can return an entry for the given identifier.
	 * - The has($id) returning true does not mean that get($id) will not throw an exception.
	 * - It does however mean that get($id) will not throw a NotFoundExceptionInterface.
	 *
	 * @todo Refactor.
	 *
	 * @param string $id Identifier of the entry to look for.
	 * @return bool
	 * @since 1.0.0
	 */
	public function has( $id ): bool {
		try {
			$item = $this->resolve( $id );

		} catch ( \Throwable | NotfoundException $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			return false;
		}
		if ( $item instanceof ReflectionClass ) {
			return $item->isInstantiable();
		}
		// @codeCoverageIgnoreStart
		return isset( $item );
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Set container item.
	 *
	 * @param string $key   Container key name.
	 * @param mixed  $value  Value to user.
	 * @return Container
	 * @since 1.0.0
	 */
	public function set( string $key, mixed $value ): Container {
		$this->services[ $key ] = $value;
		return $this;
	}

	/**
	 * Get container keys.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function keys(): array {
		return array_keys( $this->services );
	}
}
