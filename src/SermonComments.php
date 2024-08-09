<?php
/**
 * Sermon comments.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\PT;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\Registrable;

/**
 * Sermon comments.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonComments implements Initable, Registrable {

	private OptionsInt $options;

	protected function __construct() {
		$this->options = get_options_int();
	}

	/**
	 * Get initalized object.
	 *
	 * @return SermonComments
	 */
	public static function init(): SermonComments {
		return new self();
	}

	/**
	 * Register callbacks.
	 *
	 * @return null|bool Return true default.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_filter( 'wp_insert_post_data', array( $this, 'default_comments_off' ) );
		return true;
	}

	/**
	 * Turn comments off for sermons.
	 *
	 * @param array $data Data to insert.
	 * @return array
	 */
	public function default_comments_off( array $data ): array {

		$comments = $this->options->get( 'comments_off', true );

		if ( $data['post_type'] == PT::SERMON && $comments ) {
			$data['comment_status'] = 0;
		}

		return $data;
	}
}
