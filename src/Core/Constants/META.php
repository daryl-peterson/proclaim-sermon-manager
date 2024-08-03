<?php

namespace DRPSermonManager\Constants;

/**
 * Meta constants.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class META {

	public const AUDIO         = 'drpsermon_audio';
	public const AUDIO_ID      = 'drpsermon_audio_id';
	public const BIBLE_PASSAGE = 'drpsermon_bible_passage';
	public const BULLETIN      = 'drpsermon_bulletin';
	public const DATE          = 'drpsermon_date';
	public const DATE_AUTO     = 'drpsermon_date_auto';
	public const NOTES         = 'drpsermon_notes';
	public const DESCRIPTION   = 'drpsermon_description';
	public const DURATION      = 'drpsermon_duration';
	public const SIZE          = 'drpsermon_size';
	public const SERVICE_TYPE  = 'drpsermon_service_type';
	public const VIDEO         = 'drpsermon_video';
	public const VIDEO_LINK    = 'drpsermon_video_link';

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
