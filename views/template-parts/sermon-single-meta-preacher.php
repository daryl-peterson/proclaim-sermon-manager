<?php
/**
 * Sermon single meta preacher
 *
 * @package     DRPPSM/Views/Template-Parts
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$tax_preacher = DRPPSM_TAX_PREACHER;

if ( ! has_term( '', $tax_preacher, $post->ID ) ) {
	return;
}

?>

<div class="drppsm-single-meta-item">
	<div class="drppsm-single-meta-prefix"><?php echo Settings::get( Settings::PREACHER ); ?></div>
	<div class="drppsm-single-meta-text"><?php echo get_the_term_list( $post->ID, $tax_preacher ); ?></div>
</div>
