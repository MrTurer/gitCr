<?php

namespace Bitrix\Rnschatbot;

use Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\EventManager;

class RnsBot extends \Bitrix\ImBot\Bot\Base
{
    const BOT_CODE = "rnsbot";
    const MODULE_ID = "rnschatbot";
    const AGENT_FUNCTION = "\Bitrix\Rnschatbot\RnsBot::noticeAllUsers();";
    const TASKS_NOTICE_TIME = '09:00:00';
    const DEFAULT_DAYS_TO_NOTICE = 4;
    const HL_USER_SETTINGS_NAME = 'RnsBotUserSettings';
    const HL_ENTITIES_NAME = 'Entities';
    const HL_USER_SETTINGS_TABLE_NAME = 'rnsbot_user_settings';

    public static function install()
    {
        if (self::getBotId()) {
            return self::getBotId();
        }

        self::createUserSettingsHL();

        $botId = \Bitrix\Im\Bot::register(array(
            'APP_ID' => "",
            'CODE' => self::BOT_CODE,
            'MODULE_ID' => self::MODULE_ID,
            'CLASS' => __CLASS__,
            'METHOD_MESSAGE_ADD' => 'onMessageAdd',
            'METHOD_WELCOME_MESSAGE' => 'onChatStart',
            'METHOD_BOT_DELETE' => 'onBotDelete',
            'PROPERTIES' => array(
                'NAME' => Loc::getMessage('RNSCHATBOT_RNSBOT_NAME')
            )
        ));
        if ($botId) {
            self::setBotId($botId);

            $date = date('d.m.Y') . ' ' . self::TASKS_NOTICE_TIME;

            \CAgent::AddAgent(
                self::AGENT_FUNCTION,
                self::MODULE_ID,
                "Y",
                86400,
                $date,
                "Y",
                $date
            );

            \Bitrix\Im\Command::register(array(
                'MODULE_ID' => self::MODULE_ID,
                'BOT_ID' => $botId,
                'COMMAND' => 'start',
                'EXTRANET_SUPPORT' => 'Y',
                'CLASS' => __CLASS__,
                'METHOD_COMMAND_ADD' => 'onCommandAdd',
                'METHOD_LANG_GET' => 'onCommandLang'
            ));

            EventManager::getInstance()->registerEventHandler(
                "tasks",
                "OnTaskUpdate",
                self::MODULE_ID,
                "Bitrix\Rnschatbot\RnsBot",
                "noticeOnUpdateTasksAction"
            );
        }

        return $botId;
    }

    public static function createUserSettingsHL()
    {
        $settingsTable = HL\HighloadBlockTable::getList([
            'filter' => [
                'NAME' => self::HL_USER_SETTINGS_NAME
            ]
        ]);
        if ($hldata = $settingsTable->fetch()) {
            return;
        }
        $result = HL\HighloadBlockTable::add(array(
            'NAME' => self::HL_USER_SETTINGS_NAME,
            'TABLE_NAME' => self::HL_USER_SETTINGS_TABLE_NAME,
        ));
        $hlId = $result->getId();
        HL\HighloadBlockLangTable::add(array(
            'ID' => $hlId,
            'LID' => 'ru',
            'NAME' => Loc::getMessage('RNSCHATBOT_RNSBOT_HL_NAME')
        ));
        $UFObject = 'HLBLOCK_' . $hlId;
        $arCartFields = array(
            'UF_USER_ID' => array(
                'ENTITY_ID' => $UFObject,
                'FIELD_NAME' => 'UF_USER_ID',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => array('ru' => 'ИД Пользователя'),
                "LIST_COLUMN_LABEL" => array('ru' => 'ИД Пользователя'),
                "LIST_FILTER_LABEL" => array('ru' => 'ИД Пользователя')
            ),
            'UF_SETTINGS' => array(
                'ENTITY_ID' => $UFObject,
                'FIELD_NAME' => 'UF_SETTINGS',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => array('ru' => 'Настройки'),
                "LIST_COLUMN_LABEL" => array('ru' => 'Настройки'),
                "LIST_FILTER_LABEL" => array('ru' => 'Настройки')
            )
        );
        foreach ($arCartFields as $arCartField) {
            $obUserField = new \CUserTypeEntity;
            $obUserField->Add($arCartField);
        }
    }

    public static function getUserSettingsClass()
    {
        return self::getHLClass(self::HL_USER_SETTINGS_NAME);
    }

    public static function getEntitiesClass()
    {
        return self::getHLClass(self::HL_ENTITIES_NAME);
    }

