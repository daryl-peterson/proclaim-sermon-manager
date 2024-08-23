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

use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Constants\Actions;
use DRPPSM\DB\DbUpdates;
use DRPPSM\Interfaces\Executable;

/**
 * Plugin main class.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Plugin implements PluginInt {

	/**
	 * Notice interface.
	 *
	 * @var NoticeInt
	 */
	private NoticeInt $notice;

	/**
	 * String for CMB version.
	 *
	 * @todo Fix this.
	 * @var string
	 */
	private string $cmb2_version;

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->notice       = notice();
		$this->cmb2_version = '?.?.?';
	}

	public static function exec(): PluginInt {
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
			add_action( 'cmb2_init', array( $this, 'cmb2_init' ) );

			Loader::exec();
			return true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
			// @codeCoverageIgnoreEnd
			return false;
		}
	}

	/**
	 * Activation.
	 *
	 * @return bool Return true if activated with no errors. If errors false.
	 * @since 1.0.0
	 */
	public function activate(): bool {
		try {
			options()->set( 'activated', time() );
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			return false;
			// @codeCoverageIgnoreEnd
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
		options()->delete( 'activated' );
		return true;
	}

	/**
	 * Shut down cleanup.
	 *
	 * @return bool Return true if successfull.
	 * @since 1.0.0
	 */
	public function shutdown(): bool {

		$message  = "\n\n";
		$message .= str_repeat( '-', 80 );
		$message .= "\nSHUTTING DOWN\n";
		$message .= "\n\n";
		Logger::debug( $message );
		return true;
	}

	/**
	 * Attempt to CMB2 version.
	 *
	 * @return void
	 */
	public function cmb2_init() {
		$ver = '?????';
		if ( defined( 'CMB2_VERSION' ) ) {
			$ver = CMB2_VERSION;
		}
		$this->cmb2_version = $ver;
	}
}
