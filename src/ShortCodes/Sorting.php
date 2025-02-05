<?php
/**
 * Sermon sorting shortcode.
 *
 * @package     DRPPSM\SCSermonSorting
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\ShortCodes;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logger;
use DRPPSM\SCBase;
use DRPPSM\Traits\ExecutableTrait;

use function DRPPSM\get_visibility_settings;
use function DRPPSM\sermon_sorting;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon sorting shortcode.
 *
 * @package     DRPPSM\SCSermonSorting
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Sorting extends SCBase implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Shortcode
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $sc;

	/**
	 * Initialize object.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	protected function __construct() {
		parent::__construct();
		$this->sc = 'drppsm_sorting';
	}

	/**
	 * Register hooks.
	 *
	 * @return null|bool
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( shortcode_exists( $this->sc ) ) {
			return false;
		}
		add_shortcode( $this->sc, array( $this, 'show_sermon_sorting' ) );
		return true;
	}

	/**
	 * Renders sorting HTML.
	 *
	 * @param array $atts          Shortcode parameters.
	 * @return string Sorting HTML.
	 * @since 1.0.0
	 *
	 *
	 * #### Atts parameters
	 * - **series_filter** Do filtering in this specific series (slug)
	 * - **service_type_filter**
	 * - **series** Force specific series to show. Slug only
	 * - **preachers** Force specific preacher to show. Slug only
	 * - **topics** Force specific topic to show. Slug only
	 * - **books** Force specific book to show. Slug only
	 * - **visibility** 'none' to hide the forced fields, 'disable' to show them as disabled and 'suggest' to just set the default value while allowing user to change it. Default 'suggest'
	 * - **hide_topics** Hides the topics dropdown if set to "yes"
	 * - **hide_series**  Hides the series dropdown if set to "yes"
	 * - **hide_preachers**  Hides the preachers dropdown if set to "yes" hide_books - Hides the books dropdown if set to "yes"
	 */
	public function show_sermon_sorting( $atts = array() ): string {
		Logger::debug( $atts );

		// Default shortcode options.
		$args = array(
			'series_filter'       => '',
			'service_type_filter' => '',
			'series'              => '',
			'preachers'           => '',
			'topics'              => '',
			'books'               => '',
			'visibility'          => 'suggest',
			'action'              => 'none',
		);

		$visibility = get_visibility_settings();
		$args      += $visibility;

		// Merge default and user options.
		$args = shortcode_atts( $args, $atts, $this->sc );

		Logger::debug( $args );

		return sermon_sorting( $args );
	}
}
