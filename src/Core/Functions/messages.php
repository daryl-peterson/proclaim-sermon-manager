<?php
/**
 * Messages defined here.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */
namespace DRPPSM;

define(
	'DRPPSM_MSG_FILE_NOT_EXIST',
	__( 'File does not exist.', 'drppsm' )
);

define(
	'DRPPSM_MSG_FAILED_PARTIAL',
	__( 'Failed loading partial file.', 'drppsm' )
);


define(
	'DRPPSM_MSG_LABEL_SINGLE',
	__( 'The label should be in the singular form.', 'drppsm' )
);

$value = get_slug(
	Settings::FIELD_PREACHER,
	_x( 'preacher', 'slug', 'drppsm' )
);

define( 'DRPPSM_SETTINGS_PREACHER', $value );
