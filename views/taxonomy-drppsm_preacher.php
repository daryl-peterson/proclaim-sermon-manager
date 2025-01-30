<?php
/**
 * Sermon preacher taxonomy.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$term_name  = DRPPSM_TAX_PREACHER;
$image_size = 'full';
require_once 'taxonomy.php';
