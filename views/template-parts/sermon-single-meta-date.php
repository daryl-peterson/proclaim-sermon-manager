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

?>
<div class="drppsm-single-meta-item drppsm-single-meta-series">
	<div class="drppsm-single-meta-prefix">Date</div>
	<div class="drppsm-single-meta-text"><?php the_date(); ?></div>
</div>