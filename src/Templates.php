<?php

namespace DRPPSM;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;

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
class Templates implements Executable, Registrable {

	public static function exec(): Templates {
		$obj = new self();
		$obj->register();
		return $obj;
	}

	public function register(): ?bool {

		if ( has_filter( 'template_include', array( $this, 'get_template' ) ) ) {
			return false;
		}
		add_filter( 'template_include', array( $this, 'get_template' ), 10, 1 );
		return true;
	}

	public function get_template( string $template ) {
		$object = get_queried_object();
		Logger::debug( array( $object, $template ) );

		return $template;
	}
}
