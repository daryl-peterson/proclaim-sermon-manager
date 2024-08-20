<?php
/**
 * Manage options (wp_options) settings.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @uses Helper::getKeyName
 *
 * @see https://developer.wordpress.org/reference/functions/get_option/
 * @see https://developer.wordpress.org/reference/functions/add_option/
 * @see https://developer.wordpress.org/reference/functions/update_option/
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Traits\SingletonTrait;

/**
 * Manage options (wp_options) settings.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @uses Helper::getKeyName
 *
 * @see https://developer.wordpress.org/reference/functions/get_option/
 * @see https://developer.wordpress.org/reference/functions/add_option/
 * @see https://developer.wordpress.org/reference/functions/update_option/
 */
class Options implements OptionsInt {

	use SingletonTrait;

	/**
	 * Get initialize object.
	 *
	 * @return OptionsInt
	 */
	public static function init(): OptionsInt {
		return self::get_instance();
	}

	/**
	 * Get option.
	 *
	 * @param string $name Option name.
	 * @param mixed  $default_value Default value to return if not found.
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function get( string $name, mixed $default_value = null ): mixed {
		$option_name = Helper::get_key_name( $name );

		return \get_option( $option_name, $default_value );
	}

	/**
	 * Set option.
	 *
	 * @param string $name Option name.
	 * @param mixed  $value Option value.
	 * @return boolean True if option was set.
	 *
	 * @since 1.0.0
	 */
	public function set( string $name, $value = null ): bool {
		try {
			$option_value = $this->get( $name );

			if ( $option_value === $value ) {
				return true;
			}

			$option_name = Helper::get_key_name( $name );

			if ( ! $option_value ) {
				$result = \add_option( $option_name, $value );
			} else {
				$result = \update_option( $option_name, $value );
			}

			return $result;
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);

			return false;
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Delete option.
	 *
	 * @param string $name Option name.
	 * @return bool True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function delete( string $name ): bool {
		$option_name = Helper::get_key_name( $name );

		return \delete_option( $option_name );
	}
}
