<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Response\Component;
use Bitrix\Main\Loader;

class RnschatbotSettings extends \CBitrixComponent implements Controllerable
{
    public function configureActions()
    {
        return [];
    }

    public function getFormAction()
    {
        return new Component('rnschatbotsettings');
    }

    public function saveFormAction($settings)
    {
        if (!Loader::includeModule('rnschatbot')) {
            echo GetMessage("ACCESS_DENIED");
            return false;
        }
        global $USER;
        $entities = \Bitrix\Rnschatbot\RnsBot::getEntities();

        $userSettings = [];
        foreach ($entities as $entity) {
            $noticeCode = 'notice' . $entity['ID'];
            $onChangeDeadline = 'deadline' . $entity['ID'];
            $daysCode = 'days' . $entity['ID'];
            $notice = isset($settings[$noticeCode]) && $settings[$noticeCode] == 'on';
            $onChangeDeadline = isset($settings[$onChangeDeadline]) && $settings[$onChangeDeadline] == 'on';
            $days = isset($settings[$daysCode]) && (int)$settings[$daysCode] ?
                (int)$settings[$daysCode] : \Bitrix\Rnschatbot\RnsBot::getDefaultNoticeDays();
            $userSettings[$entity['ID']] = [
                'NOTICE' => $notice,
                'CHANGE_DEADLINE' => $onChangeDeadline,
                'DAYS' => $days
            ];
        }
        $userSettings = [
            'ENTITIES' => $userSettings
        ];

        \Bitrix\Rnschatbot\RnsBot::saveUserSettings($USER->GetID(), $userSettings);

        return $userSettings;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('rnschatbot')) {
            echo GetMessage("ACCESS_DENIED");
            return false;
        }
        global $USER;

        $userSettings = \Bitrix\Rnschatbot\RnsBot::getUserSettings($USER->GetID());
        $entities = \Bitrix\Rnschatbot\RnsBot::getEntities();

        $this->arResult['USER_SETTINGS'] = $userSettings;
        $this->arResult['ENTITIES'] = $entities;

        $this->includeComponentTemplate();
    }
}