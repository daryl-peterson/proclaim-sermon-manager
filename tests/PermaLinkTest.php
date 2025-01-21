<?php

namespace DRPPSM\Tests;

use DRPPSM\PermaLinks;

/**
 * Class description.
 *
 * @category
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class PermaLinkTest extends BaseTest {

	public PermaLinks $obj;

	/**
	 * This method is called before each test.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function setup(): void {
		$this->obj = PermaLinks::get_instance();
	}

	public function testPermaLinkStructure() {
	}
}
