<?php
/**
 * Register requirement checks to be run.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\RequirementCheckInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\Logging\Logger;

/**
 * Register requirement checks to be run.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Requirements implements RequirementsInt {

	/**
	 * Notice interface.
	 *
	 * @var NoticeInt
	 */
	private NoticeInt $notice;

	/**
	 * Requirements check.
	 *
	 * @var RequirementCheck
	 */
	private RequirementCheck $checks;

	/**
	 * Set object properties.
	 *
	 * @param NoticeInt           $notice Notice interface.
	 * @param RequirementCheckInt $checks Requirement check interface.
	 *
	 * @since 1.0.0
	 */
	public function __construct( NoticeInt $notice, RequirementCheckInt $checks ) {
		$this->notice = $notice;
		$this->checks = $checks;
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
			FatalError::set( $th->getMessage(), $th );
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
		try {
			$this->checks->run();
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
			// @codeCoverageIgnoreEnd
		}
	}
}
