<?php
/**
 * Create extra data for sermons, series, topics, bible.
 *
 * @package     DRPPSM\Scheduler
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DateTime;
use DateTimeZone;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

defined( 'ABSPATH' ) || exit;

/**
 * Create extra data for sermons, series, topics, bible.
 *
 * @package     DRPPSM\Scheduler
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Scheduler implements Executable, Registrable {

	/**
	 * Jobs instance.
	 *
	 * @var SchedulerJobs
	 */
	private static SchedulerJobs $jobs;

	/**
	 * Job name / action.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const JOB_NAME = 'proclaim_job';

	/**
	 * Job interval name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const JOB_INTERVAL_NAME = 'proclaim';

	/**
	 * Job name for complete meta data creation.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const COMPLETE_NAME = 'proclaim_complete';

	/**
	 * Job interval name for complete meta data creation.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public const COMPLETE_INTERVAL_NAME = 'proclaim_complete';


	/**
	 * Job callback.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $job_cb;

	/**
	 * Complete meta data creation callback.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $complete_cb;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->job_cb      = array( $this, 'job_runner' );
		$this->complete_cb = array( $this, 'complete_build' );

		if ( ! isset( self::$jobs ) ) {
			self::$jobs = SchedulerJobs::get_instance();
		}
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

		if ( has_action( self::JOB_NAME, $this->job_cb ) ) {
			return false;
		}

		// Add custom schedule.
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );

		register_deactivation_hook( FILE, array( $this, 'deactivate' ) );

		// Add actions to run jobs.
		add_action( self::JOB_NAME, $this->job_cb );
		add_action( self::COMPLETE_NAME, $this->complete_cb );

		// Add event.
		$this->add_events();

		// Do action to allow other plugins to register after this.
		do_action( DRPPSMA_SCHEDULE_REGISTERED );

		return true;
	}

	/**
	 * Add custom schedule.
	 *
	 * @param array $schedules Schedules array.
	 * @return array
	 * @since 1.0.0
	 */
	public function add_schedules( array $schedules ): array {
		$job = self::JOB_INTERVAL_NAME;
		if ( ! isset( $schedules[ $job ] ) ) {
			$interval = absint( Settings::get( Settings::CRON_INTERVAL, 2 ) );

			$schedules[ $job ] = array(
				'interval' => $interval * HOUR_IN_SECONDS,
				'display'  => __( 'Proclaim Job Runner', 'drppsm' ),
			);
		}

		$job = self::COMPLETE_INTERVAL_NAME;
		if ( ! isset( $schedules[ $job ] ) ) {
			$schedules[ $job ] = array(
				'interval' => DAY_IN_SECONDS * 2,
				'display'  => __( 'Proclaim Meta Manager', 'drppsm' ),
			);
		}

		return $schedules;
	}

	/**
	 * Add event.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_events(): void {
		try {
			if ( ! wp_next_scheduled( self::JOB_NAME ) ) {

				$result = wp_schedule_event( time(), self::JOB_INTERVAL_NAME, self::JOB_NAME );
				if ( ! $result ) {
					Logger::error(
						array(
							'ERROR'    => 'FAILED TO SCHEDULE',
							'JOB NAME' => self::JOB_NAME,
							'INTERVAL' => self::JOB_INTERVAL_NAME,
						)
					);
				}
			}

			if ( ! wp_next_scheduled( self::COMPLETE_NAME ) ) {

				$date  = new DateTime( 'now', new DateTimeZone( wp_timezone_string() ) );
				$year  = $date->format( 'Y' );
				$month = $date->format( 'm' );
				$day   = $date->format( 'd' );

				$date = new DateTime( "$year-$month-$day 03:00:00", new DateTimeZone( wp_timezone_string() ) );
				$date->modify( '+3 day' );
				$stamp = $date->getTimestamp();

				wp_schedule_event( $stamp, self::COMPLETE_INTERVAL_NAME, self::COMPLETE_NAME );
			}
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'ERROR' => $th->getMessage(),
					'TRACE' => $th->getTrace(),
				)
			);
		}
	}

	/**
	 * Deactivate scheduling.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function deactivate() {
		wp_clear_scheduled_hook( self::JOB_NAME );
		wp_clear_scheduled_hook( self::COMPLETE_NAME );
	}

	/**
	 * Run jobs for items viewed that may not have meta data created.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function job_runner(): void {

		try {

			// Call action to prevent TaxonomyMeta overwritting data.
			do_action( 'drppsm_job_runner' );

			Logger::debug( 'JOB RUNNER' );
			Logger::debug( self::$jobs );

			$jobs = self::$jobs->get_jobs();
			if ( ! $jobs ) {
				return;
			}

			foreach ( $jobs as $taxonomy => $term_ids ) {

				foreach ( $term_ids as $term_id ) {

					$obj = new TaxInfo( $taxonomy, absint( $term_id ) );

					if ( $obj ) {
						$key = TaxMeta::get_data_key( $taxonomy );
						update_term_meta( $term_id, $key, $obj );
						self::$jobs->delete( $taxonomy, $term_id );
					}
				}
			}
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'ERROR' => $th->getMessage(),
					'TRACE' => $th->getTrace(),
				)
			);
		}
	}

	/**
	 * Set complete meta data creation.
	 *
	 * @since 1.0.0
	 */
	public function complete_build() {
		$key = Timer::start( __FILE__, __FUNCTION__ );
		$tax = array_values( DRPPSM_TAX_MAP );
		foreach ( $tax as $taxonomy ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => true,
				)
			);

			if ( is_wp_error( $terms ) || ! is_array( $terms ) || count( $terms ) === 0 ) {
				continue;
			}

			foreach ( $terms as $term ) {
				$obj = new TaxInfo( $taxonomy, $term->term_id );
				if ( $obj ) {
					$key = TaxMeta::get_data_key( $taxonomy );
					update_term_meta( $term->term_id, $key, $obj );
				}
			}
		}
		Timer::stop( $key );
	}
}
