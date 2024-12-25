<?php
/**
 * Sermon archive meta date
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
<div class="drppsm-archive-meta-item drppsm-archive-meta-series">
	<div class="drppsm-archive-meta-prefix">Date</div>
	<div class="drppsm-archive-meta-text"><?php the_date(); ?></div>
</div>