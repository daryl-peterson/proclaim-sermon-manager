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
	 * @return null|bool Retruns true as default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		try {
			$hook = Helper::get_key_name( 'REQUIREMENTS_INIT' );

			if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
				// @codeCoverageIgnoreStart
				return false;
				// @codeCoverageIgnoreEnd
			}
			add_action( 'admin_init', array( $this, 'is_compatible' ) );
			do_action( $hook );

			return true;

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
	 * @return bool True if without error.
	 * @since 1.0.0
	 */
	public function is_compatible(): bool {
		$result = false;
		try {
			$this->checks->run();
			$result = true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
			// @codeCoverageIgnoreEnd
		}
		return $result;
	}
}
