<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Response\Component;
use Bitrix\Main\Loader;
use Rns\Notification\RnsBot;

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
        $entities = RnsBot::getEntities();

        $userSettings = [];
        foreach ($entities as $entity) {
            $noticeCode = 'notice' . $entity['ID'];
            $onChangeDeadline = 'change' . $entity['ID'];
            $todayDeadline = 'deadline' . $entity['ID'];
            $daysCode = 'days' . $entity['ID'];
            $notice = (isset($settings[$noticeCode]) && $settings[$noticeCode] == 'Y') ? 'Y' : 'N';
            $onChangeDeadline = (isset($settings[$onChangeDeadline]) && $settings[$onChangeDeadline] == 'Y') ? 'Y' : 'N';
            $todayDeadline = (isset($settings[$todayDeadline]) && $settings[$todayDeadline] == 'Y') ? 'Y' : 'N';
            $days = isset($settings[$daysCode]) && (int)$settings[$daysCode] ?
                (int)$settings[$daysCode] : RnsBot::getDefaultNoticeDays();
            $userSettings[$entity['ID']] = [
                'NOTICE' => $notice,
                'CHANGE_DEADLINE' => $onChangeDeadline,
                'TODAY_DEADLINE' => $todayDeadline,
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

        $userSettings = RnsBot::getUserSettings($USER->GetID());
        $entities = RnsBot::getEntities();
        if (isset($userSettings['ENTITIES'][0])) {
            foreach ($entities as $entity) {
                $userSettings['ENTITIES'][$entity['ID']] = $userSettings['ENTITIES'][0];
            }
        }

        $this->arResult['USER_SETTINGS'] = $userSettings;
        $this->arResult['ENTITIES'] = $entities;

        $this->includeComponentTemplate();
    }
}