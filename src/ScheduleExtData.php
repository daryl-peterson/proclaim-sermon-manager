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

		return;
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

			$data = $this->get_series_info( $item->term_id, $post_list );

		}
	}

	private function get_series_info( int $series_id, array $post_list ): stdClass {
		$obj           = new \stdClass();
		$obj->preacher = $this->init_object();
		$obj->topics   = $this->init_object();
		$obj->dates    = array();

		/**
		 * @var \WP_Post $post_item Post for series.
		 */
		foreach ( $post_list as $post_item ) {

			$date         = get_post_meta( $post_item->ID, Meta::DATE, true );
			$obj->dates[] = $date;

			$preacher_terms = get_the_terms( $post_item->ID, DRPPSM_TAX_PREACHER );

			if ( $preacher_terms ) {

				$this->set_term_info( $obj->preacher, $preacher_terms );
				// $obj->preacher->cnt   = count( $obj->preacher->names );

			}

			$topics = get_the_terms( $post_item->ID, DRPPSM_TAX_TOPICS );

			if ( $topics ) {
				$this->set_term_info( $obj->topics, $topics );
				$obj->topics->cnt = count( $obj->topics->names );
			} else {
				$obj->topics->cnt = 0;
			}
		}

		return $obj;
	}


	private function init_object() {
		$obj        = new \stdClass();
		$obj->names = array();
		$obj->terms = array();
		$obj->cnt   = 0;
		return $obj;
	}


	private function set_term_info( stdClass &$object, array $term_list ) {

		/**
		 * @var \WP_Term $item
		 */
		foreach ( $term_list as $item ) {
			if ( ! in_array( $item->name, $object->names ) ) {
				$object->names[] = $item->name;
				$object->terms[] = $item;
			}
		}

		$object->cnt = count( $object->names );
	}

	private function get_topic_info() {
	}
}
