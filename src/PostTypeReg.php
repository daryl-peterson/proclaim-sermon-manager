<?php
/**
 * Post type registration abstract.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Exceptions\PluginException;
use DRPSermonManager\Helper;
use DRPSermonManager\Interfaces\PostTypeRegInt;
use DRPSermonManager\Logging\Logger;

/**
 * Post type registration.
 *
 * @package     Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class PostTypeReg implements PostTypeRegInt {

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
	 * Initialize object.
	 *
	 * @param string $post_type Post type.
	 * @param string $config_file Config file.
	 *
	 * @since 1.0.0
	 */
	public function __construct( string $post_type, string $config_file ) {
		$this->pt          = $post_type;
		$this->config_file = $config_file;
	}

	/**
	 * Add post type.
	 *
	 * @since 1.0.0
	 *
	 * @throws PluginException Throws exception on failure.
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
			$def    = Helper::get_config( $this->config_file );
			$result = register_post_type( $this->pt, $def );
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			throw new PluginException( $th->getMessage(), $th->getCode(), $th );
			// @codeCoverageIgnoreEnd
		}

		if ( ! $this->exist() || is_wp_error( $result ) ) {
			// @codeCoverageIgnoreStart
			$message = __( 'Failed to add post type : ', 'drpsermon' ) . $this->pt;
			if ( is_wp_error( $result ) ) {
				$message = $result->get_error_message();
			}
			throw new PluginException( $message );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Remove post type.
	 *
	 * @since 1.0.0
	 *
	 * @throws PluginException Throws excepton on failure.
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
				$message = $result->get_error_message();
			}
			throw new PluginException( $message );
			// @codeCoverageIgnoreEnd
		}
	}

	/**
	 * Check if post type exist.
	 *
	 * @return bool True if post type exist, fail if not.
	 * @since 1.0.0
	 */
	public function exist(): bool {
		return post_type_exists( $this->pt );
	}
}