    public static function getHLClass($name)
    {
        $settingsTable = \Bitrix\Highloadblock\HighloadBlockTable::getList([
            'filter' => [
                'NAME' => $name
            ]
        ]);
        if ($hldata = $settingsTable->fetch()) {
            $hlentity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            return $hlentity->getDataClass();
        }

        return false;
    }

    public static function uninstall()
    {
        $result = \Bitrix\Im\Bot::unRegister(array('BOT_ID' => self::getBotId()));
        if ($result) {
            self::setBotId(0);

            \CAgent::RemoveModuleAgents(
                self::MODULE_ID
            );

            EventManager::getInstance()->unRegisterEventHandler(
                "tasks",
                "OnTaskUpdate",
                self::MODULE_ID,
                "Bitrix\Rnschatbot\RnsBot",
                "noticeOnUpdateTasksAction"
            );
        }

        return $result;
    }

    public static function onChatStart($dialogId, $joinFields)
    {
        $keyboard = self::getSettingsButton();

        \Bitrix\Im\Bot::addMessage(array(
            'BOT_ID' => self::getBotId()
        ),
            array(
                'DIALOG_ID' => $dialogId,
                'MESSAGE' => Loc::getMessage('RNSCHATBOT_RNSBOT_WELCOME_MESSAGE'),
                'KEYBOARD' => $keyboard
            )
        );

        return true;
    }

    public static function getSettingsButton()
    {
        $keyboard = new \Bitrix\Im\Bot\Keyboard(self::getBotId());
        $keyboard->addButton([
            "DISPLAY" => "LINE",
            "TEXT" => Loc::getMessage('RNSCHATBOT_RNSBOT_SETTINGS_BUTTON_NAME'),
            "BG_COLOR" => "#29619b",
            "TEXT_COLOR" => "#fff",
            "BLOCK" => "Y",
            "FUNCTION" => self::getOpenSettingsFunction()
        ]);

        return $keyboard;
    }

