<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\Admin\AdminSermon;
use DRPSermonManager\Constant;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @since       1.0.0
 */
class AdminSermonTest extends BaseTest
{
    protected AdminSermon $obj;

    public function setup(): void
    {
        $this->obj = AdminSermon::init();
    }

    public function testDisableGutenBerge()
    {
        $result = $this->obj->disableGutenberg(true, 'blah');
        $this->assertTrue($result);

        $result = $this->obj->disableGutenberg(true, Constant::POST_TYPE_SERMON);
        $this->assertFalse($result);

        $result = $this->obj->setMetaBoxes();
        $this->assertNull($result);
    }

    public function testAddMetaBox()
    {
        $sermon = $this->getTestSermon();
        $this->assertNotNull($sermon);

        ob_start();
        $result = $this->obj->addSermonDetails($sermon);
        $html = ob_get_clean();
        $this->assertNull($result);
        $this->assertIsString($html);

        ob_start();
        $result = $this->obj->addSermonFiles($sermon);
        $html = ob_get_clean();
        $this->assertNull($result);
        $this->assertIsString($html);
    }

    public function testSavePost()
    {
        $admin = $this->getAdminUser();

        wp_set_current_user($admin->ID);

        $sermon = $this->getTestSermon();
        $this->assertNotNull($sermon);
        $this->assertInstanceOf(\WP_Post::class, $sermon);

        $result = $this->obj->savePost($sermon->ID, $sermon, true);
        $this->assertNotNull($result);

        $sermon = $this->getTestPost();
        $result = $this->obj->savePost($sermon->ID, $sermon, true);
        $this->assertIsInt($result);

        define('DOING_AUTOSAVE', true);
        $sermon = $this->getTestSermon();
        $result = $this->obj->savePost($sermon->ID, $sermon, true);
        $this->assertIsInt($result);
    }
}
