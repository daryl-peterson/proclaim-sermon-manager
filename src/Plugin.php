<?php
/**
 * Plugin main class.
 *
 * @package     DRPPSM\Plugin
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Plugin main class.
 *
 * @package     DRPPSM\Plugin
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Plugin {

	/**
	 * Option key.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $option_key;

	/**
	 * Option key for activation storing time.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $act_key;

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->option_key = DRPPSM_PLUGIN;
		$this->act_key    = 'activated';
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
			return false;
			// @codeCoverageIgnoreEnd
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

			$options                   = get_option( $this->option_key, array() );
			$options[ $this->act_key ] = time();
			update_option( $this->option_key, $options );
			flush_rewrite_rules();

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th );
			return false;
			// @codeCoverageIgnoreEnd
		}

		return true;
	}

	/**
	 * Deactivation.
	 *
	 * @return bool Always return true.
	 * @since 1.0.0
	 */
	public function deactivate(): bool {

		$options = get_option( $this->option_key, array() );

		// @codeCoverageIgnoreStart
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		// @codeCoverageIgnoreEnd

		if ( key_exists( $this->act_key, $options ) ) {
			unset( $options[ $this->act_key ] );
		}
		update_option( $this->option_key, $options );
		do_action( Action::REWRITE_FLUSH );
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
