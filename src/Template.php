<?php
/**
 * Template class for locating templates and loading them.
 *
 * @package     DRPPSM\Templates
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Interfaces\Executable;
use DRPPSM\Interfaces\Registrable;
use DRPPSM\Traits\ExecutableTrait;

/**
 * Template class for locating templates and loading them.
 *
 * @package     DRPPSM\Templates
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
class Template implements Executable, Registrable {
	use ExecutableTrait;

	/**
	 * Pagination template.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string Pagination = 'psm-pagination';

	/**
	 * Image list template.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string TAX_IMAGE_LIST = 'psm-tax-images';

	/**
	 * Taxonomy archive template.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string TAX_ARCHIVE = 'psm-tax-archive';

	/**
	 * Wrapper start template.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string WRAPPER_START = 'psm-wrapper-start';

	/**
	 * Wrapper end template.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string WRAPPER_END = 'psm-wrapper-end';

	/**
	 * Meta item template.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string META_ITEM = 'psm-meta-item';

	/**
	 * Sermon sorting template.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string SERMON_SORTING = 'psm-sermon-sorting';

	/**
	 * Sermon column layout.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string SERMON_LAYOUT_COL = 'psm-sermon-col';

	/**
	 * Sermon row layout.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string SERMON_LAYOUT_ROW = 'psm-sermon-row';

	/**
	 * Sermon clasic layout.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	const string SERMON_LAYOUT_CLASIC = 'psm-sermon-clasic';

	/**
	 * Register hooks.
	 *
	 * @return bool Returns true if hooks were registered, otherwise false.
	 * @since 1.0.0
	 */
	public function register(): ?bool {
		TemplateFiles::exec();
		TemplateBlocks::exec();

		return true;
	}
}
