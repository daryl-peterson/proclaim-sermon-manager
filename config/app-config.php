<?php
/**
 * App configuration.
 *
 * @package     Proclaim Sermon Manager
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

defined( 'ABSPATH' ) || exit;

<<<<<<< HEAD
use DRPPSM\Interfaces\BibleLoaderInt;
use DRPPSM\Interfaces\NoticeInt;
=======
use DRPPSM\DB\DbUpdates;
use DRPPSM\Interfaces\BibleLoaderInt;
>>>>>>> 822b76c (Refactoring)
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\PermaLinkInt;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\Interfaces\RolesInt;
use DRPPSM\Interfaces\TextDomainInt;
use DRPPSM\Interfaces\ImageSizeInt;
<<<<<<< HEAD
use DRPPSM\Interfaces\RewriteInt;
use DRPPSM\Logging\LogDatabase;
=======
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\RewriteInt;
>>>>>>> 822b76c (Refactoring)
use DRPPSM\Logging\LogFile;
use DRPPSM\Logging\LogWritterInt;

return array(
<<<<<<< HEAD
	NoticeInt::class        => function (): NoticeInt {
		return Notice::init();
	},

	OptionsInt::class       => function (): OptionsInt {
		return Options::init();
	},

	RolesInt::class         => function (): RolesInt {
		return Roles::exec();
	},

	RequirementsInt::class  => function (): RequirementsInt {
		return Requirements::exec();
	},

	TextDomainInt::class    => function (): TextDomainInt {
		return TextDomain::exec();
	},

	ImageSizeInt::class     => function (): ImageSizeInt {
		return ImageSize::exec();
	},

	BibleLoaderInt::class   => function (): BibleLoaderInt {
		return BibleLoader::exec();
	},
	LogWritterInt::class    => function (): LogWritterInt {
		return LogFile::exec();
	},
	RewriteInt::class       => function (): RewriteInt {
		return Rewrite::exec();
	},

	PermaLinkInt::class     => PermaLinks::class,
	PluginInt::class        => Plugin::class,

	PostTypeSetupInt::class => PostTypeSetup::class,
=======

	AdminMenu::class         => AdminMenu::class,
	AdminSettings::class     => AdminSettings::class,
	BibleLoaderInt::class    => BibleLoader::class,
	DbUpdates::class         => DbUpdates::class,
	ImageSizeInt::class      => ImageSize::class,
	LogWritterInt::class     => LogFile::class,
	NoticeInt::class         => Notice::class,
	OptionsInt::class        => Options::class,
	Pagination::class        => Pagination::class,
	PermaLinkInt::class      => PermaLinks::class,
	PluginInt::class         => Plugin::class,
	PostTypeSetupInt::class  => PostTypeSetup::class,
	QueryVars::class         => QueryVars::class,
	QueueScripts::class      => QueueScripts::class,
	RequirementsInt::class   => Requirements::class,
	RewriteInt::class        => Rewrite::class,
	RolesInt::class          => Roles::class,
	SermonComments::class    => SermonComments::class,
	SermonEdit::class        => SermonEdit::class,
	SermonImage::class       => SermonImage::class,
	SermonListTable::class   => SermonListTable::class,
	TaxonomyImage::class     => TaxonomyImage::class,
	TaxonomyListTable::class => TaxonomyListTable::class,
	TextDomainInt::class     => TextDomain::class,
	Templates::class         => Templates::class,

	/*
	PluginInt::class         => function (): PluginInt {
		return Plugin::exec();
	},
	*/


>>>>>>> 822b76c (Refactoring)
);
