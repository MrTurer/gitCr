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
        if (!Loader::includeModule('rns.notification')) {
            echo GetMessage("ACCESS_DENIED");
            return false;
        }
        global $USER;
        $entities = \Rns\Notification\RnsBot::getEntities();

        $userSettings = [];
        foreach ($entities as $entity) {
            $noticeCode = 'notice' . $entity['ID'];
            $onChangeDeadline = 'deadline' . $entity['ID'];
            $daysCode = 'days' . $entity['ID'];
            $notice = isset($settings[$noticeCode]) && $settings[$noticeCode] == 'on';
            $onChangeDeadline = isset($settings[$onChangeDeadline]) && $settings[$onChangeDeadline] == 'on';
            $days = isset($settings[$daysCode]) && (int)$settings[$daysCode] ?
                (int)$settings[$daysCode] : \Rns\Notification\RnsBot::getDefaultNoticeDays();
            $userSettings[$entity['ID']] = [
                'NOTICE' => $notice,
                'CHANGE_DEADLINE' => $onChangeDeadline,
                'TODAY_DEADLINE' => $onChangeDeadline,
                'DAYS' => $days
            ];
        }
        $userSettings = [
            'ENTITIES' => $userSettings
        ];

        \Rns\Notification\RnsBot::saveUserSettings($USER->GetID(), $userSettings);

        return $userSettings;
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('rns.notification')) {
            echo GetMessage("ACCESS_DENIED");
            return false;
        }
        global $USER;

        $userSettings = \Rns\Notification\RnsBot::getUserSettings($USER->GetID());
        $entities = \Rns\Notification\RnsBot::getEntities();

        $this->arResult['USER_SETTINGS'] = $userSettings;
        $this->arResult['ENTITIES'] = $entities;

        $this->includeComponentTemplate();
    }
}