<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit672fe16c65cc53aa8b66e730cdfce0de
{
    public static $files = array (
        '1df5f1ca3d8a01ce642e3b1a54844504' => __DIR__ . '/../..' . '/src/Core/Functions/facades.php',
        '2b1771712136d63f0ed4fa70e62557eb' => __DIR__ . '/../..' . '/src/Core/Functions/core.php',
        '0ecdf14f9753bb884fe2a3c55c79a401' => __DIR__ . '/../..' . '/src/Core/Functions/defines.php',
        'd75c4dd93ef9af06b15ee96390970d1c' => __DIR__ . '/../..' . '/src/Core/Functions/misc.php',
        '508f928bcc69d4d7257b594ca5e3ed26' => __DIR__ . '/../..' . '/src/Core/Functions/include.php',
        '18ce892445e99103f067dd10d355c2b0' => __DIR__ . '/../..' . '/src/Core/Functions/interfaces.php',
        '1bf42cb946e1f36ab7e284ac9bd55dd2' => __DIR__ . '/../..' . '/src/Core/Functions/database.php',
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
            0 => __DIR__ . '/../..' . '/tests/unit',
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
        'DRPPSM\\About' => __DIR__ . '/../..' . '/src/About.php',
        'DRPPSM\\Action' => __DIR__ . '/../..' . '/src/Action.php',
        'DRPPSM\\AdminMenu' => __DIR__ . '/../..' . '/src/AdminMenu.php',
        'DRPPSM\\AdminSettings' => __DIR__ . '/../..' . '/src/AdminSettings.php',
        'DRPPSM\\App' => __DIR__ . '/../..' . '/src/App.php',
        'DRPPSM\\BibleLoader' => __DIR__ . '/../..' . '/src/BibleLoader.php',
        'DRPPSM\\Constants\\Bible' => __DIR__ . '/../..' . '/src/Core/Constants/Bible.php',
        'DRPPSM\\Constants\\Caps' => __DIR__ . '/../..' . '/src/Core/Constants/Caps.php',
        'DRPPSM\\Container' => __DIR__ . '/../..' . '/src/Container.php',
        'DRPPSM\\DB\\DbUpdates' => __DIR__ . '/../..' . '/src/DB/DbUpdates.php',
        'DRPPSM\\DB\\M001M000P000' => __DIR__ . '/../..' . '/src/DB/M001M000P000.php',
        'DRPPSM\\Dashboard' => __DIR__ . '/../..' . '/src/Dashboard.php',
        'DRPPSM\\DateUtils' => __DIR__ . '/../..' . '/src/DateUtils.php',
        'DRPPSM\\Deactivator' => __DIR__ . '/../..' . '/src/Deactivator.php',
        'DRPPSM\\Exceptions\\NotfoundException' => __DIR__ . '/../..' . '/src/Core/Exceptions/NotfoundException.php',
        'DRPPSM\\Exceptions\\PluginException' => __DIR__ . '/../..' . '/src/Core/Exceptions/PluginException.php',
        'DRPPSM\\FatalError' => __DIR__ . '/../..' . '/src/FatalError.php',
        'DRPPSM\\Filter' => __DIR__ . '/../..' . '/src/Filter.php',
        'DRPPSM\\FooWidget' => __DIR__ . '/../..' . '/src/FooWidget.php',
        'DRPPSM\\Helper' => __DIR__ . '/../..' . '/src/Helper.php',
        'DRPPSM\\HooksUtils' => __DIR__ . '/../..' . '/src/HooksUtils.php',
        'DRPPSM\\ImageSize' => __DIR__ . '/../..' . '/src/ImageSize.php',
        'DRPPSM\\ImportSM' => __DIR__ . '/../..' . '/src/ImportSM.php',
        'DRPPSM\\Interfaces\\AdminMenuInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/AdminMenuInt.php',
        'DRPPSM\\Interfaces\\BaseInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/BaseInt.php',
        'DRPPSM\\Interfaces\\ContainerInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/ContainerInt.php',
        'DRPPSM\\Interfaces\\DbInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/DbInt.php',
        'DRPPSM\\Interfaces\\Executable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Executable.php',
        'DRPPSM\\Interfaces\\Initable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Initable.php',
        'DRPPSM\\Interfaces\\NoticeInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/NoticeInt.php',
        'DRPPSM\\Interfaces\\PluginInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/PluginInt.php',
        'DRPPSM\\Interfaces\\Registrable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Registrable.php',
        'DRPPSM\\Interfaces\\RequirementsInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/RequirementsInt.php',
        'DRPPSM\\Interfaces\\RolesInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/RolesInt.php',
        'DRPPSM\\Interfaces\\Runable' => __DIR__ . '/../..' . '/src/Core/Interfaces/Runable.php',
        'DRPPSM\\Interfaces\\TextDomainInt' => __DIR__ . '/../..' . '/src/Core/Interfaces/TextDomainInt.php',
        'DRPPSM\\Loader' => __DIR__ . '/../..' . '/src/Loader.php',
        'DRPPSM\\Logger' => __DIR__ . '/../..' . '/src/Logger.php',
        'DRPPSM\\Logging\\LogDatabase' => __DIR__ . '/../..' . '/src/Core/Logging/LogDatabase.php',
        'DRPPSM\\Logging\\LogFile' => __DIR__ . '/../..' . '/src/Core/Logging/LogFile.php',
        'DRPPSM\\Logging\\LogRecord' => __DIR__ . '/../..' . '/src/Core/Logging/LogRecord.php',
        'DRPPSM\\Logging\\LogWritterAbs' => __DIR__ . '/../..' . '/src/Core/Logging/LogWritterAbs.php',
        'DRPPSM\\Logging\\LogWritterInt' => __DIR__ . '/../..' . '/src/Core/Logging/LogWritterInt.php',
        'DRPPSM\\Logging\\LoggerInt' => __DIR__ . '/../..' . '/src/Core/Logging/LoggerInt.php',
        'DRPPSM\\MediaPlayer' => __DIR__ . '/../..' . '/src/MediaPlayer.php',
        'DRPPSM\\Notice' => __DIR__ . '/../..' . '/src/Notice.php',
        'DRPPSM\\Options' => __DIR__ . '/../..' . '/src/Options.php',
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
        'DRPPSM\\SCBase' => __DIR__ . '/../..' . '/src/SCBase.php',
        'DRPPSM\\SCSermonImages' => __DIR__ . '/../..' . '/src/SCSermonImages.php',
        'DRPPSM\\SCSermonLatest' => __DIR__ . '/../..' . '/src/SCSermonLatest.php',
        'DRPPSM\\SCSermons' => __DIR__ . '/../..' . '/src/SCSermons.php',
        'DRPPSM\\SCTerms' => __DIR__ . '/../..' . '/src/SCTerms.php',
        'DRPPSM\\SPAdvanced' => __DIR__ . '/../..' . '/src/SPAdvanced.php',
        'DRPPSM\\SPBase' => __DIR__ . '/../..' . '/src/SPBase.php',
        'DRPPSM\\SPDisplay' => __DIR__ . '/../..' . '/src/SPDisplay.php',
        'DRPPSM\\SPGeneral' => __DIR__ . '/../..' . '/src/SPGeneral.php',
        'DRPPSM\\SPSeries' => __DIR__ . '/../..' . '/src/SPSeries.php',
        'DRPPSM\\SPSermon' => __DIR__ . '/../..' . '/src/SPSermon.php',
        'DRPPSM\\Scheduler' => __DIR__ . '/../..' . '/src/Scheduler.php',
        'DRPPSM\\SchedulerJobs' => __DIR__ . '/../..' . '/src/SchedulerJobs.php',
        'DRPPSM\\Sermon' => __DIR__ . '/../..' . '/src/Sermon.php',
        'DRPPSM\\SermonComments' => __DIR__ . '/../..' . '/src/SermonComments.php',
        'DRPPSM\\SermonDetail' => __DIR__ . '/../..' . '/src/SermonDetail.php',
        'DRPPSM\\SermonEdit' => __DIR__ . '/../..' . '/src/SermonEdit.php',
        'DRPPSM\\SermonFiles' => __DIR__ . '/../..' . '/src/SermonFiles.php',
        'DRPPSM\\SermonImageAttach' => __DIR__ . '/../..' . '/src/SermonImageAttach.php',
        'DRPPSM\\SermonImageList' => __DIR__ . '/../..' . '/src/SermonImageList.php',
        'DRPPSM\\SermonListTable' => __DIR__ . '/../..' . '/src/SermonListTable.php',
        'DRPPSM\\SermonMeta' => __DIR__ . '/../..' . '/src/SermonMeta.php',
        'DRPPSM\\SermonSorting' => __DIR__ . '/../..' . '/src/SermonSorting.php',
        'DRPPSM\\Settings' => __DIR__ . '/../..' . '/src/Settings.php',
        'DRPPSM\\ShortCodes\\Books' => __DIR__ . '/../..' . '/src/ShortCodes/Books.php',
        'DRPPSM\\ShortCodes\\Codes' => __DIR__ . '/../..' . '/src/ShortCodes/Codes.php',
        'DRPPSM\\ShortCodes\\FileTemplate' => __DIR__ . '/../..' . '/src/ShortCodes/FileTemplate.php',
        'DRPPSM\\ShortCodes\\Preachers' => __DIR__ . '/../..' . '/src/ShortCodes/Preachers.php',
        'DRPPSM\\ShortCodes\\Series' => __DIR__ . '/../..' . '/src/ShortCodes/Series.php',
        'DRPPSM\\ShortCodes\\SeriesLatest' => __DIR__ . '/../..' . '/src/ShortCodes/SeriesLatest.php',
        'DRPPSM\\ShortCodes\\SermonArchive' => __DIR__ . '/../..' . '/src/ShortCodes/SermonArchive.php',
        'DRPPSM\\ShortCodes\\ShortCode' => __DIR__ . '/../..' . '/src/ShortCodes/ShortCode.php',
        'DRPPSM\\ShortCodes\\Sorting' => __DIR__ . '/../..' . '/src/ShortCodes/Sorting.php',
        'DRPPSM\\ShortCodes\\TaxArchive' => __DIR__ . '/../..' . '/src/ShortCodes/TaxArchive.php',
        'DRPPSM\\ShortCodes\\TaxImageList' => __DIR__ . '/../..' . '/src/ShortCodes/TaxImageList.php',
        'DRPPSM\\ShortCodes\\TaxShortcode' => __DIR__ . '/../..' . '/src/ShortCodes/TaxShortcode.php',
        'DRPPSM\\ShortCodes\\Topics' => __DIR__ . '/../..' . '/src/ShortCodes/Topics.php',
        'DRPPSM\\TaxBase' => __DIR__ . '/../..' . '/src/TaxBase.php',
        'DRPPSM\\TaxBooks' => __DIR__ . '/../..' . '/src/TaxBooks.php',
        'DRPPSM\\TaxImageAttach' => __DIR__ . '/../..' . '/src/TaxImageAttach.php',
        'DRPPSM\\TaxListTable' => __DIR__ . '/../..' . '/src/TaxListTable.php',
        'DRPPSM\\TaxMeta' => __DIR__ . '/../..' . '/src/TaxMeta.php',
        'DRPPSM\\TaxPreacher' => __DIR__ . '/../..' . '/src/TaxPreacher.php',
        'DRPPSM\\TaxReg' => __DIR__ . '/../..' . '/src/TaxReg.php',
        'DRPPSM\\TaxSeries' => __DIR__ . '/../..' . '/src/TaxSeries.php',
        'DRPPSM\\TaxTopics' => __DIR__ . '/../..' . '/src/TaxTopics.php',
        'DRPPSM\\TaxUtils' => __DIR__ . '/../..' . '/src/TaxUtils.php',
        'DRPPSM\\Template' => __DIR__ . '/../..' . '/src/Template.php',
        'DRPPSM\\TemplateBlocks' => __DIR__ . '/../..' . '/src/TemplateBlocks.php',
        'DRPPSM\\TemplateFiles' => __DIR__ . '/../..' . '/src/TemplateFiles.php',
        'DRPPSM\\Tests\\AdminSermonTest' => __DIR__ . '/../..' . '/tests/unit/AdminSermonTest.php',
        'DRPPSM\\Tests\\AppTest' => __DIR__ . '/../..' . '/tests/unit/AppTest.php',
        'DRPPSM\\Tests\\BaseTest' => __DIR__ . '/../..' . '/tests/unit/BaseTest.php',
        'DRPPSM\\Tests\\BibleLoaderTest' => __DIR__ . '/../..' . '/tests/unit/BibleLoaderTest.php',
        'DRPPSM\\Tests\\Cleanup' => __DIR__ . '/../..' . '/tests/unit/Cleanup.php',
        'DRPPSM\\Tests\\FatalErrorTest' => __DIR__ . '/../..' . '/tests/unit/FatalErrorTest.php',
        'DRPPSM\\Tests\\HelperTest' => __DIR__ . '/../..' . '/tests/unit/HelperTest.php',
        'DRPPSM\\Tests\\HooksUtilsTest' => __DIR__ . '/../..' . '/tests/unit/HooksUtilsTest.php',
        'DRPPSM\\Tests\\ImageSizeTest' => __DIR__ . '/../..' . '/tests/unit/ImageSizeTest.php',
        'DRPPSM\\Tests\\LoggerTest' => __DIR__ . '/../..' . '/tests/unit/LoggerTest.php',
        'DRPPSM\\Tests\\NoticeTest' => __DIR__ . '/../..' . '/tests/unit/NoticeTest.php',
        'DRPPSM\\Tests\\PermaLinkTest' => __DIR__ . '/../..' . '/tests/unit/PermaLinkTest.php',
        'DRPPSM\\Tests\\PluginTest' => __DIR__ . '/../..' . '/tests/unit/PluginTest.php',
        'DRPPSM\\Tests\\PostTypeSetupTest' => __DIR__ . '/../..' . '/tests/unit/PostTypeSetupTest.php',
        'DRPPSM\\Tests\\QueryVarsTest' => __DIR__ . '/../..' . '/tests/unit/QueryVarsTest.php',
        'DRPPSM\\Tests\\RequirementsTest' => __DIR__ . '/../..' . '/tests/unit/RequirementsTest.php',
        'DRPPSM\\Tests\\RolesTest' => __DIR__ . '/../..' . '/tests/unit/RolesTest.php',
        'DRPPSM\\Tests\\SermonCommentsTest' => __DIR__ . '/../..' . '/tests/unit/SermonCommentsTest.php',
        'DRPPSM\\Tests\\SermonDetailTest' => __DIR__ . '/../..' . '/tests/unit/SermonDetailTest.php',
        'DRPPSM\\Tests\\SermonFilesTest' => __DIR__ . '/../..' . '/tests/unit/SermonFilesTest.php',
        'DRPPSM\\Tests\\TaxMetaTest' => __DIR__ . '/../..' . '/tests/unit/TaxMetaTest.php',
        'DRPPSM\\Tests\\TaxUtilsTest' => __DIR__ . '/../..' . '/tests/unit/TaxUtilsTest.php',
        'DRPPSM\\Tests\\TaxonomyRegTest' => __DIR__ . '/../..' . '/tests/unit/TaxonomyRegTest.php',
        'DRPPSM\\Tests\\TemplateBlocksTest' => __DIR__ . '/../..' . '/tests/unit/TemplateBlocksTest.php',
        'DRPPSM\\Tests\\TemplateFilesTest' => __DIR__ . '/../..' . '/tests/unit/TemplateFilesTest.php',
        'DRPPSM\\Tests\\TextDomainTest' => __DIR__ . '/../..' . '/tests/unit/TextDomainTest.php',
        'DRPPSM\\Tests\\TransientTest' => __DIR__ . '/../..' . '/tests/unit/TransientTest.php',
        'DRPPSM\\TextDomain' => __DIR__ . '/../..' . '/src/TextDomain.php',
        'DRPPSM\\Timer' => __DIR__ . '/../..' . '/src/Timer.php',
        'DRPPSM\\Traits\\ExecutableTrait' => __DIR__ . '/../..' . '/src/Core/Traits/ExecutableTrait.php',
        'DRPPSM\\Traits\\OverLoadTrait' => __DIR__ . '/../..' . '/src/Core/Traits/OverLoadTrait.php',
        'DRPPSM\\Traits\\SingletonTrait' => __DIR__ . '/../..' . '/src/Core/Traits/SingletonTrait.php',
        'DRPPSM\\Transient' => __DIR__ . '/../..' . '/src/Transient.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit672fe16c65cc53aa8b66e730cdfce0de::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit672fe16c65cc53aa8b66e730cdfce0de::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit672fe16c65cc53aa8b66e730cdfce0de::$classMap;

        }, null, ClassLoader::class);
    }
}
