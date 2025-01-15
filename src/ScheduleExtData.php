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

use DRPPSM\Constants\Meta;
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

		if ( ! has_filter( 'deactivate_' . FILE, array( $this, 'deactivate' ) ) ) {
			return false;
		}

		register_deactivation_hook( FILE, array( $this, 'deactivate' ) );

		return true;
	}

	/**
	 * Add event.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_event(): void {

		if ( ! wp_next_scheduled( $this->hook, $this->hook_args ) ) {
			wp_schedule_event( strtotime( '3am tomorrow' ), 'daily', $this->hook, $this->hook_args );
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


	/**
	 * Set series info.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function set_series_info() {
		$list = TaxQueries::get_terms_with_images( DRPPSM_TAX_SERIES, 'ASC', 'name' );

		if ( ! $list ) {
			return;
		}

		foreach ( $list as $item ) {
			$posts = TaxQueries::get_term_posts( DRPPSM_TAX_SERIES, $item->term_id );

			if ( ! $posts ) {
				continue;
			}

			$this->get_series_preacher( $item, $posts );

		}
	}

	private function get_series_preacher( mixed $list_item, array $posts ) {
		$data  = array();
		$count = 0;

		$data['preachers']['names'] = array();

		foreach ( $posts as $item ) {

			$meta = get_post_meta( $item->ID );

			$terms_preachers = get_the_terms( $item->ID, DRPPSM_TAX_PREACHER );

			if ( ! $terms_preachers ) {
				continue;
			}

			foreach ( $terms_preachers as $preacher ) {
				if ( ! in_array( $preacher->name, $data['preachers']['names'] ) ) {
					$data['preachers']['names'][] = $preacher->name;
					$data['preachers']['terms'][] = $preacher;
				}
			}

			Logger::debug(
				array(
					'POST ID'   => $item->ID,
					// 'TERMS'     => $terms_preachers,
					'LIST ITEM' => $list_item,
					'META'      => $meta,
					'DATA'      => $data,

				)
			);

		}
	}

	private function set_topics_info() {
	}

	private function set_preacher_info() {
	}

	private function set_sermon_info() {
	}
}
