<?php
/**
 * Overload trait.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Traits;

/**
 * Overload trait.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
trait OverLoadTrait {

	/**
	 * Used to store list of protected variables.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected array $protect = array();

	/**
	 * Array used to store dynamic properties.
	 *
	 * @var array
	 */
	protected array $data;

	/**
	 * Makes private properties settable for backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name  The private member to set.
	 * @param mixed  $value The value to set.
	 */
	public function __set( $name, $value ) {

		if ( in_array( $name, $this->protect, true ) ) {
			return;
		}

		$this->$name = $value;
	}

	/**
	 * Makes private properties check-able for backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The private member to check.
	 * @return bool If the member is set or not.
	 */
	public function __isset( $name ) {
		return isset( $this->$name );
	}

	/**
	 * Makes private properties un-settable for backward compatibility.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name  The private member to unset.
	 */
	public function __unset( $name ) {
		unset( $this->$name );
	}
}
