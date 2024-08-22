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
	 * @param NoticeInt $notice Notice interface.
	 *
	 * @since 1.0.0
	 */
	public function __construct( NoticeInt $notice ) {
		$this->notice       = $notice;
		$this->cmb2_version = '?.?.?';
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
			$hook = Actions::AFTER_PLUGIN_LOAD;

			if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
				// @codeCoverageIgnoreStart
				return true;
				// @codeCoverageIgnoreEnd
			}
			register_activation_hook( FILE, array( $this, 'activate' ) );
			register_deactivation_hook( FILE, array( $this, 'deactivate' ) );
			add_action( 'shutdown', array( $this, 'shutdown' ) );
			add_action( 'admin_notices', array( $this, 'show_notice' ) );
			add_action( 'cmb2_init', array( $this, 'cmb2_init' ) );

			AdminMenu::init()->register();

			// Load other classes.
			$app = app();

			$app->get( PostTypeSetupInt::class )->register();

			textdomain()->register();

			requirements();
			roles();
			textdomain();
			imagesize();
			bibleload();

			SermonEdit::init()->register();
			DbUpdates::exec();
			QueueScripts::exec();
			SermonImage::exec();
			SermonComments::exec();
			TaxonomyImage::exec();
			Templates::exec();
			QueryVars::exec();
			Rewrite::exec();
			Debug::exec();
			Pagination::exec();

			SermonListTable::init()->register();
			TaxonomyListTable::init()->register();

			AdminSettings::init()->register();

			do_action( $hook );

			if ( did_action( 'admin_init' ) ) {
				do_action( Actions::AFTER_ADMIN_INIT );
			}

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
