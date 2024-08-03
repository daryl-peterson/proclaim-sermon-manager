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
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\Taxonomy\BibleBookLoad;

/**
 * Plugin main class.
 *
 * @since       1.0.0
 */
class Plugin implements PluginInt {

	private NoticeInt $notice;


	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->notice = App::getNoticeInt();
	}

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		try {
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

			// Load other classes
			Requirements::init()->register();
			App::getTextDomainInt()->register();
			App::getPostTypeSetupInt()->register();
			QueueScripts::init()->register();
			AdminSermon::init()->register();
			ImageUtils::init()->register();
			BibleBookLoad::init()->register();

			Logger::debug( 'PLUGIN HOOKS INITIALIZED' );
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
