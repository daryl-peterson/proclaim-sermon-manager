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
use stdClass;
use WP_Error;
use WP_Post;

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

					$obj = $this->get_term_meta( $taxonomy, absint( $term_id ) );

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
		Logger::debug( 'COMPLETE BUILD' );
	}


	/**
	 * Get term meta.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param int    $term_id Term id.
	 * @return mixed
	 * @since 1.0.0
	 */
	private function get_term_meta( string $taxonomy, int $term_id ): mixed {

		Logger::debug(
			array(
				'TAXONOMY' => $taxonomy,
				'TERM ID'  => $term_id,
			)
		);

		switch ( $taxonomy ) {
			case DRPPSM_TAX_SERIES:
				$obj = new TaxInfo( $taxonomy, $term_id );
				$obj->init();

				Logger::debug( 'ADDING TAXONOMY' );
				$sermons = $obj->sermons();
				$obj->add_taxonomy( DRPPSM_TAX_TOPICS );

				break;
			case DRPPSM_TAX_TOPICS:
				break;
			case DRPPSM_TAX_PREACHER:
				break;
			case DRPPSM_TAX_BIBLE:
				break;
		}
		return $obj;
	}

	/**
	 * Get series info.
	 *
	 * @param array $post_list
	 * @return array
	 * @since 1.0.0
	 */
	private function get_series_info( array $post_list ): array {

		$obj              = array();
		$obj['preacher']  = $this->init_object();
		$obj['topics']    = $this->init_object();
		$obj['sermons']   = $this->init_object();
		$obj['dates']     = array();
		$obj['dates_str'] = '';

		Logger::debug( array( 'POST LIST' => $post_list ) );

		/**
		 * @var \WP_Post $post_item Post for series.
		 */
		foreach ( $post_list as $post_item ) {
			$this->set_sermon_info( $obj['sermons'], $post_list );

			$date           = get_post_meta( $post_item->ID, Meta::DATE, true );
			$obj['dates'][] = $date;
			$tax            = DRPPSM_TAX_PREACHER;

			$preacher_terms = get_the_terms( $post_item->ID, $tax );
			if ( $preacher_terms ) {
				$this->set_term_info( $obj['preacher'], $preacher_terms, $tax );
			} else {
				unset( $obj['preacher'] );
			}

			$tax    = DRPPSM_TAX_TOPICS;
			$topics = get_the_terms( $post_item->ID, $tax );

			if ( $topics ) {
				$this->set_term_info( $obj['topics'], $topics, $tax );
			} else {
				unset( $obj['topics'] );
			}
		}
		$this->set_date_info( $obj );
		return $obj;
	}

	/**
	 * Initialize object.
	 *
	 * @return stdClass
	 * @since 1.0.0
	 */
	private function init_object() {
		$obj            = new \stdClass();
		$obj->names     = array();
		$obj->names_str = '';
		$obj->ids       = array();
		$obj->links     = array();

		$obj->cnt = 0;
		return $obj;
	}

	/**
	 * Get sermon info.
	 *
	 * @param stdClass &$object
	 * @param array    $sermons
	 * @return void
	 * @since 1.0.0
	 */
	private function set_sermon_info( stdClass &$object, array $sermons ) {
		$object->names = array();
		$object->ids   = array();

		foreach ( $sermons as $sermon ) {
			$object->names[] = $sermon->post_title;
			$object->ids[]   = $sermon->ID;
			$object->slugs[] = $sermon->post_name;

			$link = get_permalink( $sermon );
			if ( ! $link instanceof WP_Error && isset( $link ) ) {
				$link = esc_url( $link );
			} else {
				$link = false;
			}
			$object->links[ $sermon->ID ] = $link;

		}
		$object->names_str = implode( ', ', $object->names );
		$object->cnt       = count( $object->names );
	}

	/**
	 * Set term info.
	 *
	 * @param stdClass $object Object to set.
	 * @param array    $term_list List of terms.
	 * @param string   $taxonomy Taxonomy name.
	 * @return void
	 * @since 1.0.0
	 */
	private function set_term_info( stdClass &$object, array $term_list, string $taxonomy ) {

		/**
		 * @var \WP_Term $item
		 */
		foreach ( $term_list as $item ) {
			if ( ! in_array( $item->name, $object->names ) ) {

				$link = get_term_link( $item, $taxonomy );
				if ( ! $link instanceof WP_Error && isset( $link ) ) {
					$link = esc_url( $link );
				} else {
					$link = false;
				}

				$object->names[]                 = $item->name;
				$object->ids[]                   = $item->term_id;
				$object->links[ $item->term_id ] = $link;
			}
		}
		$object->names_str = implode( ', ', $object->names );
		$object->cnt       = count( $object->names );
	}

	/**
	 * Set date info.
	 *
	 * @param array &$object Initialized object.
	 * @return void
	 * @since 1.0.0
	 */
	private function set_date_info( array &$object ) {
		$dates = $object['dates'];

		if ( is_array( $dates ) && 0 !== count( $dates ) ) {
			$format = 'j F Y';
			asort( $dates );

			$cnt = count( $dates );

			$date_last = '';

			if ( 1 === $cnt ) {
				$date_first = wp_date( $format, $dates[0] );
				if ( ! $date_first ) {
					$date_first = '';
				}
				$object['dates_str'] = $date_first;
			} elseif ( $cnt > 1 ) {
				$date_first = wp_date( $format, $dates[0] );

				$date_last = wp_date( $format, $dates[ $cnt - 1 ] );
				if ( ! $date_last ) {
					$date_last = '';
				} else {
					$date_last = ' - ' . $date_last;
				}

				$object['dates_str'] = $date_first . $date_last;
			}
		}
	}
}
