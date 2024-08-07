<?php
/**
 * Taxonomy Registration.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Exceptions\PluginException;
use DRPPSM\Helper;
use DRPPSM\Interfaces\TaxonomyRegInt;
use DRPPSM\Logging\Logger;

/**
 * Taxonomy Registration.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class TaxonomyReg implements TaxonomyRegInt {

	/**
	 * Taxonomy.
	 *
	 * @var string
	 */
	protected string $taxonomy;

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	protected string $post_type;

	/**
	 * Congifurage file to read.
	 *
	 * @var string
	 */
	protected string $config_file;

	/**
	 * Initialize object properties.
	 *
	 * @param string $taxonomy Taxonomomy name.
	 * @param string $post_type Post type name.
	 * @param string $config_file Configuration file.
	 *
	 * @since 1.0.0
	 */
	public function __construct( string $taxonomy, string $post_type, string $config_file ) {
		$this->taxonomy    = $taxonomy;
		$this->post_type   = $post_type;
		$this->config_file = $config_file;
	}

	/**
	 * Add Taxonomy
	 *
	 * @return void
	 * @throws PluginException Thow exception if not exist.
	 *
	 * @since 1.0.0
	 */
	public function add(): void {
		$exist  = $this->exist();
		$result = false;

		if ( ! is_blog_installed() || $exist ) {
			return;
		}

		try {
			$def    = Helper::get_config( $this->config_file );
			$result = register_taxonomy( $this->taxonomy, array( $this->post_type ), $def );
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}

		if ( ! $this->exist() || is_wp_error( $result ) ) {
			// @codeCoverageIgnoreStart
			$message = 'Failed to add taxonomy ' . $this->taxonomy;
			if ( is_wp_error( $result ) ) {
				$message = $this->get_wp_error_message( $result );
			}
			throw new PluginException( esc_html( $message ) );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Remove taxonomy.
	 *
	 * @return void
	 * @throws PluginException Thow exception if not exist.

	 * @since 1.0.0
	 */
	public function remove(): void {
		$exist = $this->exist();

		if ( ! is_blog_installed() || ( ! $exist ) ) {
			return;
		}

		try {
			$result = unregister_taxonomy( $this->taxonomy );
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			Logger::error(
				array(
					'MESSAGE' => $th->getMessage(),
					'TRACE'   => $th->getTrace(),
				)
			);
			// @codeCoverageIgnoreEnd
		}

		if ( $this->exist() || is_wp_error( $result ) ) {
			// @codeCoverageIgnoreStart
			$message = 'Failed to remove taxonomy : ' . $this->taxonomy;
			if ( is_wp_error( $result ) ) {
				$message = $this->get_wp_error_message( $result );
			}
			Logger::error( array( $this, $message ) );
			throw new PluginException( esc_html( $message ) );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Check if taxonomy exist.
	 *
	 * @return boolean
	 *
	 * @since 1.0.0
	 */
	public function exist(): bool {
		return taxonomy_exists( $this->taxonomy );
	}

	/**
	 * Get WP Error message.
	 *
	 * @param \WP_Error $error WP Error.
	 * @return string Error message.
	 *
	 * @since 1.0.0
	 */
	public function get_wp_error_message( \WP_Error $error ): string {
		return $error->get_error_message();
	}
}
