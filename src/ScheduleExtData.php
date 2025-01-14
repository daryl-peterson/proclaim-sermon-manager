<?php
/**
 * Create extra data for sermons, series, topics, bible.
 *
 * @package     DRPPSM\ScheduleExtData
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

defined( 'ABSPATH' ) || exit;

/**
 * Create extra data for sermons, series, topics, bible.
 *
 * @package     DRPPSM\ScheduleExtData
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class ScheduleExtData implements Executable, Registrable {


	/**
	 * Callbable for deactivation event.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $cb_deactivate;

	/**
	 * Schedule event.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $cb_schedule;


	/**
	 * Filter cron schedule.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $filter_cron_schedule;


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->cb_deactivate        = array( $this, 'deactivate' );
		$this->cb_schedule          = array( $this, 'do_schedule' );
		$this->filter_cron_schedule = array( $this, 'add_cron_schedule' );
	}

	/**
	 * Initialize and register.
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
	 * Register hooks.
	 *
	 * @return null|bool
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		$this->add_event();

		if ( has_filter( 'cron_schedules', $this->filter_cron_schedule ) ) {
			return false;
		}

		add_filter( 'cron_schedules', $this->filter_cron_schedule );
		register_deactivation_hook( FILE, $this->cb_deactivate );
		return true;
	}

	/**
	 * Deactivate scheduling.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function deactivate() {

		wp_clear_scheduled_hook( $this->cb_schedule );
	}

	/**
	 * Add cron schedule.
	 *
	 * @param array $schedules List of schedules.
	 * @return array
	 * @since 1.0.0
	 */
	public function add_cron_schedule( array $schedules ) {
		$schedules['daily_3am'] = array(
			'interval' => DAY_IN_SECONDS,
			'display'  => __( 'Daily 3am' ),
		);

		return $schedules;
	}

	/**
	 * Add event.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function add_event(): void {
		if ( ! wp_next_scheduled( $this->cb_schedule ) ) {
			Logger::debug( 'Scheduling event' );
			wp_schedule_event( strtotime( '3am' ), 'daily_3am', $this->cb_schedule );
		}
	}

	/**
	 * Do schedule.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function do_schedule(): void {
		$this->set_series_info();
		$this->set_topics_info();
		$this->set_preacher_info();
		$this->set_sermon_info();
	}


	private function set_series_info() {
	}

	private function set_topics_info() {
	}

	private function set_preacher_info() {
	}

	private function set_sermon_info() {
	}
}
