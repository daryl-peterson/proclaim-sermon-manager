<?php

/**
 * Sermon archive content
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) or exit;

$template     = str_replace( '.php', '', basename( __FILE__ ) );
$failure      = '<p><b>' . DRPPSM_TITLE . '</b>: Partial "<i>' . $template . '</i>" loaded incorrectly.</p>';
$requirements = array(
	'terms',
	'image_size',
);

if ( ! isset( $args ) ) {
	Logger::error( 'Args variable does not exist. Template : ' . $template );
	echo $failure;
	return;
}

extract( $args );

?>

<div id="drppms-<?php echo $taxonomy; ?>-grid-<?php echo $term->term_id; ?>" class="">



</div>
