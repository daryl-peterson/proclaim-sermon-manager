<?php
/**
 * Sermon files meta boxes.
 *
 * @package     DRPPSM\SermonFiles
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Sermon files meta boxes.
 *
 * @package     DRPPSM\SermonFiles
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 *
 * @todo Allow for custom ordering of fields.
 */
class SermonFiles implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Returns true if hooks were registered.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( ! is_admin() || has_action( Action::SERMON_EDIT_FORM, array( $this, 'show' ) ) ) {
			return false;
		}
		add_action( Action::SERMON_EDIT_FORM, array( $this, 'show' ) );
		return true;
	}

	/**
	 * Show meta box.
	 *
	 * @return bool Return true.
	 * @since 1.0.0
	 */
	public function show(): bool {

		$post_type = DRPPSM_PT_SERMON;

		$cmb = \new_cmb2_box(
			array(
				'id'           => 'drppsm_files',
				'title'        => esc_html__( 'Sermon Files', 'drppsm' ),
				'object_types' => array( $post_type ),
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
			)
		);

		$cmb->add_field(
			array(
				'name' => esc_html__( 'Video Link', 'drppsm' ),
				'desc' => esc_html__( 'Paste your link for Vimeo, Youtube, Facebook, or direct video file here', 'drppsm' ),
				'id'   => SermonMeta::VIDEO_LINK,
				'type' => 'text_url',
			)
		);

		$cmb->add_field(
			array(
				'name' => esc_html__( 'Video Embed Code', 'drppsm' ),
				'desc' => esc_html__( 'Paste your embed code for Vimeo, Youtube, Facebook, or direct video file here', 'drppsm' ),
				'id'   => SermonMeta::VIDEO,
				'type' => 'textarea_code',
			)
		);

		$cmb->add_field(
			array(
				'name' => esc_html__( 'Location of MP3', 'drppsm' ),
				'desc' => esc_html__( 'Upload an audio file or enter an URL.', 'drppsm' ),
				'id'   => SermonMeta::AUDIO,
				'type' => 'file',
				'text' => array(
					'add_upload_file_text' => 'Add Sermon Audio', // Change upload button text. Default: "Add or Upload File".
				),
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'MP3 Duration', 'drppsm' ),
				'desc' => wp_sprintf(
					// translators: %s see msgid "hh:mm:ss", effectively <code>hh:mm:ss</code>.
					esc_html__( 'Length in %s format (fill out only for remote files, local files will get data calculated by default)', 'drppsm' ),
					'<code>' . esc_html__( 'hh:mm:ss', 'drppsm' ) . '</code>'
				),
				'id'   => SermonMeta::DURATION,
				'type' => 'text',
			)
		);

		$cmb->add_field(
			array(
				'name'       => esc_html__( 'Sermon Notes', 'drppsm' ),
				'desc'       => esc_html__( 'Upload  pdf files.', 'drppsm' ),
				'id'         => SermonMeta::NOTES,
				'type'       => 'file_list',
				'text'       => array(
					'add_upload_file_text' => esc_html__( 'Add File', 'drppsm' ),
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
				'name' => esc_html__( 'Bulletin', 'drppsm' ),
				'desc' => esc_html__( 'Upload pdf files.', 'drppsm' ),
				'id'   => SermonMeta::BULLETIN,
				'type' => 'file_list',
				'text' => array(
					'add_upload_file_text' => esc_html__( 'Add File', 'drppsm' ),
					// Change upload button text. Default: "Add or Upload File".
				),
			)
		);

		return true;
	}
}
