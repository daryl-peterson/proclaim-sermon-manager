<?php
/**
 * Sermon files meta boxes.
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
use DRPPSM\Constants\Meta;
use DRPPSM\Constants\PT;
use DRPPSM\Interfaces\Initable;
use DRPPSM\Interfaces\Registrable;

/**
 * Sermon files meta boxes.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonFiles implements Initable, Registrable {

	/**
	 * Get initialized object.
	 *
	 * @return SermonFiles
	 * @since 1.0.0
	 */
	public static function init(): SermonFiles {
		return new self();
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Returns true if hooks were registered.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( ! is_admin() || has_action( Actions::SERMON_EDIT_FORM, array( $this, 'show' ) ) ) {
			return false;
		}
		add_action( Actions::SERMON_EDIT_FORM, array( $this, 'show' ) );
		return true;
	}

	/**
	 * Show meta box.
	 *
	 * @return bool Return true.
	 * @since 1.0.0
	 */
	public function show(): bool {

		$post_type = PT::SERMON;

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
				'name' => esc_html__( 'Location of MP3', 'drppsm' ),
				'desc' => esc_html__( 'Upload an audio file or enter an URL.', 'drppsm' ),
				'id'   => Meta::AUDIO,
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
				'id'   => Meta::DURATION,
				'type' => 'text',
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Video Embed Code', 'drppsm' ),
				'desc' => esc_html__( 'Paste your embed code for Vimeo, Youtube, Facebook, or direct video file here', 'drppsm' ),
				'id'   => Meta::VIDEO,
				'type' => 'textarea_code',
			)
		);
		$cmb->add_field(
			array(
				'name' => esc_html__( 'Video Link', 'drppsm' ),
				'desc' => esc_html__( 'Paste your link for Vimeo, Youtube, Facebook, or direct video file here', 'drppsm' ),
				'id'   => Meta::VIDEO_LINK,
				'type' => 'text_url',
			)
		);

		$cmb->add_field(
			array(
				'name'       => esc_html__( 'Sermon Notes', 'drppsm' ),
				'desc'       => esc_html__( 'Upload  pdf files.', 'drppsm' ),
				'id'         => Meta::NOTES,
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
				'id'   => Meta::BULLETIN,
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
