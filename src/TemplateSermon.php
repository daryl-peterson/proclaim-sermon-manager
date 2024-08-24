<?php
/**
 * Template functions for sermons.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

/**
 * Template functions for sermons.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class TemplateSermon implements Executable, Registrable {

	public static function exec(): TemplateSermon {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {
		return true;
	}

	public function get_attachments() {
	}

	public function get_excerpt() {
	}
}
