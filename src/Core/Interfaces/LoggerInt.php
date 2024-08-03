<?php

namespace DRPSermonManager\Interfaces;

/**
 * Interface description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
interface LoggerInt {

	/**
	 * Write debug log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function debug( mixed $context ): bool;

	/**
	 * Write error log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function error( mixed $context ): bool;

	/**
	 * Write info log.
	 *
	 * @param mixed $context Context for logging.
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function info( mixed $context ): bool;
}
