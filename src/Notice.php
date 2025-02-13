<?php
/**
 * Admin notice.
 *
 * @package     DRPPSM\Notice
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) || exit;
// @codeCoverageIgnoreEnd

use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Traits\ExecutableTrait;
use DRPPSM\Traits\SingletonTrait;

/**
 * Admin notice.
 *
 * @package     DRPPSM\Notice
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Notice implements NoticeInt {

	use SingletonTrait;
	use ExecutableTrait;

	/**
	 * Options key.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $option_key;

	/**
	 * Option name.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	private string $option_name;

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {
		$this->option_key  = DRPPSM_PLUGIN;
		$this->option_name = 'notice';
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
	 * Display notice if it exist.
	 *
	 * @return string|null Notice string if exist.
	 * @since 1.0.0
	 */
	public function show_notice(): ?string {
		$options = get_option( $this->option_key, array() );

		if (
			! is_array( $options ) ||
			! isset( $options[ $this->option_name ] )
		) {
			return null;
		}

		$html   = null;
		$option = $options[ $this->option_name ];

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

		}
		$this->delete();
		return $html;
	}

	/**
	 * Delete admin notice.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function delete(): void {
		$options = get_option( $this->option_key, array() );
		if ( ! is_array( $options ) || ! isset( $options[ $this->option_name ] ) ) {
			// @codeCoverageIgnoreStart
			return;
			// @codeCoverageIgnoreEnd
		}
		unset( $options[ $this->option_name ] );
		update_option( $this->option_key, $options );
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
	 * @since 1.0.0
	 */
	protected function set_option( string $title, string $message, string $notice_level ): bool {
		$title        = DRPPSM_TITLE . " $title";
		$option_value = array(
			'title'        => $title,
			'message'      => $message,
			'notice-level' => $notice_level,
		);

		$options = get_option( $this->option_key, array() );
		if ( ! is_array( $options ) || ! isset( $options[ $this->option_name ] ) ) {
			$options = array();
		}
		$options[ $this->option_name ] = $option_value;
		return update_option( $this->option_key, $options );
	}
}
