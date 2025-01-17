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



	private string $hook;

	private array $hook_args;


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->hook      = 'drppsm_scheduler';
		$this->hook_args = array( $this, 'do_schedule' );
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

		if ( has_action( $this->hook, array( $this, 'do_schedule' ) ) ) {
			return false;
		}

		register_deactivation_hook( FILE, array( $this, 'deactivate' ) );
		add_action( $this->hook, array( $this, 'do_schedule' ) );

		return true;
	}

	/**
	 * Add event.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_event(): void {
		if ( ! wp_next_scheduled( $this->hook ) ) {

			$date  = new DateTime( 'now', new \DateTimeZone( wp_timezone_string() ) );
			$year  = $date->format( 'Y' );
			$month = $date->format( 'm' );
			$day   = $date->format( 'd' );

			$date = new \DateTime( "$year-$month-$day 03:00:00", new \DateTimeZone( wp_timezone_string() ) );
			$date->modify( '+1 day' );
			$stamp = $date->getTimestamp();

			wp_schedule_event( $stamp, 'daily', $this->hook );

		}
	}

	/**
	 * Deactivate scheduling.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function deactivate() {

		wp_clear_scheduled_hook( $this->hook, $this->hook_args );
	}

	/**
	 * Do schedule.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function do_schedule(): void {
		$this->set_series_post_info();
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
			$key     = Transients::SERIES_INFO_EXTD;
			$options = Transients::get( $key );
			if ( ! is_array( $options ) ) {
				$options = array();
			}

			$data                      = $this->get_series_info( $item->term_id, $post_list );
			$options[ $item->term_id ] = $data;

			Logger::debug( array( 'OPTIONS' => $options ) );
			Transients::set( Transients::SERIES_INFO_EXTD, $options );

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
