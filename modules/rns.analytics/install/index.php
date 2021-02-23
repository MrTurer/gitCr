<?

use Bitrix\Main\EventManager;

class rns_analytics extends \CModule
{
    var $MODULE_ID = "rns.analytics";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    function __construct()
    {
        $arModuleVersion = array();

        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = "Аналитика";
        $this->MODULE_DESCRIPTION = "Аналитика посещений страниц портала";
        $this->PARTNER_NAME = 'RuNetSoft';
        $this->PARTNER_URI = 'http://www.rns-soft.ru';
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        $this->InstallFiles();
        $this->InstallEvents();
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . "/local/modules/{$this->MODULE_ID}/install/components",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components",
            true,
            true
        );
    }

    function DoUninstall()
    {
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        UnRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(
            "main",
            "OnEpilog",
            $this->MODULE_ID,
            "Rns\Analytics\Heatmap",
            "onEpilogAction"
        );
        return true;
    }

    public function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            "main",
            "OnEpilog",
            $this->MODULE_ID,
            "Rns\Analytics\Heatmap",
            "onEpilogAction"
        );
        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(__DIR__ . '/js/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js', true, true);
        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(__DIR__ . '/js/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/js/');
        return true;
    }
}

?>