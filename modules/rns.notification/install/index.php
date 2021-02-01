<?

class rns_notification extends \CModule
{
    var $MODULE_ID = "rns.notification";
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

        $this->MODULE_NAME = "Уведомления";
        $this->MODULE_DESCRIPTION = "Автоматическая рассылка уведомлений пользователям";
        $this->PARTNER_NAME = 'RuNetSoft';
        $this->PARTNER_URI = 'http://www.rns-soft.ru';
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        if (!\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            return false;
        }
        \Rns\Notification\RnsBot::install();

        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . "/local/modules/{$this->MODULE_ID}/install/components",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components",
            true,
            true
        );
    }

    function DoUninstall()
    {
        if (!\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            return false;
        }
        \Rns\Notification\RnsBot::uninstall();
        DeleteDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . "/local/modules/{$this->MODULE_ID}/install/components",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components"
        );
        UnRegisterModule($this->MODULE_ID);
    }
}

?>