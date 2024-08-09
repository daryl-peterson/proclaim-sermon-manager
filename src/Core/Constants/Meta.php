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

namespace DRPPSM\Constants;

/**
 * Meta constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Meta {

	public const AUDIO         = 'drppsm_audio';
	public const AUDIO_ID      = 'drppsm_audio_id';
	public const BIBLE_PASSAGE = 'drppsm_bible_passage';
	public const BULLETIN      = 'drppsm_bulletin';
	public const DATE          = 'drppsm_date';
	public const DATE_AUTO     = 'drppsm_date_auto';
	public const NOTES         = 'drppsm_notes';
	public const DESCRIPTION   = 'drppsm_description';
	public const DURATION      = 'drppsm_duration';
	public const SIZE          = 'drppsm_size';
	public const SERVICE_TYPE  = 'drppsm_service_type';
	public const VIDEO         = 'drppsm_video';
	public const VIDEO_LINK    = 'drppsm_video_link';

	public const META_LIST = array(
		self::AUDIO,
		self::AUDIO_ID,
		self::BIBLE_PASSAGE,
		self::BULLETIN,
		self::DATE,
		self::DATE_AUTO,
		self::NOTES,
		self::DESCRIPTION,
		self::DURATION,
		self::SIZE,
		self::SERVICE_TYPE,
		self::VIDEO,
		self::VIDEO_LINK,
	);
}
