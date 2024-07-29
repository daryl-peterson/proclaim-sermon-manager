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
    public const POST_TYPE_SERMON = 'drp_sermon';

    public const TAX_BIBLE_BOOK = 'drp_bible_book';
    public const TAX_PREACHER = 'drp_preacher';
    public const TAX_SERVICE_TYPE = 'drp_service_type';
    public const TAX_SERIES = 'drp_sermon_series';
    public const TAX_TOPICS = 'drp_sermon_topics';

    /***************************************************************
     * CAPABILITIES
     **************************************************************/

    // Read sermons
    public const CAP_READ_SERMON = 'read_drp_sermon';
    public const CAP_READ_PRIVATE_SERMONS = 'read_private_drp_sermons';

    // Edit sermons
    public const CAP_EDIT_SERMON = 'edit_drp_sermon';
    public const CAP_EDIT_SERMONS = 'edit_drp_sermons';
    public const CAP_EDIT_PRIVATE_SERMONS = 'edit_private_drp_sermons';
    public const CAP_EDIT_PUBLISHED_SERMONS = 'edit_published_drp_sermons';
    public const CAP_EDIT_OTHERS_SERMONS = 'edit_others_drp_sermons';

    // Delete sermons
    public const CAP_DELETE_SERMON = 'delete_drp_sermon';
    public const CAP_DELETE_SERMONS = 'delete_drp_sermons';
    public const CAP_DELETE_PUBLISHED_SERMONS = 'delete_published_drp_sermons';
    public const CAP_DELETE_PRIVATE_SERMONS = 'delete_private_drp_sermons';
    public const CAP_DELETE_OTHERS_SERMONS = 'delete_others_drp_sermons';

    // Publish
    public const CAP_PUBLISH_SERMONS = 'publish_drp_sermons';

    // Manage categories & tags
    public const CAP_MANAGE_CATAGORIES = 'manage_drp_sermon_categories';

    // Administrator
    public const CAP_MANAGE_SETTINGS = 'manage_drp_sermon_settings';

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
    public const ACTION_FLUSH_REWRITE_RULES = 'drp_sermon_flush_rewrite_rules';
}
