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
class Constants
{
    public const POST_TYPE_SERMON = 'drp_sermon';

    /***************************************************************
     * CAPABILITIES
     **************************************************************/

    // Read sermons
    public const CAP_READ_SERMON = 'read_drp_sermon';

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
}
