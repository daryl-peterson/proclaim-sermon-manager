<?php
/**
 * Advanced options.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Constants\Actions;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * Advanced options.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @todo Impliment this.
 */
class OptAdvance implements Initable, Registrable {

	public const OPTION_KEY = 'drppsm_options_adv';

	const DEFAULTS = array(
		// @todo Add defaults here
	);

	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
	}

	/**
	 * Get initialize object.
	 *
	 * @return OptAdvance
	 * @since 1.0.0
	 */
	public static function init(): OptAdvance {
		return new self();
	}

	/**
	 * Get options value
	 *
	 * @param string $key
	 * @param mixed  $default_value
	 * @return mixed
	 * @since 1.0.0
	 */
	public static function get( string $key, mixed $default_value = null ): mixed {

		$options = \get_option( self::OPTION_KEY, $default_value );
		if ( ! is_array( $options ) ) {
			return $default_value;
		}

		if ( ! key_exists( $key, $options ) ) {
			return $default_value;
		}
		return $options[ $key ];
	}

	/**
	 * Set option value
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @return boolean
	 * @since 1.0.0
	 */
	public static function set( string $key, mixed $value ): bool {

		$option_int = options();
		$options    = (array) $option_int->get( self::OPTION_KEY, array() );

		if ( ! is_array( $options ) ) {
			$options = array();
			foreach ( self::DEFAULTS as $opt_key => $value ) {
				if ( ! key_exists( $opt_key, $options ) ) {
					$options[ $opt_key ] = $value;
				}
			}
		}

		$options[ $key ] = $value;
		return $option_int->set( self::OPTION_KEY, $options );
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_action( Actions::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( DRPPSM_FLTR_SETTINGS_RSM, array( $this, 'set_menu' ) );
		return true;
	}

	/**
	 * Set this menu as hidden.
	 *
	 * @param array $submenus Submenu items to hide.
	 * @return array
	 * @since 1.0.0
	 */
	public function set_menu( array $submenus ): array {
		if ( ! in_array( self::OPTION_KEY, $submenus, true ) ) {
			$submenus[] = self::OPTION_KEY;
		}
		return $submenus;
	}

	/**
	 * Register metaboxes.
	 *
	 * @param callable $display_cb Display callback.
	 * @return void
	 * @since 1.0.0
	 */
	public function register_metaboxes( callable $display_cb ) {
		$title = __( 'Settings', 'drppsm' );

		/**
		 * Registers secondary options page, and set main item as parent.
		 */
		$args = array(
			'id'           => self::OPTION_KEY,
			'title'        => $title,
			'object_types' => array( 'options-page' ),
			'option_key'   => self::OPTION_KEY,
			'parent_slug'  => AdminSettings::SLUG,
			'tab_group'    => AdminSettings::TAB_GROUP,
			'tab_title'    => 'Advanced',
		);

		// 'tab_group' property is supported in > 2.4.0.
		if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
			$args['display_cb'] = $display_cb;
		}

		$secondary_options = new_cmb2_box( $args );
		$secondary_options->add_field(
			array(
				'name'    => 'Test Radio',
				'desc'    => 'field description (optional)',
				'id'      => 'radio',
				'type'    => 'radio',
				'options' => array(
					'option1' => 'Option One',
					'option2' => 'Option Two',
					'option3' => 'Option Three',
				),
			)
		);
	}
}
