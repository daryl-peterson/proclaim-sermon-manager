<?php
/**
 * App configuration.
 *
 * @package     Proclaim Sermon Manager
 *
 * @author      Daryl Peterson <@gmail.com>
 * @copyright   Copyright (c) 2024, Daryl Peterson
 * @license     https://www.gnu.org/licenses/gpl-3.0.txt
 * @since       1.0.0
 */

namespace DRPPSM;

use DRPPSM\Interfaces\NoticeInt;
use DRPPSM\Interfaces\OptionsInt;
use DRPPSM\Interfaces\PermaLinkInt;
use DRPPSM\Interfaces\PluginInt;
use DRPPSM\Interfaces\PostTypeSetupInt;
use DRPPSM\Interfaces\RequirementCheckInt;
use DRPPSM\Interfaces\RequirementsInt;
use DRPPSM\Interfaces\RolesInt;
use DRPPSM\Interfaces\TextDomainInt;


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
