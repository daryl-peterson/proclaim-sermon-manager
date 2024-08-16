<?php
/**
 * Notice testing.
 *
 * @package     Proclaim Sermon Manager.
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM\Tests;

use DRPPSM\Logging\Logger;
use function DRPPSM\notice;



/**
 * Notice testing.
 *
 * @package     Proclaim Sermon Manager.
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class NoticeTest extends BaseTest {

	public function tester() {
		$title   = 'This is the tile';
		$message = 'This is the message';

		$obj = notice();
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
