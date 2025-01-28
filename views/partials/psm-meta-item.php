<?php
/**
 * Meta item.
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use WP_Term;

defined( 'ABSPATH' ) || exit;

$requirements = array(
	'term',
);

if ( ! isset( $args ) || ! is_array( $args ) ) {
	return;
}

if ( ! has_keys( array( 'term' ), $args ) ) {
	return;
}

$term = $args['term'];
if ( ! $term instanceof WP_Term ) {
	return;
}

$label = get_taxonomy_field( $term->taxonomy, 'singular_name' );
$value = $term->name;
$link  = get_term_link( $term, $term->taxonomy );
if ( is_wp_error( $link ) ) {
	$link = '';
}

?>
<div class="meta-item">
	<div class="label"><?php echo esc_html( $label ); ?> : </div>
	<div class="value">
		<a href="<?php echo esc_attr( $link ); ?>">
			<?php echo esc_html( $value ); ?>
		</a>
	</div>
</div>
