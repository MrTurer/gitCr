<?php
use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

if(class_exists("form"))
	return;

Loc::loadMessages(__FILE__);

class industrial_office extends CModule
{
	const MODULE_ID = "industrial.office";
	public $MODULE_ID = self::MODULE_ID;
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;

	/**
	 * constructor
	 * Конструктор для регистрации модуля
	 */
	public function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__) . '/version.php');
		
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

		/* $this->MODULE_NAME = Loc::getMessage('PMO_MODULE_TITLE'); */
		$this->MODULE_NAME = "Производственный офис";
		/* $this->MODULE_DESCRIPTION = Loc::getMessage('PMO_MODULE_DESC'); */
		$this->MODULE_DESCRIPTION = 'Модуль для создания и работы с сущностями';
		$this->PARTNER_NAME = 'RuNetSoft';
        $this->PARTNER_URI = 'http://www.rns-soft.ru';
	}

	/**
	 * DoInstall
	 * Установка модуля и регистрация
	 */
	public function DoInstall()
	{
		ModuleManager::registerModule(self::MODULE_ID);
		$this->InstallEvents();
		$this->InstallFiles();
		$this->DoInstallAgents();
	}

	/**
	 * DoUninstall
	 * Удаление модуля
	 */
	public function DoUninstall()
	{
		$this->UnInstallEvents();
		$this->UnInstallFiles();
		$this->DoUnInstallAgents();
		ModuleManager::unRegisterModule(self::MODULE_ID);
	}

	/**
	 * InstallEvents
	 * метод установки обработчиков событий
	 * @return bool
	 */
	public function InstallEvents()
	{
		return true;
	}
	/**
	 * UnInstallEvents
	 * Метод удаления обработчиков событий
	 * @return bool
	 */
	public function UnInstallEvents()
	{
		return true;
	}

	/**
	 * DoInstallAgents
	 * метод регистрации агентов
	 * @return bool
	 */
	public function DoInstallAgents()
	{
		return true;
	}

	/**
	 * sm_js DoInstallAgents
	 * метод удаления агентов
	 * @return bool
	 */
	public function DoUnInstallAgents()
	{
		\CAgent::RemoveModuleAgents($this->MODULE_ID);
		return true;
	}

	/**
	 * sm_js InstallFiles
	 * Метод копирования файлов
	 * @return bool
	 */
	public function InstallFiles()
	{
		CopyDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true, true);
		CopyDirFiles(__DIR__ . '/templates/', $_SERVER['DOCUMENT_ROOT'] . '/local/templates/.default/components/bitrix', true, true);
		return true;
	}

	/**
	 * sm_js UnInstallFiles
	 * Метод удаления файлов
	 * @return bool
	 */
	public function UnInstallFiles()
	{
		DeleteDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/');
		DeleteDirFiles(__DIR__ . '/templates/', $_SERVER['DOCUMENT_ROOT'] . '/local/templates/.default/components/bitrix');
		return true;
	}
}