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

use DRPPSM\Constants\Meta;
use DRPPSM\Constants\PT;
use DRPPSM\Constants\Tax;
use DRPPSM\Interfaces\OptionsInt;

/**
 * Show sermon deail meta box.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonDetail {

	/**
	 * Options interface
	 *
	 * @var OptionsInt
	 */
	private OptionsInt $options;

	/**
	 * Initialize object.
	 *
	 * @param OptionsInt $options Options interface.
	 * @since 1.0.0
	 */
	public function __construct( OptionsInt $options ) {
		$this->options = $options;
	}

	/**
	 * Show sermon detail controls.
	 *
	 * @return bool Return true.
	 * @since 1.0.0
	 */
	public function show(): bool {
		$options = $this->options;

		// @codeCoverageIgnoreStart
		switch ( $options->get( 'date_format', '' ) ) {
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

		$post_type = PT::SERMON;

		/**
		 * Initiate the metabox.
		 */
		$cmb = \new_cmb2_box(
			array(
				'id'           => 'drppsm_details',
				'title'        => __( 'Sermon Details', 'drppsm' ),
				'object_types' => array( $post_type ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

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
				'id'               => Meta::SERVICE_TYPE,
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => TaxUtils::get_term_options( Tax::SERVICE_TYPE ),
			)
		);

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
				'id'   => 'bible_passage',
				'type' => 'text',
			)
		);

		/**
		 * Description meta.
		 */
		$meta = Meta::DESCRIPTION;
		$cmb->add_field(
			array(
				'name'    => __( 'Description', 'drppsm' ),
				'desc'    => __(
					'Type a brief description about this sermon, an outline, or a full manuscript',
					'drppsm'
				),
				'id'      => $meta,
				'type'    => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 7,
					'media_buttons' => true,
				),
			)
		);
		return true;
	}
}
