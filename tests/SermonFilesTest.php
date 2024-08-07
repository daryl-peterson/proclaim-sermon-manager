<?php

namespace DRPPSM\Tests;

use DRPPSM\SermonFiles;

/**
 * Test sermon files.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonFilesTest extends BaseTest {
	public function test_get() {
		$obj = $this->app->get( SermonFiles::class );
		$this->assertNotNull( $obj );
	}

	public function test_show() {
		$obj = $this->app->get( SermonFiles::class )->show();
		$this->assertNull( $obj );
	}
}
