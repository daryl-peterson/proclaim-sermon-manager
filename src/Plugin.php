<?php
/**
 * Plugin main class.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Admin\AdminSermon;
use DRPSermonManager\Admin\QueueScripts;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\PluginInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Interfaces\RequirementsInt;
use DRPSermonManager\Interfaces\RolesInt;
use DRPSermonManager\Interfaces\TextDomainInt;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\BibleLoad;

/**
 * Plugin main class.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
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
	 * @return void
	 */
	public function register(): void {
		try {
			FatalError::check();
			$hook = Helper::get_key_name( 'PLUGIN_INIT' );

			if ( did_action( $hook ) && ! defined( 'PHPUNIT_TESTING' ) ) {
				// @codeCoverageIgnoreStart
				return;
				// @codeCoverageIgnoreEnd
			}
			register_activation_hook( FILE, array( $this, 'activate' ) );
			register_deactivation_hook( FILE, array( $this, 'deactivate' ) );
			add_action( 'shutdown', array( $this, 'shutdown' ) );
			add_action( 'admin_notices', array( $this, 'show_notice' ) );

			// Load other classes.
			$app = App::init();
			$app->get( RequirementsInt::class )->register();
			$app->get( TextDomainInt::class )->register();
			$app->get( PostTypeSetupInt::class )->register();
			$app->get( RolesInt::class )->register();
			$app->get( AdminSermon::class )->register();
			$app->get( BibleLoad::class )->register();
			$app->get( TaxonomyImg::class )->register();

			ImageUtils::init()->register();
			QueueScripts::init()->register();
			SermonImage::init()->register();

			Logger::debug( 'PLUGIN HOOKS INITIALIZED' );
			do_action( $hook );

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Activativation.
	 *
	 * @return void
	 */
	public function activate(): void {
		Logger::debug( 'Activated' );
		// @todo Add activation cleanup
	}




	/**
	 * Deactivation.
	 *
	 * @return void
	 */
	public function deactivate(): void {
		Logger::debug( 'DEACTIVATING' );
		// @todo Add deactivation cleanup
	}

	/**
	 * Dispaly admin notice
	 *
	 * @return void
	 */
	public function show_notice(): void {
		$this->notice->show_notice();
	}

	/**
	 * Shut down cleanup.
	 *
	 * @return void
	 */
	public function shutdown(): void {

		Logger::debug( "SHUTDOWN\n" . str_repeat( '-', 80 ) . "\n\n\n" );
	}
}
