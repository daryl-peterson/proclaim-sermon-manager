<?php

namespace DRPPSM\Tests;

use DRPPSM\Tax;
use DRPPSM\SermonDetail;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class description
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class SermonDetailTest extends BaseTest {

	private SermonDetail $obj;

	public function setup(): void {
		$this->obj = SermonDetail::init();
	}
	public function test_register() {
		$result = $this->obj->register();
		$this->assertIsBool( $result );

		$result = $this->obj->register();
		$this->assertFalse( $result );
	}

	public function test_get() {
		$this->assertNotNull( $this->obj );
		$this->assertInstanceOf( SermonDetail::class, $this->obj );
	}
}
