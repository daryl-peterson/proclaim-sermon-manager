<?php
/**
 * Sermon service type taxonomy.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

$qv_tax = get_query_var( 'taxonomy' );
echo do_shortcode( '[' . $qv_tax . ']' );
