<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf5da42b7feb65ba592bc9e68a42f25f4
{
    public static $files = array (
        'd75c4dd93ef9af06b15ee96390970d1c' => __DIR__ . '/../..' . '/src/Core/Functions/misc.php',
        '508f928bcc69d4d7257b594ca5e3ed26' => __DIR__ . '/../..' . '/src/Core/Functions/include.php',
        '18ce892445e99103f067dd10d355c2b0' => __DIR__ . '/../..' . '/src/Core/Functions/interfaces.php',
        '1bf42cb946e1f36ab7e284ac9bd55dd2' => __DIR__ . '/../..' . '/src/Core/Functions/database.php',
        'b10806ebc85e93fdf657c73b9e090110' => __DIR__ . '/../..' . '/src/Core/Functions/messages.php',
        '8e0760fd21128b00441ec5c4abbdfb75' => __DIR__ . '/../..' . '/src/Core/Functions/templates.php',
    );

    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DRPPSM\\Traits\\' => 14,
            'DRPPSM\\Tests\\' => 13,
            'DRPPSM\\Logging\\' => 15,
            'DRPPSM\\Interfaces\\' => 18,
            'DRPPSM\\Exceptions\\' => 18,
            'DRPPSM\\Constants\\' => 17,
            'DRPPSM\\Abstracts\\' => 17,
            'DRPPSM\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DRPPSM\\Traits\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core/Traits',
        ),
        'DRPPSM\\Tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'DRPPSM\\Logging\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core/Logging',
        ),
        'DRPPSM\\Interfaces\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core/Interfaces',
        ),
        'DRPPSM\\Exceptions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core/Exceptions',
        ),
        'DRPPSM\\Constants\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core/Constants',
        ),
        'DRPPSM\\Abstracts\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Core/Abstracts',
        ),
        'DRPPSM\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DRPPSM\\Activator' => __DIR__ . '/../..' . '/src/Activator.php',
        'DRPPSM\\AdminMenu' => __DIR__ . '/../..' . '/src/AdminMenu.php',
        'DRPPSM\\AdminSettings' => __DIR__ . '/../..' . '/src/AdminSettings.php',
        'DRPPSM\\App' => __DIR__ . '/../..' . '/src/App.php',
        'DRPPSM\\BibleLoader' => __DIR__ . '/../..' . '/src/BibleLoader.php',
        'DRPPSM\\Constants\\Actions' => __DIR__ . '/../..' . '/src/Core/Constants/Actions.php',
        'DRPPSM\\Constants\\Bible' => __DIR__ . '/../..' . '/src/Core/Constants/Bible.php',
        'DRPPSM\\Constants\\Caps' => __DIR__ . '/../..' . '/src/Core/Constants/Caps.php',
        'DRPPSM\\Constants\\Filters' => __DIR__ . '/../..' . '/src/Core/Constants/Filters.php',
        'DRPPSM\\Constants\\Meta' => __DIR__ . '/../..' . '/src/Core/Constants/Meta.php',
        'DRPPSM\\Constants\\PT' => __DIR__ . '/../..' . '/src/Core/Constants/PT.php',
        'DRPPSM\\Constants\\Tax' => __DIR__ . '/../..' . '/src/Core/Constants/Tax.php',
        'DRPPSM\\Container' => __DIR__ . '/../..' . '/src/Container.php',
        'DRPPSM\\DB\\DB' => __DIR__ . '/../..' . '/src/DB/DB.php',
        'DRPPSM\\DB\\DbUpdates' => __DIR__ . '/../..' . '/src/DB/DbUpdates.php',
        'DRPPSM\\DB\\M001M000P000' => __DIR__ . '/../..' . '/src/DB/M001M000P000.php',
        'DRPPSM\\DateUtils' => __DIR__ . '/../..' . '/src/DateUtils.php',
        'DRPPSM\\Deactivator' => __DIR__ . '/../..' . '/src/Deactivator.php',
        'DRPPSM\\Exceptions\\NotfoundException' => __DIR__ . '/../..' . '/src/Core/Exceptions/NotfoundException.php',
        'DRPPSM\\Exceptions\\PluginException' => __DIR__ . '/../..' . '/src/Core/Exceptions/PluginException.php',
        'DRPPSM\\FatalError' => __DIR__ . '/../..' . '/src/FatalError.php',
        'DRPPSM\\Helper' => __DIR__ . '/../..' . '/src/Helper.php',
        'DRPPSM\\HooksUtils' => __DIR__ . '/../..' . '/src/HooksUtils.php',
        'DRPPSM\\ImageSize' => __DIR__ . '/../..' . '/src/ImageSize.php',
        'DRPPSM\\Interfaces\\AdminMenuInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/AdminMenuInt.php',
        'DRPPSM\\Interfaces\\BaseInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/BaseInt.php',
        'DRPPSM\\Interfaces\\BibleLoaderInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/BibleLoaderInt.php',
        'DRPPSM\\Interfaces\\ContainerInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/ContainerInt.php',
        'DRPPSM\\Interfaces\\DbInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/DbInt.php',
        'DRPPSM\\Interfaces\\Executable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Executable.php',
        'DRPPSM\\Interfaces\\ImageSizeInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/ImageSizeInt.php',
        'DRPPSM\\Interfaces\\Initable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Initable.php',
        'DRPPSM\\Interfaces\\NoticeInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/NoticeInt.php',
        'DRPPSM\\Interfaces\\OptionsInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/OptionsInt.php',
        'DRPPSM\\Interfaces\\PermaLinkInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/PermaLinkInt.php',
        'DRPPSM\\Interfaces\\PluginInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/PluginInt.php',
        'DRPPSM\\Interfaces\\PostTypeRegInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/PostTypeRegInt.php',
        'DRPPSM\\Interfaces\\PostTypeSetupInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/PostTypeSetupInt.php',
        'DRPPSM\\Interfaces\\Registrable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Registrable.php',
        'DRPPSM\\Interfaces\\Removable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Removable.php',
        'DRPPSM\\Interfaces\\RequirementsInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/RequirementsInt.php',
        'DRPPSM\\Interfaces\\RewriteInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/RewriteInt.php',
        'DRPPSM\\Interfaces\\RolesInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/RolesInt.php',
        'DRPPSM\\Interfaces\\Runable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Runable.php',
        'DRPPSM\\Interfaces\\TaxonomyRegInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/TaxonomyRegInt.php',
        'DRPPSM\\Interfaces\\TextDomainInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/TextDomainInt.php',
        'DRPPSM\\Loader' => __DIR__ . '/../..' . '/src/Loader.php',
        'DRPPSM\\Logger' => __DIR__ . '/../..' . '/src/Logger.php',
        'DRPPSM\\Logging\\LogDatabase' => __DIR__ . '/../..' . '/src/Core/Logging/LogDatabase.php',
        'DRPPSM\\Logging\\LogFile' => __DIR__ . '/../..' . '/src/Core/Logging/LogFile.php',
        'DRPPSM\\Logging\\LogRecord' => __DIR__ . '/../..' . '/src/Core/Logging/LogRecord.php',
        'DRPPSM\\Logging\\LogWritterAbs' => __DIR__ . '/../..' . '/src/Core/Logging/LogWritterAbs.php',
        'DRPPSM\\Logging\\LogWritterInt' => __DIR__ . '/../..' . '/src/Core/Logging/LogWritterInt.php',
        'DRPPSM\\Logging\\LoggerInt' => __DIR__ . '/../..' . '/src/Core/Logging/LoggerInt.php',
        'DRPPSM\\Notice' => __DIR__ . '/../..' . '/src/Notice.php',
        'DRPPSM\\OptAdvance' => __DIR__ . '/../..' . '/src/OptAdvance.php',
        'DRPPSM\\OptGeneral' => __DIR__ . '/../..' . '/src/OptGeneral.php',
        'DRPPSM\\Options' => __DIR__ . '/../..' . '/src/Options.php',
        'DRPPSM\\Pagination' => __DIR__ . '/../..' . '/src/Pagination.php',
        'DRPPSM\\PermaLinks' => __DIR__ . '/../..' . '/src/PermaLinks.php',
        'DRPPSM\\Plugin' => __DIR__ . '/../..' . '/src/Plugin.php',
        'DRPPSM\\PostTypeReg' => __DIR__ . '/../..' . '/src/PostTypeReg.php',
        'DRPPSM\\PostTypeSetup' => __DIR__ . '/../..' . '/src/PostTypeSetup.php',
        'DRPPSM\\PostTypeUtils' => __DIR__ . '/../..' . '/src/PostTypeUtils.php',
        'DRPPSM\\QueryVars' => __DIR__ . '/../..' . '/src/QueryVars.php',
        'DRPPSM\\QueueScripts' => __DIR__ . '/../..' . '/src/QueueScripts.php',
        'DRPPSM\\Requirements' => __DIR__ . '/../..' . '/src/Requirements.php',
        'DRPPSM\\Rewrite' => __DIR__ . '/../..' . '/src/Rewrite.php',
        'DRPPSM\\Roles' => __DIR__ . '/../..' . '/src/Roles.php',
        'DRPPSM\\SermonComments' => __DIR__ . '/../..' . '/src/SermonComments.php',
        'DRPPSM\\SermonDetail' => __DIR__ . '/../..' . '/src/SermonDetail.php',
        'DRPPSM\\SermonEdit' => __DIR__ . '/../..' . '/src/SermonEdit.php',
        'DRPPSM\\SermonFiles' => __DIR__ . '/../..' . '/src/SermonFiles.php',
        'DRPPSM\\SermonImage' => __DIR__ . '/../..' . '/src/SermonImage.php',
        'DRPPSM\\SermonListTable' => __DIR__ . '/../..' . '/src/SermonListTable.php',
        'DRPPSM\\Settings' => __DIR__ . '/../..' . '/src/Settings.php',
        'DRPPSM\\TaxUtils' => __DIR__ . '/../..' . '/src/TaxUtils.php',
        'DRPPSM\\TaxonomyImage' => __DIR__ . '/../..' . '/src/TaxonomyImage.php',
        'DRPPSM\\TaxonomyListTable' => __DIR__ . '/../..' . '/src/TaxonomyListTable.php',
        'DRPPSM\\TaxonomyReg' => __DIR__ . '/../..' . '/src/TaxonomyReg.php',
        'DRPPSM\\TemplateSermon' => __DIR__ . '/../..' . '/src/TemplateSermon.php',
        'DRPPSM\\Templates' => __DIR__ . '/../..' . '/src/Templates.php',
        'DRPPSM\\Tests\\ActivateDeactiveTest' => __DIR__ . '/../..' . '/tests/ActivateDeactiveTest.php',
        'DRPPSM\\Tests\\AdminSermonTest' => __DIR__ . '/../..' . '/tests/AdminSermonTest.php',
        'DRPPSM\\Tests\\AppTest' => __DIR__ . '/../..' . '/tests/AppTest.php',
        'DRPPSM\\Tests\\BaseTest' => __DIR__ . '/../..' . '/tests/BaseTest.php',
        'DRPPSM\\Tests\\ContainerTest' => __DIR__ . '/../..' . '/tests/ContainerTest.php',
        'DRPPSM\\Tests\\FatalErrorTest' => __DIR__ . '/../..' . '/tests/FatalErrorTest.php',
        'DRPPSM\\Tests\\HelperTest' => __DIR__ . '/../..' . '/tests/HelperTest.php',
        'DRPPSM\\Tests\\HooksUtilsTest' => __DIR__ . '/../..' . '/tests/HooksUtilsTest.php',
        'DRPPSM\\Tests\\ImageSizeTest' => __DIR__ . '/../..' . '/tests/ImageSizeTest.php',
        'DRPPSM\\Tests\\LoggerTest' => __DIR__ . '/../..' . '/tests/LoggerTest.php',
        'DRPPSM\\Tests\\NoticeTest' => __DIR__ . '/../..' . '/tests/NoticeTest.php',
        'DRPPSM\\Tests\\OptionsTest' => __DIR__ . '/../..' . '/tests/OptionsTest.php',
        'DRPPSM\\Tests\\PermaLinkTest' => __DIR__ . '/../..' . '/tests/PermaLinkTest.php',
        'DRPPSM\\Tests\\PluginTest' => __DIR__ . '/../..' . '/tests/PluginTest.php',
        'DRPPSM\\Tests\\PostTypeSetupTest' => __DIR__ . '/../..' . '/tests/PostTypeSetupTest.php',
        'DRPPSM\\Tests\\QueryVarsTest' => __DIR__ . '/../..' . '/tests/QueryVarsTest.php',
        'DRPPSM\\Tests\\RequirementsTest' => __DIR__ . '/../..' . '/tests/RequirementsTest.php',
        'DRPPSM\\Tests\\RolesTest' => __DIR__ . '/../..' . '/tests/RolesTest.php',
        'DRPPSM\\Tests\\SermonCommentsTest' => __DIR__ . '/../..' . '/tests/SermonCommentsTest.php',
        'DRPPSM\\Tests\\SermonDetailTest' => __DIR__ . '/../..' . '/tests/SermonDetailTest.php',
        'DRPPSM\\Tests\\SermonFilesTest' => __DIR__ . '/../..' . '/tests/SermonFilesTest.php',
        'DRPPSM\\Tests\\TaxUtilsTest' => __DIR__ . '/../..' . '/tests/TaxUtilsTest.php',
        'DRPPSM\\Tests\\TaxonomyRegTest' => __DIR__ . '/../..' . '/tests/TaxonomyRegTest.php',
        'DRPPSM\\Tests\\TextDomainTest' => __DIR__ . '/../..' . '/tests/TextDomainTest.php',
        'DRPPSM\\Tests\\XTest' => __DIR__ . '/../..' . '/tests/XTest.php',
        'DRPPSM\\TextDomain' => __DIR__ . '/../..' . '/src/TextDomain.php',
        'DRPPSM\\Traits\\OverLoadTrait' => __DIR__ . '/../..' . '/src/Core/Traits/OverLoadTrait.php',
        'DRPPSM\\Traits\\SingletonTrait' => __DIR__ . '/../..' . '/src/Core/Traits/SingletonTrait.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf5da42b7feb65ba592bc9e68a42f25f4::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf5da42b7feb65ba592bc9e68a42f25f4::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf5da42b7feb65ba592bc9e68a42f25f4::$classMap;

        }, null, ClassLoader::class);
    }
}
