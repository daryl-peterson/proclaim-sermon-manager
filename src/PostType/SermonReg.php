<?php
/**
 * Sermon post type registration.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager\PostType;

use DRPSermonManager\Constants\PT;
use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Helper;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Logging\Logger;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

/**
 * Sermon post type registration.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class SermonReg implements PostTypeRegInt {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected string $pt;

	/**
	 * Congifurage file to read.
	 *
	 * @var string
	 */
	protected string $config_file;

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->pt          = PT::SERMON;
		$this->config_file = 'post-type-sermon.php';
	}

	/**
	 * Get initialize object.
	 *
	 * @return SermonReg
	 * @since 1.0.0
	 */
	public static function init(): SermonReg {
		$obj = new self();

		return $obj;
	}

	/**
	 * Add post type.
	 *
	 * @return void
	 * @throws PluginException Throws exception if post type is not removed.
	 *
	 * @since 1.0.0
	 */
	public function add(): void {
		$exist = $this->exist();
		if ( ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			if ( ! is_blog_installed() || $exist ) {
				return;
			}
			// @codeCoverageIgnoreEnd
		}

		try {
			$def = Helper::get_config( $this->config_file );
			Logger::debug( $def );
			$result = register_post_type( $this->pt, $def );
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
			$message = __( 'Failed to add post type : ', 'drpsermon' ) . $this->pt;
			if ( is_wp_error( $result ) ) {
				$message = $this->get_wp_error_message( $result );
			}
			throw new PluginException( esc_html( $message ) );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Remove post type.
	 *
	 * @return void
	 * @throws PluginException Throws exception if post type is not removed.
	 *
	 * @since 1.0.0
	 */
	public function remove(): void {
		$exist = $this->exist();
		if ( ! defined( 'PHPUNIT_TESTING' ) ) {
			// @codeCoverageIgnoreStart
			if ( ! is_blog_installed() || ( ! $exist ) ) {
				return;
			}
			// @codeCoverageIgnoreEnd
		}

		try {
			$result = unregister_post_type( $this->pt );
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
			$message = __( 'Failed to remove post type : ', 'drpsermon' ) . $this->pt;
			if ( is_wp_error( $result ) ) {
				$message = $this->get_wp_error_message( $result );
			}
			throw new PluginException( esc_html( $message ) );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Check if post type exist.
	 *
	 * @return boolean True if post type exist, false if not.
	 * @since 1.0.0
	 */
	public function exist(): bool {
		return post_type_exists( $this->pt );
	}

	/**
	 * Get WP_Error message.
	 *
	 * @param \WP_Error $error WP Error.
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_wp_error_message( \WP_Error $error ): string {
		return $error->get_error_message();
	}
}
