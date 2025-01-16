<?php
/**
 * Plugin main class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Actions;

/**
 * Plugin main class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Plugin {

	/**
	 * Option key for activation storing time.
	 *
	 * @var string
	 */
	private string $act_key;

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->act_key = 'drppsm_activated';
	}

	/**
	 * Initializale and register hooks.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Initialize plugin hooks.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function register(): bool {
		try {
			FatalError::check();

			if ( has_action( 'shutdown', array( $this, 'shutdown' ) ) ) {
				return true;
			}

			register_activation_hook( FILE, array( $this, 'activate' ) );
			register_deactivation_hook( FILE, array( $this, 'deactivate' ) );
			add_action( 'shutdown', array( $this, 'shutdown' ) );
			Loader::exec();

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
			// @codeCoverageIgnoreEnd
			return false;
		}
		return true;
	}

	/**
	 * Activation.
	 *
	 * @return bool Return true if activated with no errors. If errors false.
	 * @since 1.0.0
	 */
	public function activate(): ?bool {
		try {

			PostTypeSetup::exec()->add();
			update_option( $this->act_key, time() );
			flush_rewrite_rules();

		} catch ( \Throwable $th ) {
			FatalError::set( $th );
			return false;
		}

		return true;
	}

	/**
	 * Deactivation.
	 *
	 * @return bool Return true if no errors. If errors false.
	 * @since 1.0.0
	 */
	public function deactivate(): bool {
		delete_option( $this->act_key );
		do_action( Actions::REWRITE_FLUSH );
		return true;
	}

	/**
	 * Shut down cleanup.
	 *
	 * @return bool Return true if successfull.
	 * @since 1.0.0
	 */
	public function shutdown(): bool {
		return true;
	}
}
