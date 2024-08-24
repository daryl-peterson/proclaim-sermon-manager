<?php
/**
 * Single sermon template.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

$comments = get_setting( 'comments' );


get_header();

echo get_partial( 'content-sermon-wrapper-start' );
echo apply_filters( 'single-sermon-before-sermons', '' );

while ( have_posts() ) :
	global $post;
	the_post();

	if ( ! post_password_required( $post ) ) {
		// wpfc_sermon_single_v2();
	} else {
		echo get_the_password_form( $post );
	}

	if ( comments_open() || get_comments_number() ) :
		if ( isset( $comments ) ) {
			comments_template();
		}
	endif;
endwhile;

echo apply_filters( 'single-wpfc_sermon-after-sermons', '' );

?>

<?php echo wpfc_get_partial( 'content-sermon-wrapper-end' ); ?>

<?php
get_footer();
