<?php
/**
 * Sermon Archive Shortcode.
 *
 * @package     DRPPSM\SermonArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logger;
use DRPPSM\SermonImageList;
use DRPPSM\Traits\ExecutableTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon Archive Block Theme.
 *
 * @package     DRPPSM\SermonArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonArchive implements Executable, Registrable {
	use ExecutableTrait;

	private string $sc;

	public function __construct() {
		$this->sc = 'drppsm_sermon_archive';
	}

	public function register(): ?bool {
		if ( shortcode_exists( $this->sc ) ) {
			return false;
		}
		add_shortcode( $this->sc, array( $this, 'show_sermons' ) );
		return true;
	}

	public function show_sermons( array $atts ): string {
		$obj = new SermonImageList();
		ob_start();
		$obj->show_data();

		$result = ob_get_clean();
		return $result;
	}
}
