<?php
/**
 * Taxonomy constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Constants\Caps;
use DRPPSM\Interfaces\Executable;
use DRPPSM\OptGeneral;

/**
 * Taxonomy constants.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Tax implements Executable {

	public const BIBLE_BOOK              = 'drppsm_bible';
	public const BIBLE_BOOK_FIELD        = 'bible_label';
	public const BIBLE_BOOK_DEFAULT      = 'book';
	public const BIBLE_BOOK_SORT_FIELD   = 'bible_sort';
	public const BIBLE_BOOK_SORT_DEFAULT = true;

	public const PREACHER              = 'drppsm_preacher';
	public const PREACHER_FIELD        = 'preacher_label';
	public const PREACHER_DEFAULT      = 'Preacher';
	public const PREACHER_SORT_FIELD   = 'preacher_sort';
	public const PREACHER_SORT_DEFAULT = true;

	public const SERVICE_TYPE              = 'drppsm_stype';
	public const SERVICE_TYPE_FIELD        = 'service_type_label';
	public const SERVICE_TYPE_DEFAULT      = 'Service Type';
	public const SERVICE_TYPE_SORT_FIELD   = 'service_type_sort';
	public const SERVICE_TYPE_SORT_DEFAULT = false;

	public const SERIES              = 'drppsm_series';
	public const SERIES_FIELD        = 'series_label';
	public const SERIES_DEFAULT      = 'series';
	public const SERIES_SORT_FIELD   = 'series_sort';
	public const SERIES_SORT_DEFAULT = true;


	public const TOPICS              = 'drppsm_topics';
	public const TOPICS_FIELD        = 'topics_label';
	public const TOPICS_DEFAULT      = 'topics';
	public const TOPICS_SORT_FIELD   = 'topics_sort';
	public const TOPICS_SORT_DEFAULT = true;

	public const LIST = array(
		self::BIBLE_BOOK,
		self::PREACHER,
		self::SERVICE_TYPE,
		self::SERIES,
		self::TOPICS,
	);

	public const CAPS = array(
		'manage_terms' => Caps::MANAGE_CATAGORIES,
		'edit_terms'   => Caps::MANAGE_CATAGORIES,
		'delete_terms' => Caps::MANAGE_CATAGORIES,
		'assign_terms' => Caps::MANAGE_CATAGORIES,
	);

	public array $map;

	protected function __construct() {
		$this->map = array(
			self::BIBLE_BOOK   => array(
				'label'   => self::BIBLE_BOOK,
				'default' => _x( self::BIBLE_BOOK_DEFAULT, 'slug', 'drppsm' ),
			),
			self::PREACHER     => array(
				'label'   => self::PREACHER_FIELD,
				'default' => _x( self::PREACHER_DEFAULT, 'slug', 'drppsm' ),
			),
			self::SERVICE_TYPE => array(
				'label'   => self::SERVICE_TYPE_FIELD,
				'default' => _x( self::SERVICE_TYPE_DEFAULT, 'slug', 'drppsm' ),
			),
			self::SERIES       => array(
				'label'   => self::SERIES_FIELD,
				'default' => _x( self::SERIES_DEFAULT, 'slug', 'drppsm' ),
			),
			self::TOPICS       => array(
				'label'   => self::TOPICS_FIELD,
				'default' => _x( self::TOPICS_DEFAULT, 'slug', 'drppsm' ),
			),
		);
	}

	/**
	 * Initailize and register hooks.
	 *
	 * @return Tax
	 * @since 1.0.0
	 */
	public static function exec(): Tax {
		$obj = new self();
		return $obj;
	}

	public static function get_list() {
		$obj = self::exec();
		return array_keys( $obj->map );
	}

	public static function get_label( string $tax, string $default = '' ): ?string {
		$obj   = self::exec();
		$label = $obj->get_value( $tax, 'label' );
		return OptGeneral::get( $label, $default );
	}

	public static function get_default( string $tax ): string {
		$obj = self::exec();
		return $obj->get_value( $tax, 'default' );
	}

	private function get_value( string $tax, string $key ): string {
		if ( ! key_exists( $tax, $this->map ) ) {
			return null;
		}

		$value = $this->map[ $tax ];

		if ( ! key_exists( $key, $value ) ) {
			return '';
		}
		return (string) $value[ $key ];
	}
}
