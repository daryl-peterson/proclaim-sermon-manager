<?php
/**
 * App configuration.
 *
 * @package     DRPPSM/Config
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

use DRPPSM\DB\DbUpdates;
use DRPPSM\Interfaces\BibleLoaderInt;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\PermaLinkInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\Interfaces\RolesInt;
use DRPPSM\Interfaces\TextDomainInt;
use DRPPSM\Interfaces\ImageSizeInt;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Logging\LogFile;
use DRPPSM\Logging\LoggerInt;
use DRPPSM\Logging\LogWritterInt;

return array(
	AdminMenu::class           => AdminMenu::class,
	AdminSettings::class       => AdminSettings::class,
	BibleLoaderInt::class      => BibleLoader::class,
	DbUpdates::class           => DbUpdates::class,
	ImageSizeInt::class        => ImageSize::class,
	LogWritterInt::class       => LogFile::class,
	LoggerInt::class           => Logger::class,
	NoticeInt::class           => Notice::class,
	OptionsInt::class          => Options::class,
	// Pagination::class          => Pagination::class,
	PermaLinkInt::class        => PermaLinks::class,
	Plugin::class              => Plugin::class,
	PostTypeSetupInt::class    => PostTypeSetup::class,
	QueryVars::class           => QueryVars::class,
	QueueScripts::class        => QueueScripts::class,
	RequirementsInt::class     => Requirements::class,
	Rewrite::class             => Rewrite::class,
	RolesInt::class            => Roles::class,
	SermonComments::class      => SermonComments::class,
	SermonEdit::class          => SermonEdit::class,
	SermonImageAttach::class   => SermonImageAttach::class,
	SermonListTable::class     => SermonListTable::class,
	ShortCodes::class          => ShortCodes::class,
	SCSeriesLatest::class      => SCSeriesLatest::class,
	SCSermonLatest::class      => SCSermonLatest::class,
	SCSermons::class           => SCSermons::class,
	SCSermonSorting::class     => SCSermonSorting::class,
	SCTerms::class             => SCTerms::class,
	TaxonomyImageAttach::class => TaxonomyImageAttach::class,
	TaxonomyListTable::class   => TaxonomyListTable::class,
	TextDomainInt::class       => TextDomain::class,
	Templates::class           => Templates::class,

	/*
	PluginInt::class         => function (): PluginInt {
		return Plugin::exec();
	},
	*/

);
