<?php
/**
 * App configuration.
 *
 * @package     Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPSermonManager;

use DRPSermonManager\Interfaces\NoticeInt;
use DRPSermonManager\Interfaces\OptionsInt;
use DRPSermonManager\Interfaces\PermaLinkInt;
use DRPSermonManager\Interfaces\PluginInt;
use DRPSermonManager\Interfaces\PostTypeSetupInt;
use DRPSermonManager\Interfaces\RequirementCheckInt;
use DRPSermonManager\Interfaces\RequirementsInt;
use DRPSermonManager\Interfaces\RolesInt;
use DRPSermonManager\Interfaces\TextDomainInt;


return array(
	NoticeInt::class           => function () {
		return Notice::init();
	},

	OptionsInt::class          => function () {
		return Options::init();
	},

	PermaLinkInt::class        => PermaLinks::class,
	PluginInt::class           => Plugin::class,
	TextDomainInt::class       => TextDomain::class,
	RequirementCheckInt::class => RequirementCheck::class,
	RequirementsInt::class     => Requirements::class,
	RolesInt::class            => Roles::class,
	PostTypeSetupInt::class    => PostTypeSetup::class,
);
