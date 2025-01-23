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
use DRPPSM\Constants\Meta;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

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
	use ExecutableTrait;

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

			$jobs = self::$jobs->get_jobs();
			if ( ! $jobs ) {
				return;
			}

			foreach ( $jobs as $tax_name => $term_ids ) {

				foreach ( $term_ids as $term_id ) {
					$item = get_term( $term_id, $tax_name );
					update_term_meta( $item->term_id, "{$tax_name}_cnt", $item->count );
					$this->set_get_date( $tax_name, $item->term_id );
					self::$jobs->delete( $tax_name, $term_id );
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

		$tax = array_values( DRPPSM_TAX_MAP );
		foreach ( $tax as $tax_name ) {
			$term_list = get_terms(
				array(
					'taxonomy'   => $tax_name,
					'hide_empty' => true,
				)
			);

			if ( is_wp_error( $term_list ) || ! is_array( $term_list ) || count( $term_list ) === 0 ) {
				continue;
			}

			foreach ( $term_list as $item ) {

				update_term_meta( $item->term_id, "{$tax_name}_cnt", $item->count );
				$this->set_get_date( $tax_name, $item->term_id );

			}
		}
	}


	private function set_get_date( string $tax_name, int $term_id, bool $first = true ): void {

		$args = array(
			'post_type'   => DRPPSM_PT_SERMON,
			'numberposts' => 1,
			'order'       => 'ASC',
			'orderby'     => 'meta_value_num',
			'tax_query'   => array(
				array(
					'taxonomy'         => $tax_name,
					'field'            => 'term_id',
					'terms'            => $term_id,
					'include_children' => false,
				),

			),
			'meta_query'  => array(
				'orderby'      => 'meta_value_num',
				'meta_key'     => Meta::DATE,
				'meta_value'   => time(),
				'meta_compare' => '<=',
			),

		);
		$post_list = get_posts( $args );

		if ( is_wp_error( $post_list ) || ! is_array( $post_list ) || ! count( $post_list ) > 0 ) {
			return;
		}

		$post_item = array_shift( $post_list );
		if ( ! $post_item ) {
			return;
		}

		$meta = get_post_meta( $post_item->ID, Meta::DATE, true );

		if ( ! isset( $meta ) || empty( $meta ) ) {
			return;
		}

		update_term_meta( $term_id, "{$tax_name}_date", $meta );
	}
}
