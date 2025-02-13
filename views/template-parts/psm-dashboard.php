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
namespace DRPPSM;

use DRPPSM\Data\Sermon;

// These must be defined before including psm-check-args.
$template = str_replace( '.php', '', basename( __FILE__ ) );
$required = array( 'info', 'sermon' );

$result = require_once 'psm-check-args.php';
if ( ! $result ) {
	return;
}

$info      = $args['info'];
$sermon    = $args['sermon'];
$src       = null;
$link_text = __( 'View ', 'drppsm' );

/**
 * @var ?Sermon $sermon Sermon object.
 */
if ( $sermon ) {
	$src = get_sermon_image_url( ImageSize::SERMON_MEDIUM, true, true, $sermon->post );
}

?>
<div id="drppsm-dashboard">
	<h3>Posts & Taxonomies</h3>
	<ul class="plugin-info">
		<li class="info ">

			<?php foreach ( $info as $key => $value ) : ?>
				<div class="detail">
				<a href="<?php echo esc_url( $value['link'] ); ?>">
					<span class="cnt"><?php echo esc_html( $value['count'] ); ?></span>
					<span class="name"><?php echo esc_html( $key ); ?></span>
				</a>
				</div>
			<?php endforeach; ?>

		</li>
	</ul>
	<?php
	if ( $src ) :
		?>
		<h3>Recent Sermon</h3>

		<div class="sermon-wrap">
			<div class="image-wrap">
				<img src="<?php echo esc_url( $src ); ?>" alt="" class="sermon"/>
			</div>
			<div class="sermon-info">
				<div class="title"><?php echo esc_html( $sermon->post->post_title ); ?></div>
				<div class="date"><?php echo esc_html( $sermon->meta->date() ); ?>&nbsp;</div>
				<div class="preacher"><?php echo esc_html( $sermon->preacher->name() ); ?></div>
				<div class="archive-link">
					<a href="<?php echo esc_attr( $sermon->link ); ?>" title="<?php echo esc_attr( $sermon->post->post_title ); ?>">
						<?php echo esc_html( $link_text ); ?>
					</a>
					&nbsp;
				</div>
			</div>
		</div>

	<?php endif; ?>

</div>