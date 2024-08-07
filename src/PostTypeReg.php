<?php
/**
 * Post type registration abstract.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd


use DRPPSM\Exceptions\PluginException;
use DRPPSM\Helper;
use DRPPSM\Interfaces\PostTypeRegInt;
use DRPPSM\Logging\Logger;

/**
 * Post type registration.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
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
	 * @since 1.0.0
	 */
	public function __construct( string $post_type, string $config_file ) {
		$this->pt          = $post_type;
		$this->config_file = $config_file;
	}

	/**
	 * Add post type.
	 *
	 * @return void
	 * @throws PluginException Throws exception on failure.
	 * @since 1.0.0
	 */
	public function add(): void {
		$exist = $this->exist();

		if ( ! is_blog_installed() || $exist ) {
			return;
		}

		try {
			$def = Helper::get_config( $this->config_file );
			Logger::debug( array( $this->pt, $this->config_file, $def ) );
			$result = register_post_type( $this->pt, $def );
			Logger::debug( array( 'RESULT' => $result ) );
			// @codeCoverageIgnoreStart
		} catch ( \Throwable $th ) {
			throw new PluginException( $th->getMessage(), $th->getCode(), $th );
			// @codeCoverageIgnoreEnd
		}

		if ( ! $this->exist() || is_wp_error( $result ) ) {
			// @codeCoverageIgnoreStart
			$message = __( 'Failed to add post type : ', 'drppsm' ) . $this->pt;
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
	 * @return void
	 * @throws PluginException Throws excepton on failure.
	 * @since 1.0.0
	 */
	public function remove(): void {
		$exist = $this->exist();

		if ( ! is_blog_installed() || ( ! $exist ) ) {
			return;
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
			$message = __( 'Failed to remove post type : ', 'drppsm' ) . $this->pt;
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
