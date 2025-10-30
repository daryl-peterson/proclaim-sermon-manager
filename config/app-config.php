<?php
/**
 * App configuration.
 *
 * @package     DRPPSM
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\Admin\AdminMenu;
use DRPPSM\Admin\AdminSettings;
use DRPPSM\Interfaces\RolesInt;
use DRPPSM\Interfaces\TextDomainInt;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Logging\LogFile;
use DRPPSM\Logging\LoggerInt;
use DRPPSM\Logging\LogWritterInt;
use DRPPSM\ShortCodes\Codes;


return array(
	TextDomainInt::class     => TextDomain::class,
	AdminMenu::class         => AdminMenu::class,
	AdminSettings::class     => AdminSettings::class,
	BibleLoader::class       => BibleLoader::class,
	Codes::class             => Codes::class,
	ImageSize::class         => ImageSize::class,
	LogWritterInt::class     => LogFile::class,
	LoggerInt::class         => Logger::class,
	NoticeInt::class         => Notice::class,
	Plugin::class            => Plugin::class,
	PostTypeSetup::class     => PostTypeSetup::class,
	QueryVars::class         => QueryVars::class,
	QueueScripts::class      => QueueScripts::class,
	Requirements::class      => Requirements::class,
	Rewrite::class           => Rewrite::class,
	RolesInt::class          => Roles::class,
	SermonComments::class    => SermonComments::class,
	SermonEdit::class        => SermonEdit::class,
	SermonImageAttach::class => SermonImageAttach::class,
	SermonListTable::class   => SermonListTable::class,
	Scheduler::class         => Scheduler::class,
	SchedulerJobs::class     => SchedulerJobs::class,
	TaxImageAttach::class    => TaxImageAttach::class,
	TaxListTable::class      => TaxListTable::class,
	TaxMeta::class           => TaxMeta::class,
	// TextDomainInt::class     => TextDomain::class,
	Template::class          => Template::class,
	Dashboard::class         => Dashboard::class,
);
