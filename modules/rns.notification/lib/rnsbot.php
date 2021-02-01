<?php

namespace Rns\Notification;

use Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\EventManager;
use Bitrix\Main\Config\Option;

class RnsBot extends \Bitrix\ImBot\Bot\Base
{
    const BOT_CODE = "rnsbot";
    const MODULE_ID = "rns.notification";
    const AGENT_FUNCTION = "\Rns\Notification\RnsBot::noticeAllUsers();";
    const TASKS_NOTICE_TIME = '09:00:00';
    const HL_USER_SETTINGS_NAME = 'RnsBotUserSettings';
    const HL_ENTITIES_NAME = 'Entities';
    const HL_USER_SETTINGS_TABLE_NAME = 'rnsbot_user_settings';

    public static function install()
    {
        if (self::getBotId()) {
            return self::getBotId();
        }

        self::createUserSettingsHL();

        return self::register();
    }

    public static function register()
    {
        if (self::getBotId()) {
            return self::getBotId();
        }

        $botId = \Bitrix\Im\Bot::register(array(
            'APP_ID' => "",
            'CODE' => self::BOT_CODE,
            'MODULE_ID' => self::MODULE_ID,
            'CLASS' => __CLASS__,
            'METHOD_MESSAGE_ADD' => 'onMessageAdd',
            'METHOD_WELCOME_MESSAGE' => 'onChatStart',
            'METHOD_BOT_DELETE' => 'onBotDelete',
            'PROPERTIES' => array(
                'NAME' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_NAME'),
                'COLOR' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_COLOR')
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
                "Rns\Notification\RnsBot",
                "noticeOnUpdateTasksAction"
            );
        }

        return $botId;
    }

    public static function update()
    {
        if (!self::getBotId()) {
            return false;
        }

        \Bitrix\Im\Bot::update(
            [
                'BOT_ID' => self::getBotId()
            ],
            [
                'PROPERTIES' => [
                    'NAME' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_NAME'),
                    'COLOR' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_COLOR'),
                    'PERSONAL_PHOTO' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_ICON')
                ]
            ]
        );

        return true;
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
            'NAME' => Loc::getMessage('RNSNOTIFICATION_RNSBOT_HL_NAME')
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

    public static function deleteUserSettingsHL()
    {
        $settingsTable = HL\HighloadBlockTable::getList([
            'filter' => [
                'NAME' => self::HL_USER_SETTINGS_NAME
            ]
        ]);
        if ($hldata = $settingsTable->fetch()) {
            HL\HighloadBlockTable::delete($hldata['ID']);
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
        self::deleteUserSettingsHL();
        return self::unregister();
    }

    public static function unregister()
    {
        if (!self::getBotId()) {
            return true;
        }
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
                "Rns\Notification\RnsBot",
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
                'MESSAGE' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_WELCOME_MESSAGE'),
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
            "TEXT" => Loc::getMessage('RNSNOTIFICATION_RNSBOT_SETTINGS_BUTTON_NAME'),
            "BG_COLOR" => "#29619b",
            "TEXT_COLOR" => "#fff",
            "BLOCK" => "Y",
            "FUNCTION" => self::getOpenSettingsFunction()
        ]);

        return $keyboard;
    }

    public static function getOpenSettingsFunction()
    {
        return 'BX.SidePanel.Instance.open("rns:chatbotsettings", {
                    contentCallback: function (slider) {
                        return new Promise(function (resolve, reject) {
                            BX.ajax.runComponentAction(
                                "rns:chatbotsettings",
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
        if (!\Bitrix\Main\Loader::includeModule('socialnetwork')) {
            return false;
        }

        $tasksArr = [];
        $tasksTodayArr = [];
        $settings = self::getUserSettings($userId);

        foreach ($settings['ENTITIES'] as $entityId => $entitySettings) {
            if ($entitySettings['NOTICE'] == 'Y') {
                try {
                    $days = (int)$entitySettings['DAYS'];
                    $toDate = date("d.m.Y 23:59", strtotime("+$days day"));
                    $fromDate = date("d.m.Y 00:00", strtotime("+$days day"));
                    $arFilter = self::getTasksFilter($userId, $entityId, $fromDate, $toDate);
                    $template = Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_DAYS_DEADLINE_TEMPLATE_CONTENT');

                    list($arItems) = \CTaskItem::fetchList($userId, [], $arFilter);
                    foreach ($arItems as $item) {
                        $task = $item->getData(false);
                        $tasksArr[$task['GROUP_ID']] .= self::getTaskMessage($userId, $task, $template);
                    }
                } catch (\Exception $e) {
                }
            }
            if ($entitySettings['TODAY_DEADLINE'] == 'Y') {
                try {
                    $fromDate = date("d.m.Y H:i");
                    $toDate = date("d.m.Y 23:59");
                    $arFilter = self::getTasksFilter($userId, $entityId, $fromDate, $toDate);
                    $template = Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_TODAY_DEADLINE_TEMPLATE_CONTENT');

                    list($arItems) = \CTaskItem::fetchList($userId, [], $arFilter, [], ['*']);
                    foreach ($arItems as $item) {
                        $task = $item->getData(false);
                        $tasksTodayArr[$task['GROUP_ID']] .= self::getTaskMessage($userId, $task, $template);
                    }
                } catch (\Exception $e) {
                }
            }
        }

        foreach ($tasksTodayArr as $groupId => $tasksTodayString) {
            $titleTemplate = Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_TODAY_DEADLINE_TEMPLATE_TITLE');
            self::sendTasksMessage($userId, $tasksTodayString, $groupId, $titleTemplate);

        }
        foreach ($tasksArr as $groupId => $tasksString) {
            $titleTemplate = Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_DAYS_DEADLINE_TEMPLATE_TITLE');
            self::sendTasksMessage($userId, $tasksString, $groupId, $titleTemplate);
        }
    }

    public static function getTasksFilter($userId, $entityId, $fromDate, $toDate)
    {
        $arFilter = [
            '::LOGIC' => 'AND',
            'REAL_STATUS' => [\CTasks::STATE_NEW, \CTasks::STATE_PENDING, \CTasks::STATE_IN_PROGRESS],
            '<DEADLINE' => $toDate,
            '>DEADLINE' => $fromDate,
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
        if ($entityId) {
            $arFilter['UF_TYPE_ENTITY'] = $entityId;
        }
        return $arFilter;
    }

    public static function getTaskMessage($userId, array $task, $template)
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

        return str_replace(
            [
                '#url#',
                '#title#',
                '#deadline#'
            ],
            [
                $viewUrl,
                $task['TITLE'],
                $task["DEADLINE"]
            ],
            $template
        );
    }

    public static function sendTasksMessage($userId, $tasksMessage, $groupId, $title)
    {
        $groupUrl = '/';
        $groupName = ' --- ';
        if ($groupId) {
            $group = \CSocNetGroup::GetByID($groupId);
            $groupUrl = "/workgroups/group/$groupId/";
            $groupName = $group['NAME'];
        }
        $title = str_replace(
            [
                '#url#',
                '#title#'
            ],
            [
                $groupUrl,
                $groupName
            ],
            $title
        );
        $keyboard = self::getSettingsButton();
        \Bitrix\Im\Bot::addMessage(array('BOT_ID' => self::getBotId()), array(
            'DIALOG_ID' => $userId,
            'MESSAGE' => $title . $tasksMessage,
            'KEYBOARD' => $keyboard
        ));
    }

    public static function noticeAllUsers()
    {
        $allUsers = \CUser::GetList(($by = "ID"), ($order = "ASC"), [
            'ACTIVE' => 'Y',
            'EXTERNAL_AUTH_ID' => false
        ]);

        while ($arUser = $allUsers->Fetch()) {
            self::noticeAllTasksAction($arUser['ID']);
        }

        return self::AGENT_FUNCTION;
    }

    public static function noticeOnUpdateTasksAction($taskId, $arFields)
    {
        $oldArFields = $arFields['META:PREV_FIELDS'];

        $createdBy = $oldArFields['CREATED_BY'];
        $responsibleId = isset($arFields['RESPONSIBLE_ID']) ? $arFields['RESPONSIBLE_ID'] : $oldArFields['RESPONSIBLE_ID'];
        $changedBy = $arFields['CHANGED_BY'];

        if ($oldArFields['DEADLINE'] == $arFields['DEADLINE']) {
            return;
        }

        $arFields['TITLE'] = isset($arFields['TITLE']) ? $arFields['TITLE'] : $oldArFields['TITLE'];

        $entityId = $oldArFields['UF_TYPE_ENTITY'];

        if ($createdBy != $changedBy) {
            $settings = self::getUserSettings($createdBy);
            if ($settings['ENTITIES'][$entityId]['CHANGE_DEADLINE'] == 'Y' || $settings['ENTITIES'][0]['CHANGE_DEADLINE'] == 'Y') {
                self::noticeUpdateTask($createdBy, $arFields);
            }
        }

        if ($responsibleId != $changedBy && $responsibleId != $createdBy) {
            $settings = self::getUserSettings($responsibleId);
            if ($settings['ENTITIES'][$entityId]['CHANGE_DEADLINE'] == 'Y' || $settings['ENTITIES'][0]['CHANGE_DEADLINE'] == 'Y') {
                self::noticeUpdateTask($responsibleId, $arFields);
            }
        }
    }

    public static function noticeUpdateTask($userId, $task)
    {
        $template = Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHANGE_DEADLINE_TEMPLATE_CONTENT');
        $titleTemplate = Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHANGE_DEADLINE_TEMPLATE_TITLE');
        $taskString = self::getTaskMessage($userId, $task, $template);
        self::sendTasksMessage($userId, $taskString, 0, $titleTemplate);
    }

    public static function onCommandLang($command, $lang = null)
    {
        $title = Loc::getMessage('RNSNOTIFICATION_RNSBOT_COMMAND_' . mb_strtoupper($command) . '_TITLE', null, $lang);
        $params = Loc::getMessage('RNSNOTIFICATION_RNSBOT_COMMAND_' . mb_strtoupper($command) . '_PARAMS', null, $lang);

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
            $userSettings = [
                'ENTITIES' => [
                    [
                        'NOTICE' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_NOTICE'),
                        'DAYS' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_DAYS'),
                        'CHANGE_DEADLINE' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_CHANGE'),
                        'TODAY_DEADLINE' => Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_TODAY_DEADLINE')
                    ]
                ]
            ];
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
                'MESSAGE' => Loc::getMessage('RNSNOTIFICATION_RNSBOT_MESSAGE_SAVE')
            ]
        );

        return true;
    }

    public static function getDefaultNoticeDays()
    {
        return Option::get(self::MODULE_ID, 'RNSNOTIFICATION_OPT_CHATBOT_DAYS');
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