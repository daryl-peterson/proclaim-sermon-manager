<?php
/**
 * Base settings.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use CMB2;

/**
 * Base settings.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SettingsBase {
	protected int $separator_count = 0;
	public string $option_key      = 'NOT SET';


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->set_defaults();
	}

	public function get_option_key() {
		return $this->option_key;
	}

	/**
	 * Set this menu as the main.
	 *
	 * @param mixed $menu Main menu.
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
	 * @param null|boolean|null $force
	 * @return boolean
	 * @since 1.0.0
	 */
	public function set_defaults( null|bool $force = null ): bool {

		$options  = \get_option( $this->option_key, array() );
		$defaults = Settings::get_defaults( $this->option_key );

		if ( ! $defaults ) {
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

		\delete_option( $this->option_key );
		return \add_option( $this->option_key, $options, '', false );
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

	/**
	 * Add heading seperator.
	 *
	 * @param CMB2   $cmb
	 * @param string $title
	 * @return void
	 * @since 1.0.0
	 */
	protected function add_seperator( CMB2 $cmb, string $title ): void {
		++$this->separator_count;
		$args = array(
			'id'            => 'heading_' . $this->separator_count,
			'name'          => $title,
			'type'          => 'heading',
			'repeatable'    => true,
			'render_row_cb' => function () use ( $title ) {
				echo "<h2 class='drppsm-seperator'>$title</h2><hr>";
			},
		);

		$cmb->add_field( $args );
	}

	protected function add_html( CMB2 $cmb, string $html ) {
		++$this->separator_count;
		$args = array(
			'id'            => 'heading_' . $this->separator_count,
			'name'          => 'heading_' . $this->separator_count,
			'type'          => 'heading',
			'repeatable'    => true,
			'render_row_cb' => function () use ( $html ) {
				echo "$html";
			},
		);

		$cmb->add_field( $args );
	}
}
