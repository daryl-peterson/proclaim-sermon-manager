<?php
/**
 * Show sermon deail meta box.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use CMB2;
use DRPPSM\Constants\Actions;
use DRPPSM\Constants\Meta;
use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Logging\Logger;
use WP_Error;
use WP_Term;

/**
 * Show sermon deail meta box.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonDetail implements Initable, Registrable {


	/**
	 * Post type.
	 *
	 * @var string
	 */
	private string $pt;

	/**
	 * CMB2 id.
	 *
	 * @var string
	 */
	private string $cmb_id;


	/**
	 * Initialize object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt     = PT::SERMON;
		$this->cmb_id = 'drppsm_details';
	}

	/**
	 * Get initialize object.
	 *
	 * @return SermonDetail
	 * @since 1.0.0
	 */
	public static function init(): SermonDetail {
		return new self();
	}

	/**
	 * Register callbacks.
	 *
	 * @return boolean|null Returns true.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		add_action( Actions::SERMON_EDIT_FORM, array( $this, 'show' ) );

		$pt = 'post';
		// add_action( 'cmb2_init', array( $this, 'cmb_init' ) );
		add_action( "cmb2_save_{$pt}_fields_{$this->cmb_id}", array( $this, 'save' ), 10, 3 );
		return true;
	}

	/**
	 * Show sermon detail controls.
	 *
	 * @return bool Return true.
	 * @since 1.0.0
	 */
	public function show(): bool {

		/**
		 * Initiate the metabox.
		 */
		$cmb = \new_cmb2_box(
			array(
				'id'           => $this->cmb_id,
				'title'        => __( 'Sermon Details', 'drppsm' ),
				'object_types' => array( $this->pt ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$this->add_date_preached( $cmb );
		$this->add_service_type( $cmb );
		$this->add_bible_passage( $cmb );
		$this->add_description( $cmb );
		return true;
	}


	/**
	 * Fires after save. Used to seto object terms.
	 *
	 * @param int   $post_ID Post ID.
	 * @param array $updated List of fields that were updated.
	 * @param CMB2  $cmb CMB2 Object.
	 * @return void
	 * @since 1.0.0
	 */
	public function save( int $post_ID, array $updated, CMB2 $cmb ): void {

		if ( PT::SERMON !== get_post_type() ) {
			return;
		}

		$result = $this->save_service_type( $post_ID, $cmb->data_to_save );
	}

	/**
	 * Save service type.
	 *
	 * @param integer $post_ID Post ID
	 * @param array   $data Data array
	 * @return bool
	 * @since 1.0.0
	 */
	private function save_service_type( int $post_ID, array $data ): bool {

		$term = get_term_by(
			'id',
			sanitize_text_field( $data[ Tax::SERVICE_TYPE ] ),
			Tax::SERVICE_TYPE
		);

		if ( $term ) {
			$service_type = $term->slug;
		}

		$result = wp_set_object_terms( $post_ID, empty( $service_type ) ? null : $service_type, Tax::SERVICE_TYPE );
		if ( $result instanceof WP_Error ) {
			Logger::error( $result->get_error_message() );
			return false;
		}

		// wp_remove_object_terms()
		return true;
	}

	/**
	 * Add date preached field.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_date_preached( CMB2 $cmb ): void {
		// @codeCoverageIgnoreStart
		switch ( get_setting( 'date_format', '' ) ) {
			case '0':
				$date_format_label = 'mm/dd/YYYY';
				$date_format       = 'm/d/Y';
				break;
			case '1':
				$date_format_label = 'dd/mm/YYYY';
				$date_format       = 'd/m/Y';
				break;
			case '2':
				$date_format_label = 'YYYY/mm/dd';
				$date_format       = 'Y/m/d';
				break;
			case '3':
				$date_format_label = 'YYYY/dd/mm';
				$date_format       = 'Y/d/m';
				break;
			default:
				$date_format_label = 'mm/dd/YYYY';
				$date_format       = 'm/d/Y';
				break;
		}
		// @codeCoverageIgnoreEnd

		/**
		 * Date preached.
		 */
		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Date Preached', 'drppsm' ),
				// translators: %1 Date preached.
				'desc'         => '<br>' . wp_sprintf( esc_html__( 'format: %s', 'drppsm' ), $date_format_label ),
				'id'           => Meta::DATE,
				'type'         => 'text_date_timestamp',
				'date_format'  => $date_format,
				'autocomplete' => 'off',
			)
		);
	}

	/**
	 * Add service type field.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_service_type( CMB2 $cmb ): void {
		/**
		 * Service Type.
		 */
		$cmb->add_field(
			array(
				'name'             => TaxUtils::get_taxonomy_field( Tax::SERVICE_TYPE, 'singular_name' ),
				'desc'             => wp_sprintf(
					// translators: %1$s The singular label. Default Service Type.
					// translators: %2$s The plural label. Default Service Types.
					// translators: %3$s <a href="edit-tags.php?taxonomy=drppsm_service_type&post_type=drppsm" target="_blank">here</a>.
					esc_html__( 'Select the %1$s. Modify the %2$s %3$s.', 'drppsm' ),
					strtolower( TaxUtils::get_taxonomy_field( Tax::SERVICE_TYPE, 'singular_name' ) ),
					strtolower( TaxUtils::get_taxonomy_field( Tax::SERVICE_TYPE, 'label' ) ),
					'<a href="' . admin_url( 'edit-tags.php?taxonomy=drppsm_service_type&post_type=drppsm' ) . '" target="_blank">here</a>'
				),
				'id'               => Tax::SERVICE_TYPE,
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => TaxUtils::get_term_options( Tax::SERVICE_TYPE ),
			)
		);
	}

	/**
	 * Add bible passage field.
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_bible_passage( CMB2 $cmb ): void {
		$meta = Meta::BIBLE_PASSAGE;

		$desc = wp_sprintf(
			// translators: %1$s Bible book.
			// translators: %2$s The plural label. Bible books.
			esc_html__( 'Enter the Bible passage with the full book names, e.g. %1$s Or multiple books like %2$s', 'drppsm' ),
			'<code>' . __( 'John 3:16-18', 'drppsm' ) . '</code><br>',
			'<code>' .
				__( 'John 3:16-18, Luke 2:1-3', 'drppsm' ) .
			'</code>'
		);
		$cmb->add_field(
			array(
				'name' => __( 'Main Bible Passage', 'drppsm' ),
				'desc' => $desc,
				'id'   => $meta,
				'type' => 'text',
			)
		);
	}

	/**
	 * Add description field
	 *
	 * @param CMB2 $cmb
	 * @return void
	 * @since 1.0.0
	 */
	private function add_description( CMB2 $cmb ): void {

		/**
		 * Description meta.
		 */
		$meta = Meta::DESCRIPTION;
		$cmb->add_field(
			array(
				'id'      => $meta,
				'name'    => __( 'Description', 'drppsm' ),
				'desc'    => __(
					'Type a brief description about this sermon, an outline, or a full manuscript',
					'drppsm'
				),
				'type'    => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 7,
					'media_buttons' => true,
				),
			)
		);
	}
}
