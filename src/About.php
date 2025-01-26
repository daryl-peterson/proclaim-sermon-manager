<?php
/**
 * Plugin about page.
 *
 * @package     DRPPSM\About
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;
use PHP_CodeSniffer\Reports\Report;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin about page.
 *
 * @package     DRPPSM\About
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class About implements Executable, Registrable {

	use ExecutableTrait;

	public function __construct() {
		// Code Here
	}

	public function register(): ?bool {
		if ( ! has_action( 'show_about', array( $this, 'register_about' ) ) ) {
			add_action( 'cmb2_admin_init', array( $this, 'register_about' ) );
			return false;
		}
		return true;
	}

	public function register_about() {
		/**
	 * Metabox to be displayed on a single page ID
	 */
		$cmb_about_page = new_cmb2_box(
			array(
				'id'           => 'about',
				'title'        => esc_html__( 'About Page Metabox', 'cmb2' ),
				'object_types' => array( 'page' ), // Post type
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true, // Show field names on the left
				'show_on'      => array(
					'id' => array( 6 ),
				), // Specific post IDs to display this metabox
			)
		);

		$cmb_about_page->add_field(
			array(
				'name' => esc_html__( 'Test Text', 'cmb2' ),
				'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
				'id'   => 'yourprefix_about_text',
				'type' => 'text',
			)
		);
	}
}
