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
		add_action( 'wp_dashboard_setup', array( $this, 'widget' ) );
		return true;
	}

	public function widget() {
		wp_add_dashboard_widget(
			'wporg_dashboard_widget',                          // Widget slug.
			esc_html__( 'Proclaim Sermon Manger', 'drppsm' ), // Title.
			function () {
				get_partial( 'psm-dashboard' );
			}           // Display function.
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
