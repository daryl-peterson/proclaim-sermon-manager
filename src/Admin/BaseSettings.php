<?php
/**
 * Base settings.
 *
 * @package     DRPPSM\SettingsBase
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Admin;

use CMB2;
use DRPPSM\Settings;

/**
 * Base settings.
 *
 * @package     DRPPSM\SettingsBase
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class BaseSettings {

	/**
	 * Count for separtor so it can be reused.
	 *
	 * @var integer
	 * @since 1.0.0
	 */
	protected int $separator_count = 0;

	/**
	 * Option key - should be changed in child class.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public string $option_key = 'NOT SET';


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->set_defaults();
	}

	/**
	 * Set this menu as the main.
	 *
	 * @param mixed $submenus Submenus.
	 * @return string
	 * @since 1.0.0
	 */
	public function set_menu( mixed $submenus ): mixed {

		if ( is_array( $submenus ) && ! in_array( $this->option_key, $submenus, true ) ) {
			$submenus[] = $this->option_key;
		}
		return $submenus;
	}

	/**
	 * Set defaults.
	 *
	 * @param null|boolean|null $force Force default reset.
	 * @return boolean
	 * @since 1.0.0
	 */
	public function set_defaults( null|bool $force = null ): bool {

		$options  = \get_option( $this->option_key, array() );
		$defaults = Settings::get_defaults( $this->option_key );

		if ( ! $defaults && ! $force ) {
			return false;
		}

		$changed = false;
		foreach ( $defaults as $key => $value ) {
			if ( ! is_array( $options ) ) {
				continue;
			}
			if ( ! key_exists( $key, $options ) ) {
				$options[ $key ] = $value;
				$changed         = true;
			}
		}

		if ( ! $changed ) {
			return true;
		}

		return update_option( $this->option_key, $options, true );
	}

	/**
	 * Add section heading.
	 *
	 * @param string $title Section title.
	 * @return void
	 * @since 1.0.0
	 */
	protected function add_section( string $title ) {
		echo "<h3 class='seperator'>" . esc_html( $title ) . "</h3>\n";
		echo "<hr>\n";
	}


	/**
	 * Move description to a new line.
	 *
	 * @param string $desc Description.
	 * @return string
	 * @since 1.0.0
	 */
	protected function description( string $desc ): string {
		return '<div class="description">' . $desc . '</div>';
	}

	/**
	 * Create spacing between new lines.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	protected function dot(): string {
		return '<span class="spacer"></span>';
	}
}
