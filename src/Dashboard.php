<?php
/**
 * Dashboard class
 *
 * @package     DRPPSM\Dashboard
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

// @
defined( 'ABSPATH' ) || exit;

/**
 * Dashboard class
 *
 * @package     DRPPSM\Dashboard
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Dashboard implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Register hooks.
	 *
	 * @return null|bool Return true as default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( 'dashboard_glance_items', array( $this, 'glance' ) ) ) {
			return false;
		}
		add_action( 'dashboard_glance_items', array( $this, 'glance' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );
		return true;
	}

	/**
	 * Add dashboard widget.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget(
			'wporg_dashboard_widget',                          // Widget slug.
			esc_html__( 'Proclaim Sermon Manger', 'drppsm' ), // Title.
			function () {
				$this->show_dashboard_widget();
			}
		);
	}

	/**
	 * Show dashboard widget.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function show_dashboard_widget() {

		$info    = array();
		$sermons = wp_count_posts( DRPPSM_PT_SERMON )->publish;

		$key                   = get_post_field( DRPPSM_PT_SERMON, 'label' );
		$info[ $key ]['count'] = number_format_i18n( $sermons );
		$info[ $key ]['link']  = admin_url( 'edit.php?post_type=drppsm_sermon' );

		foreach ( DRPPSM_TAX_MAP as $key => $value ) {
			$result = wp_count_terms(
				array(
					'taxonomy'   => $value,
					'hide_empty' => true,
				)
			);

			if ( is_wp_error( $result ) ) {
				$num = 0;
			} else {
				$num = number_format_i18n( $result );
			}

			$label = get_taxonomy_field( $value, 'label' );

			$info[ $label ]['count'] = $num;
			$info[ $label ]['link']  = admin_url( 'edit-tags.php?taxonomy=' . $value . '&post_type=drppsm_sermon' );
		}

		$sermon = SermonUtils::get_latest();
		if ( is_array( $sermon ) ) {
			$sermon = array_shift( $sermon );
		}

		Logger::debug( $sermon );

		get_partial(
			'psm-dashboard',
			array(
				'info'   => $info,
				'sermon' => $sermon,
			)
		);
	}

	/*
	public function widget_render() {
		esc_html_e( "Howdy! I'm a great Dashboard Widget.", 'wporg' );
		get_partial( 'psm-dashboard' );
	}
	*/

	/**
	 * Display sermon count in dashboard.
	 *
	 * @since 1.0.0
	 */
	public function glance(): void {

		$icon = Settings::get( Settings::MENU_ICON );
		// Get current sermon count.
		$num_posts = wp_count_posts( DRPPSM_PT_SERMON )->publish;

		// Format the number to current locale.
		$num = number_format_i18n( $num_posts );

		// Put correct singular or plural text
		// translators: %s integer count of sermons.
		$text = wp_sprintf( esc_html( _n( '%s Sermon', '%s Sermons', intval( $num_posts ), 'drppsm' ) ), $num );

		$count = '<li class="drppsm-sermon">';
		$url   = admin_url( 'edit.php?post_type=drppsm_sermon' );

		if ( current_user_can( 'edit_posts' ) ) {
			$count .= "<a href=\"{$url}\" class=\"$icon\">" . $text . '</a>';
		} else {
			$count .= $text;
		}

		$count .= '</li>';
		echo $count;
	}
}
