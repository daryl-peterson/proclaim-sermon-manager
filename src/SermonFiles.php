<?php
/**
 * Sermon files meta boxes.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Constants\Meta;
use DRPSermonManager\Constants\PT;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Sermon files meta boxes.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonFiles {

	/**
	 * Show meta box.
	 */
	public function show(): void {

		$post_type = PT::SERMON;

		$cmb = \new_cmb2_box(
			array(
				'id'           => 'drpsermon_files',
				'title'        => esc_html__( 'Sermon Files', 'drpsermon' ),
				'object_types' => array( $post_type ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Location of MP3', 'drpsermon' ),
				'desc' => esc_html__( 'Upload an audio file or enter an URL.', 'drpsermon' ),
				'id'   => Meta::AUDIO,
				'type' => 'file',
				'text' => array(
					'add_upload_file_text' => 'Add Sermon Audio', // Change upload button text. Default: "Add or Upload File".
				),
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'MP3 Duration', 'drpsermon' ),
				// translators: %s see msgid "hh:mm:ss", effectively <code>hh:mm:ss</code>.
				'desc' => wp_sprintf(
					esc_html__( 'Length in %s format (fill out only for remote files, local files will get data calculated by default)', 'drpsermon' ),
					'<code>' . esc_html__( 'hh:mm:ss', 'drpsermon' ) . '</code>'
				),
				'id'   => Meta::DURATION,
				'type' => 'text',
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Video Embed Code', 'drpsermon' ),
				'desc' => esc_html__( 'Paste your embed code for Vimeo, Youtube, Facebook, or direct video file here', 'drpsermon' ),
				'id'   => Meta::VIDEO,
				'type' => 'textarea_code',
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Video Link', 'drpsermon' ),
				'desc' => esc_html__( 'Paste your link for Vimeo, Youtube, Facebook, or direct video file here', 'drpsermon' ),
				'id'   => Meta::VIDEO_LINK,
				'type' => 'text_url',
			)
		);

		$cmb->add_field(
			array(
				'name'       => esc_html__( 'Sermon Notes', 'drpsermon' ),
				'desc'       => esc_html__( 'Upload  pdf files.', 'drpsermon' ),
				'id'         => Meta::NOTES,
				'type'       => 'file_list',
				'text'       => array(
					'add_upload_file_text' => esc_html__( 'Add File', 'drpsermon' ),
					// Change upload button text. Default: "Add or Upload File".
				),
				'query_args' => array(
					'type' => 'application/pdf', // Make library only display PDFs.
				),
				// 'sanitization_cb' => array( $this, 'sanitize_pdf' ) Not using yet.
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Bulletin', 'drpsermon' ),
				'desc' => esc_html__( 'Upload pdf files.', 'drpsermon' ),
				'id'   => Meta::BULLETIN,
				'type' => 'file_list',
				'text' => array(
					'add_upload_file_text' => esc_html__( 'Add File', 'drpsermon' ),
					// Change upload button text. Default: "Add or Upload File".
				),
			)
		);
	}
}
