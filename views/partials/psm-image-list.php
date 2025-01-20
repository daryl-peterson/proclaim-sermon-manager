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

if ( ! has_keys( array( 'list', 'columns', 'size' ), $args ) ) {
	render_html( $failure );
	return;
}


$list = $args['list'];
$cols = $args['columns'];
$size = $args['size'];
?>


<div id="drppsm-sc-wrapper">
	<div id="drppsm-image-list">
		<ul>

<?php

/**
 * @var TaxInfo $item
 */
foreach ( $list as $item ) :

	$term = $item->term();

	$preacher     = false;
	$preacher_cnt = $item->preachers()->count();
	$preacher_lbl = $item->preachers()->label();
	if ( $preacher_cnt > 0 ) {
		$preacher = true;
	}

	$books = $item->books()->count();

	$topic     = false;
	$topic_cnt = $item->topics()->count();
	$topic_lbl = $item->topics()->label();
	if ( $topic_cnt > 0 ) {
		$topic = true;
	}

	?>
			<li class="<?php echo esc_attr( $cols ); ?>">

			<a href="<?php echo esc_attr( $term->link ); ?>" title="<?php echo esc_attr( $term->name ); ?>">
				<img src="<?php echo esc_attr( $term->images[ $size ] ); ?>">
				</a>
				<div class="list-info">
					<h5><?php echo esc_html( $term->name ); ?></h5>

					<ul>
						<?php if ( $preacher ) : ?>
						<li>
							<?php echo esc_html( $preacher_lbl ); ?>: <?php echo esc_html( $preacher_cnt ); ?>
						</li>
						<?php endif; ?>
						<?php if ( $topic ) : ?>
						<li>
							<?php echo esc_html( $topic_lbl ); ?>: <?php echo esc_html( $topic_cnt ); ?>
						</li>
						<?php endif; ?>

					</ul>
				</div>
			</li>

	<?php
endforeach;
?>
		</ul>
	</div>
</div>