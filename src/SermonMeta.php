<?php
/**
 * Meta constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

/**
 * Meta constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonMeta {

	/**
	 * Audo file name with path.
	 *
	 * @since 1.0.0
	 */
	public const AUDIO = 'drppsm_audio';

	/**
	 * Audio attachment id.
	 *
	 * @since 1.0.0
	 */
	public const AUDIO_ID = 'drppsm_audio_id';


	public const BIBLE_PASSAGE = 'drppsm_bible_passage';
	public const BULLETIN      = 'drppsm_bulletin';
	public const DATE          = 'drppsm_date';
	public const DATE_AUTO     = 'drppsm_date_auto';
	public const NOTES         = 'drppsm_notes';
	public const DESCRIPTION   = 'drppsm_description';
	public const DURATION      = 'drppsm_duration';
	public const SIZE          = 'drppsm_size';
	public const SERVICE_TYPE  = DRPPSM_TAX_SERVICE_TYPE;
	public const VIDEO         = 'drppsm_video';
	public const VIDEO_LINK    = 'drppsm_video_link';

	public const META_LIST = array(
		self::AUDIO         => 'audio',
		self::AUDIO_ID      => 'audio_id',
		self::BIBLE_PASSAGE => 'bible_passage',
		self::BULLETIN      => 'bulletin',
		self::DATE          => 'date',
		self::DATE_AUTO     => 'date_auto',
		self::NOTES         => 'notes',
		self::DESCRIPTION   => 'description',
		self::DURATION      => 'duration',
		self::SERVICE_TYPE  => 'service_type',
		self::VIDEO         => 'video',
		self::VIDEO_LINK    => 'video_link',
	);

	/**
	 * Get meta.
	 *
	 * @param int  $post_id Post ID.
	 * @param bool $empty Return empty values.
	 * @return array
	 * @since 1.0.0
	 */
	public static function get_meta( int $post_id, bool $empty = true ): array {
		$meta = array();
		foreach ( self::META_LIST as $meta_key => $meta_name ) {
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			if ( $empty && empty( $meta_value ) ) {
				$meta[ $meta_name ] = null;
				continue;
			}

			if ( ! empty( $meta_value ) ) {
				$meta[ $meta_name ] = $meta_value;
			}
		}
		return $meta;
	}
}
