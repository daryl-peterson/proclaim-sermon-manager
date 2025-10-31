<?php
/**
 * Media player.
 *
 * @package     DRPPSM\MediaPlayer
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

/**
 * Media player.
 *
 * @package     DRPPSM\MediaPlayer
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class MediaPlayer {

	/**
	 * Set object properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	}


	/**
	 * Renders the media player.
	 *
	 * @since 1.0.0
	 *
	 * @todo Finish implementing.
	 */
	public static function render() {
	}

	/**
	 * Renders the video player.
	 *
	 * @param string   $url  The URL of the video file.
	 * @param int|bool $seek Allows seeking to specific second in audio file.\
	 *                       Pass an int to override auto detection or false to disable auto detection.
	 * @param ?string  $poster Poster image URL.
	 *
	 * @return string Video player HTML.
	 * @since 1.0.0
	 */
	public static function render_video( $url = '', $seek = true, ?string $poster = null ): string {
		if ( ! is_string( $url ) || trim( $url ) === '' ) {
			return '';
		}

		if ( strpos( $url, 'facebook.' ) !== false ) {
			wp_enqueue_script( 'drppsm-fb-video' );

			parse_str( parse_url( $url, PHP_URL_QUERY ), $query );

			return '<div class="fb-video" data-href="' . $url . '" data-width="' . ( isset( $query['width'] ) ? ( is_numeric( $query['width'] ) ? $query['width'] : '600' ) : '600' ) . '" data-allowfullscreen="' . ( isset( $query['fullscreen'] ) ? ( 'yes' === $query['width'] ? 'true' : 'false' ) : 'true' ) . '"></div>';
		}

		$player = strtolower( Settings::get( Settings::PLAYER ) );

		if ( strtolower( 'WordPress' ) === $player ) {
			$attr = array(
				'src'     => $url,
				'preload' => 'none',
			);

			$output = wp_video_shortcode( $attr );
		} else {
			$is_youtube_long  = strpos( strtolower( $url ), 'youtube.com' );
			$is_youtube_short = strpos( strtolower( $url ), 'youtu.be' );
			$is_youtube       = $is_youtube_long || $is_youtube_short;
			$is_vimeo         = strpos( strtolower( $url ), 'vimeo.com' );
			$extra_settings   = '';
			$output           = '';

			if ( is_numeric( $seek ) || true === $seek ) {
				if ( is_numeric( $seek ) ) {
					$seconds = $seek;
				} else {
					$seconds = self::get_media_url_seconds( $url );
				}

				// Sanitation just in case.
				$extra_settings = 'data-plyr_seek=\'' . intval( $seconds ) . '\'';
			}

			if ( $poster ) {
				$extra_settings .= ' data-poster="' . $poster . '"';
			}

			// Remove seek from URL.
			$url = preg_replace( '/(\?|#|&)t.*$/', '', $url );

			if ( 'plyr' === $player && ( $is_youtube || $is_vimeo ) ) {
				$output .= '<div data-plyr-provider="' . ( $is_youtube ? 'youtube' : 'vimeo' ) . '" data-plyr-embed-id="' . $url . '" class="plyr__video-embed drppsm-video-player video-' . ( $is_youtube ? 'youtube' : 'vimeo' ) . ( 'mediaelement' === $player ? 'mejs__player' : '' ) . '" ' . $extra_settings . '></div>';
			} else {
				$output .= '<video controls preload="metadata" class="drppsm-video-player ' . ( 'mediaelement' === $player ? 'mejs__player' : '' ) . '" ' . $extra_settings . '>';
				$output .= '<source src="' . $url . '">';
				$output .= '</video>';
			}
		}

		/**
		 * Allows changing of the video player to any HTML.
		 *
		 * @param string $output Video player HTML.
		 * @param string $url    Video source URL.
		 */
		return apply_filters( 'drppsm_video_player', $output, $url );
	}

	/**
	 * Renders the audio player.
	 *
	 * @param int|string $source The ID of the sermon, or alternatively, the URL or the attachment ID of the audio file.
	 * @param int        $seek   Seek to specific second in audio file.
	 *
	 * @return string|false Audio player HTML or false if sermon has no audio.
	 * @since 1.0.0
	 */
	public static function render_audio( $source = '', $seek = null ): ?string {
		// For later filtering.
		$source_orig = $source;

		// Check if it's a sermon or attachment ID.
		if ( is_numeric( $source ) ) {
			$object = get_post( $source );

			if ( ! $object ) {
				return null;
			}

			switch ( $object->post_type ) {
				case DRPPSM_PT_SERMON:
					$sermon_audio_id     = get_sermon_meta( 'sermon_audio_id' );
					$sermon_audio_url    = get_sermon_meta( 'sermon_audio' );
					$sermon_audio_url_wp = $sermon_audio_id ? wp_get_attachment_url( intval( $sermon_audio_id ) ) : false;

					$source = $sermon_audio_id && $sermon_audio_url_wp ? $sermon_audio_url_wp : $sermon_audio_url;
					break;
				case 'attachment':
					$source = wp_get_attachment_url( $object->ID );
					break;
			}
		}

		// Check if set.
		if ( ! $source ) {
			return null;
		}

		// Get the current player.
		$player = strtolower( Settings::get( Settings::PLAYER ) );

		switch ( strtolower( $player ) ) {
			case 'wordpress': // phpcs:ignore
				$attr = array(
					'src'     => $source,
					'preload' => 'none',
				);

				$output = wp_audio_shortcode( $attr );
				break;
			default:
				$extra_settings = '';

				if ( is_numeric( $seek ) ) {
					// Sanitation just in case.
					$extra_settings = 'data-plyr_seek=\'' . intval( $seek ) . '\'';
				}

				$output = '';

				$output .= '<audio controls preload="metadata" class="drppsm-player ' . ( 'mediaelement' === $player ? 'mejs__player' : '' ) . '" ' . $extra_settings . '>';
				$output .= '<source src="' . $source . '" type="audio/mp3">';
				$output .= '</audio>';

				break;
		}

		/**
		 * Allows changing of the audio player to any HTML.
		 *
		 * @param string     $output      Audio player HTML.
		 * @param string     $source      Audio source URL.
		 * @param int|string $source_orig The original source parameter.
		 * @since 1.0.0
		 */
		return apply_filters( 'drppsm_audio_player', $output, $source, $source_orig );
	}

	/**
	 * Converts different video URL time formats to seconds. Examples:
	 * "?t=2m12s" => 132
	 * "?t=1h2s" => 3602
	 * "#t=1m" => 60
	 * "#t=25s" => 25
	 * "?t=56" => 56
	 * "?t=10:45" => 645
	 * "?t=01:00:01" => 3601
	 *
	 * @param string $url The URL to the video file.
	 *
	 * @return false|int|null Seconds if successful, null if it couldn't decode the format, and false if the parameter is
	 *                        not set.
	 *
	 * @since 1.0.0
	 */
	private static function get_media_url_seconds( $url ) {
		$seconds = 0;

		if ( strpos( $url, '?t=' ) === false && strpos( $url, '#t=' ) === false && strpos( $url, '&t=' ) === false ) {
			return false;
		}

		// Try out hms format first (example: 1h2m3s).
		preg_match( '/t=(\d+h)?(\d+m)?(\d+s)+?/', $url, $hms );
		if ( ! empty( $hms ) ) {
			for ( $i = 1; $i <= 3; $i++ ) {
				if ( '' === $hms[ $i ] ) {
					continue;
				}

				switch ( $i ) {
					case 1:
						$multiplication = HOUR_IN_SECONDS;
						break;
					case 2:
						$multiplication = MINUTE_IN_SECONDS;
						break;
					default:
						$multiplication = 1;
				}

				$seconds += intval( substr( $hms[ $i ], 0, - 1 ) ) * $multiplication;
			}

			return $seconds;
		}

		// Try out colon format (example: 25:12).
		preg_match( '/t=(\d+:)?(\d+:)?(\d+)+?/', $url, $colons );
		if ( ! empty( $colons ) ) {
			// Fix hours and minutes if needed.
			if ( empty( $colons[2] ) && ! empty( $colons[1] ) ) {
				$colons[2] = $colons[1];
				$colons[1] = '';
			}

			for ( $i = 1; $i <= 3; $i++ ) {
				if ( '' === $colons[ $i ] ) {
					continue;
				}

				switch ( $i ) {
					case 1:
						$multiplication = HOUR_IN_SECONDS;
						$colons[ $i ]   = substr( $colons[ $i ], 0, - 1 );
						break;
					case 2:
						$multiplication = MINUTE_IN_SECONDS;
						$colons[ $i ]   = substr( $colons[ $i ], 0, - 1 );
						break;
					default:
						$multiplication = 1;
				}

				$seconds += intval( $colons[ $i ] ) * $multiplication;
			}

			return $seconds;
		}

		// Try out seconds (example: 12 (or 12s)).
		preg_match( '/t=(\d+)/', $url, $seconds );
		if ( ! empty( $seconds ) && ! empty( $seconds[1] ) ) {
			return intval( $seconds[1] );
		}

		return null;
	}
}
