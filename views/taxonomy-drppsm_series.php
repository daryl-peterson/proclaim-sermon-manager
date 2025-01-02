<?php
/**
 * Sermon series taxonomy template.
 *
 * @package     DRPPSM/Views/
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

get_header();

get_partial( 'sermon-wrapper-start' );
get_partial( 'content-sermon-filtering' );



get_partial( 'sermon-wrapper-end' );
get_footer();
