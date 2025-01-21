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
 * @var TaxInfo $item Object.
 */
foreach ( $list as $item ) :

	$object   = $item->term();
	$book     = false;
	$preacher = false;
	$sermon   = false;
	$topic    = false;

	if ( $item->has_books() ) {
		$book     = true;
		$book_cnt = $item->books()->count();
		$book_lbl = $item->books()->label();
	}

	if ( $item->has_preachers() ) {
		$preacher     = true;
		$preacher_cnt = $item->preachers()->count();
		$preacher_lbl = $item->preachers()->label();
	}

	if ( $item->has_sermons() ) {
		$sermon     = true;
		$sermon_cnt = $item->sermons()->count();
		$sermon_lbl = $item->sermons()->label();
	}

	if ( $item->has_topics() ) {
		$topic     = true;
		$topic_cnt = $item->topics()->count();
		$topic_lbl = $item->topics()->label();
	}

	?>
			<li class="<?php echo esc_attr( $cols ); ?>">

			<a href="<?php echo esc_attr( $object->link ); ?>" title="<?php echo esc_attr( $object->name ); ?>">
				<img src="<?php echo esc_attr( $object->images[ $size ] ); ?>">
				</a>
				<div class="list-info">
					<h5><?php echo esc_html( $object->name ); ?></h5>

					<ul class="list-meta">
						<?php if ( $sermon ) : ?>
						<li>
							<?php echo esc_html( $sermon_lbl ); ?>: <?php echo esc_html( $sermon_cnt ); ?>
						</li>
						<?php endif; ?>
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
						<?php if ( $book ) : ?>
						<li>
							<?php echo esc_html( $book_lbl ); ?>: <?php echo esc_html( $book_cnt ); ?>
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