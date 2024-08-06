<?php
/**
 * Show sermon deail meta box.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\Meta;
use DRPSermonManager\Constants\PT;
use DRPSermonManager\Constants\Tax;
use DRPSermonManager\Interfaces\OptionsInt;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Show sermon deail meta box.
 */
class SermonDetail {

	/**
	 * Options interface
	 *
	 * @var OptionsInt
	 */
	private OptionsInt $options;

	public function __construct( OptionsInt $options ) {
		$this->options = $options;
	}

	public function show(): void {
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
				'id'           => 'drpsermon_details',
				'title'        => __( 'Sermon Details', 'drpsermon' ),
				'object_types' => array( $post_type ), // Post type
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,           // Show field names on the left
			// 'cmb_styles' => false,       // false to disable the CMB stylesheet
			// 'closed'     => true,        // Keep the metabox closed by default
			)
		);

		/**
		 * Date preached.
		 */
		$cmb->add_field(
			array(
				'name'         => esc_html__( 'Date Preached', 'drpsermon' ),
				'desc'         => '<br>' . wp_sprintf( esc_html__( 'format: %s', 'drpsermon' ), $date_format_label ),
				'id'           => Meta::DATE,
				'type'         => 'text_date_timestamp',
				'date_format'  => $date_format,
				'autocomplete' => 'off',
			)
		);

		// Service Type
		$cmb->add_field(
			array(
				'name'             => TaxUtils::get_taxonomy_field( Tax::SERVICE_TYPE, 'singular_name' ),
				// translators: %1$s The singular label. Default Service Type.
				// translators: %2$s The plural label. Default Service Types.
				// translators: %3$s <a href="edit-tags.php?taxonomy=drpsermon_service_type&post_type=drpsermon" target="_blank">here</a>.
				'desc'             => wp_sprintf(
					esc_html__( 'Select the %1$s. Modify the %2$s %3$s.', 'drpsermon' ),
					strtolower( TaxUtils::get_taxonomy_field( Tax::SERVICE_TYPE, 'singular_name' ) ),
					strtolower( TaxUtils::get_taxonomy_field( Tax::SERVICE_TYPE, 'label' ) ),
					'<a href="' . admin_url( 'edit-tags.php?taxonomy=drpsermon_service_type&post_type=drpsermon' ) . '" target="_blank">here</a>'
				),
				'id'               => Meta::SERVICE_TYPE,
				'type'             => 'select',
				'show_option_none' => true,
				'options'          => TaxUtils::get_term_options( Tax::SERVICE_TYPE ),
			)
		);

		$meta = Meta::BIBLE_PASSAGE;
		// translators: %1$s Bible book.
		// translators: %2$s The plural label. Bible books.
		$desc = wp_sprintf(
			esc_html__( 'Enter the Bible passage with the full book names, e.g. %1$s Or multiple books like %2$s', 'drpsermon' ),
			'<code>' . esc_html__( 'John 3:16-18', 'drpsermon' ) . '</code><br>',
			'<code>' .
			esc_html( 'John 3:16-18, Luke 2:1-3', 'drpsermon' ) .
			'</code>'
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Main Bible Passage', 'drpsermon' ),
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
				'name'    => esc_html__( 'Description', 'drpsermon' ),
				'desc'    => esc_html__(
					'Type a brief description about this sermon, an outline, or a full manuscript',
					'drpsermon'
				),
				'id'      => $meta,
				'type'    => 'wysiwyg',
				'options' => array(
					'textarea_rows' => 7,
					'media_buttons' => true,
				),
			)
		);

		// Add other metaboxes as needed
	}
}
