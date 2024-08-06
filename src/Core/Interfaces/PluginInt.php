<?php
/**
 * Plugin interface.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\Interfaces;

/**
 * Plugin interface.
 *
 * @since       1.0.0
 */
interface PluginInt {

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	public function register(): void;


	/**
	 * Activation.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function activate(): void;

	/**
	 * Deactivation.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function deactivate(): void;

	/**
	 * Display admin notice.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function show_notice(): void;

	/**
	 * Plugin cleanup.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function shutdown(): void;
}
