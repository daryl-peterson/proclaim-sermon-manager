<?php
/**
 * Sermon Archive Shortcode.
 *
 * @package     DRPPSM\ShortCodes\SermonArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\SermonImageList;
use DRPPSM\Traits\ExecutableTrait;

use function DRPPSM\sermon_sorting;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon Archive Block Theme.
 *
 * @package     DRPPSM\ShortCodes\SermonArchive
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonArchive implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Shortcode name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc;

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct( array $atts = array() ) {
		$this->sc = 'drppsm_sermon_archive';
	}

	/**
	 * Register the shortcode.
	 *
	 * @return bool|null
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( shortcode_exists( $this->sc ) ) {
			return false;
		}
		add_shortcode( $this->sc, array( $this, 'show' ) );
		return true;
	}

	/**
	 * Display the sermons.
	 *
	 * @param array $atts
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function show( array $atts ): string {

		ob_start();
		echo sermon_sorting();
		new SermonImageList();
		$result = ob_get_clean();
		return $result;
	}
}
