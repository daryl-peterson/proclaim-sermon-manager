<?php
/**
 * Image grid.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;


$template = str_replace( '.php', '', basename( __FILE__ ) );
$failure  = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . esc_html( $template ) . '</i>" loaded incorrectly.</p>';

if ( ! isset( $args ) || ! is_array( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	render_html( $failure );
	return;
}

if ( ! key_exists( 'list', $args ) ) {
	render_html( $failure );
}
$list = $args['list'];


?>


<div id="drppsm-sc-wrapper">
	<div id="drppsm-image-list">
		<ul>

<?php
foreach ( $list as $item ) :

	?>
			<li class="<?php echo esc_attr( $item['columns'] ); ?>">
				<a href="<?php echo esc_attr( $item['term_link'] ); ?>" title="<?php echo esc_attr( $item['term_name'] ); ?>">
				<img src="<?php echo esc_attr( $item['image_url'] ); ?>">
				</a>
				<div class="list-info">
					<h5><?php echo esc_html( $item['term_name'] ); ?></h5>

					<p class="ext-data">
						<?php echo esc_html( Settings::get( Settings::ARCHIVE_SLUG ) ); ?>  : <?php echo esc_html( $item['count'] ); ?><br>
						<?php echo esc_html( $item['preacher_label'] ); ?> : <?php echo esc_html( $item['preacher_cnt'] ); ?>
					</p>
				</div>
			</li>

	<?php
endforeach;
?>
		</ul>
	</div>
</div>