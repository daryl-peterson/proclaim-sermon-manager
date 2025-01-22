<?php
/**
 * General settings.
 *
 * @package     DRPPSM\SPGeneral
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use CMB2;
use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

/**
 * General settings.
 *
 * @package     DRPPSM\SPGeneral
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SPGeneral extends SPBase implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Key used in storing options.
	 *
	 * @var string
	 */
	public string $option_key = Settings::OPTION_KEY_GENERAL;

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Always true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		$object_type = 'options-page';
		$id          = $this->option_key;

		if ( ! is_admin() || has_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) ) ) {
			return false;
		}

		add_action( Action::SETTINGS_REGISTER_FORM, array( $this, 'register_metaboxes' ) );
		add_action( "cmb2_save_{$object_type}_fields_{$id}", array( $this, 'flush_check' ), 10, 3 );
		return true;
	}

	/**
	 * Check if rewrite rules need to be flushed after cmb save
	 *
	 * @param string     $object_id CMB object id.
	 * @param null|array $updated Updated flash.
	 * @param CMB2       $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	public function flush_check( string $object_id, null|array $updated, CMB2 $cmb ) {

		$check = array(
			'archive_slug',
			'drppsm_preacher',
			'drppsm_stype',
			'preacher_label',
			'service_type_label',
			'common_base_slug',
		);

		$flush = false;
		foreach ( $check as $value ) {

			if ( in_array( $value, $updated, true ) ) {
				$flush = true;
				break;
			}
		}

		if ( $flush ) {
			do_action( Action::REWRITE_FLUSH );
		}
	}

	/**
	 * Register metaboxes.
	 *
	 * @param callable $display_cb Callback to display on form.
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
			'id'           => $this->option_key,
			'title'        => $title,
			'menu_title'   => $menu_title,
			'object_types' => array( 'options-page' ),
			'option_key'   => $this->option_key,
			'parent_slug'  => AdminSettings::SLUG,
			'tab_group'    => AdminSettings::TAB_GROUP,
			'tab_title'    => 'General',
			'display_cb'   => $display_cb,
		);

		$cmb = new_cmb2_box( $args );
		$this->add_seperator( $cmb, __( 'General Settings', 'drppsm' ) );
		$this->add_player( $cmb );
		$this->add_menu_icon( $cmb );
		$this->add_sermon_comments( $cmb );

		$this->add_seperator( $cmb, __( 'Sermon Labels', 'drppsm' ), true );
		$this->add_common_base_slug( $cmb );

		// Preacher labels.
		$this->add_seperator( $cmb, __( 'Preacher Labels', 'drppsm' ), true );
		$this->preacher_single( $cmb );
		$this->preacher_plural( $cmb );

		// Series labels.
		$this->add_seperator( $cmb, __( 'Series Labels', 'drppsm' ), true );
		$this->series_single( $cmb );
		$this->series_plural( $cmb );

		// Service type labels.
		$this->add_seperator(
			$cmb,
			__( 'Service Type Labels', 'drppsm' ),
			true
		);
		$this->service_type_singular( $cmb );
		$this->service_type_plural( $cmb );
	}

	/**
	 * Add audio / video player selection
	 *
	 * @param CMB2 $cmb CMB2 object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_player( CMB2 $cmb ): void {
		$desc = __( 'Select which player to use for playing Sermons.', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::PLAYER,
				'name'             => DRPPSM_SETTINGS_PLAYER_NAME,
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'plyr'         => 'Plyr',
					'mediaelement' => 'Mediaelement',
					'WordPress'    => 'Old WordPress player',
					'none'         => 'Browser HTML5',
				),
				'after_row'        => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add menu icon.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_menu_icon( CMB2 $cmb ): void {
		$desc = __( 'Allows for changing the admin menu icon.', 'drppsm' );
		$cmb->add_field(
			array(
				'id'               => Settings::MENU_ICON,
				'name'             => DRPPSM_SETTINGS_MENU_ICON_NAME,
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => array(
					'dashicons-drppsm-bible'       => __( 'Bible', 'drppsm' ),
					'dashicons-drppsm-bible-alt'   => __( 'Bible Alt', 'drppsm' ),
					'dashicons-drppsm-church'      => __( 'Church', 'drppsm' ),
					'dashicons-drppsm-church-alt'  => __( 'Church', 'drppsm' ),
					'dashicons-drppsm-cross'       => __( 'Cross', 'drppsm' ),
					'dashicons-drppsm-alt'         => __( 'Cross Alt', 'drppsm' ),
					'dashicons-drppsm-fish'        => __( 'Fish', 'drppsm' ),
					'dashicons-drppsm-fish-alt'    => __( 'Fish Alt', 'drppsm' ),
					'dashicons-drppsm-megaphone'   => __( 'Megaphone', 'drppsm' ),
					'dashicons-drppsm-pulpit'      => __( 'Pulpit', 'drppsm' ),
					'dashicons-drppsm-pulpit-alt'  => __( 'Pulpit Alt', 'drppsm' ),
					'dashicons-drppsm-sermon'      => __( 'Sermon', 'drppsm' ),
					'dashicons-drppsm-sermon-inv'  => __( 'Sermon Alt', 'drppsm' ),
					'dashicons-drppsm-holy-spirit' => __( 'Holy Spirit', 'drppsm' ),
				),
				'after_row'        => $this->description( $desc ),
			)
		);
	}



	/**
	 * Add sermon comments field.
	 *
	 * @param CMB2 $cmb  CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_sermon_comments( CMB2 $cmb ): void {
		$cmb->add_field(
			array(
				'id'   => Settings::COMMENTS,
				'name' => DRPPSM_SETTINGS_COMMENTS_NAME,
				'type' => 'checkbox',
			)
		);
	}



	/**
	 * Service type singular label.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function service_type_singular( CMB2 $cmb ) {
		$s1 = '<code>' . __( '/service-type/morning', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/congregation/morning', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_SINGLE . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default service type slug/path. Effectively <code>/service-type/morning</code>.
			// translators: %2$s Example congregation slug/path. Effectively <code>/congreation/morning</code>.
			__( 'Changing "Service Type" to "Congregation" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;

		$cmb->add_field(
			array(
				'id'        => Settings::SERVICE_TYPE_SINGULAR,
				'name'      => __( 'Singular Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Service type plural label.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function service_type_plural( CMB2 $cmb ) {
		$s1 = '<code>' . __( '/service-types/', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/congregations/', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_PLURAL . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default service types slug/path. Effectively <code>/service-types/</code>.
			// translators: %2$s Example congregations slug/path. Effectively <code>/congreations/</code>.
			__( 'Changing "Service Types" to "Congregations" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;

		$cmb->add_field(
			array(
				'id'        => Settings::SERVICE_TYPE_PLURAL,
				'name'      => __( 'Plural Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Add common base slug.
	 *
	 * @param CMB2 $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	private function add_common_base_slug( CMB2 $cmb ): void {

		$desc  = __( 'If this option is checked, the taxonomies would also be under the slug set above.', 'drppsm' );
		$desc .= $this->dot();
		$s1    = '<code>' . __( '/sermons/series/jesus', 'drppsm' ) . '</code>';
		$s2    = '<code>' . __( '/sermons/preacher/mark', 'drppsm' ) . '</code>';

		$desc .= wp_sprintf(
			// translators: %1$s Example series path, effectively <code>/sermons/series/jesus</code>.
			// translators: %2$s Example preacher path, effectively <code>/sermons/preacher/mark</code>.
			__( 'For example, by default, series named “Jesus” would be under %1$s, preacher “Mark” would be under %2$s, and so on.', 'drppsm' ),
			$s1,
			$s2
		);

		$cmb->add_field(
			array(
				'id'        => Settings::COMMON_BASE_SLUG,
				'name'      => __( 'Common Base Slug', 'drppsm' ),
				'type'      => 'checkbox',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Series singular field.
	 * - Allows to give alias to the series taxonomy.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function series_single( CMB2 $cmb ) {
		$s1 = '<code>' . __( '/series/mark', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/listing/mark', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_SINGLE . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default preacher slug/path. Effectively <code>/preacher/mark</code>.
			// translators: %2$s Example reverend slug/path. Effectively <code>/reverend/mark</code>.
			__( 'Changing "Series" to "Listing" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;

		$cmb->add_field(
			array(
				'id'        => Settings::SERIES_SINGULAR,
				'name'      => __( 'Singular Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Series plural field.
	 * - Allows to give alias to the series taxonomy.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function series_plural( CMB2 $cmb ) {
		$s1 = '<code>' . __( '/series/', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/listings/', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_PLURAL . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default series slug/path. Effectively <code>/series/</code>.
			// translators: %2$s Example listings slug/path. Effectively <code>/listings/</code>.
			__( 'Changing "Series" to "Listings" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;

		$cmb->add_field(
			array(
				'id'        => Settings::SERIES_PLURAL,
				'name'      => __( 'Plural Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Preacher singular field.
	 * - Allows to give alias to the preacher taxonomy.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function preacher_single( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/preacher/mark', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/reverend/mark', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_SINGLE . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default preacher slug/path. Effectively <code>/preacher/mark</code>.
			// translators: %2$s Example reverend slug/path. Effectively <code>/reverend/mark</code>.
			__( 'Changing "Preacher" to "Reverend" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;

		$cmb->add_field(
			array(
				'id'        => Settings::PREACHER_SINGULAR,
				'name'      => __( 'Singular Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}

	/**
	 * Preacher plural field.
	 * - Allows to give alias to the preachers taxonomy.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function preacher_plural( CMB2 $cmb ): void {
		$s1 = '<code>' . __( '/preachers/', 'drppsm' ) . '</code>';
		$s2 = '<code>' . __( '/reverends/', 'drppsm' ) . '</code>';

		$desc  = DRPPSM_MSG_LABEL_PLURAL . '<br>';
		$desc .= wp_sprintf(
			// translators: %1$s Default preachers slug/path. Effectively <code>/preachers/</code>.
			// translators: %2$s Example reverends slug/path. Effectively <code>/reverends/</code>.
			__( 'Changing "Preacher" to "Reverend" would result in %1$s becoming %2$s.', 'drppsm' ),
			$s1,
			$s2
		);
		$desc .= '<br>' . DRPPSM_MSG_SLUG_NOTE;
		$cmb->add_field(
			array(
				'id'        => Settings::PREACHER_PLURAL,
				'name'      => __( 'Plural Label', 'drppsm' ),
				'type'      => 'text',
				'after_row' => $this->description( $desc ),
			)
		);
	}
}
