<?php

namespace DRPSermonManager;

/**
 * Constants for plugin.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Constant
{
    public const POST_TYPE_SERMON = 'drpsermon';

    /***************************************************************
     * TAXONOMY
     **************************************************************/
    public const TAX_BIBLE_BOOK = 'drpsermon_bible_book';
    public const TAX_PREACHER = 'drpsermon_preacher';
    public const TAX_SERVICE_TYPE = 'drpsermon_service_type';
    public const TAX_SERIES = 'drpsermon_series';
    public const TAX_TOPICS = 'drpsermon_topics';

    /***************************************************************
     * META
     **************************************************************/
    public const META_AUDIO = 'drpsermon_audio';
    public const META_AUDIO_ID = 'drpsermon_audio_id';
    public const META_BIBLE_PASSAGE = 'drpsermon_bible_passage';
    public const META_BULLETIN_ID = 'drpsermon_bulletin_id';
    public const META_DATE = 'drpsermon_date';
    public const META_DATE_AUTO = 'drpsermon_date_auto';
    public const META_NOTES_ID = 'drpsermon_notes_id';
    public const META_SERMON_DURATION = 'drpsermon_duration';
    public const META_SERMON_SIZE = 'drpsermon_size';
    public const META_SERVICE_TYPE = 'drpsermon_service_type';
    public const META_VIDEO = 'drpsermon_video';
    public const META_VIDEO_LINK = 'drpsermon_video_link';

    public const META_LIST = [
        self::META_AUDIO,
        self::META_AUDIO_ID,
        self::META_BIBLE_PASSAGE,
        self::META_BULLETIN_ID,
        self::META_DATE,
        self::META_DATE_AUTO,
        self::META_NOTES_ID,
        self::META_SERMON_DURATION,
        self::META_SERMON_SIZE,
        self::META_SERVICE_TYPE,
        self::META_VIDEO,
        self::META_VIDEO_LINK,
    ];

    /***************************************************************
     * CAPABILITIES
     **************************************************************/

    // Read sermons
    public const CAP_READ_SERMON = 'read_drpsermon';
    public const CAP_READ_PRIVATE_SERMONS = 'read_private_drpsermons';

    // Edit sermons
    public const CAP_EDIT_SERMON = 'edit_drpsermon';
    public const CAP_EDIT_SERMONS = 'edit_drpsermons';
    public const CAP_EDIT_PRIVATE_SERMONS = 'edit_private_drpsermons';
    public const CAP_EDIT_PUBLISHED_SERMONS = 'edit_published_drpsermons';
    public const CAP_EDIT_OTHERS_SERMONS = 'edit_others_drpsermons';

    // Delete sermons
    public const CAP_DELETE_SERMON = 'delete_drpsermon';
    public const CAP_DELETE_SERMONS = 'delete_drpsermons';
    public const CAP_DELETE_PUBLISHED_SERMONS = 'delete_published_drpsermons';
    public const CAP_DELETE_PRIVATE_SERMONS = 'delete_private_drpsermons';
    public const CAP_DELETE_OTHERS_SERMONS = 'delete_others_drpsermons';

    // Publish
    public const CAP_PUBLISH_SERMONS = 'publish_drpsermons';

    // Manage categories & tags
    public const CAP_MANAGE_CATAGORIES = 'manage_drpsermon_categories';

    // Administrator
    public const CAP_MANAGE_SETTINGS = 'manage_drpsermon_settings';

    // List
    public const CAP_LIST = [
        self::CAP_READ_SERMON,
        self::CAP_READ_PRIVATE_SERMONS,
        self::CAP_EDIT_SERMON,
        self::CAP_EDIT_SERMONS,
        self::CAP_EDIT_PRIVATE_SERMONS,
        self::CAP_EDIT_PUBLISHED_SERMONS,
        self::CAP_EDIT_OTHERS_SERMONS,
        self::CAP_DELETE_SERMON,
        self::CAP_DELETE_SERMONS,
        self::CAP_DELETE_PUBLISHED_SERMONS,
        self::CAP_DELETE_PRIVATE_SERMONS,
        self::CAP_PUBLISH_SERMONS,
        self::CAP_MANAGE_CATAGORIES,
        self::CAP_MANAGE_SETTINGS,
    ];

    /***************************************************************
     * BIBLE BOOKS
     **************************************************************/
    public const BIBLE_BOOKS = [
        'Genesis',
        'Exodus',
        'Leviticus',
        'Numbers',
        'Deuteronomy',
        'Joshua',
        'Judges',
        'Ruth',
        '1 Samuel',
        '2 Samuel',
        '1 Kings',
        '2 Kings',
        '1 Chronicles',
        '2 Chronicles',
        'Ezra',
        'Nehemiah',
        'Esther',
        'Job',
        'Psalm',
        'Proverbs',
        'Ecclesiastes',
        'Song of Songs',
        'Isaiah',
        'Jeremiah',
        'Lamentations',
        'Ezekiel',
        'Daniel',
        'Hosea',
        'Joel',
        'Amos',
        'Obadiah',
        'Jonah',
        'Micah',
        'Nahum',
        'Habakkuk',
        'Zephaniah',
        'Haggai',
        'Zechariah',
        'Malachi',
        'Matthew',
        'Mark',
        'Luke',
        'John',
        'Acts',
        'Romans',
        '1 Corinthians',
        '2 Corinthians',
        'Galatians',
        'Ephesians',
        'Philippians',
        'Colossians',
        '1 Thessalonians',
        '2 Thessalonians',
        '1 Timothy',
        '2 Timothy',
        'Titus',
        'Philemon',
        'Hebrews',
        'James',
        '1 Peter',
        '2 Peter',
        '1 John',
        '2 John',
        '3 John',
        'Jude',
        'Revelation',
    ];

    /***************************************************************
     * ACTIONS
     **************************************************************/
    public const ACTION_FLUSH_REWRITE_RULES = 'drpsermon_flush_rewrite_rules';
}
