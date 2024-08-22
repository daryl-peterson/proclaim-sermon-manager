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

use DRPPSM\Interfaces\BibleLoaderInt;
use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\PermaLinkInt;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\Interfaces\RolesInt;
use DRPPSM\Interfaces\TextDomainInt;
use DRPPSM\Interfaces\ImageSizeInt;
use DRPPSM\Interfaces\RewriteInt;
use DRPPSM\Logging\LogDatabase;
use DRPPSM\Logging\LogWritterInt;

return array(
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
		return LogDatabase::exec();
	},
	RewriteInt::class       => function (): RewriteInt {
		return Rewrite::exec();
	},

	PermaLinkInt::class     => PermaLinks::class,
	PluginInt::class        => Plugin::class,

	PostTypeSetupInt::class => PostTypeSetup::class,
);
