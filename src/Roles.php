<?php

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\RolesInt;

/**
 * Add custom capabilities to roles.
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class Roles implements RolesInt
{
    public static function init(): RolesInt
    {
        return new self();
    }

    public function add(): void
    {
        $role_list = ['administrator', 'editor', 'author'];

        foreach ($role_list as $role_name) {
            $role = get_role($role_name);
            if (null === $role || !($role instanceof \WP_Role)) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            // Read sermons.
            $role->add_cap(Constant::CAP_READ_SERMON);
            $role->add_cap(Constant::CAP_READ_PRIVATE_SERMONS);

            // Edit sermons.
            $role->add_cap(Constant::CAP_EDIT_SERMON);
            $role->add_cap(Constant::CAP_EDIT_SERMONS);
            $role->add_cap(Constant::CAP_EDIT_PRIVATE_SERMONS);
            $role->add_cap(Constant::CAP_EDIT_PUBLISHED_SERMONS);

            // Delete sermons.
            $role->add_cap(Constant::CAP_DELETE_SERMON);
            $role->add_cap(Constant::CAP_DELETE_SERMONS);
            $role->add_cap(Constant::CAP_DELETE_PUBLISHED_SERMONS);
            $role->add_cap(Constant::CAP_DELETE_PRIVATE_SERMONS);

            // Publish sermons.
            $role->add_cap(Constant::CAP_PUBLISH_SERMONS);

            // Manage categories & tags.
            $role->add_cap(Constant::CAP_MANAGE_CATAGORIES);

            // Add additional roles for administrator.
            if ('administrator' === $role_name) {
                // Access to Sermon Manager Settings.
                $role->add_cap(Constant::CAP_MANAGE_SETTINGS);
            }

            // Add additional roles for administrator and editor.
            if ('author' !== $role_name) {
                $role->add_cap(Constant::CAP_EDIT_OTHERS_SERMONS);
                $role->add_cap(Constant::CAP_DELETE_OTHERS_SERMONS);
            }
        }
    }

    public function remove(): void
    {
        $role_list = ['administrator', 'editor', 'author'];

        foreach ($role_list as $role_name) {
            $role = get_role($role_name);
            if (null === $role || !($role instanceof \WP_Role)) {
                // @codeCoverageIgnoreStart
                continue;
                // @codeCoverageIgnoreEnd
            }

            // Read sermons.
            $role->remove_cap(Constant::CAP_READ_SERMON);
            $role->remove_cap(Constant::CAP_READ_PRIVATE_SERMONS);

            // Edit sermons.
            $role->remove_cap(Constant::CAP_EDIT_SERMON);
            $role->remove_cap(Constant::CAP_EDIT_SERMONS);
            $role->remove_cap(Constant::CAP_EDIT_PRIVATE_SERMONS);
            $role->remove_cap(Constant::CAP_DELETE_PUBLISHED_SERMONS);

            // Delete sermons.
            $role->remove_cap(Constant::CAP_DELETE_SERMON);
            $role->remove_cap(Constant::CAP_DELETE_SERMONS);
            $role->remove_cap(Constant::CAP_DELETE_PUBLISHED_SERMONS);
            $role->remove_cap(Constant::CAP_DELETE_PRIVATE_SERMONS);

            // Publish sermons.
            $role->remove_cap(Constant::CAP_PUBLISH_SERMONS);

            // Manage categories & tags.
            $role->remove_cap(Constant::CAP_MANAGE_CATAGORIES);

            // Add additional roles for administrator.
            if ('administrator' === $role_name) {
                // Access to Sermon Manager Settings.
                $role->remove_cap(Constant::CAP_MANAGE_SETTINGS);
            }

            // Add additional roles for administrator and editor.
            if ('author' !== $role_name) {
                $role->remove_cap(Constant::CAP_EDIT_OTHERS_SERMONS);
                $role->remove_cap(Constant::CAP_DELETE_OTHERS_SERMONS);
            }
        }
    }
}
