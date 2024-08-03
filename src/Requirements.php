<?php
/**
 * Register requirement checks to be run.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since 1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\RequirementsInt;
use DRPSermonManager\Logging\Logger;

/**
 * Register requirement checks to be run.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since 1.0.0
 */
class Requirements implements RequirementsInt {

	/**
	 * Notice interface
	 *
	 * @var NoticeInt
	 */
	private NoticeInt $notice;

	/**
	 * Requirements check
	 *
	 * @var RequirementCheck
	 */
	private RequirementCheck $require;

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->notice  = Notice::init();
		$this->require = RequirementCheck::init();
	}

	/**
	 * Initialize object properties.
	 *
	 * @return RequirementCheckInt
	 *
	 * @since 1.0.0
	 */
	public static function init(): Requirements {
		return new self();
	}

	/**
	 * Register callbacks.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public function register(): void {
		try {
			$hook = Helper::get_key_name( 'REQUIREMENTS_INIT' );

			if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
				// @codeCoverageIgnoreStart
				return;
				// @codeCoverageIgnoreEnd
			}
			add_action( 'admin_init', array( $this, 'is_compatible' ) );
			Logger::debug( 'REQUIREMENTS HOOKS INITIALIZED' );
			do_action( $hook );

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Get notice interface.
	 *
	 * @return NoticeInt Notice interface.
	 *
	 * @since 1.0.0
	 */
	public function notice(): NoticeInt {
		return $this->notice;
	}

	/**
	 * Check if plugin is compatible.
	 *
	 * @since 1.0.0
	 */
	public function is_compatible(): void {
		$transient = Helper::get_key_name( 'compatible' );
		try {
			Logger::debug( 'CHECKING REQUIREMENTS' );
			$this->require->run();
			Logger::debug( 'REQUIREMENTS MET' );
			set_transient( $transient, true, 500 );

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			delete_transient( $transient );
			Deactivator::init()->run();

			return;
			// @codeCoverageIgnoreEnd
		}
	}
}
