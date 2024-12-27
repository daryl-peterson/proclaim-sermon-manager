<?php
/**
 * Sermon single meta date
 *
 * @package     DRPPSM/Views/Template-Parts
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;
Logger::debug( $post );
$view_label = __( 'Views', 'drppsm' );
$view_count = get_sermon_view_count( (int) $post->ID );
?>
<div class="drppsm-single-meta-item">
	<div class="drppsm-single-meta-prefix"><?php echo $view_label; ?></div>
	<div class="drppsm-single-meta-text"><?php echo $view_count; ?></div>
</div>