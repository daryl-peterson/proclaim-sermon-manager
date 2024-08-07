<?php
/**
 * Notice interface
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Notice interface.
 */
interface NoticeInt extends Initable {

	/**
	 * Display notice if it exist.
	 *
	 * @since 1.0.0
	 *
	 * @return string|null
	 */
	public function show_notice();

	/**
	 * Set error notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_error( string $title, string $message ): bool;

	/**
	 * Set warning notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_warning( string $title, string $message ): bool;

	/**
	 * Set info notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_info( string $title, string $message ): bool;

	/**
	 * Set success notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_success( string $title, string $message ): bool;

	/**
	 * Delete admin notice.
	 */
	public function delete(): void;
}
