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

use DateInterval;
use DateTime;
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
 * @package     DRPPSM\ScheduleExtData
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @todo Fix this.
 */
class ScheduleExtData implements Executable, Registrable {


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
	 * Job callback.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private array $job_cb;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->job_cb = array( $this, 'job_runner' );
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

		// Add action to run jobs.
		add_action( self::JOB_NAME, $this->job_cb );

		// Add event.
		$this->add_event();

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
				'display'  => __( 'Proclaim Sermon Manager Job', 'drppsm' ),
			);
		}
		Logger::debug( array( 'SCHEDULES' => $schedules ) );
		return $schedules;
	}

	/**
	 * Add event.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_event(): void {

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
	}

	/**
	 * Deactivate scheduling.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function deactivate() {

		wp_clear_scheduled_hook( self::JOB_NAME );
	}

	/**
	 * Run jobs.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function job_runner(): void {
		$jobs = get_option( Options::KEY_JOBS );
		if ( ! is_array( $jobs ) ) {
			return;
		}

		foreach ( $jobs as $job_key => $job_value ) {
			Logger::debug(
				array(
					'JOB'   => $job_key,
					'VALUE' => $job_value,
				)
			);
		}
	}


	/**
	 * Set series info.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function set_series_post_info(): void {

		$list = TaxUtils::get_terms_with_images(
			array(
				'taxonomy' => DRPPSM_TAX_SERIES,
				'order'    => 'ASC',
				'orderby'  => 'name',
			)
		);

		if ( ! $list ) {
			return;
		}

		/**
		 * @var \WP_Term $item
		 */
		foreach ( $list as $item ) {

			$data = new \stdClass();

			$post_args = array(
				'post_type' => DRPPSM_PT_SERMON,
				'taxonomy'  => DRPPSM_TAX_SERIES,
				'terms'     => $item->term_id,
			);
			$post_list = TaxUtils::get_term_posts( $post_args );

			if ( ! $post_list ) {
				continue;
			}
			$key     = Transient::SERIES_INFO_EXTD;
			$options = Transient::get( $key );
			if ( ! is_array( $options ) ) {
				$options = array();
			}

			$data                      = $this->get_series_info( $item->term_id, $post_list );
			$options[ $item->term_id ] = $data;

			Logger::debug( array( 'OPTIONS' => $options ) );
			Transient::set( Transient::SERIES_INFO_EXTD, $options );

		}
	}

	private function get_series_info( int $series_id, array $post_list ): stdClass {

		$obj            = new \stdClass();
		$obj->preacher  = $this->init_object();
		$obj->topics    = $this->init_object();
		$obj->dates     = array();
		$obj->dates_str = '';

		/**
		 * @var \WP_Post $post_item Post for series.
		 */
		foreach ( $post_list as $post_item ) {

			$date         = get_post_meta( $post_item->ID, Meta::DATE, true );
			$obj->dates[] = $date;

			$tax            = DRPPSM_TAX_PREACHER;
			$preacher_terms = get_the_terms( $post_item->ID, $tax );
			Logger::debug( array( 'PREACHERS' => $preacher_terms ) );

			if ( $preacher_terms ) {
				$this->set_term_info( $obj->preacher, $preacher_terms, $tax );

			}

			$tax    = DRPPSM_TAX_TOPICS;
			$topics = get_the_terms( $post_item->ID, $tax );

			if ( $topics ) {
				$this->set_term_info( $obj->topics, $topics, $tax );
			} else {
				$obj->topics->cnt = 0;
			}
		}
		$this->set_date_info( $obj );
		return $obj;
	}


	private function init_object() {
		$obj            = new \stdClass();
		$obj->names     = array();
		$obj->names_str = '';
		$obj->ids       = array();
		$obj->cnt       = 0;
		return $obj;
	}


	private function set_term_info( stdClass &$object, array $term_list, string $taxonomy ) {

		/**
		 * @var \WP_Term $item
		 */
		foreach ( $term_list as $item ) {
			if ( ! in_array( $item->name, $object->names ) ) {

				$link = get_term_link( $item, $taxonomy );
				if ( ! $link instanceof WP_Error ) {
					$link = esc_url( $link );
				} else {
					$link = false;
				}

				$object->names[]               = $item->name;
				$object->ids[ $item->term_id ] = $link;
			}
		}
		$object->names_str = implode( ', ', $object->names );
		$object->cnt       = count( $object->names );
	}

	private function set_date_info( stdClass &$object ) {
		$dates = $object->dates;

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
				$object->dates_str = $date_first;
			} elseif ( $cnt > 1 ) {
				$date_first = wp_date( $format, $dates[0] );

				$date_last = wp_date( $format, $dates[ $cnt - 1 ] );
				if ( ! $date_last ) {
					$date_last = '';
				} else {
					$date_last = ' - ' . $date_last;
				}

				$object->dates_str = $date_first . $date_last;
			}
		}
	}
}
