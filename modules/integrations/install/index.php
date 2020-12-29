<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

if (class_exists('integrations')) {
    return;
}

class integrations extends CModule
{
    /** @var string */
    public $MODULE_ID;

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    /** @var string */
    public $MODULE_GROUP_RIGHTS = 'Y';

    public function __construct()
    {
        $this->MODULE_ID = 'integrations';
        $this->MODULE_VERSION = '1.0.1';
        $this->MODULE_VERSION_DATE = '2020-12-23 11:45:00';
        $this->MODULE_NAME = Loc::getMessage('INTEGRATIONS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('INTEGRATIONS_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = 'RuNetSoft';
        $this->PARTNER_URI = 'http://www.rns-soft.ru';
    }

    public function doInstall()
    {
        $eventManager = EventManager::getInstance();

        $this->installFiles();
        $this->installDB();
        RegisterModuleDependences('main', 'OnPageStart', $this->MODULE_ID);
        ModuleManager::registerModule($this->MODULE_ID);
    }

    public function installFiles($arParams = array())
    {
        CopyDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);
        return true;
    }

    public function installDB()
    {
        global $APPLICATION;
        global $DB;
        global $errors;

        if(!$DB->Query("SELECT 'x' FROM integration_external_system", true)) {
            $errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/integrations/install/db/" . mb_strtolower($DB->type) . "/install.sql");
        }

        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode('. ', $errors));
            return false;
        }

        $arCount = $DB->Query("select count(id) as CNT from integration_exchange_type", true)->Fetch();
        if (is_array($arCount) && isset($arCount['CNT']) && intval($arCount['CNT']) <= 0) {
            $DB->Query("insert into integration_exchange_type (name, code) values('REST API', 'api')", true);
            $DB->Query("insert into integration_exchange_type (name, code) values('Почтовые сообщения', 'email')", true);
            $DB->Query("insert into integration_exchange_type (name, code) values('Запросы к СУБД', 'database')", true);
            $DB->Query("insert into integration_exchange_type (name, code) values('Импорт/экспорт файлов', 'files')", true);
        }

        $arCount = $DB->Query("select count(id) as CNT from integration_external_system", true)->Fetch();
        if (is_array($arCount) && isset($arCount['CNT']) && intval($arCount['CNT']) <= 0) {
            $DB->Query("insert into integration_external_system (name, code, created_by, modified_by) values('Jira', 'jira', 1, 1)", true);
            $DB->Query("insert into integration_external_system (name, code, created_by, modified_by) values('MS Outlook', 'outlook', 1, 1)", true);
            $DB->Query("insert into integration_external_system (name, code, created_by, modified_by) values('MS Project', 'msproject', 1, 1)", true);
            $DB->Query("insert into integration_external_system (name, code, created_by, modified_by) values('SAP', 'sap', 1, 1)", true);
        }

        return true;
    }

    public function doUninstall()
    {
        $eventManager = EventManager::getInstance();

        UnRegisterModuleDependences('main', 'OnPageStart', $this->MODULE_ID);
        ModuleManager::unregisterModule($this->MODULE_ID);
        $this->uninstallFiles();
        $this->unInstallDB();
    }

    public function uninstallFiles(array $arParams = array())
    {
        DeleteDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/');
        return true;
    }

    public function unInstallDB()
    {
        global $APPLICATION, $DB, $errors;

        $errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/local/modules/integrations/install/db/".mb_strtolower($DB->type)."/uninstall.sql");

        if (!empty($errors)) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }

        return true;
    }
}