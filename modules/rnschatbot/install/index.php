<?

class rnschatbot extends \CModule
{
    var $MODULE_ID = "rnschatbot";
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

        $this->MODULE_NAME = "Модуль чат-бота уведомлений";
        $this->MODULE_DESCRIPTION = "Автоматическая рассылка уведомений в чате";
        $this->PARTNER_NAME = 'RuNetSoft';
        $this->PARTNER_URI = 'http://www.rns-soft.ru';
    }

    function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        if (!\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            return false;
        }
        \Bitrix\Rnschatbot\RnsBot::install();
    }

    function DoUninstall()
    {
        if (!\Bitrix\Main\Loader::includeModule($this->MODULE_ID)) {
            return false;
        }
        \Bitrix\Rnschatbot\RnsBot::uninstall();
        UnRegisterModule($this->MODULE_ID);
    }
}

?>