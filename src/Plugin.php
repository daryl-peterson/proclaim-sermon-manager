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

use DRPPSM\Admin\QueueScripts;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\Interfaces\TextDomainInt;
use DRPPSM\BibleLoad;
use DRPPSM\Constants\Filters;
use DRPPSM\Logging\Logger;

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
	 * Set object properties.
	 *
	 * @param NoticeInt $notice Notice interface.
	 *
	 * @since 1.0.0
	 */
	public function __construct( NoticeInt $notice ) {
		$this->notice = $notice;
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
			$hook = Filters::AFTER_PLUGIN_LOAD;

			if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
				// @codeCoverageIgnoreStart
				return true;
				// @codeCoverageIgnoreEnd
			}
			register_activation_hook( FILE, array( $this, 'activate' ) );
			register_deactivation_hook( FILE, array( $this, 'deactivate' ) );
			add_action( 'shutdown', array( $this, 'shutdown' ) );
			add_action( 'admin_notices', array( $this, 'show_notice' ) );

			// Load other classes.
			$app = app();
			$app->get( RequirementsInt::class )->register();
			$app->get( TextDomainInt::class )->register();
			$app->get( PostTypeSetupInt::class )->register();

			QueueScripts::init()->register();
			SermonEdit::init()->register();
			SermonListTable::init()->register();
			SermonComments::init()->register();
			TaxonomyListTable::init()->register();
			ImageSizes::init()->register();

			do_action( $hook );

			return true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
			// @codeCoverageIgnoreEnd
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
			$obj = get_roles_int();
			$obj->add();

			BibleLoad::init()->run();

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
		return true;
	}

	/**
	 * Display notice if it exist.
	 *
	 * @return string|null Notice strig if exist.
	 * @since 1.0.0
	 */
	public function show_notice(): ?string {
		return $this->notice->show_notice();
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
