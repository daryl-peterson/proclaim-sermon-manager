<?php
/**
 * Sermon Settings.
 *
 * @package     DRPPSM\SPSermon
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use CMB2;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

defined( 'ABSPATH' ) || exit;

/**
 * Sermon Settings.
 *
 * @package     DRPPSM\SPSermon
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SPSermon extends SPBase implements Executable, Registrable {

	/**
	 * Key used in storing options.
	 *
	 * @var string
	 */
	public string $option_key = Settings::OPTION_KEY_SERMONS;

	/**
	 * Initailize and register hooks.
	 *
	 * @return self
	 * @since 1.0.0
	 */
	public static function exec(): self {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Register metaboxes.
	 *
	 * @param callable $display_cb Callback to display on form.
	 * @return void
	 * @since 1.0.0
	 */
	public function register(): ?bool {

		if ( ! is_admin() || has_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) ) ) {
			return false;
		}

		add_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_filter( Filter::SETTINGS_REMOVE_SUBMENU, array( $this, 'set_menu' ) );
		return true;
	}

	/**
	 * Register metaboxes.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_metaboxes( callable $display_cb ): void {
		$menu_title = __( 'Settings', 'drppsm' );
		$title      = 'Proclaim ' . __( 'Sermon Manager Settings', 'drppsm' );

		/**
		 * Registers main options page menu item and form.
		 */
		$args = array(
			'id'           => Settings::OPTION_KEY_SERMONS,
			'title'        => $title,
			'menu_title'   => $menu_title,
			'object_types' => array( 'options-page' ),
			'option_key'   => Settings::OPTION_KEY_SERMONS,
			'parent_slug'  => AdminSettings::SLUG,
			'tab_group'    => AdminSettings::TAB_GROUP,
			'tab_title'    => 'Sermons',
			'display_cb'   => $display_cb,
		);

		$cmb = new_cmb2_box( $args );
		$this->add_seperator( $cmb, __( 'Sermon Settings', 'drppsm' ) );
		$this->date_format( $cmb );
		$this->sermon_count( $cmb );
		$this->sermon_single( $cmb );
		$this->sermon_plural( $cmb );
	}

	/**
	 * Add date format field.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function date_format( CMB2 $cmb ): void {
		$desc = __( 'Used only in admin area, when creating a new Sermon', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::DATE_FORMAT,
				'name'             => __( 'Sermon Date Format', 'drppsm' ),
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'mm/dd/YY' => 'mm/dd/YY',
					'dd/mm/YY' => 'dd/mm/YY',
					'YY/mm/dd' => 'YY/mm/dd',
					'YY/dd/mm' => 'YY/dd/mm',
				),
				'after_row'        => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add sermon count field.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function sermon_count( CMB2 $cmb ): void {
		$desc = __( 'Affects only the default number, other settings will override it', 'drppsm' );
		$cmb->add_field(
			array(
				'id'         => Settings::SERMON_COUNT,
				'name'       => __( 'Sermons Per Page', 'drppsm' ),
				'type'       => 'text',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
				),
				'after_row'  => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add sermon single label.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function sermon_single( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/sermon/mark', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/lecture/mark', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_SINGLE . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default sermon slug/path. Effectively <code>/sermon/mark</code>.
			// translators: %2$s Example lecture slug/path. Effectively <code>/lecture/mark</code>.
			__( 'Changing "Sermon" to "Lecture" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;

		$cmb->add_field(
			array(
				'id'        => Settings::SERMON_SINGULAR,
				'name'      => __( 'Singular Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add sermon plural label.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function sermon_plural( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/sermons/', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/lectures/', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_PLURAL . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default series slug/path. Effectively <code>/sermons/</code>.
			// translators: %2$s Example listings slug/path. Effectively <code>/lectures/</code>.
			__( 'Changing "Sermons" to "Lectures" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;

		$cmb->add_field(
			array(
				'id'        => Settings::SERMON_PLURAL,
				'name'      => __( 'Plural Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}
}
