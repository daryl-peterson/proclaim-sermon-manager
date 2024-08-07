<?php
/**
 * Sermon images
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

/**
 * Sermon images
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonImage {


	public static function init() {
		return new self();
	}

	public function register() {
		add_action( 'init', array( $this, 'sermon_image_plugin_add_image_size' ) );
		add_filter( 'attachment_fields_to_edit', array( $this, 'sermon_image_plugin_modal_button' ), 20, 2 );
	}
	/**
	 * Get a url to a file in this plugin.
	 *
	 * @return    string
	 * @access    private
	 * @since     0.7
	 */
	public function sermon_image_plugin_url( $file = '' ) {
		static $path = '';
		if ( empty( $path ) ) {
			$path = plugin_dir_url( __FILE__ );
		}

		return $path . $file;
	}

	/**
	 * Detail Image Size.
	 *
	 * @return    array     Configuration for the "detail" image size.
	 * @access    private
	 * @since     0.7
	 */
	public function sermon_image_plugin_detail_image_size() {
		return array(
			'name' => 'detail',
			'size' => array( 75, 75, true ),
		);
	}


	/**
	 * Register custom image size with WordPress.
	 *
	 * @access    private
	 * @since     2010-10-28
	 */
	public function sermon_image_plugin_add_image_size() {
		$detail = sermon_image_plugin_detail_image_size();
		add_image_size(
			$detail['name'],
			$detail['size'][0],
			$detail['size'][1],
			$detail['size'][2]
		);
	}

	/**
	 * Modal Button.
	 *
	 * Create a button in the modal media window to associate the current image to the term.
	 *
	 * @param     array     Multidimensional array representing the images form.
	 * @param     stdClass  WordPress post object.
	 *
	 * @return    array     The image's form array with added button if modal window was accessed by this script.
	 *
	 * @access    private
	 * @since     2010-10-28
	 * @alter     0.7
	 */
	public function sermon_image_plugin_modal_button( $fields, $post ) {
		if ( isset( $fields['image-size'] ) && isset( $post->ID ) ) {
			$image_id = (int) $post->ID;

			$o  = '<div class="sermon-image-modal-control" id="' . esc_attr( 'sermon-image-modal-control-' . $image_id ) . '">';
			$o .= '<span class="button create-association">' . wp_sprintf( esc_html__( 'Associate with %1$s', 'sermon-manager-for-wordpress' ), '<span class="term-name">' . esc_html__( 'this term', 'sermon-manager-for-wordpress' ) . '</span>' ) . '</span>';
			$o .= '<span class="remove-association">' . wp_sprintf( esc_html__( 'Remove association with %1$s', 'sermon-manager-for-wordpress' ), '<span class="term-name">' . esc_html__( 'this term', 'sermon-manager-for-wordpress' ) . '</span>' ) . '</span>';
			$o .= '<input class="sermon-image-button-image-id" name="' . esc_attr( 'sermon-image-button-image-id-' . $image_id ) . '" type="hidden" value="' . esc_attr( $image_id ) . '" />';
			$o .= '<input class="sermon-image-button-nonce-create" name="' . esc_attr( 'sermon-image-button-nonce-create-' . $image_id ) . '" type="hidden" value="' . esc_attr( wp_create_nonce( 'sermon-image-plugin-create-association' ) ) . '" />';
			$o .= '<input class="sermon-image-button-nonce-remove" name="' . esc_attr( 'sermon-image-button-nonce-remove-' . $image_id ) . '" type="hidden" value="' . esc_attr( wp_create_nonce( 'sermon-image-plugin-remove-association' ) ) . '" />';
			$o .= '</div>';

			$fields['image-size']['extra_rows']['sermon-image-plugin-button']['html'] = $o;
		}

		return $fields;
	}
}
