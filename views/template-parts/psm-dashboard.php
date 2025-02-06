<?php
/**
 * Dashboard Widget
 *
 * @package     DRPPSM\Dashboard
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

// Get current sermon count.
$num_posts = wp_count_posts( DRPPSM_PT_SERMON )->publish;

// Format the number to current locale.
$num = number_format_i18n( $num_posts );

// Put correct singular or plural text
// translators: %s integer count of sermons.
$text = wp_sprintf( esc_html( _n( '%s Sermon', '%s Sermons', intval( $num_posts ), 'drppsm' ) ), $num );


?>
<div id="drppsm-dashboard">
	<ul class="plugin-info">
		<li class="info wp-clearfix">
			<div class="detail">


			</div>

		</li>
	</ul>
</div>