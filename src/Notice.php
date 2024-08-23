<?php
/**
 * Admin notice.
 *
 * @package     Proclaim Sermon Manager.
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Traits\SingletonTrait;

/**
 * Admin notice.
 *
 * @package     Proclaim Sermon Manager.
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Notice implements NoticeInt {

	use SingletonTrait;

	/**
	 * Options interface.
	 *
	 * @var OptionsInt
	 */
	private OptionsInt $options;

	/**
	 * Option name.
	 *
	 * @var string
	 */
	private string $option_name = 'notice';

	/**
	 * Set object properties.
	 */
	protected function __construct() {
		// @codeCoverageIgnoreStart
		$this->options = options();
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Register hooks.
	 *
	 * @return boolean|null Returns true if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		if ( has_action( 'admin_notices', array( $this, 'show_notice' ) ) ) {
			return false;
		}
		add_action( 'admin_notices', array( $this, 'show_notice' ) );
		return true;
	}

	/**
	 * Get initialize object.
	 *
	 * @return NoticeInt
	 * @since 1.0.0
	 */
	public static function exec(): NoticeInt {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	/**
	 * Display notice if it exist.
	 *
	 * @return string|null Notice string if exist.
	 * @since 1.0.0
	 */
	public function show_notice(): ?string {
		$option = $this->options->get( $this->option_name, null );
		$html   = null;

		if ( ! isset( $option ) ) {
			return null;
		}

		$title        = esc_html( isset( $option['title'] ) ? $option['title'] : '' );
		$message      = isset( $option['message'] ) ? $option['message'] : false;
		$notice_level = ! empty( $option['notice-level'] ) ? $option['notice-level'] : 'notice-error';
		if ( $message ) {
			$html  = "\n";
			$html .= <<<HTML
				<div class="notice $notice_level is-dismissible">
				<h2>$title</h2>
				<p>$message</p>
				</div>
			HTML;

			echo wp_kses( $html, allowed_html() );
			$this->options->delete( $this->option_name );
		}
		return $html;
	}

	/**
	 * Delete admin notice.
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->options->delete( $this->option_name );
	}

	/**
	 * Set error notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_error( string $title, string $message ): bool {
		return $this->set_option( $title, $message, 'notice-error' );
	}

	/**
	 * Set warning notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_warning( string $title, string $message ): bool {
		return $this->set_option( $title, $message, 'notice-warning' );
	}

	/**
	 * Set info notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_info( string $title, string $message ): bool {
		return $this->set_option( $title, $message, 'notice-info' );
	}

	/**
	 * Set success notice.
	 *
	 * @param string $title Notice title.
	 * @param string $message Notice message.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	public function set_success( string $title, string $message ): bool {
		return $this->set_option( $title, $message, 'notice-success' );
	}

	/**
	 * Set admin notice option
	 *
	 * @param string $title         Notice title.
	 * @param string $message       Notice message.
	 * @param string $notice_level   Notice level.
	 * @return boolean True on success, false on failure.
	 *
	 * @since 1.0.0
	 */
	protected function set_option( string $title, string $message, string $notice_level ): bool {
		$title        = NAME . " $title";
		$option_value = array(
			'title'        => $title,
			'message'      => $message,
			'notice-level' => $notice_level,
		);

		return $this->options->set( $this->option_name, $option_value );
	}
}
