<?php
/**
 * Hook utilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use Closure;
use DRPPSM\Interfaces\Initable;

/**
 * Hook utilities.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class HooksUtils implements Initable {

	/**
	 * Get initialize object.
	 *
	 * @return HooksUtils
	 * @since 1.0.0
	 */
	public static function init(): HooksUtils {
		return new self();
	}

	/**
	 * Remove Class Action Without Access to Class Object
	 *
	 * @see remove_object_filter()
	 */
	public function remove_object_action( string $hook_name, $static_callback, $priority = null ): bool {
		return $this->remove_object_filter( $hook_name, $static_callback, $priority );
	}

	/**
	 * Remove filters without access to class object (instance).
	 *
	 * To use remove_filter() function you need to have access to class instance,
	 * or the filter should be added using static method as hook callback.
	 * This function allows you to remove filters with callbacks you don't have access to.
	 *
	 * @param string       $hook_name          Filter name to remove.
	 * @param string|array $static_callback    Hook callback where instance represented as class name.
	 *                                         Eg: [ '\Space\My_Class', 'my_method' ] OR '\Space\My_Class' to remove all methods added to hook.
	 * @param int|null     $priority           (optional) Priority of the filter. If not set all hooks with any priority will be removed.
	 *
	 * @return bool Whether the hook is removed.
	 *
	 * @requires WP 4.7+
	 * @author   Kama (wp-kama.com)
	 * @version  2.0
	 */
	public function remove_object_filter( string $hook_name, $static_callback, $priority = null ): bool {
		if ( is_string( $static_callback ) ) {
			// '\Space\My_Class::my_method' or '\Space\My_Class'

			// @codeCoverageIgnoreStart
			$static_callback = explode( '::', $static_callback ) + array( '', '' );
			// @codeCoverageIgnoreEnd
		}

		$found = $this->find_hook_callback_instances( $hook_name, $static_callback, $priority );

		$res = 0;
		foreach ( $found as $item ) {
			$callback = array( $item['instance'], $item['method'] );
			$res     += (int) remove_filter( $hook_name, $callback, $item['priority'] );
		}

		return (bool) $res;
	}

	/**
	 * Removes the hook when it has been added by a closure.
	 * The accuracy of the function is not guaranteed - the first hook
	 * that matches the priority and the number of hook arguments will be removed.
	 *
	 * @param string $name
	 * @param int    $priority
	 * @param int    $accepted_args
	 */
	public function remove_closure_hook( $name, $priority = 10, $accepted_args = 1 ): bool {
		global $wp_filter;

		if ( empty( $wp_filter[ $name ]->callbacks[ $priority ] ) ) {
			return false;
		}

		$callbacks = & $wp_filter[ $name ]->callbacks[ $priority ];

		// Find our hook.
		// It is not always possible to identify it unambiguously, but
		// at least we know that it was created with a closure
		// and we know it's priority and number of parameters.
		foreach ( $callbacks as $key => $hook ) {

			if ( ! ( $hook['function'] instanceof Closure ) ) {
				continue;
			}

			if ( $hook['accepted_args'] !== $accepted_args ) {
				continue;
			}

			// remove
			unset( $callbacks[ $key ] );

			// first suitable only
			return true;
		}

		return false;
	}


	/**
	 * Finds the instance of the object whose specified method is added for the specified hook.
	 *
	 * To use remove_filter() function you need to have access to class instance,
	 * or the filter should be added using static method as hook callback.
	 * This function allows you to find class instance that was used when the hook was added.
	 *
	 * @param string       $hook_name          Filter name.
	 * @param string|array $static_callback    Hook callback where instance represented as class name.
	 *                                         Eg: [ '\Space\My_Class', 'my_method' ]
	 *                                         or [ '\Space\My_Class' ] to get all methods added for the hook.
	 * @param int|null     $priority           (optional) Priority of the filter.
	 *
	 * @return array{ instance: object|string, method:string, priority:int }[]
	 *
	 * @author  Kama (wp-kama.com)
	 * @version 1.1
	 */
	function find_hook_callback_instances( string $hook_name, array $static_callback, $priority = null ): array {
		global $wp_filter;

		/** @var \WP_Hook $wp_hook WP hooks. */
		$wp_hook = $wp_filter[ $hook_name ] ?? null;

		// @codeCoverageIgnoreStart
		if ( empty( $wp_hook->callbacks ) ) {
			return array();
		}
		// @codeCoverageIgnoreEnd

		$find_class_name  = ltrim( $static_callback[0], '\\' ); // > \Space\My_Class >>> Space\My_Class
		$find_method_name = $static_callback[1] ?? '';

		$found = array();
		foreach ( $wp_hook->callbacks as $the_priority => $hooks_data ) {
			foreach ( $hooks_data as $hook_data ) {

				$real_callback = $hook_data['function'] ?? null;
				if ( ! isset( $real_callback ) || ! is_array( $real_callback ) ) {
					continue;
				}

				[ $object, $the_method_name ] = $real_callback;
				$class_name                   = is_object( $object ) ? get_class( $object ) : $object;

				if (
				$class_name !== $find_class_name
				|| ( $find_method_name && $the_method_name !== $find_method_name )
				|| ( null !== $priority && $the_priority !== $priority )
				) {
					continue;
				}

				$found[] = array(
					'instance' => $object,
					'method'   => $the_method_name,
					'priority' => $the_priority,
				);
			}
		}

		return $found;
	}
}
