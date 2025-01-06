<?php

/**
 * Sermon single series meta
 *
 * @package     DRPPSM/Views/Partials
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

?>

<a href="<?php the_permalink( $post->id ); ?>" class="drppsm-single-title" title="<?php the_title(); ?>">
	<h4><?php the_title( '', '' ); ?></h4>
</a>








