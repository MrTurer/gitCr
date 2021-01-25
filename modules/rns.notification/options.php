<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader;

global $APPLICATION, $USER;

$module_id = 'rns.notification';

if (!$USER->IsAdmin()) {
    return;
}

if (!Loader::includeModule($module_id)) {
    return;
}

/**
 *
 * Описание логики табов и настроек в табах
 */
$tabs = [
    [
        'DIV' => 'chatbot',
        'TAB' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHATBOT_NAME'),
        'TITLE' => Loc::getMessage('RNSNOTIFICATION_OPT_TAB_CHATBOT_TITLE')
    ]
];

$isChatbotActiveSet = Option::get($module_id, 'RNSNOTIFICATION_OPT_CHATBOT_ACTIVE');
$isChatbotActive = \Rns\Notification\RnsBot::getBotId() ? 'Y' : 'N';
if ($isChatbotActiveSet != $isChatbotActive) {
    Option::set($module_id, 'RNSNOTIFICATION_OPT_CHATBOT_ACTIVE', $isChatbotActive);
}

$options['chatbot'] = [
    [
        'RNSNOTIFICATION_OPT_CHATBOT_ACTIVE',
        Loc::getMessage('RNSNOTIFICATION_OPT_CHATBOT_ACTIVE'),
        $isChatbotActive,
        ['checkbox'],
        '',
        ''
    ]
];

if (check_bitrix_sessid() && (strlen($_POST['save']) > 0 || strlen($_POST['apply']) > 0)) {
    if ($_POST['RNSNOTIFICATION_OPT_CHATBOT_ACTIVE'] == 'Y') {
        \Rns\Notification\RnsBot::register();
    } else {
        \Rns\Notification\RnsBot::unregister();
    }
    foreach ($options as $option) {
        __AdmSettingsSaveOptions($module_id, $option);
    }
    if (strlen($_POST['save']) > 0) {
        LocalRedirect($APPLICATION->GetCurPageParam());
    }
}

$tabControl = new CAdminTabControl('tabControl', $tabs);
$tabControl->Begin();
?>

<form method="POST"
      action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"
      id="baseexchange_form">

    <?
    if (!empty($options)) {
        foreach ($options as $option) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $option);
        }
    }
    $tabControl->Buttons(['btnApply' => true, 'btnCancel' => false, 'btnSaveAndAdd' => false]);
    echo bitrix_sessid_post();
    $tabControl->End();
    ?>
</form>