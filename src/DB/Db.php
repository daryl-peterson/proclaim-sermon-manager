<?php

namespace DRPPSM;

use DRPPSM\Traits\OverLoadTrait;
use wpdb;

/**
 * Database
 *
 * @package
 * @category
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
#[\AllowDynamicProperties]
class Db {
	use OverLoadTrait;


	public string $table;

	private wpdb $db;



	public function __construct( string $table ) {
		global $wpdb;

		$this->db      = $wpdb;
		$this->table   = $table;
		$this->protect = array(
			'where',
			'order',
			'orderby',

		);
	}

	public function where( string $field, mixed $value, string $operator = '=' ): Db {
		$this->where = array(
			'field' => $field,
			'value' => $value,
			'op'    => $operator,
		);
		return $this;
	}

	public function order( string|null $order = 'ASC' ): Db {
		if ( ! isset( $order ) ) {
			$order = 'ASC';
		}
		$this->order = $order;
		return $this;
	}

	public function orderby( string $field ): Db {
		$this->field = $field;
		return $this;
	}

	public function per_page( int $per_page ): Db {
		$this->per_page = $per_page;
		return $this;
	}

	public function offset( int $offset ): Db {
		$this->offset = $offset;
		return $this;
	}

	public function reset( string $field ) {
		$this->__unset( $field );
	}

	public function query() {
	}
}
