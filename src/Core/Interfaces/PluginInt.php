<?php
/**
 * Plugin interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Interfaces;

/**
 * Plugin interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
<<<<<<< HEAD
interface PluginInt {

	/**
	 * Initialize hooks.
	 *
	 * @return bool True if hooks were registered.
	 * @since 1.0.0
	 */
	public function register(): bool;

=======
interface PluginInt extends Executable, Registrable {
>>>>>>> 822b76c (Refactoring)

	/**
	 * Activation.
	 *
	 * @return bool Return true if activated with no errors. If errors false.
	 * @since 1.0.0
	 */
	public function activate(): bool;

	/**
	 * Deactivation.
	 *
	 * @return bool Return true if no errors. If errors false.
	 * @since 1.0.0
	 */
	public function deactivate(): bool;

	/**
<<<<<<< HEAD
	 * Display notice if it exist.
	 *
	 * @return string|null Notice strig if exist.
	 * @since 1.0.0
	 */
	public function show_notice(): ?string;

	/**
=======
>>>>>>> 822b76c (Refactoring)
	 * Shut down cleanup.
	 *
	 * @return bool Return true if successfull.
	 * @since 1.0.0
	 */
	public function shutdown(): bool;
}
