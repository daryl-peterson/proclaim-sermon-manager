<?php

namespace DRPPSM;

use DRPPSM\Constants\Actions;
use DRPPSM\Constants\Filters;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * Class description
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class OptAdvance implements Initable, Registrable {

	public const OPTION_KEY = 'drppsm_options_adv';

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
	 * Register callbacks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_action( Actions::REGISTER_SETTINGS_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( Filters::OPTIONS_HIDDEN_MENUS, array( $this, 'set_menu' ) );
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
