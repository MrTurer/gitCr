<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\Config\Option,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader;

global $APPLICATION, $USER;

$module_id = 'rns.analytics';

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
        'DIV' => 'heatmap',
        'TAB' => Loc::getMessage('RNSANALYTICS_OPT_TAB_HEATMAP_NAME'),
        'TITLE' => Loc::getMessage('RNSANALYTICS_OPT_TAB_HEATMAP_TITLE')
    ]
];

$options['heatmap'] = [
    [
        'RNSANALYTICS_OPT_API_URL',
        Loc::getMessage('RNSANALYTICS_OPT_API_URL'),
        '',
        ['text', 70]
    ]
];

if (check_bitrix_sessid() && (strlen($_POST['save']) > 0 || strlen($_POST['apply']) > 0)) {
    foreach ($options as $option) {
        __AdmSettingsSaveOptions($module_id, $option);
    }

    if (strlen($_POST['save']) > 0) {
        LocalRedirect($APPLICATION->GetCurPageParam());
    }
}
?>

<form method="POST"
      action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>"
      ENCTYPE="multipart/form-data">
    <?
    $tabControl = new CAdminTabControl('tabControl', $tabs);
    $tabControl->Begin();

    if (!empty($options)) {
        foreach ($options as $optionArr) {
            $tabControl->BeginNextTab();
            foreach ($optionArr as $option) {
                __AdmSettingsDrawRow($module_id, $option);
            }
        }
    }
    $tabControl->Buttons(['btnApply' => true, 'btnCancel' => false, 'btnSaveAndAdd' => false]);
    echo bitrix_sessid_post();
    $tabControl->End();
    ?>
</form>