<?php
/**
 * Preacher taxonomy data structure for sermons.
 *
 * @package     DRPPSM\Data\TaxPreacher
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Data;

use WP_Post;

/**
 * Preacher taxonomy data structure for sermons.
 *
 * @package     DRPPSM\Data\TaxPreacher
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TaxPreacher extends TaxData {

	/**
	 * Constructor
	 *
	 * @param WP_Post $post
	 */
	public function __construct( WP_Post $post ) {
		parent::__construct( $post, DRPPSM_TAX_PREACHER );
	}
}
