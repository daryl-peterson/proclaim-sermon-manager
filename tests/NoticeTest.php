<?php

namespace DRPSermonManager\Tests;

use DRPSermonManager\App;
use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Logging\Logger;
use DRPSermonManager\Notice;

class NoticeTest extends BaseTest {

	public function tester() {
		$title   = 'This is the tile';
		$message = 'This is the message';

		$obj = Notice::init();
		$obj->set_success( $title, $message );
		$obj->set_warning( $title, $message );
		$obj->set_info( $title, $message );
		$obj->set_error( $title, $message );

		ob_start();
		$obj->show_notice();
		$result = ob_get_clean();
		Logger::debug( $result );
		$this->assertIsString( $result );

		$obj->delete();

		$obj->show_notice();
	}
}
