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

<<<<<<< HEAD

=======
>>>>>>> 822b76c (Refactoring)
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Constants\Actions;
use DRPPSM\DB\DbUpdates;
<<<<<<< HEAD
=======
use DRPPSM\Interfaces\Executable;
>>>>>>> 822b76c (Refactoring)

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

<<<<<<< HEAD

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

=======
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

>>>>>>> 822b76c (Refactoring)
	/**
	 * Initialize plugin hooks.
	 *
	 * @return bool
	 * @since 1.0.0
	 */
	public function register(): bool {
		try {
			FatalError::check();
<<<<<<< HEAD
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

			textdomain();

			requirements();
			roles();
			textdomain();
			imagesize();
			bible_loader();
			log_writter();

			SermonEdit::init()->register();
			DbUpdates::exec();
			QueueScripts::exec();
			SermonImage::exec();
			SermonComments::exec();
			TaxonomyImage::exec();
			Templates::exec();
			QueryVars::exec();
			Rewrite::exec();

			Pagination::exec();

			SermonListTable::init()->register();
			TaxonomyListTable::init()->register();

			AdminSettings::init()->register();

			do_action( $hook );

			if ( did_action( 'admin_init' ) ) {
				do_action( Actions::AFTER_ADMIN_INIT );
			}

=======

			if ( has_action( 'shutdown', array( $this, 'shutdown' ) ) ) {
				return true;
			}

			register_activation_hook( FILE, array( $this, 'activate' ) );
			register_deactivation_hook( FILE, array( $this, 'deactivate' ) );
			add_action( 'shutdown', array( $this, 'shutdown' ) );
			add_action( 'cmb2_init', array( $this, 'cmb2_init' ) );

			Loader::exec();
>>>>>>> 822b76c (Refactoring)
			return true;

			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			FatalError::set( $th->getMessage(), $th );
			// @codeCoverageIgnoreEnd
<<<<<<< HEAD
=======
			return false;
>>>>>>> 822b76c (Refactoring)
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
<<<<<<< HEAD
	 * Display notice if it exist.
	 *
	 * @return string|null Notice strig if exist.
	 * @since 1.0.0
	 */
	public function show_notice(): ?string {
		return $this->notice->show_notice();
	}

	/**
=======
>>>>>>> 822b76c (Refactoring)
	 * Shut down cleanup.
	 *
	 * @return bool Return true if successfull.
	 * @since 1.0.0
	 */
	public function shutdown(): bool {
<<<<<<< HEAD
=======

		$message  = "\n\n";
		$message .= str_repeat( '-', 80 );
		$message .= "\nSHUTTING DOWN\n";
		$message .= "\n\n";
		Logger::debug( $message );
>>>>>>> 822b76c (Refactoring)
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