    public static function getOpenSettingsFunction()
    {
        return 'BX.SidePanel.Instance.open("rnschatbotsettings", {
                    contentCallback: function (slider) {
                        return new Promise(function (resolve, reject) {
                            BX.ajax.runComponentAction(
                                "rnschatbotsettings",
                                "getForm",
                                {
                                    mode: "class"
                                }
                            ).then(function (response) {
                                if (response.status === "success") {
                                    resolve({
                                        html: response.data.html
                                    });
                                }
                
                            });
                        });
                    },
                    animationDuration: 100,
                    width: 400
                });';
    }

    public static function noticeAllTasksAction($userId)
    {
        if (!\Bitrix\Main\Loader::includeModule('tasks')) {
            return false;
        }

        $tasks = '';
        $settings = self::getUserSettings($userId);

        foreach ($settings['ENTITIES'] as $entityId => $entitySettings) {
            if ($entitySettings['NOTICE']) {
                try {
                    $days = (int)$entitySettings['DAYS'];
                    $toDate = date("d.m.Y H:i", strtotime("+$days day"));
                    $fromDate = date("d.m.Y 23:59", strtotime("-1 day"));
                    $arFilter = [
                        '::LOGIC' => 'AND',
                        'REAL_STATUS' => [\CTasks::STATE_NEW, \CTasks::STATE_PENDING, \CTasks::STATE_IN_PROGRESS],
                        '<DEADLINE' => $toDate,
                        '>DEADLINE' => $fromDate,
                        'UF_TYPE_ENTITY' => $entityId,
                        '::SUBFILTER-1' => [
                            '::LOGIC' => 'OR',
                            '::SUBFILTER-1' => [
                                'CREATED_BY' => [$userId],
                            ],
                            '::SUBFILTER-2' => [
                                'RESPONSIBLE_ID' => [$userId],
                            ],
                        ]
                    ];

                    list($arItems) = \CTaskItem::fetchList($userId, [], $arFilter);
                    foreach ($arItems as $item) {
                        $task = $item->getData(false);
                        $tasks .= self::getTaskMessage($userId, $task);
                    }


                } catch (\Exception $e) {
                    $tasks = 'error';
                }
            }
        }

        if ($tasks) {
            $keyboard = self::getSettingsButton();
            \Bitrix\Im\Bot::addMessage(array('BOT_ID' => self::getBotId()), array(
                'DIALOG_ID' => $userId,
                'MESSAGE' => 'Приближается срок по задачам:[br]' . $tasks,
                'KEYBOARD' => $keyboard
            ));
        }
    }

    public static function getTaskMessage($userId, array $task)
    {
        $pathToUserTask = \COption::GetOptionString("tasks", "paths_task_user_action", null, SITE_ID);
        $pathToUserTask = str_replace("#user_id#", $userId, $pathToUserTask);

        $viewUrl = \CComponentEngine::MakePathFromTemplate(
            $pathToUserTask,
            [
                "task_id" => $task["ID"],
                "action" => "view"
            ]
        );

        return "[br][URL=$viewUrl]{$task['TITLE']}[/URL][br]Крайний срок: {$task["DEADLINE"]}[br]";
    }

    public static function noticeAllUsers()
    {
        $settingsClass = self::getUserSettingsClass();
        $allUserSettings = $settingsClass::getList();

        while ($userSettings = $allUserSettings->fetch()) {
            self::noticeAllTasksAction($userSettings['UF_USER_ID']);
        }

        return self::AGENT_FUNCTION;
    }

    public static function noticeOnUpdateTasksAction($taskId, $arFields)
    {
        $oldArFields = $arFields['META:PREV_FIELDS'];

        if ($oldArFields['DEADLINE'] == $arFields['DEADLINE']) {
            return;
        }

        if ($arFields['CREATED_BY'] != $arFields['CHANGED_BY']) {
            self::noticeUpdateTask($arFields['CREATED_BY'], $arFields);
        }

        if ($arFields['RESPONSIBLE_ID'] != $arFields['CHANGED_BY']) {
            self::noticeUpdateTask($arFields['RESPONSIBLE_ID'], $arFields);
        }
    }

    public static function noticeUpdateTask($userId, $task)
    {
        \Bitrix\Im\Bot::addMessage(array('BOT_ID' => self::getBotId()), array(
            'DIALOG_ID' => $userId,
            'MESSAGE' => 'Был изменен срок по задаче:[br]' . self::getTaskMessage($userId, $task)
        ));
    }

    public static function onCommandLang($command, $lang = null)
    {
        $title = Loc::getMessage('RNSCHATBOT_RNSBOT_COMMAND_' . mb_strtoupper($command) . '_TITLE', null, $lang);
        $params = Loc::getMessage('RNSCHATBOT_RNSBOT_COMMAND_' . mb_strtoupper($command) . '_PARAMS', null, $lang);

        $result = false;
        if ($title <> '') {
            $result = array(
                'TITLE' => $title,
                'PARAMS' => $params
            );
        }

        return $result;
    }

    public static function onCommandAdd($messageId, $messageFields)
    {
        if ($messageFields['SYSTEM'] == 'Y') {
            return false;
        }

        $userId = $messageFields['DIALOG_ID'];

        if ($messageFields['COMMAND'] == 'start') {
            self::noticeAllTasksAction($userId);
        }

        return true;
    }

    public static function getUserSettings($userId): array
    {
        $settingsClass = self::getUserSettingsClass();

        $userSettings = $settingsClass::getList([
            'filter' => [
                'UF_USER_ID' => $userId,
            ]
        ]);

        if ($userSettings = $userSettings->fetch()) {
            $userSettings = json_decode($userSettings['UF_SETTINGS'], true);
        } else {
            $userSettings = [];
        }

        return $userSettings;
    }

    public static function saveUserSettings($userId, array $settings)
    {
        $settingsClass = self::getUserSettingsClass();

        $userSettings = $settingsClass::getList([
            'filter' => [
                'UF_USER_ID' => $userId,
            ]
        ]);

        $data = [
            "UF_USER_ID" => $userId,
            "UF_SETTINGS" => json_encode($settings)
        ];

        if ($userSettings = $userSettings->fetch()) {
            $settingsClass::update($userSettings['ID'], $data);
        } else {
            $settingsClass::add($data);
        }

        \Bitrix\Im\Bot::addMessage(
            [
                'BOT_ID' => self::getBotId()
            ],
            [
                'DIALOG_ID' => $userId,
                'MESSAGE' => Loc::getMessage('RNSCHATBOT_RNSBOT_MESSAGE_SAVE')
            ]
        );

        return true;
    }

    public static function getDefaultNoticeDays()
    {
        return self::DEFAULT_DAYS_TO_NOTICE;
    }

    public static function getEntities()
    {
        $entitiesClass = self::getEntitiesClass();

        $entities = $entitiesClass::getList([
            'select' => ['ID', 'UF_NAME']
        ]);

        $result = [];
        while ($entity = $entities->fetch()) {
            $result[] = [
                'ID' => $entity['ID'],
                'NAME' => $entity['UF_NAME']
            ];
        }

        return $result;
    }
}